<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    actions: {--csv-list="imported||default=all||not-imported"} {--csv-import} {--mail}';

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

            public function __construct($options) 
            {
                foreach($options as $option) {
                    if (method_exists($this, $option)) {
                        $this->{$option}();
                    }   
                }
            }

            public function csv_list() 
            {
                
            }
            
            public function csv_import() 
            {

            }

            public function mail() 
            {
                
            }
            
        };
    }
}
