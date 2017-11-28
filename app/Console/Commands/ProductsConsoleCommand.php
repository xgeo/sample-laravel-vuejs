<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProductsDispatcher;
use App\Product;

class ProductsConsoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pc:run {--csv-import} {--mail}';

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

        ProductsDispatcher::dispatch($options)
                        ->onConnection('redis')
                        ->onQueue('products');
    }
}
