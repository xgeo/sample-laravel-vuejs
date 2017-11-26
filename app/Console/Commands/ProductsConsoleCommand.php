<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\DefaultDispatcher;
use App\Product;

class ProductsConsoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pc:run {--csv-list} {--csv-list-imported} {--csv-import} {--mail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import csv of products from file to database.
    actions: {--csv-import} {--mail}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arguments  = $this->arguments();
        $options    = $this->options();

        $routines = new class($options) {
            
            public $activatedRoutine;

            public function __construct($options) 
            {
                foreach($options as $key => $value) {
                    if ($option) $this->activatedRoutine = str_replace('-', '_', $key);
                }
            }
            
            public function csv_import() 
            {
                // Product::firstOrCreate($array[$i]);
                
            }

            public function mail() 
            {
                
            }

            private function toArray($filename = '', $delimiter = ',')
            {
                if (!file_exists($filename) || !is_readable($filename)) return false;
            
                $header = null;
                $data = array();

                if (($handle = fopen($filename, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                        if (!$header)
                            $header = $row;
                        else
                            $data[] = array_combine($header, $row);
                    }
                    fclose($handle);
                }
            
                return $data;
            }

            private function getCSV($path)
            {
                $file = public_path($path);
            
                return $this->toArray($file);  
            }

        };

        DefaultDispatcher::dispatch($routines)
                        ->onConnection('redis')
                        ->onQueue('products');
    }
}
