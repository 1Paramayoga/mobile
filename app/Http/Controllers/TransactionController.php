<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('details.item')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'total_item'  => 'required|integer',
            'cash'        => 'required|numeric',
            'change'      => 'required|numeric',
            'items'       => 'required|array',
            'items.*.id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price'    => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $transaction = Transaction::create([
                'transaction_number' => 'TRX-' . strtoupper(Str::random(6)),
                'user_id'       => $request->user()->id,
                'total_price'   => $request->total_price,
                'total_item'    => $request->total_item,
                'cash'          => $request->cash,
                'change'        => $request->change,
                'date'          => Carbon::now()->format('Y-m-d'),
            ]);

            foreach ($request->items as $cartItem) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_id'        => $cartItem['id'],
                    'quantity'       => $cartItem['quantity'],
                    'price'          => $cartItem['price'],
                ]);

                // Kurangi stok
                $item = Item::find($cartItem['id']);
                $item->decrement('stock', $cartItem['quantity']);
            }

            DB::commit();

            return response()->json(['message' => 'Transaction saved', 'data' => $transaction->load('details.item')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Transaction failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with('details.item')->findOrFail($id);
        return response()->json($transaction);
    }
}
