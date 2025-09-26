<?php

namespace App\Exports;

use App\Models\LeadsExportHistory;
use Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeadsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $campaign_id;

    public function __construct($campaign_id)
    {
        $this->campaign_id = $campaign_id;
    }

    public function collection()
    {
        return LeadsExportHistory::where('user_id', Auth::id())
                                ->where('campaign_id', $this->campaign_id)
                                ->select(
                                    'campaign_name',
                                    'total_contacts',
                                    'picked',
                                    'busy',
                                    'swiched_off',
                                    'lead',
                                    'total',
                                    'picked_percentage',
                                    'busy_percentage',
                                    'swiched_off_percentage',
                                    'lead_percentage',
                                    'lead_percentage_expectation',
                                    'export_date'
                                )
                                ->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            'campaign name',
            'total contacts',
            'picked',
            'busy',
            'swiched off',
            'lead',
            'total',
            'picked percentage',
            'busy percentage',
            'swiched off percentage',
            'lead percentage',
            'lead percentage expectation',
            'export date',
        ];
    }
}
