<?php
namespace App\Http\Controllers;
use App\Functions\Functions;
use App\User;
use App\Products;
use App\RevenueReport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use Session;
use DB;




class RevenueReportController extends BaseController
{

    public function __construct()
    {
        parent::__construct();

        $this->report = new RevenueReport;



    }

    public function index(){


        return view('reports.revenue_report');
    }

    public function getYearByDate(){

     return   $year_by_date = $this->report->getYear_byDate();
    }
    public function getYearByDatePerDay(){

        return   $year_by_date_perDay = $this->report->getYear_byDatePerDay();
    }
    public function getYearByDatePerWeek(){

        return   $year_by_date_perWeek = $this->report->getYear_byDatePerWeek();
    }
    public function getLmonthByDate(){

        return   $lmonth_by_date = $this->report->getLmonth_byDate();
    }
    public function getLmonthByDatePerWeek(){

        return   $lmonth_by_date_perWeek = $this->report->getLmonth_byDatePerWeek();
    }
    public function getCmonthByDate(){

        return   $cmonth_by_date = $this->report->getCmonth_byDate();
    }
    public function getCmonthByDatePerWeek(){

        return   $cmonth_by_date_perWeek = $this->report->getCmonth_byDatePerWeek();
    }
    public function get14DaysByDate(){

        return   $L14days_by_date = $this->report->get14Days_byDate();
    }
    public function get14DaysByDatePerWeek(){

        return   $L14days_by_date_perWeek = $this->report->get14Days_byDatePerWeek();
    }
    public function get7DaysByDate(){

        return   $L7days_by_date = $this->report->get7Days_byDate();
    }
    public function getYesterdayByDate(){

        return   $Yesterday_by_date = $this->report->getYesterday_byDate();
    }
    public function getTodayByDate(){

        return   $today_by_date = $this->report->getToday_byDate();
    }
    public function getCustomByDate(Request $request){
        $input = $request->all();

        return   $today_by_date = $this->report->getCustom_byDate($input);
    }
    public function getCustomByDatePerWeek(Request $request){
        $input = $request->all();

        return   $today_by_date_perWeek = $this->report->getCustom_byDate_perWeek($input);
    }
    public function getCustomByDatePerMonth(Request $request){
        $input = $request->all();

        return   $today_by_date_perMonth = $this->report->getCustom_byDate_perMonth($input);
    }

    ////////////////////////////////Product functions////////////////////

    public function sale_by_product(){


        return view('reports.revenue_report_by_product');
    }
    public function showSaleByProduct($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts($perpage,$keywords,$sort_by,$order);

    }
    public function showSaleByProduct_lmonth($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts_lmonth($perpage,$keywords,$sort_by,$order);

    }
    public function showSaleByProduct_cmonth($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts_cmonth($perpage,$keywords,$sort_by,$order);

    }
    public function showSaleByProduct_14day($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts_14day($perpage,$keywords,$sort_by,$order);

    }
    public function showSaleByProduct_7day($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts_7day($perpage,$keywords,$sort_by,$order);

    }
    public function showSaleByProduct_yes($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts_yes($perpage,$keywords,$sort_by,$order);

    }
    public function showSaleByProduct_today($perpage,$keywords,$sort_by,$order ){

        return $show_list = $this->report->show_parentProducts_today($perpage,$keywords,$sort_by,$order);

    }

    public function showSaleByProduct_custom($perpage,$keywords,$sort_by,$order,$from,$to ){

        return $show_list = $this->report->show_parentProducts_custom($perpage,$keywords,$sort_by,$order,$from,$to);

    }
    public function showProductVar(Request $request){
        $input = $request->all();
        return $show_var = $this->report->showProductVar($input);

    }

    public function getYearByProduct(Request $request){
        $input = $request->all();
        return   $year_by_product = $this->report->getYear_byProduct($input);
    }
    public function getLmonthByProduct(Request $request){
        $input = $request->all();
        return   $lmonth_by_product = $this->report->getLmonth_byProduct($input);


    }
    public function getCmonthByProduct(Request $request){
        $input = $request->all();
        return   $cmonth_by_product = $this->report->getCmonth_byProduct($input);


    }

    public function get14daysByProduct(Request $request){
        $input = $request->all();

        return   $fdays_by_product = $this->report->get14days_byProduct($input);


    }
    public function get7daysByProduct(Request $request){
        $input = $request->all();
        return   $fdays_by_product = $this->report->get7days_byProduct($input);


    }
    public function getYesterdaysByProduct(Request $request){
        $input = $request->all();
        return   $fdays_by_product = $this->report->getYesterday_byProduct($input);


    }
    public function getTodayByProduct(Request $request){
        $input = $request->all();
        return   $fdays_by_product = $this->report->getToday_byProduct($input);


    }
    public function getCustomByProduct(Request $request){
        $input = $request->all();
        return   $cus_by_product = $this->report->getCustom_byProduct($input); 
        
    }
}
?>