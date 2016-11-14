@extends('layouts.master')

@section('css')

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
            <span>Voorraad</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}

<div class="row">
    <div class="col-md-12">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
            @endif

                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-orders"></i>Stocks </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="orders-status-ul">
                                    <li> <a href="/stocks"> Alle <span class="badge badge-primary">{{ $counts['all'] }}</span> </a> </li>
                                    <li> <a href="/stocks?filter=in"> In voorraad <span class="badge badge-success">{{ $counts['instock'] }}</span>  </a> </li>
                                    <li> <a href="/stocks?filter=near"> Laag in voorraad <span class="badge badge-warning">{{ $counts['nealry_outofstock'] }}</span>  </a></li>
                                    <li> <a href="/stocks?filter=out"> Niet in voorraad<span class="badge badge-danger">{{ $counts['outofstock'] }}</span>  </a></li>
                                </ul>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-9 npl">
                                    <form class="pull-left col-sm-5 npall nmall">
                                        <div class="form-group-sm col-sm-8 npall nmall">
                                            <select class="form-control" id="pagination">
                                                <option>50</option>
                                                <option>100</option>
                                                <option>200</option>
                                                <option>500</option>
                                            </select>
                                        </div>

                                        {{--<div class="form-group-sm col-sm-4">
                                            <button type="button" class="btn btn-sm btn-default">Toepassen</button>
                                        </div>--}}
                                    </form>
                                </div>
                                <div class="col-sm-3">
                                <form class="pull-right npall nmall">
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" placeholder="Zoeken" name="keyword">
                                                <span class="input-group-btn">
                                                    <button class="btn green" type="submit">Zoeken</button>
                                                </span>
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_orders">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="5%">
                                    <input type="checkbox" class="group-checkable">
                                </th>
                                <th width="5%"> Status </th>
                                <th width="25%"> Productnaam </th>
                                <th width="10%"> Voorraadaantal </th>
                                <th width="10%"> Levertijd</th>
                                <th width="10%"> Producthorizon </th>
                                <th width="10%"> Retouren </th>
                                <th width="10%"> Gemiddelde dagelijkse verkopen </th>
                                <th width="10%"> Verwachte datum uit voorraad </th>
                                <th width="10%"> Aantal bijbestellen</th>
                            </tr>

                            </thead>
                            <tbody>

                            @if(count($stocks) > 0)

                                @foreach($stocks as $item)

                                    <tr @if($inventory[$item['id']]['stock_status'] == 'in') @if(!$item['near_stock'])class="success"@else class="warning" @endif @elseif($inventory[$item['id']]['stock_status'] == 'out') class="danger" @endif>
                                        <td><input type="checkbox" class="group-checkable"></td>
                                        <td> <i class="check-square"></i>{{$inventory[$item['id']]['stock_status']}}</td>
                                        <td> @if(strpos($item['name'],'Variatie')===false){{$item['name']}}@else {{substr($item['name'],(strpos($item['name'],'van') + 4))}} @endif @if(!empty($attribute[$item['id']])) - <strong>{!!$attribute[$item['id']]!!}</strong> @endif <br/><small>{{$item['sku']}}</small> </td>
                                        <td> {{$inventory[$item['id']]['stock_qty']}}</td>
                                        <td class="text-edit"> <span>{{$inventory[$item['id']]['days_for_delivery']}}</span><input style="display:none;width: 100%" class="txdedit" type="number" name="delivery-{{$item['id']}}" id="delivery-{{$item['id']}}" data-id="{{$item['sku']}}" value="{{$inventory[$item['id']]['days_for_delivery']}}" /> </td>
                                        <td class="text-edit"> <span>{{$inventory[$item['id']]['product_horizon']}}</span><input style="display: none; width: 100%" class="txhedit" type="number" name="horizon-{{$item['id']}}" id="horizon-{{$item['id']}}" data-id="{{$item['sku']}}" value="{{$inventory[$item['id']]['product_horizon']}}" /></td>
                                        <td> {{$refund[$item['id']]}}</td>
                                        <td> {{$average[$item['id']]}} </td>
                                        <td> {{$date_outstock[$item['id']]}}</td>
                                        <td> {{$amount_required[$item['id']]}} </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <d colspan="7"><h4>Geen producten gevonden.</h4></d>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        @if(count($stocks) > 0)
                            <div class="pull-right">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! $stocks->appends(Input::except('page'))->links() !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

</div>
</div>
<!-- End: life time stats -->
</div>

</div>

{{-- end of page content --}}
@endsection

@section('scripts')
<script type="text/javascript">
    $('.text-edit span').click(function () {
        $(this).hide();
        $(this).parent().find('.txhedit').show().focus();
        $(this).parent().find('.txdedit').show().focus();
    });
    $('.txhedit').blur(function () {
        $(this).hide();
        $(this).parent().find('span').show().html($(this).val());
        $.ajax({
            url: '/stocks/updateHorizon',
            data:{'horizon':$(this).val(), 'sku':$(this).attr('data-id')},
            type:'post',
            success: function (response) {

            }
        });

    })
    $('.txdedit').blur(function () {
        $(this).hide();
        $(this).parent().find('span').show().html($(this).val());
        $.ajax({
            url: '/stocks/updateDelivery',
            data:{'delivery':$(this).val(), 'sku':$(this).attr('data-id')},
            type:'post',
            success: function (response) {

            }
        });

    })
    $(document).ready(function () {
       $('#pagination').change(function () {
           $.ajax({
               url: '/stocks/pagination',
               data:{'pagination':$(this).val()},
               type:'post',
               success: function (response) {
                    window.location.href = window.location.href;
               }
           });
       });
        $('#pagination').val({{$pagination}});
    });
</script>
@endsection
