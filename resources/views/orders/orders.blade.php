@extends('layouts.master')

@section('css')
    {!! HTML::style('assets/css/roders_plugins.css') !!}
@endsection

@section('content')

        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <span><a href="{{ url('orders') }}">Bestellingen</a></span>
        </li>
    </ul>

</div>
<br>

{{-- page content --}}

<div class="row">
    <div class="col-md-12">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif

        @if(Session::has('flash_warning'))
            <div class="alert alert-warning">
                {{ Session::get('flash_warning') }}
            </div>
    @endif

                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-orders"></i>Bestellingen </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="orders-status-ul">
                                    <li> <a href="{{ url('orders') }}"> All <span class="badge badge-success">{{ $counts['all'] }}</span> </a> </li>
                                    <li> <a href="{{ url('orders?status=completed') }}"> Voltooid <span class="badge badge-primary">{{ $counts['completed'] }}</span>  </a></li>
                                    <li> <a href="{{ url('orders?status=cancelled') }}"> Geannuleerd <span class="badge badge-danger">{{ $counts['cancelled'] }}</span>  </a></li>
                                    <li> <a href="{{ url('orders?status=pending') }}"> In afwachting <span class="badge badge-info">{{ $counts['pending'] }}</span>  </a> </li>
                                    <li> <a href="{{ url('orders?status=refunded') }}"> Teruggestort <span class="badge badge-warning">{{ $counts['refunded'] }}</span>  </a> </li>
                                    <li> <a href="{{ url('orders?status=failed') }}"> Mislukt <span class="badge badge-danger">{{ $counts['failed'] }}</span>  </a> </li>
                                    <li> <a href="{{ url('orders?status=processing') }}"> In verwerking <span class="badge badge-info">{{ $counts['processing'] }}</span>  </a> </li>
                                    <li> <a href="{{ url('orders?status=on-hold') }}"> In de wacht <span class="badge badge-primary">{{ $counts['on-hold'] }}</span>  </a> </li>
                                    <li> <a href="{{ url('orders?status=deleted') }}"> Verwijderd <span class="badge badge-danger">{{ $counts['trash'] }}</span>  </a> </li>
                                </ul>

                            </div>
                        </div>

                        <hr style="margin-top: 0;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form class="form-horizental col-sm-8 pull-right npall nmall">
                                        <div class="pull-left col-sm-6 npall nmall">
                                            <div class="form-group-sm col-sm-8 npall nmall">

                                                <div class="input-group input date-picker input-daterange"
                                                     data-date="{!! date('d-m-Y') !!}" data-date-format="dd-mm-yyyy">
                                                    <input type="text" class="form-control" name="date_from" value="@if(isset($_GET['date_from']) && isDateTime(urldecode($_GET['date_from']))){{ date('d-m-Y', strtotime(urldecode($_GET['date_from']))) }}@endif">
                                                    <span class="input-group-addon"> to </span>
                                                    <input type="text" class="form-control" name="date_to" value="@if(isset($_GET['date_to']) && isDateTime(urldecode($_GET['date_to']))){{ date('d-m-Y', strtotime(urldecode($_GET['date_to']))) }}@endif"></div>
                                                <!-- /input-group -->
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="form-group-sm col-sm-4">
                                                <button type="submit" class="btn btn-sm btn-default">Filter</button>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 pull-right  npall nmall">
                                            <div class="">
                                                <div class="form-group">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                                        <span class="input-group-btn">
                                                            <button type="submit" class="btn green" type="button">Zoeken</button>
                                                        </span>
                                                    </div>
                                                    <!-- /input-group -->
                                                </div>
                                            </div>
                                        </div>
                                        {!!  $fields  !!}
                                    </form>
                                </div>
                            </div>
                        <form method="post" class="" action="{{ action('OrdersController@bulkAction') }}">
                            {{ csrf_field() }}

                            <div class="pull-left col-sm-3 npl" id="orders-bulk-form">
                                <div class="form-group-sm col-sm-8 npall nmall">
                                    <select class="form-control" name="bulk-action">
                                        <option value="">Acties</option>
                                        <option value="deleted">Verwijderen</option>
                                        <option value="pending">Markeer in afwachting</option>
                                        <option value="completed">Markeer als voltooid</option>
                                    </select>
                                </div>
                                <div class="form-group-sm col-sm-4">
                                    <button type="submit" class="btn btn-sm btn-default">Toepassen</button>
                                </div>
                            </div>


                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_orders">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="5%">
                                    <input type="checkbox" class="group-checkable" id="check-all-orders">
                                </th>
                                <th width="5%"> Status </th>
                                <th width="15%"> Bestelnummer </th>
                                <th width="20%"> Gekochte items </th>
                                <th width="20%"> Verzendadres </th>
                                <th width="10%"> Datum </th>
                                <th width="10%"> Besteltotaal </th>
                                <th width="20%"> Acties </th>
                            </tr>

                            </thead>
                            <tbody>

                            @if(count($orders) > 0)

                                @foreach($orders as $order)

                                    <tr>
                                        <td>
                                            <input type="checkbox" class="order-checkbox" name="bulk_orders[]" id="bulk_{{ $order->id }}" value="{{ $order->id }}">
                                        </td>
                                        <td class="text-center">
                                            <?php
                                                switch ($order->status)
                                                {
                                                    case 'completed':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Completed" aria-describedby="tooltip222393"> <i class="fa fa-check order-status-complete"></i> </a>';
                                                        break;
                                                    case 'pending':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Pending" aria-describedby="tooltip222393"> <i class="fa fa-hourglass-o order-status-pending"></i> </a>';
                                                        break;
                                                    case 'refunded':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Refund" aria-describedby="tooltip222393"> <i class="fa fa-retweet order-status-refund"></i> </a>';
                                                        break;
                                                    case 'processing':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Processing" aria-describedby="tooltip222393"> <i class="fa fa-hourglass-half order-status-processing"></i> </a>';
                                                        break;
                                                    case 'on-hold':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order On Hold" aria-describedby="tooltip222393"> <i class="fa fa-hand-stop-o order-status-on-hold"></i> </a>';
                                                        break;
                                                    case 'cancelled':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Cancelled" aria-describedby="tooltip222393"> <i class="fa fa-remove order-status-cancelled"></i> </a>';
                                                        break;
                                                    case 'failed':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Failed" aria-describedby="tooltip222393"> <i class="fa fa-warning order-status-failed"></i> </a>';
                                                        break;
                                                    case 'deleted':
                                                        echo '<a href="javascript:;" class="tooltips" title="" data-placement="bottom" data-original-title="Order Trashed" aria-describedby="tooltip222393"> <i class="fa fa-trash order-status-failed"></i> </a>';
                                                        break;

                                                }

                                            ?>

                                        </td>
                                        <td>
                                            <span><a href="{{ route('orders.edit',$order->id) }}" > #{{$order->id}} </a></span>
                                            <span class="color-gray">door</span>

                                            <span>
                                             @if($order->customer)
                                                    <span class="color-gray">{{ $order->customer->username }}</span>
                                                    <a href="{{ route('customers.edit',$order->customer->id) }}">
                                                        {{ $order->customer->email }}
                                                    </a>
                                             @else
                                                Gast
                                             @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="col-sm-12 npall">
                                                <a href="javascript:;" class="show_items" id="show_items_{{ $order->id }}">
                                                    {{ count($order->order_items) }} Items
                                                </a>
                                            </div>

                                            <div class="col-sm-12 items_div npall" id="items_div_{{ $order->id }}">
                                                <ul>
                                                @foreach($order->order_items as $order_item)
                                                    <li>
                                                   <?php
                                                        //$product = new \App\Products();
                                                        $product_details = \App\Products::find($order_item->product_id);
                                                        if($product_details)
                                                            {
                                                                echo '<span class="color-gray">'.$order_item->qty.'  ' . $product_details->sku . ' </span> <a href="'.route('products.edit', $product_details->id).'">'.$product_details->name.'</a>';
                                                            }
                                                    ?>
                                                    </li>
                                                @endforeach
                                                </ul>
                                            </div>

                                        </td>
                                        <td>
                                            @if($order->shipping_info)
                                                {{$order->shipping_info->first_name}}
                                                {{$order->shipping_info->last_name}}<br/>
                                                {{$order->shipping_info->address_1}},<br/>
                                                {{$order->shipping_info->city}},
                                                {{$order->shipping_info->postcode}}<br/>
                                                {{$order->shipping_info->email}}
                                            @endif
                                        </td>
                                        <td> {{ displayDate($order->created_at) }} </td>
                                        <td> {{ money($order->amount) }} </td>
                                        <td class="text-center">
                                            <div class="col-sm-12 npall">
                                                <div class="actions">
                                                    <div class="btn-group">
                                                        <a class="btn btn-xs btn-default dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                                                            <span class="hidden-xs"> Acties </span>
                                                            <i class="fa fa-angle-down"></i>
                                                        </a>
                                                        <div class="dropdown-menu pull-right">

                                                            @if($order->status !='processing')
                                                                <li>
                                                                    <a href="{{ url('orders', ['id' => $order->id, 'status' => 'processing']) }}"> <i class="fa fa-hourglass"></i> Mark in verwerking </a>
                                                                </li>
                                                            @endif
                                                            @if($order->status !='completed')
                                                                <li>
                                                                    <a href="{{ url('orders', ['id' => $order->id, 'status' => 'completed']) }}"> <i class="fa fa-check"></i> Markeer voltooid </a>
                                                                </li>
                                                            @endif

                                                                @if($order->status !='refunded')
                                                                    <li>
                                                                        <a href="{{ url('orders', ['id' => $order->id, 'status' => 'refunded']) }}"> <i class="fa fa-retweet"></i> Markeer teruggestort </a>
                                                                    </li>
                                                                @endif

                                                                @if($order->status !='pending')
                                                                    <li>
                                                                        <a href="{{ url('orders', ['id' => $order->id, 'status' => 'pending']) }}"> <i class="fa fa-hourglass-o"></i> Markeer in afwachting </a>
                                                                    </li>
                                                                @endif

                                                                @if($order->status !='on-hold')
                                                                    <li>
                                                                        <a href="{{ url('orders', ['id' => $order->id, 'status' => 'on-hold']) }}"> <i class="fa fa-hand-stop-o"></i> Markeer in de wacht </a>
                                                                    </li>
                                                                @endif

                                                                @if($order->status !='cancelled')
                                                                    <li>
                                                                        <a href="{{ url('orders', ['id' => $order->id, 'status' => 'cancelled']) }}"> <i class="fa fa-remove"></i> Markeer Geannuleerd </a>
                                                                    </li>
                                                                @endif

                                                                @if($order->status !='failed')
                                                                    <li>
                                                                        <a href="{{ url('orders', ['id' => $order->id, 'status' => 'failed']) }}"> <i class="fa fa-warning"></i> Markeer Mislukt </a>
                                                                    </li>
                                                                @endif


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-sm-12 npall action-icons">
                                                <a data-original-title="Send Confirmation Email" title="Stuur bevestigingsemail"><i class="fa fa-envelope"></i></a>
                                                <a href="{{ route('downloadinvoice',$order->id) }}" data-original-title="Download Receipt" title="Download Factuur"><i class="fa fa-file-text-o"></i></a>
                                                {{--<a class="btn btn-xs btn-primary" data-original-title="Move to Trash" title="Verwijderen"><i class="fa fa-trash"></i></a>--}}
                                                <a href="{{ route('orders.destroy',$order->id) }}"   data-method="delete" data-token="{{csrf_token()}}" data-confirm="Weet je het zeker?"data-original-title="Move to Trash" title="Verwijderen"><i class="fa fa-trash"></i></a>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"><h4>Geen bestellingen gevonden</h4></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        </form>
                        @if(count($orders) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    {{-- {!! $orders->links() !!}--}}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="pull-right">
                        <div class="row">
                            <div class="col-md-12">
                                {!! $orders->appends(Input::except('page'))->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
    </div>

</div>

{{-- end of page content --}}
@endsection

@section('scripts')
    {!! HTML::script('assets/js/orders_plugins.js') !!}
    {!! HTML::script('assets/js/orders.js') !!}
    {!! HTML::script('assets/js/laravel-delete.js') !!}
    <script>
        $('.date-picker').datepicker();
    </script>
@endsection
