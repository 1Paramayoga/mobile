<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $query = Transaction::with('details.item')->orderBy('date', 'desc');

        if ($this->start && $this->end) {
            $query->whereBetween('date', [$this->start, $this->end]);
        }

        $transactions = $query->get();

        $data = [];

        foreach ($transactions as $trx) {
            foreach ($trx->details as $detail) {
                $data[] = [
                    'Tanggal'    => $trx->date,
                    'Invoice'    => $trx->transaction_number,
                    'Item'       => $detail->item->name ?? '-',
                    'Harga Satuan' => $detail->price,
                    'Jumlah'     => $detail->quantity,
                    'Total Item' => $trx->total_item,
                    'Total Bayar'=> $trx->total_price,
                    'Tunai'      => $trx->cash,
                    'Kembali'    => $trx->change,
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Invoice',
            'Item',
            'Harga Satuan',
            'Jumlah',
            'Total Item',
            'Total Bayar',
            'Tunai',
            'Kembali',
        ];
    }
}
