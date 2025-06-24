<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary()
    {
        $today = Carbon::today();

        // Ringkasan hari ini
        $todayTransactions = Transaction::whereDate('date', $today)->get();
        $totalItemToday = $todayTransactions->sum('total_item');
        $totalSalesToday = $todayTransactions->sum('total_price');

        // Ringkasan keseluruhan
        $totalItem = Transaction::sum('total_item');
        $totalSales = Transaction::sum('total_price');

        return response()->json([
            'today' => [
                'total_item' => $totalItemToday,
                'total_sales' => $totalSalesToday,
            ],
            'overall' => [
                'total_item' => $totalItem,
                'total_sales' => $totalSales,
            ]
        ]);
    }

    public function chart(Request $request)
    {
        $month = $request->query('month', Carbon::now()->format('m'));
        $year = Carbon::now()->year;

        $data = Transaction::select(
                DB::raw('DAY(date) as day'),
                DB::raw('SUM(total_price) as total_sales')
            )
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return response()->json($data);
    }
}
