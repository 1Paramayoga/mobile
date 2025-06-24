<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Exports\TransactionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $query = Transaction::with('details.item')->orderBy('date', 'desc');

        if ($start && $end) {
            $query->whereBetween('date', [$start, $end]);
        }

        return response()->json($query->get());
    }

    public function export(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        return Excel::download(new TransactionsExport($start, $end), 'laporan_transaksi.xlsx');
    }
}
