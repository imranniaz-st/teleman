<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Contact;
use App\Models\CsvImportQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CsvImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will import csv file to sql';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Contact $contact)
    {
        parent::__construct();

        $this->contact = $contact;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            // Retrieve all CSV records with status 0 (unprocessed)
            $csvDataList = CsvImportQueue::where('status', 0)->get();
    
            // Check if there are no records to process
            if ($csvDataList->isEmpty()) {
                echo "No import available.";
                return false;
            }
    
            foreach ($csvDataList as $csvData) {
                $csvFile = base_path('public/uploads/csv/' . $csvData->user_id . '/' . $csvData->name);
                if (!file_exists($csvFile)) {
                    throw new Exception("CSV file not found: $csvFile");
                }
    
                $rows = $this->readCsv($csvFile);
                $this->validateAndInsertData($rows);
    
                $csvData->update(['status' => 1]);
                echo "CSV data " . $csvData->name . " inserted successfully.";
            }
    
            // Artisan::call('import:csv');
    
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            // Logging and other error handling...
        }
    }

    /**
     * The function reads a CSV file and returns the data as a collection of rows.
     * 
     * @param csvFile The parameter `csvFile` is the path to the CSV file that you want to read.
     * 
     * @return a collection of rows from a CSV file.
     */
    private function readCsv($csvFile)
    {
        $handle = fopen($csvFile, 'r');
        if (!$handle) {
            throw new Exception("Failed to open CSV file: $csvFile");
        }

        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = $data;
        }

        fclose($handle);
        return collect($rows);
    }

    /**
     * The function validates and inserts data from a CSV file into a database table.
     * 
     * @param rows The parameter `` is expected to be a collection of arrays, where each array
     * represents a row of data from a CSV file. Each row should contain 7 elements, representing the
     * following fields:
     * 
     * @return an array of associative arrays, where each associative array represents a row of data
     * from the input CSV file. Each associative array has the following keys: 'user_id', 'name',
     * 'phone', 'country', 'gender', 'dob', and 'profession'.
     */
    private function validateAndInsertData($rows)
    {
        $rows->chunk(500)->each(function ($chunk) 
        {
            $batch = $chunk->map(function ($row) 
            {
                if (count($row) < 7) 
                {
                    throw new Exception("Invalid CSV row: " . implode(', ', $row));
                }

                return [
                    'user_id' => $row[0],
                    'name' => $row[1],
                    'phone' => $row[2],
                    'country' => $row[3],
                    'gender' => $row[4],
                    'dob' => $row[5],
                    'profession' => $row[6],
                ];
            });

            $this->contact->insert($batch->all());
        });
    }

}
