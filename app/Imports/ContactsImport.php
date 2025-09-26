<?php

namespace App\Imports;

use App\Models\Contact;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ContactsImport implements ToModel, WithHeadingRow, WithProgressBar, WithBatchInserts
{
    use Importable;
    /**
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Contact([
            'user_id' => Auth::id(),
            'name' => $row['name'],
            'phone' => $row['phone'],
            'country' => $row['country'],
            'gender' => $row['gender'],
            'dob' => $row['dob'],
            'profession' => $row['profession'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
