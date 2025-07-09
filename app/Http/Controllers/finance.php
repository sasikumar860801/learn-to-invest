<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\pl_report; 
use App\Models\portfolio; 
use App\Services\StockService;
use Carbon\Carbon;
use RuntimeException;
use InvalidArgumentException;
use Illuminate\Support\Facades\Cache;



// use Nyholm\EffectiveInterestRate\Xirr;

// use JpMurray\Xirr\Xirr;

class finance extends Controller
{
    public function search(Request $request)
            {
                $query = $request->input('q');

                $response = Http::get('https://www.screener.in/api/company/search/', [
                    'q' => $query,
                    'v' => 3,
                    'fts' => 1
                ]);

                if ($response->ok()) {
                    $data = $response->json();

                    $autocompleteData = [];
                    foreach ($data as $item) {
                        $autocompleteData[] = [
                            'label' => $item['name'], 
                            'value' => $item['id'] 
                        ];
                    }

                    return response()->json($autocompleteData);
                }

                return response()->json([]);
            }

    public function fetchChart($id)
        {
            $url = "https://www.screener.in/api/company/{$id}/chart/?q=Price-DMA50-DMA200-Volume&days=7&consolidated=true";

            $response = Http::get($url);

            return $response->json();
        }

        public function pl_report(Request $request)
        {
            $cap = $request->input('Market_cap');
            if ($cap == 'all') {
                $data = pl_report::all();
            } else {
                $data = pl_report::where('Market_cap', $cap)->get();
            }
        
            // Assuming you have methods to calculate realized PL amount and percentage
            $data->each(function ($item) {
                $item->realized_pl_amount = $item->total_sell_price - $item->total_buy_price;
                $item->realized_pl_percentage = round(($item->realized_pl_amount / $item->total_buy_price) * 100,1);
            });
        
            if ($request->ajax()) {
                return response()->json(['data' => $data]);
            }
        
            return view('pl_report', compact('data'));
        }
        
        

        public function calculateXirr()
    {
        // Fetch data from pl_report
        $plReports = pl_report::all();
        
        // Extract and prepare dates for pl_report
        $plBuyDates = $plReports->pluck('buy_date')->toArray();
        $plSellDates = $plReports->pluck('sell_date')->toArray();
        $plDates = array_merge($plBuyDates, $plSellDates);
     
        // Extract and prepare prices for pl_report
        $plBuyPrices = $plReports->pluck('total_buy_price')->map(function ($price) {
            return -$price; // Convert to negative value
        })->toArray();

        $plSellPrices = $plReports->pluck('total_sell_price')->toArray();
        $plCashFlows = array_merge($plBuyPrices, $plSellPrices);  

        // Fetch data from Portfolio
        $portfolios = Portfolio::all();
        
        // Extract and prepare dates for Portfolio
        $portfolioBuyDates = $portfolios->pluck('buy_date')->toArray();
        $portfolioSellDates = array_fill(0, count($portfolioBuyDates), Carbon::today()->toDateString());
        $portfolioDates = array_merge($portfolioBuyDates, $portfolioSellDates);

        // Extract and prepare prices for Portfolio
        $portfolioBuyPrices = $portfolios->pluck('buy_price')->map(function ($price) {
            return -$price; // Convert to negative value
        })->toArray();

        // Fetch latest prices for Portfolio
        $stockIds = $portfolios->pluck('stock_id')->toArray();
        $latestPrices = $this->fetchLatestPrices($stockIds);
        $portfolioSellPrices = [];
        foreach ($portfolios as $portfolio) {
            $portfolioSellPrices[] = $latestPrices[$portfolio->stock_id] * $portfolio->quantity;
        }

        // Merge cash flows and dates
        $portfolioCashFlows = array_merge($portfolioBuyPrices, $portfolioSellPrices);
        $allDates = array_merge($plDates, $portfolioDates);
        $allCashFlows = array_merge($plCashFlows, $portfolioCashFlows);

        // Calculate XIRR
        try {
            $xirr = $this->xirr($allCashFlows, $allDates);
            return response()->json([
                'XIRR' => $xirr * 100 . "%" // Convert to percentage
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }


        public function index()
        {
            // dd('test');
            $data = Portfolio::paginate(15); 
        
            $stockIds = $data->pluck('stock_id')->toArray();
       
            $data2 = $this->cmp($stockIds);
            // dd($data2);
        
            // Initialize the total current value
            $totalCurrentValue = 0;
        
            foreach ($data as $item) {
                if (isset($data2[$item->stock_id]['latest_price'])) {
                    $latestPrice = $data2[$item->stock_id]['latest_price'];
                    $currentValue = $latestPrice * $item->quantity;
                    $totalCurrentValue += $currentValue;
        
                    // Add one day change data to the item
                    $item->one_day_change_value = $data2[$item->stock_id]['one_day_change_value'];
                    $item->one_day_change_percentage = $data2[$item->stock_id]['one_day_change_percentage'];
                }
            }
        
            $totalCurrentValue = round($totalCurrentValue);
        
            // Ensure the 'Market_cap' filter is correct as per your requirements
            $totalinvest = round(Portfolio::where('Market_cap', 'small')->sum('total_price'));
        
            // Calculate the Total PL Amount
            $pl_amount = round($totalCurrentValue - $totalinvest);
        
            return view('home', compact('data', 'data2', 'totalinvest', 'totalCurrentValue', 'pl_amount'));
        }
        
        // public function cmp($ids)
        // {
        //     if (!is_array($ids)) {
        //         $ids = [$ids];
        //     }
        
        //     $results = [];
        
        //     foreach ($ids as $id) {
        //         $url = "https://www.screener.in/api/company/{$id}/chart/?q=Price-DMA50-DMA200-Volume&days=7&consolidated=true";
        //         $response = Http::get($url);
        
        //         if ($response->successful()) {
        //             $data = $response->json();
        //             $latestPrice = $this->extractLatestPrice($data);
        //             $oneDayChange = $this->calculateOneDayChange($data);
        //             $results[$id] = [
        //                 'latest_price' => $latestPrice,
        //                 'one_day_change_value' => $oneDayChange['value'],
        //                 'one_day_change_percentage' => $oneDayChange['percentage']
        //             ];
        //         } else {
        //             $results[$id] = [
        //                 'latest_price' => null,
        //                 'one_day_change_value' => null,
        //                 'one_day_change_percentage' => null
        //             ]; // Handle the case where the API request fails
        //         }
        //     }
        //     return $results;
        // }

        public function cmp($ids)
{
    if (!is_array($ids)) {
        $ids = [$ids];
    }

    $results = [];

    foreach ($ids as $id) {
        $cacheKey = "cmp_data_{$id}";

        // Try getting from cache, or fetch and store if not present
        $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($id) {
            $url = "https://www.screener.in/api/company/{$id}/chart/?q=Price-DMA50-DMA200-Volume&days=7&consolidated=true";
            $response = Http::get($url);

            return $response->successful() ? $response->json() : null;
        });

        if ($data) {
            $latestPrice = $this->extractLatestPrice($data);
            $oneDayChange = $this->calculateOneDayChange($data);
            $results[$id] = [
                'latest_price' => $latestPrice,
                'one_day_change_value' => $oneDayChange['value'],
                'one_day_change_percentage' => $oneDayChange['percentage']
            ];
        } else {
            $results[$id] = [
                'latest_price' => null,
                'one_day_change_value' => null,
                'one_day_change_percentage' => null
            ];
        }
    }

    return $results;
}
        private function fetchLatestPrices($stockIds)
    {
        if (!is_array($stockIds)) {
            $stockIds = [$stockIds];
        }
        
        $results = [];
        
        foreach ($stockIds as $id) {
            $url = "https://www.screener.in/api/company/{$id}/chart/?q=Price-DMA50-DMA200-Volume&days=7&consolidated=true";
            $response = Http::get($url);
        
            if ($response->successful()) {
                $data = $response->json();
                $latestPrice = $this->extractLatestPrice($data);
                $results[$id] = $latestPrice;
            } else {
                $results[$id] = null; // Handle the case where the API request fails
            }
        }
        return $results;
    }
        private function extractLatestPrice($data)
        {
            foreach ($data['datasets'] as $dataset) {
                if ($dataset['metric'] === 'Price') {
                    // Assuming the last value is the most recent
                    $latestValue = end($dataset['values']);
                    return $latestValue[1]; // Return the price value
                }
            }
            return null; // Return null if no price data is found
        }
        
        private function calculateOneDayChange($data)
        {
            $prices = [];
            foreach ($data['datasets'] as $dataset) {
                if ($dataset['metric'] === 'Price') {
                    $prices = $dataset['values'];
                    break;
                }
            }
        
            $numPrices = count($prices);
            if ($numPrices >= 2) {
                $latestPrice = (float) $prices[$numPrices - 1][1];
                $previousPrice = (float) $prices[$numPrices - 2][1];
                $changeValue = $latestPrice - $previousPrice;
                $changePercentage = ($changeValue / $previousPrice) * 100;
                return [
                    'value' => round($changeValue, 2),
                    'percentage' => round($changePercentage, 2)
                ];
            }
        
            return [
                'value' => null,
                'percentage' => null
            ]; // Return null if not enough data points
        }
            

            public function store(Request $request)
            {
                $validatedData = $request->validate([
                    'quantity' => 'required|numeric',
                    'stock_name' => 'required|string',
                    'total_price' => 'required|numeric',
                    'Market_cap' => 'required|string',
                    'buy_price' => 'required|numeric',
                    'stock_id' => 'required|integer'
                ]);
            
                $existingStock = Portfolio::where('stock_id', $request->stock_id)->first();
            
                if ($existingStock) {
                    // Calculate new quantity
                    $newQuantity = $existingStock->quantity + $validatedData['quantity'];
            
                    // Calculate new average buy price
                    $totalInvestment = ($existingStock->buy_price * $existingStock->quantity) + ($validatedData['buy_price'] * $validatedData['quantity']);
                    $newBuyPrice = $totalInvestment / $newQuantity;
            
                    // Calculate new total price
                    $newTotalPrice = $existingStock->total_price + $validatedData['total_price'];
            
                    // Calculate new average buy date
                    $existingTimestamp = Carbon::parse($existingStock->buy_date)->timestamp;
                    $newTimestamp = Carbon::parse($request->buy_date)->timestamp;
                    $weightedAvgTimestamp = (($existingStock->quantity * $existingTimestamp) + ($validatedData['quantity'] * $newTimestamp)) / $newQuantity;
                    $newBuyDate = Carbon::createFromTimestamp($weightedAvgTimestamp)->toDateString();
            
                    // Update existing stock record
                    $existingStock->update([
                        'quantity' => $newQuantity,
                        'buy_price' => $newBuyPrice,
                        'total_price' => $newTotalPrice,
                        'buy_date' => $newBuyDate
                    ]);
            
                    return response()->json(['message' => 'success']);
                } else {
                    // Create new stock record with current date as buy_date
                    $validatedData['buy_date'] = now();
                    Portfolio::create($validatedData);
            
                    return response()->json(['message' => 'success']);
                }
            }
            

       

        public function exitStock(Request $request)
    {
        $stockId = $request->input('stock_id');
        $sellQuantity = $request->input('quantity');

        $portfolio = Portfolio::where('stock_id', $stockId)->first();

        if ($portfolio) {
            //insert the plreport page 
            $plReport = new pl_report();
            $plReport->stock_id = $portfolio->stock_id;
            $plReport->stock_name = $portfolio->stock_name;
            $plReport->Market_Cap = $portfolio->Market_Cap;
            $plReport->quantity = $sellQuantity;
            $plReport->buy_price = $portfolio->buy_price;
            $sellPrice = $request->input('current_market_price'); // Retrieve current market price from the request
            

            $plReport->sell_price = $sellPrice;
            $plReport->total_buy_price = $portfolio->buy_price * $sellQuantity;
            $plReport->total_sell_price = $sellPrice * $sellQuantity; // Corrected calculation for total sell price
            $plReport->buy_date = $portfolio->buy_date;
            $plReport->sell_date = now();
            $plReport->save();

            if ($sellQuantity >= $portfolio->quantity) {
                // Delete the row if all shares are sold
                $portfolio->delete();
                return response()->json(['status' => 'success', 'message' => 'Stock fully sold and removed']);
            } else {
                // Update the quantity and total price
                $portfolio->quantity -= $sellQuantity;
                $portfolio->total_price -= ($portfolio->buy_price * $sellQuantity);
                $portfolio->save();
                return response()->json(['status' => 'success', 'message' => 'Stock quantity updated']);
            }
        }

        return response()->json(['status' => 'error', 'message' => 'Stock not found'], 404);
    }

    public function filterByMarketCap(Request $request)
{
    $marketCap = $request->get('marketcap', 'small'); // Default to 'small'

    $data = Portfolio::where('Market_cap', $marketCap)->paginate(15);

    // Check if the portfolio data is empty
    if ($data->isEmpty()) {
        return response()->json([
            'html' => '<p>No data available.</p>',
            'totalCurrentValue' => 0,
            'totalInvest' => 0,
            'plAmount' => 0,
            'total_pl_percent' => 0,
            'totalDayChangeValue' => 0,
            'averageDayChangePercentage' => 0,
            'xirrPercentage' => 'N/A',
        ]);
    }

    $stockIds = $data->pluck('stock_id')->toArray();
    $data2 = $this->cmp($stockIds);

    // Initialize the total current value and total day change value
    $totalCurrentValue = 0;
    $totalDayChangeValue = 0;
    $totalDayChangePercentage = 0;

    $cashFlows = [];
    $dates = [];

    foreach ($data as $item) {
        if (isset($data2[$item->stock_id]['latest_price'])) {
            $latestPrice = $data2[$item->stock_id]['latest_price'];
            $currentValue = $latestPrice * $item->quantity;
            $totalCurrentValue += $currentValue;

            // Calculate day change value and percentage
            if (isset($data2[$item->stock_id]['one_day_change_value'])) {
                $dayChangeValue = $data2[$item->stock_id]['one_day_change_value'] * $item->quantity;
                $totalDayChangeValue += $dayChangeValue;

                $totalInvest = $item->total_price;
                if ($totalInvest > 0) {
                    $dayChangePercentage = ($dayChangeValue / $totalInvest) * 100;
                    $totalDayChangePercentage += $dayChangePercentage;
                }
            }

            // Add cash flow from Portfolio
            $cashFlows[] = -$item->total_price;
            $dates[] = $item->buy_date;
        }
    }

    // Add current value as the final cash flow
    $cashFlows[] = $totalCurrentValue;
    $dates[] = date('Y-m-d'); // Today's date

    // Add cash flows from PL_Report filtered by Market_cap
    $plReports = PL_Report::where('Market_cap', $marketCap)->get();
    // dd($plReports);
    foreach ($plReports as $report) {
        $cashFlows[] = -$report->total_buy_price;
        $dates[] = $report->buy_date;

        $cashFlows[] = $report->total_sell_price;
        $dates[] = $report->sell_date;
    }

    // dd($cashFlows, $dates);

    // Calculate XIRR
    try {
        $xirr = $this->xirr($cashFlows, $dates);
        $xirrPercentage = round($xirr * 100, 2); // Convert to percentage
    } catch (Exception $e) {
        $xirrPercentage = 'N/A';
    }

    $totalCurrentValue = round($totalCurrentValue);
    $totalInvest = round(Portfolio::where('Market_cap', $marketCap)->sum('total_price'));
    $plAmount = round($totalCurrentValue - $totalInvest);
    $total_pl_percent = ($totalInvest > 0) ? ($plAmount / $totalInvest) * 100 : 0;

    // Average day change percentage
    $averageDayChangePercentage = $data->count() > 0 ? $totalDayChangePercentage / $data->count() : 0;

    // Render the updated HTML for the portfolio data
    $html = view('partials.portfolioData', compact('data', 'data2'))->render();

    return response()->json([
        'html' => $html,
        'totalCurrentValue' => $totalCurrentValue,
        'totalInvest' => $totalInvest,
        'plAmount' => $plAmount,
        'total_pl_percent' => round($total_pl_percent, 1),
        'totalDayChangeValue' => round($totalDayChangeValue),
        'averageDayChangePercentage' => round($averageDayChangePercentage, 2),
        'xirrPercentage' => $xirrPercentage,
    ]);
}

    
private function xirr($cashFlows, $dates, $guess = 0.1)
{
    // Validate inputs
    if (count($cashFlows) !== count($dates) || count($cashFlows) === 0) {
        throw new InvalidArgumentException('Cash flows and dates must have the same number of elements and be non-empty.');
    }

    // Define constants
    $MAX_ITER = 1000;
    $TOLERANCE = 1e-6;
    
    // Initial guess
    $rate = $guess;
    
    // Helper function to calculate the sum of discounted cash flows
    function calculateNPV($rate, $cashFlows, $dates) {
        $npv = 0.0;
        $initialDate = $dates[0];
        
        foreach ($cashFlows as $i => $cashFlow) {
            $days = (strtotime($dates[$i]) - strtotime($initialDate)) / 86400; // Convert seconds to days
            $npv += $cashFlow / pow(1 + $rate, $days / 365);
        }
        
        return $npv;
    }

    // Newton-Raphson method to find the root
    for ($i = 0; $i < $MAX_ITER; $i++) {
        $npv = calculateNPV($rate, $cashFlows, $dates);
        $derivative = 0.0;
        $initialDate = $dates[0];
        
        foreach ($cashFlows as $j => $cashFlow) {
            $days = (strtotime($dates[$j]) - strtotime($initialDate)) / 86400;
            $discountedCashFlow = $cashFlow / pow(1 + $rate, $days / 365);
            $derivative -= ($days / 365) * $discountedCashFlow / (1 + $rate);
        }
        
        if ($derivative == 0) {
            throw new RuntimeException('Division by zero in XIRR calculation.');
        }
        
        $newRate = $rate - $npv / $derivative;
        
        if (abs($newRate - $rate) < $TOLERANCE) {
            return $newRate;
        }
        
        $rate = $newRate;
    }
    
    throw new RuntimeException('XIRR calculation did not converge.');
}
        
}
