<?php

namespace App\Console\Commands;

use App\Http\Controllers\CategoriesController;
use Illuminate\Console\Command;

class SendTryoutEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tryout category Product email sending, if 100 likes are clicked in 10 days for a product.';

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
        $categoryCtrl = new CategoriesController();
        $categoryCtrl->sendtryoutemail();
        $this->info('emails:send Cummand Run successfully!');
    }
}
