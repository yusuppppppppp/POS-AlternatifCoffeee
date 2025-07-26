<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's data
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        
        // Total orders today
        $totalOrdersToday = DB::table('orders')
            ->whereDate('created_at', $today)
            ->count();
            
        // Total orders yesterday
        $totalOrdersYesterday = DB::table('orders')
            ->whereDate('created_at', $yesterday)
            ->count();
            
        // Calculate percentage change for orders
        $orderPercentageChange = 0;
        if ($totalOrdersYesterday > 0) {
            $orderPercentageChange = (($totalOrdersToday - $totalOrdersYesterday) / $totalOrdersYesterday) * 100;
        } elseif ($totalOrdersToday > 0) {
            $orderPercentageChange = 100;
        }
        
        // Total income today
        $totalIncomeToday = DB::table('orders')
            ->whereDate('created_at', $today)
            ->sum('total_amount'); // Assuming you have a total_amount column
            
        // Total income yesterday
        $totalIncomeYesterday = DB::table('orders')
            ->whereDate('created_at', $yesterday)
            ->sum('total_amount');
            
        // Calculate percentage change for income
        $incomePercentageChange = 0;
        if ($totalIncomeYesterday > 0) {
            $incomePercentageChange = (($totalIncomeToday - $totalIncomeYesterday) / $totalIncomeYesterday) * 100;
        } elseif ($totalIncomeToday > 0) {
            $incomePercentageChange = 100;
        }
        
        // Get monthly data for chart (last 5 months)
        $monthlyData = [];
        for ($i = 4; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $orderCount = DB::table('orders')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
                
            $incomeAmount = DB::table('orders')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
                
            $monthlyData[] = [
                'month' => $month->format('M'),
                'orders' => $orderCount,
                'income' => $incomeAmount
            ];
        }
        
        return view('dashboard', compact(
            'totalOrdersToday',
            'totalIncomeToday',
            'orderPercentageChange',
            'incomePercentageChange',
            'monthlyData'
        ));
    }
}