<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class ProductsDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $method;
    public $timeout = 120;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($options)
    {
        foreach($options as $key => $value) {
            if ($value) $this->method = str_replace('-', '_', $key);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->{$this->method}();
        } catch(Exception $e) {
            dd($e->getMessage());
        }
    }
    
    public function csv_import() 
    {
        $this->scanDirectory(storage_path('app/csv_files/'), function($csvFiles) {
            foreach($csvFiles as $fileName => $arrayInsert) {

                $date = (new \DateTime())->format('Y-m-d H:i:s.v');
                
                if (!($product = Product::create($arrayInsert))) {
                    file_put_contents(storage_path("logs/error_import_csv-{$date}.log"), json_encode([$fileName => $arrayInsert]), \FILE_APPEND);
                }

                file_put_contents(storage_path("logs/success_import_csv-{$date}.log"), json_encode([$fileName => $product]), \FILE_APPEND);
                Storage::move(storage_path("app/csv_files/{$fileName}"), storage_path("app/csv_files/imported/{$fileName}"));
            }
        });
    }

    public function mail() 
    {
        
    }

    private function scanDirectory($path, $handler) 
    {
        $csvfiles = [];
        foreach(glob("{$path}*.csv", GLOB_BRACE) as $i => $file) {
            $csvfiles[basename($file)] = $this->getCSV($file);
        }
        $handler($csvfiles);
    }

    private function getCSV(string $filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) return false;
        
        try {
            
            $header = null;
            $data   = [];

            if (($handle = fopen($filename, 'r')) !== false) {

                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                    if (!$header)
                        $header = $row;
                    else
                        $data[] = array_combine($header, $row);
                }
                
                fclose($handle);

                return $data;
            }
        } catch(Excpetion $e) {
            dd($e->getMessage());
        }
    }
}
