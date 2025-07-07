

public function filterByMarketCap(Request $request)
    {
        $marketCap = $request->get('marketcap', 'small'); // Default to 'small'
    
        $data = Portfolio::where('Market_cap', $marketCap)->paginate(15);
        $stockIds = $data->pluck('stock_id')->toArray();
        $data2 = $this->cmp($stockIds);
    
        // Initialize the total current value and total day change value
        $totalCurrentValue = 0;
        $totalDayChangeValue = 0;
        $totalDayChangePercentage = 0;
    
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
            }
        }
    
        $totalCurrentValue = round($totalCurrentValue);
        $totalInvest = round(Portfolio::where('Market_cap', $marketCap)->sum('total_price'));
        $plAmount = round($totalCurrentValue - $totalInvest);
    
        // Average day change percentage
        $averageDayChangePercentage = $totalDayChangePercentage / $data->count();
    
        // Render the updated HTML for the portfolio data
        $html = view('partials.portfolioData', compact('data', 'data2'))->render();
    
        return response()->json([
            'html' => $html,
            'totalCurrentValue' => $totalCurrentValue,
            'totalInvest' => $totalInvest,
            'plAmount' => $plAmount,
            'totalDayChangeValue' => round($totalDayChangeValue),
            'averageDayChangePercentage' => round($averageDayChangePercentage, 2)
        ]);
    }



    raw xirr 
    <?php

function xirr($cashFlows, $dates, $guess = 0.1) {
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
        
        $newRate = $rate - $npv / $derivative;
        
        if (abs($newRate - $rate) < $TOLERANCE) {
            return $newRate;
        }
        
        $rate = $newRate;
    }
    
    throw new RuntimeException('XIRR calculation did not converge.');
}
$cashFlows = [-10000, 3000, 4200, 6800];
$dates = ['2023-01-01', '2023-06-01', '2023-12-01', '2024-06-01'];

try {
    $xirr = xirr($cashFlows, $dates);
    echo "XIRR: " . ($xirr * 100) . "%\n"; // Convert to percentage
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
