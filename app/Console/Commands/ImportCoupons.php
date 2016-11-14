<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Import;

class ImportCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:coupons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'import all coupons from live site';

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
        //
        $import = new Import();
        // To Do: for multi domains pass connection as parameter
        $import->importCoupons();
        $this->info('import:coupons Command Run successfully!');
    }
}
