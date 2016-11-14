<?php
namespace App\Http\Controllers;
use App\Functions\Functions;

use App\RevenueReport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use Session;
use DB;




class SummaryTableController extends Controller
{
    public function __construct()
    {
      //  parent::__construct();
        $this->report = new RevenueReport;
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
    }

    public function index(){

        $this->report->order_perYear_byDate();
        

    }
    
    

}