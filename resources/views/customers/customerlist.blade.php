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
                <a href="/customers">Customers</a>

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
                            <i class="fa fa-Products"></i>Customers </div>
                       {{-- <div class="actions">

                        </div>--}}
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-6 npl">
                                        <a href="{{ url('customers') }}"> All <span class="badge badge-success">{{$customerCount['all']}}</span></a>  | <a href="{{ url('customers?status=active') }}">Active</a> <span class="badge badge-primary">{{ $customerCount['active'] }}</span> | <a href="{{ url('customers?status=inactive') }}">Inactive</a> <span class="badge badge-primary">{{$customerCount['inactive']}}</span> | <a href="{{ url('customers?status=deleted') }}">Trash <span class="badge badge-primary"> {{$customerCount['deleted']}}</span> </a>


                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-12 npl">

                                        <form  {{action('CustomerController@index')}} class="form-horizental">
                                            <div class="col-sm-3 pull-right npr">

                                                <div class="form-group">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Search">
                                                    <span class="input-group-btn">
                                                        <button class="btn red" type="submit">Search</button>
                                                    </span>
                                                    </div>
                                                    <!-- /input-group -->
                                                </div>

                                            </div>
                                        </form>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <form role="form" action="{{action('CustomerController@delete')}}" method="post">
                                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-sm-2 npl">
                                                <select  name="remove" id="remove" class="form-control input-sm">
                                                    <option value="">Actions</option>
                                                    <option value="inactive">Inactive</option>
                                                    <option value="delete">In trash</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-5 npall">
                                                <button class="btn btn-sm">Apply</button>
                                            </div>

                                            <div class="col-sm-2">

                                            </div>

                                            <div class="col-sm-5">
                                                @if(count($data['customers']) > 0)

                                                            <div class="pull-right">
                                                                {!! $data['customers']->appends(Input::except('page'))->links() !!}
                                                                {{--{!! $data['products']->links() !!}--}}
                                                            </div>

                                                @endif
                                            </div>


                                        </div>

                                        <div class="clearfix"></div>

                                    </div>


                                    <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="5%">
                                                <input type="checkbox" class="group-checkable" id="ck">
                                            </th>
                                            <th width="20%"> Username </th>
                                            <th width="20%"> Name </th>
                                            <th width="20%"> Email </th>
                                        </tr>

                                        </thead>
                                        <tbody>
                                        @if(count($data['customers']) > 0)

                                            @foreach($data['customers'] as $customer)
                                                <tr>
                                                    <td><input name="del[]" id="del[]" value="{!! $customer['id'] !!}" type="checkbox" class="checkboxes"></td>

                                                    <td> <a href="{{ route('customers.edit',$customer['id']) }}" > {!! $customer['username'] !!}  </a>

                                                        <div class="product_menu" id="">
                                                            <span><a href="{{ route('customers.edit',$customer['id']) }}">Edit</a> </span>
                                                            <span><a href="{{ route('customers.edit',$customer['id']) }}">Quick Edit</a> </span>
                                                            <span><a href="#"   data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">Trash</a> </span>
                                                            <span><a href="#">Copy</a> </span></div>


                                                    </td>
                                                    <td>{!! $customer['first_name']." ".$customer['last_name'] !!} </td>
                                                    <td>{!! $customer['email'] !!}</td>
                                                    {{--<td>@if($customer['status'] == 'active') Active @elseif($customer['status'] == 'inactive') Inactive @elseif($customer['status'] == 'deleted')Deleted @endif</td>--}}

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td align="center" colspan="8"><h4>No Customers Found</h4></td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                    @if(count($data['customers']) > 0)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    {!! $data['customers']->appends(Input::except('page'))->links() !!}
                                                    {{--{!! $data['products']->links() !!}--}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>

                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
        </div>

        <div class="modal fade in" id="quick-edit-model" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal Title</h4>
                    </div>
                    <div class="modal-body"> Modal body goes here </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    {{-- end of page content --}}
@endsection

@section('scripts')

@endsection
