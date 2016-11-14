<?php

namespace App;

use App\functions\Functions;
use App\Input;
use DB;
use Auth;
class RevenueReport extends BaseModel
{
    public $timestamps = false;
    public $base_connection = 'eagleeye';




    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

    }

    public function order_perYear_byDate()
    {

        $drop_table_peryearByDate_purchased = DB::connection($this->connection)->table('report_bydate_purchased')->delete();



        $create_table_peryearByDate_purchased = DB::connection($this->connection)->statement('insert into report_bydate_purchased (order_id,purchased_item,created_at)
                SELECT a.id, sum(b.qty) as purchased_item, a.created_at   FROM orders a
                INNER join order_items
                b  on b.order_id = a.id  
                WHERE YEAR(a.created_at) = YEAR(CURDATE())  
                GROUP by year(a.created_at),month(a.created_at), day(a.created_at),a.id ORDER by created_at ASC');
        
      $drop_table_peryearByDate =  DB::connection($this->connection)->table('report_bydate_orders')->delete();


        $create_table_peryearByDate =  DB::connection($this->connection)->statement('insert into report_bydate_orders(order_id,gross_sale, sum_tax,net_sale ,average_sale ,orders_placed,created_at )
                select id,sum(amount) as gross_sale, sum(total_tax) as sum_tax ,sum(amount)- sum(total_tax) as net_sale, 
                (sum(amount)/365) as average_sale, count(id) as orders_placed , created_at
                from orders WHERE YEAR(created_at) = YEAR(CURDATE()) 
                GROUP by year(created_at),month(created_at), day(created_at),id ORDER by created_at ASC');





       $drop_table_peryearByDate_refunds = DB::connection($this->connection)->table('report_bydate_refunds')->delete();



         $create_table_peryearByDate_refunds = DB::connection($this->connection)->statement('insert into report_bydate_refunds(order_id,refund_amount,refund_orders,created_at)
            select order_id, sum(amount) as refund_amount,count(id) as refund_orders,created_at  from refunds
            WHERE YEAR(created_at) = YEAR(CURDATE())
            GROUP by year(created_at),month(created_at), day(created_at),order_id ORDER by created_at ASC');

        $drop_table_byProducts = DB::connection($this->connection)->table('report_byproducts')->delete();

        $create_table_byProducts = DB::connection($this->connection)->statement('insert into report_byproducts(order_id,product_id,name,sku,qty,gross,tax,net,refund_qty,refund_total,refund_tax,refund_net,parent, created_at)
            SELECT a.id as order_id, b.product_id,p.name,p.sku,b.qty, b.total as gross, b.total_tax as tax, 
            (b.total-b.total_tax) as net ,IFNULL(r.qty,0) as refund_qty, IFNULL(r.total, 0) as refund_total, 
            IFNULL(r.total_tax,0) as refund_tax,(IFNULL(r.total,0)- IFNULL(r.total_tax,0)) as refund_net,
            case
                 when p.parent_id = \'0\' then p.id
                 when p.parent_id != \'0\' then p.parent_id
            End as parent , a.created_at
            FROM orders a
                    inner join order_items b  on b.order_id = a.id
                    inner join products p on p.id = b.product_id
                    left join refund_items r on r.order_id = a.id and r.product_id = b.product_id
            WHERE YEAR(a.created_at) = YEAR(CURDATE())
            ORDER BY year(a.created_at),month(a.created_at), day(a.created_at),order_id ,product_id ,parent  ASC');

        $drop_table_byProductsName = DB::connection($this->connection)->table('report_byproducts_name')->delete();

        $create_table_byProductsName = DB::connection($this->connection)->statement('insert into report_byproducts_name(order_id,product_id,name,sku,qty,gross,tax,net,refund_qty,refund_total,refund_tax,refund_net,parent, created_at, parent_name,parent_sku)
        SELECT a.order_id,a.product_id,a.name,a.sku,a.qty,a.gross,a.tax,a.net,a.refund_qty,a.refund_total,a.refund_tax,a.refund_net,a.parent, a.created_at,b.name as parent_name,b.sku as parent_sku FROM report_byproducts a
              left join products b on b.id = a.parent');

        echo "done !";


    }
    public function getYear_byDate()
    {

        $order_details_perMonth = DB::connection($this->connection)
        ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000 
            as graph_date,
            sum(gross_sale) as gross_sale,
            sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/month(CURDATE())) as average_sale, count(id) as orders_placed 
            from report_bydate_orders WHERE YEAR(created_at) = YEAR(CURDATE())  GROUP by year(created_at), month(created_at)
            ORDER by created_at ASC');

        $order_details_perYear = DB::connection($this->connection)
                ->select('select  sum(gross_sale) as total_gross_sale,
            sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/month(CURDATE())) as total_average_sale, count(id) as total_orders_placed 
            from report_bydate_orders WHERE YEAR(created_at) = YEAR(CURDATE())  GROUP by year(created_at)');

        if(!empty($order_details_perYear)){
            $total_orders_placed = $order_details_perYear[0]->total_orders_placed;
            $total_gross_sale = $order_details_perYear[0]->total_gross_sale;
            $total_net_sale =  $order_details_perYear[0]->total_net_sale;
            $total_average_sale = $order_details_perYear[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }



        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000 
            as graph_date , sum(refund_amount) as refund_amount, 
            sum(refund_orders) as refund_orders from report_bydate_refunds  
            WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at),month(created_at)');

        $refund_details_perYear = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
            sum(refund_orders) as total_refund_orders from report_bydate_refunds 
            WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at)');

        if(!empty($refund_details_perYear)){
            $total_refund_amount = $refund_details_perYear[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perYear[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perMonth = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000
                    as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
            WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at), month(created_at)');

        $purchased_details_perYear = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by Year(created_at)');

        if(!empty($purchased_details_perYear)){
            $total_purchased_item =$purchased_details_perYear[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }
        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perMonth as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perMonth as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perMonth as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
       $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
             'order_net_amount' => $arr_order_net_amount,
             'refund_amount' => $arr_refund_amount,
            'order_average_amount' =>  $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' => Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

       return json_encode($data_array);


    }

    public function getYear_byDatePerDay()
    {

        $order_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
            as graph_date,
            sum(gross_sale) as gross_sale,
            sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/365) as average_sale, count(id) as orders_placed 
            from report_bydate_orders WHERE YEAR(created_at) = YEAR(CURDATE())  GROUP by year(created_at), month(created_at), day(created_at)
            ORDER by created_at ASC');

        $order_details_perYear = DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/365) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE YEAR(created_at) = YEAR(CURDATE())  GROUP by year(created_at)');

        if(!empty($order_details_perYear)){
            $total_orders_placed = $order_details_perYear[0]->total_orders_placed;
            $total_gross_sale = $order_details_perYear[0]->total_gross_sale;
            $total_net_sale =  $order_details_perYear[0]->total_net_sale;
            $total_average_sale = $order_details_perYear[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }



        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date , sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at),month(created_at),day(created_at)');

        $refund_details_perYear = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
         WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at)');

        if(!empty($refund_details_perYear)){
            $total_refund_amount = $refund_details_perYear[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perYear[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perMonth = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\'  )))*1000
                    as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
            WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at), month(created_at), day(created_at)');

        $purchased_details_perYear = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by Year(created_at)');

        if(!empty($purchased_details_perYear)){
            $total_purchased_item =$purchased_details_perYear[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }
        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perMonth as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perMonth as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perMonth as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' =>  $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' => Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);


    }

    public function getYear_byDatePerWeek()
    {

        $order_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',
LPAD(month( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',
day( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date, WEEKOFYEAR(created_at) AS period,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/ WEEKOFYEAR(CURDATE())) as average_sale, count(id) as orders_placed
        from report_bydate_orders WHERE YEAR(created_at) = YEAR(CURDATE())  GROUP by year(created_at), period
         ORDER by created_at ASC');


        $order_details_perYear = DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/WEEKOFYEAR(CURDATE())) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE YEAR(created_at) = YEAR(CURDATE())  GROUP by year(created_at)');

        if(!empty($order_details_perYear)){
            $total_orders_placed = $order_details_perYear[0]->total_orders_placed;
            $total_gross_sale = $order_details_perYear[0]->total_gross_sale;
            $total_net_sale =  $order_details_perYear[0]->total_net_sale;
            $total_average_sale = $order_details_perYear[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }



        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY) ),\'-\',LPAD(month( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY) ), 2, \'0\'),\'-\',day( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY) ),\' 00:00:00\' )))*1000 
        as graph_date, created_at, WEEKOFYEAR(created_at) AS period,
        sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at),period');

        $refund_details_perYear = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
         WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at)');

        if(!empty($refund_details_perYear)){
            $total_refund_amount = $refund_details_perYear[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perYear[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perMonth = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\'  )))*1000
                    as graph_date, WEEKOFYEAR(created_at) AS period,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
            WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by year(created_at),period');

        $purchased_details_perYear = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE YEAR(created_at) = YEAR(CURDATE()) GROUP by Year(created_at)');

        if(!empty($purchased_details_perYear)){
            $total_purchased_item =$purchased_details_perYear[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }
        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perMonth as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perMonth as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perMonth as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' =>  $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' => Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);


    }


    
    
    public function getLmonth_byDate()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/DAY(LAST_DAY(CURDATE()- INTERVAL 1 MONTH))) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  GROUP by day(created_at)
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/DAY(LAST_DAY(CURDATE()- INTERVAL 1 MONTH))) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE  YEAR(created_at) 
        = YEAR(CURDATE())and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  GROUP by month(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

           ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
         WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
           $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
             WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by  day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by month(created_at)');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function getLmonth_byDatePerWeek()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,created_at, WEEKOFYEAR(created_at) AS period,
        
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/4) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  GROUP by period
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/4) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  GROUP by month(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000 as graph_date, WEEKOFYEAR(created_at) AS period,
        sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
         WHERE month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by period');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
        WHERE month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date, WEEKOFYEAR(created_at) AS period,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
             WHERE month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by  period');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) GROUP by month(created_at)');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }
    
    
    


    public function getCmonth_byDate()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/DAY(LAST_DAY(CURDATE()))) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE month(created_at) = month(CURDATE())  GROUP by day(created_at)
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/DAY(LAST_DAY(CURDATE()))) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE month(created_at) = month(CURDATE())  GROUP by month(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
         WHERE month(created_at) = month(CURDATE()) GROUP by day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
        WHERE month(created_at) = month(CURDATE()) GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
             WHERE month(created_at) = month(CURDATE()) GROUP by  day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE month(created_at) = month(CURDATE()) GROUP by month(created_at)');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function getCmonth_byDatePerWeek()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale, WEEKOFYEAR(created_at) AS period,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/4) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE month(created_at) = month(CURDATE())  GROUP by period
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/4) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE month(created_at) = month(CURDATE())  GROUP by month(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select  Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000 as graph_date, WEEKOFYEAR(created_at) AS period, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
         WHERE month(created_at) = month(CURDATE()) GROUP by period');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
        WHERE month(created_at) = month(CURDATE()) GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date, WEEKOFYEAR(created_at) AS period,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
             WHERE month(created_at) = month(CURDATE()) GROUP by  period');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE month(created_at) = month(CURDATE()) GROUP by month(created_at)');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function get14Days_byDate()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/14) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )   GROUP by  month(created_at), day(created_at)
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/14) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY ) 
          GROUP by month(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY ) GROUP by  month(created_at), day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
       WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )  GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
         WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY ) GROUP by  month(created_at), day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function get14Days_byDatePerWeek()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/2) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )  
         
         GROUP by  month(created_at), day(created_at)
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/2) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY ) 
          GROUP by month(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date, WEEKOFYEAR(created_at) AS period, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY ) GROUP by  period');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
       WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )  GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date,WEEKOFYEAR(created_at) AS period,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
         WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY ) GROUP by  period');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }




    public function get7Days_byDate()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/7) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY )  GROUP by  month(created_at), day(created_at)
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/7) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY ) 
          GROUP by month(created_at) ');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY ) GROUP by  month(created_at), day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
       WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY ) GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
         WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY )  GROUP by  month(created_at), day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY )');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function getYesterday_byDate()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/1) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY )  GROUP by  month(created_at), day(created_at)
         ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/1) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY ) 
          GROUP by month(created_at) ');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY ) GROUP by  month(created_at), day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
       WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY ) GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
         WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY ) GROUP by  month(created_at), day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY )');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function getToday_byDate()
    {
        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,created_at,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/1) as average_sale, count(id) as orders_placed 
        from report_bydate_orders  
        WHERE   year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE()) 
        GROUP by  month(created_at), day(created_at) ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/1) as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders   WHERE   year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE()) 
        GROUP by  month(created_at), day(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE   year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE())  
       GROUP by  month(created_at), day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
        WHERE   year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE()) 
        GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
        WHERE   year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE()) 
       GROUP by  month(created_at), day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
                WHERE   year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE()) ');

        if(!empty($purchased_details_perMonth) ){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' =>  $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);
    }

    public function getCustom_byDate($input){

        $from = $input['from'];
        $to = $input['to'];
        $from_db = date('Y-m-d' , strtotime($from));
        $to_db = date('Y-m-d' , strtotime($to));
        $date1 = strtotime($from);
        $date2 = strtotime($to);
        $diff=$date2 - $date1;
        $days = floor($diff / (60*60*24) );

        $order_details_perDay= DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,created_at,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/ "'.$days.'") as average_sale, count(id) as orders_placed 
        from report_bydate_orders  
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
        GROUP by  month(created_at), day(created_at) ORDER by created_at ASC');

        $order_details_perMonth= DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/"'.$days.'") as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders   
       WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
       GROUP by  month(created_at), day(created_at)');

        if(!empty($order_details_perMonth)){
            $total_orders_placed = $order_details_perMonth[0]->total_orders_placed;
            $total_gross_sale = $order_details_perMonth[0]->total_gross_sale;
            $total_net_sale =  $order_details_perMonth[0]->total_net_sale;
            $total_average_sale = $order_details_perMonth[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////

        $refund_details_perDay = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date, sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
         WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'") 
        GROUP by  month(created_at), day(created_at)');

        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
         WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'") 
        GROUP by month(created_at)');

        if(!empty($refund_details_perMonth)){
            $total_refund_amount = $refund_details_perMonth[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perMonth[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perDay = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000
        as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'") 
         GROUP by  month(created_at), day(created_at)');

        $purchased_details_perMonth = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
                WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'") ');

        if(!empty($purchased_details_perMonth)){
            $total_purchased_item =$purchased_details_perMonth[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }


        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perDay as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perDay as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perDay as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' => $arr_average,
            'total_order_count' =>  $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' =>  Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);




    }


    public function getCustom_byDate_perWeek($input){

        $from = $input['from'];
        $to = $input['to'];
        $from_db = date('Y-m-d' , strtotime($from));
        $to_db = date('Y-m-d' , strtotime($to));
        $date1 = strtotime($from);
        $date2 = strtotime($to);
        $diff=$date2 - $date1;
        $days = floor($diff / (60*60*24) );
        $weeks = floor($days/7);



        $order_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\' )))*1000
        as graph_date, WEEKOFYEAR(created_at) AS period,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/ "'.$weeks.'") as average_sale, count(id) as orders_placed
        from report_bydate_orders 
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
         
         GROUP by year(created_at), period
         ORDER by created_at ASC');


        $order_details_perYear = DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/ "'.$weeks.'") as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
         GROUP by year(created_at)');

        if(!empty($order_details_perYear)){
            $total_orders_placed = $order_details_perYear[0]->total_orders_placed;
            $total_gross_sale = $order_details_perYear[0]->total_gross_sale;
            $total_net_sale =  $order_details_perYear[0]->total_net_sale;
            $total_average_sale = $order_details_perYear[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }



        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY) ),\'-\',LPAD(month( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY) ), 2, \'0\'),\'-\',day( DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY) ),\' 00:00:00\' )))*1000 
        as graph_date, created_at, WEEKOFYEAR(created_at) AS period,
        sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
         
         GROUP by year(created_at),period');

        $refund_details_perYear = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
          
          GROUP by year(created_at)');

        if(!empty($refund_details_perYear)){
            $total_refund_amount = $refund_details_perYear[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perYear[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perMonth = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\'-\',LPAD(month(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)), 2, \'0\'),\'-\',day(DATE(created_at + INTERVAL (8-DAYOFWEEK(created_at)) DAY)),\' 00:00:00\'  )))*1000
                    as graph_date, WEEKOFYEAR(created_at) AS period,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
            WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
            
            GROUP by year(created_at),period');

        $purchased_details_perYear = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
              WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
               GROUP by Year(created_at)');

        if(!empty($purchased_details_perYear)){
            $total_purchased_item =$purchased_details_perYear[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }
        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perMonth as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perMonth as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perMonth as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' =>  $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' => Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);

    }

    public function getCustom_byDate_perMonth($input){

        $from = $input['from'];
        $to = $input['to'];
        $from_db = date('Y-m-d' , strtotime($from));
        $to_db = date('Y-m-d' , strtotime($to));
        $date1 = strtotime($from);
        $date2 = strtotime($to);
        $diff=$date2 - $date1;
        $days = ceil($diff / (60*60*24) );
        $months = ceil($days/30);


        $order_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000 
        as graph_date,
        sum(gross_sale) as gross_sale,
        sum(gross_sale)- sum(sum_tax) as net_sale, (sum(gross_sale)/ "'.$months.'") as average_sale, count(id) as orders_placed 
        from report_bydate_orders 
        
         WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
         
         GROUP by year(created_at), month(created_at)
         ORDER by created_at ASC');

        $order_details_perYear = DB::connection($this->connection)
            ->select('select  sum(gross_sale) as total_gross_sale,
        sum(gross_sale)- sum(sum_tax) as total_net_sale, (sum(gross_sale)/ "'.$months.'") as total_average_sale, count(id) as total_orders_placed 
        from report_bydate_orders 
         WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'") 
        GROUP by year(created_at)');

        if(!empty($order_details_perYear)){
            $total_orders_placed = $order_details_perYear[0]->total_orders_placed;
            $total_gross_sale = $order_details_perYear[0]->total_gross_sale;
            $total_net_sale =  $order_details_perYear[0]->total_net_sale;
            $total_average_sale = $order_details_perYear[0]->total_average_sale;
        }
        else{
            $total_orders_placed = 0;
            $total_gross_sale = 0;
            $total_net_sale = 0;
            $total_average_sale = 0;

        }



        $refund_details_perMonth = DB::connection($this->connection)
            ->select('select Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000 
        as graph_date , sum(refund_amount) as refund_amount, 
        sum(refund_orders) as refund_orders from report_bydate_refunds  
        WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'") 
        GROUP by year(created_at),month(created_at)');

        $refund_details_perYear = DB::connection($this->connection)
            ->select('select  sum(refund_amount) as total_refund_amount, 
        sum(refund_orders) as total_refund_orders from report_bydate_refunds 
         WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
          
          GROUP by year(created_at)');

        if(!empty($refund_details_perYear)){
            $total_refund_amount = $refund_details_perYear[0]->total_refund_amount;
            $total_refund_orders = $refund_details_perYear[0]->total_refund_orders;
        }
        else{
            $total_refund_amount = 0;
            $total_refund_orders = 0;
        }
        $purchased_details_perMonth = DB::connection($this->connection)
            ->select('SELECT Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000
                    as graph_date,sum(purchased_item) as purchased_item FROM `report_bydate_purchased` 
            WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
             GROUP by year(created_at), month(created_at)');

        $purchased_details_perYear = DB::connection($this->connection)->
        select('SELECT sum(purchased_item) as total_purchased_item FROM `report_bydate_purchased` 
               WHERE   (created_at >= "'.$from_db.'" and  created_at <= "'.$to_db.'")
               GROUP by Year(created_at)');

        if(!empty($purchased_details_perYear)){
            $total_purchased_item =$purchased_details_perYear[0]->total_purchased_item;

        }
        else{
            $total_purchased_item = 0;

        }
        $arr_order_amount = array();
        $arr_order_net_amount = array();
        $arr_average = array();
        $arr_orders_placed  = array();
        $arr_refund_amount = array();
        $arr_purchased_item = array();
        foreach($order_details_perMonth as $odr){

            array_push($arr_order_amount , [$odr->graph_date, $odr->gross_sale ]);
            array_push($arr_order_net_amount , [$odr->graph_date, $odr->net_sale ]);
            array_push($arr_average , [$odr->graph_date, $odr->average_sale ]);
            array_push($arr_orders_placed , [$odr->graph_date, $odr->orders_placed ]);
        }

        foreach($refund_details_perMonth as $rfd){

            array_push($arr_refund_amount , [$rfd->graph_date, $rfd->refund_amount ]);

        }
        foreach($purchased_details_perMonth as $prs){

            array_push($arr_purchased_item , [$prs->graph_date, $prs->purchased_item ]);
        }
        $data_array= array(
            'order_count' => $arr_orders_placed,
            'order_item_count' => $arr_purchased_item,
            'order_amounts' => $arr_order_amount ,
            'order_net_amount' => $arr_order_net_amount,
            'refund_amount' => $arr_refund_amount,
            'order_average_amount' =>  $arr_average,
            'total_order_count' => $total_orders_placed,
            'total_order_amount' =>  Functions::moneyForField($total_gross_sale),
            'total_order_net_amount' =>  Functions::moneyForField($total_net_sale),
            'total_average' => Functions::moneyForField($total_average_sale),
            'total_refund_amount'=>  Functions::moneyForField($total_refund_amount),
            'total_refund_orders' => $total_refund_orders,
            'total_order_item_count' => $total_purchased_item

        );

        return json_encode($data_array);


    }

    public function show_parentProducts($perpage,$keywords,$sort_by,$order)
    {

       if($sort_by != 'undefined' && $order != 'undefined'){

           $sort = "ORDER BY ".$sort_by."  ".$order." ";
       }
       if($perpage != '' && $perpage != 'undefined'){

           $limit = "LIMIT $perpage ";
       }
       else{
           $limit = "LIMIT 50";

       }
       
       if($keywords != 'undefined'){
           $keywords	= "%".str_replace('+','',$keywords)."%";

           $key = "and parent_name like '$keywords'";
        }
       else{
           $key = '';
       }


       $result = DB::connection($this->connection)
           ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE YEAR(created_at) = YEAR(CURDATE()) '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }

    public function show_parentProducts_lmonth($perpage,$keywords,$sort_by,$order){

        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH) '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }

    public function show_parentProducts_cmonth($perpage,$keywords,$sort_by,$order){

        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()) '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }

    public function show_parentProducts_14day($perpage,$keywords,$sort_by,$order){

        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE created_at >= ( CURDATE() - INTERVAL 13 DAY )   '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }

    public function show_parentProducts_7day($perpage,$keywords,$sort_by,$order){

        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE created_at >= ( CURDATE() - INTERVAL 6 DAY )   '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }

    public function show_parentProducts_yes($perpage,$keywords,$sort_by,$order){

        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE created_at >= ( CURDATE() - INTERVAL 1 DAY )   '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }
    public function show_parentProducts_today($perpage,$keywords,$sort_by,$order){

        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE  year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE())   '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }

    public function show_parentProducts_custom($perpage,$keywords,$sort_by,$order,$from,$to){



        if($sort_by != 'undefined' && $order != 'undefined'){

            $sort = "ORDER BY ".$sort_by."  ".$order." ";
        }
        if($perpage != '' && $perpage != 'undefined'){

            $limit = "LIMIT $perpage ";
        }
        else{
            $limit = "LIMIT 50";

        }

        if($keywords != 'undefined'){
            $keywords	= "%".str_replace('+','',$keywords)."%";

            $key = "and parent_name like '$keywords'";
        }
        else{
            $key = '';
        }

        if($from != 'undefined' && $to != 'undefined'){

            $from_array = explode('-', $from);
            $fdate = $from_array[2]."-".$from_array[0]."-".$from_array[1];

            $to_array = explode('-', $to);
            $tdate = $to_array[2]."-".$to_array[0]."-".$to_array[1];

            $date_range = '(date(created_at) >= "'.$fdate.'" and  date(created_at) <= "'.$tdate.'") '  ;
        }
        else{
            $date_range = '(date(created_at) >= " " and  date(created_at) <= " ") ';
        }

        $result = DB::connection($this->connection)
            ->select('SELECT parent,parent_name,parent_sku, sum(qty) as sales, sum(gross) as revenue, sum(net) as net_revenue,
                        sum(refund_qty) as returns,created_at FROM report_byproducts_name
                        WHERE   '.$date_range.'  '.$key.' group by year(created_at), parent
                        '.$sort.'  '.$limit.' ');



        $result_array = array();
        $count = 1;
        foreach($result as $rs) {
            array_push($result_array , [
                'count'  => $count,
                'parent' => $rs->parent,
                'parent_name' => $rs->parent_name,
                'parent_sku' => $rs->parent_sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue),

            ]);

            $count++;
        }

        $data_array= array('data'=>   $result_array );

        return json_encode($data_array);
    }



    
    

    public function getYear_byProduct($input)
    {
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];

        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE YEAR(created_at) = YEAR(CURDATE())  and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at),parent
        ');

        }
        else if($product_type == 'child'){

            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-01 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE YEAR(created_at) = YEAR(CURDATE())  and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE YEAR(created_at) = YEAR(CURDATE())  and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE YEAR(created_at) = YEAR(CURDATE())  and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);

        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;
        }
        else{
            $total_sales = 0;
            $total_refunds = 0;
        }
        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);
        }
        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
         );

        return json_encode($data_array);

    }

    public function getLmonth_byProduct($input){
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE()- INTERVAL 1 MONTH)  and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);

        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;

        }
        else{
            $total_sales = 0;
            $total_refunds = 0;


        }



        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);

        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);



    }

    public function getCmonth_byProduct($input){
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE())  and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE())  and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE())  and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  YEAR(created_at) = YEAR(CURDATE()) and month(created_at) = month(CURDATE())  and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);

        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;

        }
        else{
            $total_sales = 0;
            $total_refunds = 0;


        }



        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);

        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);



    }

    public function get14days_byProduct($input){
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 13 DAY )  and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 13 DAY )  and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 13 DAY )  and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 13 DAY )  and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);

        }
        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;

        }
        else{
            $total_sales = 0;
            $total_refunds = 0;
        }
        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);

        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);



    }

    public function get7days_byProduct($input){
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 6 DAY )  and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 6 DAY )  and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 6 DAY )  and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 6 DAY )  and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);
        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;
        }
        else{
            $total_sales = 0;
            $total_refunds = 0;
        }

        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);

        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);



    }

    public function getYesterday_byProduct($input){
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 1 DAY )  and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 1 DAY )  and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 1 DAY )  and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  created_at >= ( CURDATE() - INTERVAL 1 DAY )  and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);

        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;

        }
        else{
            $total_sales = 0;
            $total_refunds = 0;
        }
        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){
            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);
        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);
    }


    public function getToday_byProduct($input){
        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE())   and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE())   and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE())   and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE  year(created_at) = year(CURDATE()) and month(created_at) = month(CURDATE()) and day(created_at) = day(CURDATE())   and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);
        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;
        }
        else{
            $total_sales = 0;
            $total_refunds = 0;
        }

        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);
        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);
    }

    public function getCustom_byProduct($input){

        $product_id = $input['parent_id'];
        $product_type = $input['product_type'];
        $from = $input['from'];
        $to = $input['to'];
        $from_db = date('Y-m-d' , strtotime($from));
        $to_db = date('Y-m-d' , strtotime($to));
        if($product_type == 'parent'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE  (date(created_at) >= "'.$from_db.'" and  date(created_at) <= "'.$to_db.'")   and parent = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at) ,parent
        ');
        }
        else if($product_type == 'child'){
            $parent_details_perMonth = DB::connection($this->connection)
                ->select('SELECT  Round(UNIX_TIMESTAMP(CONCAT(year(created_at),\'-\',LPAD(month(created_at), 2, \'0\'),\'-\',day(created_at),\' 00:00:00\' )))*1000 
        as graph_date, sum(qty) as sales , sum(refund_qty) as refunds,parent,name
        FROM report_byproducts
        WHERE   (date(created_at) >= "'.$from_db.'" and  date(created_at) <= "'.$to_db.'")    and product_id = '.$product_id.' 
        GROUP by year(created_at), month(created_at), day(created_at),product_id
        ');

        }

        if($product_type == 'parent') {
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE   (date(created_at) >= "'.$from_db.'" and  date(created_at) <= "'.$to_db.'")    and parent = ' . $product_id . ' 
        GROUP by year(created_at),parent');

            $pname =  Functions::getProductName($product_id);
        }
        else if($product_type == 'child'){
            $parent_details_perYear = DB::connection($this->connection)
                ->select('SELECT  sum(qty) as total_sales , sum(refund_qty) as total_refunds
        FROM report_byproducts
        WHERE   (date(created_at) >= "'.$from_db.'" and  date(created_at) <= "'.$to_db.'")    and product_id = ' . $product_id . ' 
        GROUP by year(created_at),product_id');

            $pname =  Functions::getVarNameWithSize($product_id);
        }

        if(!empty($parent_details_perYear)){
            $total_sales = $parent_details_perYear[0]->total_sales;
            $total_refunds = $parent_details_perYear[0]->total_refunds;
        }
        else{
            $total_sales = 0;
            $total_refunds = 0;
        }

        $arr_gross_amount = array();
        $arr_refund_amount = array();

        foreach($parent_details_perMonth as $odr){

            array_push($arr_gross_amount , [$odr->graph_date, $odr->sales ]);
            array_push($arr_refund_amount , [$odr->graph_date, $odr->refunds ]);
        }

        $data_array= array(
            'order_amounts' => $arr_gross_amount,
            'refund_amount' => $arr_refund_amount,
            'product_name'  => $pname,
            'total_sales'   =>$total_sales,
            'total_refunds' =>$total_refunds
        );

        return json_encode($data_array);

    }

    public function showProductVar($input){
        
      $parent_id = $input['parent_id'];
      $period = $input['period'];
        $from =  $input['from'];
        $to = $input['to'];
        $from_db = date('Y-m-d' , strtotime($from));
        $to_db = date('Y-m-d' , strtotime($to));
        if($period == 'year'){
          $result = DB::connection($this->connection)
            ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at FROM report_byproducts_name a
                        WHERE  YEAR(a.created_at) = YEAR(CURDATE()) and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.' 
                         group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
          }
        else if($period == 'lmonth'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at FROM report_byproducts_name a
                       WHERE YEAR(a.created_at) = YEAR(CURDATE()) and month(a.created_at) = month(CURDATE()- INTERVAL 1 MONTH) 
                        and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.'  
                        group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }
        else if($period == 'cmonth'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at FROM report_byproducts_name a
                        WHERE YEAR(a.created_at) = YEAR(CURDATE()) and month(a.created_at) = month(CURDATE()) 
                        and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.'  group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }
        else if($period == '14days'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at FROM report_byproducts_name a
                        WHERE a.created_at >= ( CURDATE() - INTERVAL 13 DAY )  and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.' 
                         group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }
        else if($period == '7day'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at  FROM report_byproducts_name a
                        WHERE a.created_at >= ( CURDATE() - INTERVAL 6 DAY )  and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.' 
                         group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }
        else if($period == 'yesterday'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at  FROM report_byproducts_name a
                        WHERE  a.created_at >= ( CURDATE() - INTERVAL 1 DAY )  and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.' 
                         group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }
        else if($period == 'today'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at  FROM report_byproducts_name a
                        
                        WHERE  year(a.created_at) = year(CURDATE()) and month(a.created_at) = month(CURDATE()) and day(a.created_at) = day(CURDATE())  
                         and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.'  group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }
        else if($period == 'custom'){
            $result = DB::connection($this->connection)
                ->select('SELECT a.product_id, a.parent,a.name,a.sku, sum(a.qty) as sales, sum(a.gross) as revenue, sum(a.net) as net_revenue,
                        sum(a.refund_qty) as returns,a.created_at FROM report_byproducts_name a
                       
                        WHERE    (date(created_at) >= "'.$from_db.'" and  date(created_at) <= "'.$to_db.'")    
                         and a.parent = '.$parent_id.' and  a.product_id != '.$parent_id.'  group by year(a.created_at), a.product_id
                        ORDER BY sales DESC');
        }

        $var_array = array();
        $count = 1;
        foreach($result as $rs) {
           /* if(!empty($rs->attributes)){

                $attr = array_values(unserialize($rs->attributes));
               $att_string = " - <strong>".$attr[0]."</strong>";
            }
            else{
                $att_string = '';
            }

            $name_with_attr = substr($rs->name, 17).$att_string;*/
            array_push($var_array , [

                'count'  => $count,
                'product_id'=> $rs->product_id,
                'parent' => $rs->parent,
                'name' => Functions::getVarNameWithSize($rs->product_id),
                'sku' => $rs->sku,
                'sales' => $rs->sales,
                'returns' => $rs->returns,
                'revenue' => Functions::moneyForField($rs->revenue),
                'net_revenue' => Functions::moneyForField($rs->net_revenue)]);
            $count++;
        }
        $data_array= array('data'=>   $var_array );

        return json_encode($var_array);




    }


}

?>

