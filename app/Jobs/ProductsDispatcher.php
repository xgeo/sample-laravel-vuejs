<?php

namespace App\Jobs;

use App\Mail\ProductsImported;
use App\Product;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProductsDispatcher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $method;
    public $timeout = 120;
    private $filePath;
    
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

        $this->filePath = storage_path('app/csv_files/');
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
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }
    
    public function csv_import() 
    {
        $this->scanDirectory($this->filePath . 'files/', function($csvFiles) {

            if (!count($csvFiles)) return;

            $date = (new \DateTime())->format('Y-m-d H:i');
            $log = [];
            $product = '';

            try {
                \DB::beginTransaction();

                foreach($csvFiles as $fileName => $dataInsert) {

                    foreach($dataInsert as $data) {
                        $product = Product::create($data);
                        $log[] = [$fileName => $product];

                    }

                    $file = $this->filePath . "files/{$fileName}";
                    $importedFile = $this->filePath . "imported/{$fileName}";

                    if (file_exists($file)) {

                        \File::move($file, $importedFile);

                        \Mail::to(User::first()->getAttribute('email'))
                            ->send(new ProductsImported($log, $importedFile));
                    }

                    \DB::commit();
                }

            } catch(\Exception $exception) {
                $log = [$fileName => $product, 'error' => $exception->getMessage()];
                \DB::rollBack();
            }

            file_put_contents(storage_path("logs/import_csv-{$date}.log"), json_encode($log), FILE_APPEND);
        });
    }

    private function scanDirectory($path, $handler) 
    {
        $csvfiles = [];
        foreach(glob("{$path}*.csv", GLOB_BRACE) as $i => $file) {
            $csvfiles[basename($file)] = $this->getCSV($file);
        }
        return $handler($csvfiles);
    }

    private function getCSV(string $filename, string $delimiter = ',')
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
        } catch(\Exception $e) {
            dd($e->getMessage());
        }
    }
}
