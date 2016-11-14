@extends('layouts.master')

@section('css')

@endsection

@section('content')

    <!-- BEGIN tab BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <span>Actions</span>
            </li>
        </ul>

    </div>
    <br>

    {{-- pae content --}}

    <div class="row">
        <div class="col-md-12">
        <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-Products"></i>Actions </div>
                    <div class="actions">
                        <a href="{{ route('actions.create') }}" class="btn orders btn-info">
                            <i class="fa fa-plus"></i>
                            <span class="hidden-xs"> New Action</span>
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <div class="row">
                            <div class="col-sm-12">

                                @if(Session::has('flash_message'))
                                    <div class="alert alert-success">
                                        {{ Session::get('flash_message') }}
                                    </div>
                                @endif

                                <form role="form" action="" method="post">
                                <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                <table class="table table-striped table-bordered table-hover top10" id="datatable_products">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th width="30%"> Title </th>
                                            <th width="10%"> Type  </th>
                                            <th width="10%"> Applied on</th>
                                            <th width="15%"> Discount value</th>
                                            <th width="25%"> From - To Date</th>
                                            <th width="10%"> &nbsp; </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($pricing) > 0)
                                        @foreach($pricing as $action)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('actions.edit',$action->id) }}" >{{ $action->title }}</a>
                                                </td>
                                                <td>
                                                    @if($action->discount_type == 'percentage')
                                                        Percentage
                                                    @elseif($action->discount_type == 'fixed')
                                                        Fixed Amount
                                                    @elseif($action->discount_type == 'freeshipping')
                                                        Free Shipping
                                                    @elseif($action->discount_type == 'freeproduct')
                                                        Free Product
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $action->applies_on }}
                                                </td>
                                                <td>
                                                    @if($action->discount_type == 'freeshipping' ||  $action->discount_type == 'freeproduct')
                                                        -
                                                    @else
                                                        @if($action->discount_type == 'percentage')
                                                            {{ $action->discount_value }}%
                                                        @elseif($action->discount_type == 'fixed')
                                                            {{ money($action->discount_value) }}
                                                        @endif

                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $action->date_from }} - {{ $action->date_to }}
                                                </td>
                                                <td align="right">
                                                    <a href="{{ route('actions.edit',$action->id) }}" class="btn btn-xs btn-primary" title="Edit"><i class="fa fa-pencil"></i></a>
                                                    <a href="{{ route('actions.destroy',$action->id) }}" class="btn btn-xs btn-danger" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?"><i class="fa fa-remove"></i></a>
                                                </td>


                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td align="center" colspan="8"><h4>No tabs Found</h4></td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                                @if(count($pricing) > 0)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                                {{--{!! $data['tabs']->appends(Input::except('tab'))->links() !!}--}}
                                                {!! $pricing->links() !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                            </div>
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
    </div>

    {{-- end of tab content --}}
@endsection

@section('scripts')

    {!! HTML::script('assets/js/laravel-delete.js') !!}
@endsection
