<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current month and previous month
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Total orders this month
        $totalOrdersThisMonth = DB::table('orders')
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();
            
        // Total orders last month
        $totalOrdersLastMonth = DB::table('orders')
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();
            
        // Calculate percentage change for orders
        $orderPercentageChange = 0;
        if ($totalOrdersLastMonth > 0) {
            $orderPercentageChange = (($totalOrdersThisMonth - $totalOrdersLastMonth) / $totalOrdersLastMonth) * 100;
        } elseif ($totalOrdersThisMonth > 0) {
            $orderPercentageChange = 100;
        }
        
        // Total income this month
        $totalIncomeThisMonth = DB::table('orders')
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('total_amount');
            
        // Total income last month
        $totalIncomeLastMonth = DB::table('orders')
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->sum('total_amount');
            
        // Calculate percentage change for income
        $incomePercentageChange = 0;
        if ($totalIncomeLastMonth > 0) {
            $incomePercentageChange = (($totalIncomeThisMonth - $totalIncomeLastMonth) / $totalIncomeLastMonth) * 100;
        } elseif ($totalIncomeThisMonth > 0) {
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
            'totalOrdersThisMonth',
            'totalIncomeThisMonth',
            'orderPercentageChange',
            'incomePercentageChange',
            'monthlyData'
        ));
    }
}