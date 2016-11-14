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
                <span>Tabs</span>
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
                            <i class="fa fa-Products"></i>Tabs </div>
                        <div class="actions">
                            <a href="{{ route('tabs.create') }}" class="btn orders btn-info">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-xs"> New Tab</span>
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="row">
                                <form role="form" action="" method="post">
                                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="col-sm-3">
                                                @if(count($data['tabs']) > 0)
                                                    {!! $data['tabs']->appends(Input::except('tab'))->links() !!}
                                                    {{--{!! $data['products']->links() !!}--}}

                                                @endif
                                            </div>


                                        </div>

                                        <div class="clearfix"></div>

                                    </div>


                                    <table class="table table-striped table-bordered table-hover top10" id="datatable_products">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="40%"> Title </th>
                                            <th width="20%"> Tab Type </th>
                                            <th width="30%"> Parent Product </th>
                                            <th width="10%"> &nbsp; </th>

                                        </tr>

                                        </thead>
                                        <tbody>
                                        @if(count($data) > 0)
                                            @foreach($data as $tab)
                                                <tr>

                                                    <td>
                                                        <div class="btn-group">

                                                            <a href="" data-hover="dropdown" data-delay="500" data-close-others="true"> {!! $tab['name'] !!}  </a>

                                                            <ul class="dropdown-menu" role="menu">
                                                                <li>
                                                                    <a href="{{ route('tabs.edit',$tab['id']) }}"><i class="fa fa-pencil"></i> Edit </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;" class="quick-edit-modal-open" data-product-id=""><i class="fa fa-pencil"></i> Quick Edit</a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;"><i class="fa fa-file"></i> Copy themusthaves.nl  </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;"><i class="fa fa-file"></i> Copy themusthaves.de  </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;"><i class="fa fa-file"></i> Copy musthaveforreal.com  </a>
                                                                </li>

                                                                <li>

                                                                    <a href="javascript:;"><i class="fa fa-eye"></i> View </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;"> <i class="fa fa-trash"></i>Trash </a>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                    </td>
                                                    <td>
                                                        @if($tab['type'] == 'global') Global @else Product @endif
                                                    </td>
                                                    <td>
                                                        @if($tab['type'] == 'global') N/A @elseif($tab->products) <a href="{{ route('products.edit',$tab->products->id) }}"> {{ $tab->products->name  }} </a>@endif
                                                    </td>
                                                    <td align="right">
                                                        <a href="{{ route('tabs.edit',$tab['id']) }}" class="btn btn-xs btn-primary" title="Edit"><i class="fa fa-pencil"></i></a>
                                                        <a href="{{ route('tabs.destroy',$tab['id']) }}" class="btn btn-xs btn-danger" data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?"><i class="fa fa-remove"></i></a>
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
                                    @if(count($data['tabs']) > 0)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    {!! $data['tabs']->appends(Input::except('tab'))->links() !!}
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
    </div>

    {{-- end of tab content --}}
@endsection

@section('scripts')

    {!! HTML::script('assets/js/laravel-delete.js') !!}
@endsection
