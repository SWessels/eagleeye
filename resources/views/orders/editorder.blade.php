@extends('layouts.master')

@section('css')


{!! HTML::style('assets/css/orders_plugins.css') !!}


@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('orders.index') }}>Bestellingen</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Bestellingen
    <small>Bestelling wijzigen</small>
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
{!! Form::open(array('method' => 'patch', 'id'=> 'order-form' ,'action' => array('OrdersController@update',$order->id))) !!}

        <input type="hidden" name="order_id" id="order_id" value="{{ $order->id }}">
    <div class="row">
        <div class="col-sm-12">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>

            @endif
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            </div>

            <div class="col-md-9">
                <div class="portlet light">

                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Order {{ $order->id }} </h3>
                                <p class="order_details">Betaling via Klarna  | Transactie ID: 4389693240923</p>
                                <?php
                                    $client_details = explode("|", $order->client_details);
                                    if(isset($client_details[0]))
                                    {
                                        $client_ip = $client_details[0];
                                    }else{
                                        $client_ip = '';
                                    }

                                    if(isset($client_details[1]))
                                    {
                                        $user_agent = $client_details[1];
                                    }else{
                                        $user_agent = '';
                                    }

                                ?>
                                <p class="order_details">Klant IP: <?php echo $client_ip; ?> </p>
                                <p class="order_details">User Agent: <?php echo $user_agent; ?> </p>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 npall">
                                        <div class="col-md-12">
                                            <h5><strong>Algemene details</strong></h5>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label>Order Datum</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control input-sm" id="order_date_time" name="order_date_time" value="{{ $order->created_at }}"> </div>
                                                </div>


                                                <div class="form-group">
                                                    <label>Customer</label>
                                                    <div class="input-group">
                                                        <div class="customer_drop_down">
                                                            <select name="order_customer" class="form-control" id="order_customer">
                                                                @if($order->customer)
                                                                    <option value="{{ $order->customer_id }}"> @if($order->customer_id == 0) Guest @else {{ $order->customer->username }} # {{ $order->customer->id }} {{ $order->customer->email }} @endif</option>
                                                                @else
                                                                    <option>Gast</option>
                                                                @endif

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 npr">
                                        <div class="col-md-12 npall">
                                            <div class="billing_details">
                                                <div class="col-md-12">
                                                    <h5><strong>Factuuradres</strong>
                                                        <span class="pull-right">
                                                            <a class="billing_form_action"><i class="fa fa-pencil"></i></a>
                                                        </span>
                                                    </h5>
                                                    <div class="billing_details_display">
                                                        <p>
                                                            <div class="col-sm-12 npall"><strong class="gray">Adres</strong></div>
                                                            {{ @$order->billing_info->first_name}}
                                                            {{ @$order->billing_info->last_name}}<br/>
                                                            {{ @$order->billing_info->city}}<br/>
                                                            {{ @$order->billing_info->address_1}},<br/>
                                                            {{ @$order->billing_info->postcode}}
                                                        </p>
                                                        <p>
                                                            <div class="col-sm-12 npall"><strong class="gray">E-mail</strong></div>
                                                            <p class="col-sm-12 npall">@if( @$order->billing_info->email){{ @$order->billing_info->email}}@else - @endif</p>
                                                        </p>
                                                        <p>
                                                            <div class="col-sm-12 npall"><strong class="gray">Telefoon</strong></div>
                                                            <p class="col-sm-12 npall">{{ @$order->billing_info->phone }}</p>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="billing_details_form col-sm-12 npall">
                                                <div class="col-md-6 npall">
                                                    <div class="form-group">
                                                        <label>Voornaam</label>
                                                        <div class="input-group">
                                                            <input type="text" name="billing_fname" class="form-control input-sm" value="{{ @$order->billing_info->first_name  }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 npall">
                                                    <div class="form-group">
                                                        <label>Achternaam</label>
                                                        <div class="input-$order->billing_info->first_nameoup">
                                                            <input type="text" name="billing_lname" class="form-control input-sm" value="{{ @$order->billing_info->last_name }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 npall">
                                                    <div class="form-group">
                                                        <label>Address 1</label>
                                                        <div class="input-group">
                                                            <input type="text" name="billing_address1" class="form-control input-sm" value="{{ @$order->billing_info->address_1 }}">
                                                        </div>
                                                    </div>
                                                </div>
   
                                                <div class="col-md-6 npall">
                                                    <div class="form-group">
                                                        <label>Postcode</label>
                                                        <div class="input-group">
                                                            <input type="text" name="billing_postcode" class="form-control input-sm" value="{{ @$order->billing_info->postcode }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 npall">
                                                    <div class="form-group">
                                                        <label>Plaats</label>
                                                        <div class="input-group">
                                                            <input type="text" name="billing_city" class="form-control input-sm" value="{{ @$order->billing_info->city }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 npall">
                                                    <div class="form-group">
                                                        <label>Land</label>
                                                        <div class="input-group">
                                                            <select class="form-control" name="billing_country">
                                                                @foreach($countries as $country)
                                                                    @if($country->code == $order->billing_info->country)
                                                                        <option value="{{ $country->code }}" selected>{{ $country->name }}</option>
                                                                    @else
                                                                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 npall">
                                                    <div class="form-group">
                                                        <label>E-mail</label>
                                                        <div class="input-group">
                                                            <input type="text" name="billing_email" class="form-control input-sm" value="{{ $order->billing_info->email }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 npall">
                                                    <div class="form-group">
                                                        <label>Telefoon</label>
                                                        <div class="input-group">
                                                            <input type="text" name="billing_phone" class="form-control input-sm" value="{{ $order->billing_info->phone }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 npr">
                                        <div class="shipping_details">
                                            <div class="col-md-12 npall">
                                                <h5><strong>Verzendadres</strong>
                                                        <span class="pull-right">
                                                            <a class="shipping_form_action"><i class="fa fa-pencil"></i></a>
                                                        </span>
                                                </h5>
                                                <div class="shipping_details_display">
                                                    <p>
                                                    <div class="col-sm-12 npall"><strong class="gray">Adres</strong></div>
                                                    {{$order->shipping_info->first_name}}
                                                    {{$order->shipping_info->last_name}}<br/>
                                                    {{$order->shipping_info->city}}<br/>
                                                    {{$order->shipping_info->address_1}},<br/>
                                                    {{$order->shipping_info->postcode}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shipping_details_form col-sm-12 npall">

                                            <div class="col-md-6 npall">
                                                <div class="form-group">
                                                    <label>Voornaam</label>
                                                    <div class="input-group">
                                                        <input type="text" name="shipping_fname" class="form-control input-sm" value="{{ $order->shipping_info->first_name  }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 npall">
                                                <div class="form-group">
                                                    <label>Achternaam</label>
                                                    <div class="input-$order->shipping_info->first_nameoup">
                                                        <input type="text" name="shipping_lname" class="form-control input-sm" value="{{ $order->shipping_info->last_name }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 npall">
                                                <div class="form-group">
                                                    <label>Adres</label>
                                                    <div class="input-group">
                                                        <input type="text" name="shipping_address1" class="form-control input-sm" value="{{ $order->shipping_info->address_1 }}">
                                                    </div>
                                                </div>
                                            </div>
 
                                            <div class="col-md-6 npall">
                                                <div class="form-group">
                                                    <label>Postcode</label>
                                                    <div class="input-group">
                                                        <input type="text" name="shipping_postcode" class="form-control input-sm" value="{{ $order->shipping_info->postcode }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 npall">
                                                <div class="form-group">
                                                    <label>Plaats</label>
                                                    <div class="input-group">
                                                        <input type="text" name="shipping_city" class="form-control input-sm" value="{{ $order->shipping_info->city }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 npall">
                                                <div class="form-group">
                                                    <label>Land</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="shipping_country">
                                                            @foreach($countries as $country)
                                                                @if($country->code == $order->shipping_info->country)
                                                                    <option value="{{ $country->code }}" selected>{{ $country->name }}</option>
                                                                @else
                                                                    <option value="{{ $country->code }}">{{ $country->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-md-12 npall">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-puzzle font-grey-gallery"></i>
                                <span class="caption-subject bold font-grey-gallery uppercase"> Bestelling items </span>
                            </div>
                            <div class="tools">
                                <a href="" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table data-toggle="table" class="table-light">
                                <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" class="group-checkable" id="check-all-orders">
                                    </th>
                                    <th  width="53%" data-field="item" data-sortable="true" >Item</th>
                                    <th  width="8%" data-field="price"  data-sortable="true" >Prijs excl. BTW</th>
                                    <th  width="8%" data-field="qty"  data-sortable="true">Aantal</th>
                                    <th  width="8%" data-field="total"  data-sortable="true">Totaal</th>
                                    <th  width="8%" data-field="vat"  data-sortable="true">BTW</th>
                                    <th  width="10%" data-field="column"  data-sortable="true">Prijs incl. BTW</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                        $total_discount = 0;
                                        $total_shipping_cost = 0 ;
                                        $total_vat = 0;
                                        $total_order = 0 ;
                                        $amount_total =  0 ;
                                        $total_refund = 0;
                                ?>
                                @foreach($order->order_items as $order_item)

                                <tr>
                                    <td>
                                        <input type="checkbox" class="group-checkable" id="{{ $order_item->id }}">
                                    </td>
                                    <?php
                                    $product_details = \App\Products::with('parent')->with('attributes')->with('media_featured_image')->where('id', $order_item->product_id)->first();
                                    //dd($product_details);
                                    if($product_details)
                                    {
                                        echo '<td><div class="col-md-12 npall"><span class="p-image-mini">
                                                <img src="'.productSmallThumb($product_details->path).'" alt="">
                                                </span>';
                                        echo $product_details->sku .' - ' ;

                                        if($product_details->product_type =='variation')
                                            {
                                                echo '<a href="'.route('products.edit',$product_details->parent->id).'">';
                                                echo $product_details->parent->name;
                                            }else{
                                            echo '<a href="'.route('products.edit',$order_item->product_id).'">';
                                            echo $product_details->name;
                                        }
                                        echo '</a>';
                                        if($product_details->attributes)
                                            {
                                                $atts = unserialize($product_details->attributes->attributes);


                                                foreach ($atts as $att => $term)
                                                    {

                                                        $att_details = \App\ProductAttributes::find($att);
                                                        //dd($att_details);
                                                        $term_obj   =  new \App\Terms();
                                                        $term_db    = $term_obj->getTermBySlug($term);
                                                        if($att_details && $term_db)
                                                            {
                                                                echo '<span class="attribute-span"><strong>'.$att_details->name.': </strong>'.$term_db->name.'</span>';
                                                            }

                                                    }
                                            }
                                        //echo '<span class="full-width"><strong>Back ordered: </strong> 2 </span>';
                                        echo '</div></td>';
                                    }else{
                                        echo '<td><div class="col-md-12 npall"> <span class="p-image-mini">
                                                <img src="'.productSmallThumb('').'" alt="">
                                                </span>';
                                        //echo '<span class="full-width"><strong>Back ordered: </strong> 2 </span>';
                                        echo '</div></td>';
                                    }
                                    ?>

                                    <td>{{ money($order_item->unit_price) }}</td>
                                    <td>{{ $order_item->qty }}</td>
                                    <td>{{ money($order_item->total) }}</td>
                                    <td>{{ money($order_item->total_tax) }}</td>
                                    <td>{{ money($order_item->unit_price + $order_item->total_tax) }}</td>
                                </tr>
                                    <?php

                                            $amount_total += $order_item->total  ;
                                            $total_discount = 0;
                                            $total_shipping_cost = 0 ;
                                            $total_vat += $order_item->total_tax;
                                            $total_order += $order_item->total;

                                    ?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if(count($order->refunds) > 0)
                <div class="col-md-12 npall">
                    <div class="portlet light">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-puzzle font-grey-gallery"></i>
                                <span class="caption-subject bold font-grey-gallery uppercase"> Retouren </span>
                            </div>
                            <div class="tools">
                                <a href="" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-light">
                                <tbody>

                                @if($order->refunds)
                                    @foreach($order->refunds as $refund)
                                        @foreach($refund->refundItems as $refundItem)
                                            <?php
                                            $product_details = \App\Products::with('attributes')->where('id', $refundItem->product_id)->first();
                                            //dd($product_details);
                                            if($product_details)
                                            {
                                                echo '<tr><td>&nbsp;</td><td>';
                                                echo $product_details->sku .' - ' ;
                                                echo $product_details->name;
                                                echo ' - ';
                                                echo $refund->created_at;
                                                echo ' - ';
                                                echo $refund->reason;
                                                if($product_details->attributes)
                                                {
                                                    $atts = unserialize($product_details->attributes->attributes);
                                                    foreach ($atts as $att => $term)
                                                    {
                                                        $att_details = \App\ProductAttributes::find($att);
                                                        //dd($att_details);
                                                        $term_obj   =  new \App\Terms();
                                                        $term_db    = $term_obj->getTermBySlug($term);
                                                        if($att_details && $term_db)
                                                        {
                                                            echo '<span class="attribute-span"><strong>'.$att_details->name.': </strong>'.$term_db->name.'</span>';
                                                        }

                                                    }
                                                }
                                                echo '</td>';
                                                echo '<td>';
                                                echo  '-' . money($refund->amount);
                                                echo '</td>';
                                                echo '</tr>';

                                            }
                                            ?>
                                        @endforeach
                                        <?php $total_refund += $refund->amount; ?>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-sm-12 npall">
                    <table class="table pull-right">

                        <tbody>
                        <tr>
                            <td class="text-right">Korting:</td>
                            <td class="text-right"><?php echo money($total_discount); ?></td>
                        </tr>

                        <tr>
                            <td class="text-right">Verzendkosten:</td>
                            <td class="text-right"><?php echo money($total_shipping_cost); ?></td>
                        </tr>

                        <tr>
                            <td class="text-right">BTW:</td>
                            <td class="text-right"><?php echo money($total_vat); ?></td>
                        </tr>

                        <tr>
                            <td class="text-right">Bestelling Totaal:</td>
                            <td class="text-right"><?php if($total_refund>0) { echo '<span class="line-through">'.money(($amount_total+$total_vat)).'</span> '  ;}  echo money(($amount_total+$total_vat)-$total_refund); ?></td>
                        </tr>

                        <tr class="color-red">
                            <td class="text-right">Retour:</td>
                            <td class="text-right"><?php echo '-' . money($total_refund); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-3 npall">
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-grey-gallery uppercase"> Bestelling acties </span>
                        </div>
                        <div class="tools">
                            <a href="" class="collapse" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <select name="order_action" class="form-control">
                            <option value="completed" @if($order->status == 'completed') selected="selected" @endif>  Voltooid </option>
                            <option value="cancelled" @if($order->status == 'cancelled') selected="selected" @endif> Geannuleerd </option>
                            <option value="pending" @if($order->status == 'pending') selected="selected" @endif> In afwachting </option>
                            <option value="refunded" @if($order->status == 'refunded') selected="selected" @endif> Teruggestort </option>
                            <option value="failed" @if($order->status == 'failed') selected="selected" @endif> Mislukt </option>
                            <option value="processing" @if($order->status == 'processing') selected="selected" @endif> In verwerking </option>
                            <option value="on-hold" @if($order->status == 'on-hold') selected="selected" @endif> In de wacht</option>
                            <option value="deleted" @if($order->status == 'deleted') selected="selected" @endif> Verwijderd </option>
                        </select>
                        <hr>
                        <div class="col-sm-12">
                            <button  type="submit" class="btn btn-primary pull-right"> Bestelling opslaan</button>
                        </div>
                    </div>


                </div>
                <hr>
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject font-grey-gallery uppercase"> Bestelnotities</span>
                        </div>
                        <div class="tools">
                            <a href="" class="collapse" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="col-sm-12 npall" id="order_notes_div">
                            <ul class="order_notes_ul">
                                @if($order->orderNotes)
                                    @foreach($order->orderNotes as $orderNote)

                                        <li id="order_note_{{ $orderNote->id }}" class="order_note">
                                            <div class="note_content">
                                                <p>{{ $orderNote->note }}</p>
                                            </div>
                                            <p class="meta">
                                                <abbr class="exact-date" title="{{ $orderNote->created_at }}">
                                                <?php
                                                        $exp_date = explode(' ',$orderNote->created_at);
                                                        echo $date = $exp_date[0];
                                                        echo $time = $exp_date[1];
                                                    ?>
                                                </abbr>
                                                <a class="delete_order_note" id="delete_order_note_{{ $orderNote->id }}">Notitie verwijderen</a>
                                            </p>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <div class="col-sm-12 npall">
                            <textarea name="order_note" id="order_note" class="order_note"></textarea>
                            <hr>
                            <div class="col-sm-12 npall">
                                <button  type="button" id="save_order_note" class="btn btn-primary pull-right"> Notitie opslaan</button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>

    </div>
</form>

@extends('includes.gallery')


@endsection

@section('scripts')
    {!! HTML::script('assets/js/orders_plugins.js') !!}
    {!! HTML::script('assets/js/orders.js') !!}
@endsection