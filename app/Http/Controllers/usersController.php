<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class usersController extends Controller
{
    

    public function dashboard()
{
    $user_id = Auth::id();

    $users_data = DB::table('users')->where('id', $user_id)->first();

    // Get paginated portfolios (5 per page)
    $users_portfolio_data = DB::table('users_portfolio')
        ->where('user_id', $user_id)
        ->paginate(10);

    foreach ($users_portfolio_data as $item) {
        $item->latest_price = Cache::remember("stock_price_{$item->stock_id}", 300, function () use ($item) {
            return $this->fetchLatestPrices($item->stock_id);
        });
    }

    return view('dashboard', compact('users_data', 'users_portfolio_data'));
}

    private function fetchLatestPrices($id) {
        $url = "https://www.screener.in/api/company/{$id}/chart/?q=Price-DMA50-DMA200-Volume&days=7&consolidated=true";
        $response = Http::get($url);
        $data = $response->json();
        $latestPrice = $this->extractLatestPrice($data);
        return $latestPrice;
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

 public function buystock(Request $request)
{
    $user_id = Auth::id();

    // Validate input
    $validatedData = $request->validate([
        'stock_id'     => 'required|integer',
        'stock_name'   => 'required|string',
        'quantity'     => 'required|numeric|min:1',
        'buy_price'    => 'required|numeric|min:0',
    ]);

    // Calculate total price
    $validatedData['total_price'] = $validatedData['quantity'] * $validatedData['buy_price'];

    // Check user balance
    $available_balance = DB::table('users')->where('id', $user_id)->value('available_balance');

    if ($available_balance < $validatedData['total_price']) {
        return response()->json(['error' => 'Insufficient balance'], 400);
    }

    $existingStock = DB::table('users_portfolio')
        ->where('stock_id', $validatedData['stock_id'])
        ->where('user_id', $user_id)
        ->first();

    $now = now();
    $buyEntry = [
        'buy_price' => $validatedData['buy_price'],
        'buy_date'  => $now->toDateTimeString(),
        'quantity'  => $validatedData['quantity']
    ];

    DB::beginTransaction();

    try {
        if ($existingStock) {
            // Update portfolio
            $newQuantity = $existingStock->quantity + $validatedData['quantity'];

            $totalInvestment = ($existingStock->buy_price * $existingStock->quantity)
                             + ($validatedData['buy_price'] * $validatedData['quantity']);
            $newBuyPrice = $totalInvestment / $newQuantity;

            $newTotalPrice = $existingStock->total_price + $validatedData['total_price'];

            $existingTimestamp = Carbon::parse($existingStock->buy_date)->timestamp;
            $newTimestamp = $now->timestamp;
            $weightedAvgTimestamp = (($existingStock->quantity * $existingTimestamp)
                                    + ($validatedData['quantity'] * $newTimestamp)) / $newQuantity;
            $newBuyDate = Carbon::createFromTimestamp($weightedAvgTimestamp)->toDateTimeString();

            $avgHistory = json_decode($existingStock->avg, true) ?? [];
            $avgHistory[] = $buyEntry;

            DB::table('users_portfolio')
                ->where('id', $existingStock->id)
                ->update([
                    'quantity'     => $newQuantity,
                    'buy_price'    => $newBuyPrice,
                    'total_price'  => $newTotalPrice,
                    'buy_date'     => $newBuyDate,
                    'avg'          => json_encode($avgHistory),
                    'updated_at'   => $now
                ]);
        } else {
            // Insert new portfolio entry
            DB::table('users_portfolio')->insert([
                'stock_id'     => $validatedData['stock_id'],
                'user_id'      => $user_id,
                'stock_name'   => $validatedData['stock_name'],
                'quantity'     => $validatedData['quantity'],
                'buy_price'    => $validatedData['buy_price'],
                'total_price'  => $validatedData['total_price'],
                'buy_date'     => $now,
                'avg'          => json_encode([$buyEntry]),
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // Debit user balance
        DB::table('users')
            ->where('id', $user_id)
            ->decrement('available_balance', $validatedData['total_price']);

        DB::commit();

        return response()->json(['message' => 'Stock purchase successful']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Transaction failed', 'details' => $e->getMessage()], 500);
    }
}

public function exitstock(Request $request)
{
    // dd($request->all());
    $user_id = Auth::id();

    // Validate input
    $validatedData = $request->validate([
        'stock_id'  => 'required|integer',
        'quantity'  => 'required|numeric|min:1',
    ]);

    $stock_id = $validatedData['stock_id'];
    $exit_quantity = $validatedData['quantity'];

    $portfolio = DB::table('users_portfolio')
        ->where('stock_id', $stock_id)
        ->where('user_id', $user_id)
        ->first();

    if (!$portfolio) {
        return response()->json(['error' => 'Stock not found in portfolio'], 404);
    }

    if ($exit_quantity > $portfolio->quantity) {
        return response()->json(['error' => 'Exit quantity exceeds holdings'], 400);
    }

    try {
        DB::beginTransaction();

        // Fetch latest market price
        $latest_price = $this->fetchLatestPrices($stock_id);

        if (!$latest_price) {
            throw new \Exception("Latest price not available");
        }

        $sell_price = $latest_price;
        $total_sell_price = $exit_quantity * $sell_price;
        $buy_price = $portfolio->buy_price;
        $total_buy_price = $exit_quantity * $buy_price;

        // Insert into users_pl_reports
        DB::table('users_pl_reports')->insert([
            'stock_id'          => $stock_id,
            'user_id'           => $user_id,
            'stock_name'        => $portfolio->stock_name,
            'Market_Cap'        => 'N/A', // You can update this with actual value if available
            'quantity'          => $exit_quantity,
            'buy_price'         => $buy_price,
            'sell_price'        => $sell_price,
            'total_buy_price'   => $total_buy_price,
            'total_sell_price'  => $total_sell_price,
            'buy_date'          => $portfolio->buy_date,
            'avg'               => $portfolio->avg,
            'sell_date'         => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Update or delete portfolio
        if ($exit_quantity == $portfolio->quantity) {
            DB::table('users_portfolio')->where('id', $portfolio->id)->delete();
        } else {
            $remaining_quantity = $portfolio->quantity - $exit_quantity;
            $remaining_total_price = $portfolio->total_price - $total_buy_price;

            DB::table('users_portfolio')
                ->where('id', $portfolio->id)
                ->update([
                    'quantity'    => $remaining_quantity,
                    'total_price' => $remaining_total_price,
                    'updated_at'  => now()
                ]);
        }

        // Update user balance
        DB::table('users')
            ->where('id', $user_id)
            ->increment('available_balance', $total_sell_price);

        DB::commit();

        return response()->json(['message' => 'Stock exit successful']);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Transaction failed', 'details' => $e->getMessage()], 500);
    }
}

public function pl_report()
{
    $user_id = Auth::id();
    $data= DB::table('users_pl_reports')
    ->where('user_id', $user_id)
    ->orderBy('id', 'desc')
    ->get();

    // dd($data);

    return view('users.pl-report', compact('data'));
}

}