<?php

namespace App\Exports;

use App\Models\PaymentHistory;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class PaymentHistoriesExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (Auth::user()->role == 'admin') {
            return PaymentHistory::all();
        } else {
            return PaymentHistory::where('user_id', Auth::id())->get();
        }
    }
}
