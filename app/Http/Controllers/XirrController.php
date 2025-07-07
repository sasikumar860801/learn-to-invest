<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;
use App\Models\pl_report; // Use proper naming conventions for models
use App\Models\Portfolio;

class XirrController extends Controller
{
    public function sendData()
    {
        // Fetch pl_report data
        $plreport = pl_report::all();
        
        // Extract and merge dates
        $dates = array_merge(
            $plreport->pluck('buy_date')->toArray(),
            $plreport->pluck('sell_date')->toArray()
        );
     
        // Extract and modify prices
        $buyPrices = $plreport->pluck('total_buy_price')->map(function ($price) {
            return -$price; // Convert to negative value
        })->toArray();

        $sellPrices = $plreport->pluck('total_sell_price')->toArray();

        // Merge cash flows
        $cashFlows = array_merge($buyPrices, $sellPrices);  

        try {
            // Calculate XIRR
            $xirr = $this->xirr($cashFlows, $dates);
            echo "XIRR: " . ($xirr * 100) . "%\n"; // Convert to percentage
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
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
        $calculateNPV = function($rate, $cashFlows, $dates) {
            $npv = 0.0;
            $initialDate = $dates[0];
            
            foreach ($cashFlows as $i => $cashFlow) {
                $days = (strtotime($dates[$i]) - strtotime($initialDate)) / 86400; // Convert seconds to days
                $npv += $cashFlow / pow(1 + $rate, $days / 365);
            }
            
            return $npv;
        };
    
        // Newton-Raphson method to find the root
        for ($i = 0; $i < $MAX_ITER; $i++) {
            $npv = $calculateNPV($rate, $cashFlows, $dates);
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
