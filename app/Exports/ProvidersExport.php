<?php

namespace App\Exports;

use App\Models\Provider;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProvidersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Provider::where('user_id', Auth::id())->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'provider_name',
            'account_sid',
            'auth_token',
            'phone',
            'say',
            'audio',
            'xml',
            'provider',
            'hourly_quota',
            'status',
        ];
    }
}
