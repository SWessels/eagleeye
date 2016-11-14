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
                <span>Pagina's</span>
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
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                        <!-- Begin: life time stats -->
                <div class="portlet ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-Products"></i>Pagina's </div>
                        <div class="actions">


                            <a href="{{ route('pages.create') }}" class="btn orders btn-info">
                                <i class="fa fa-plus"></i>
                                <span class="hidden-xs"> Nieuwe pagina</span>
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-6 npl">
                                        <a href="{{ url('pages') }}"> Alle <span class="badge badge-success">{{$pageCount['all']}}</span></a>  | <a href="{{ url('pages?status=publish') }}">Gepubliceerd</a> <span class="badge badge-primary">{{ $pageCount['published'] }}</span> | <a href="{{ url('pages?status=draft') }}">Concept</a> <span class="badge badge-primary">{{$pageCount['draft']}}</span> | <a href="{{ url('pages?status=deleted') }}">Verwijderd <span class="badge badge-primary"> {{$pageCount['deleted']}}</span> </a>


                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="col-sm-12 npl">

                                        <form  {{action('PagesController@index')}} class="form-horizental">
                                            <div class="col-sm-3 pull-right npr">

                                                <div class="form-group">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                                    <span class="input-group-btn">
                                                        <button class="btn red" type="submit">Zoeken</button>
                                                    </span>
                                                    </div>
                                                    <!-- /input-group -->
                                                </div>

                                            </div>
                                        </form>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                <form role="form" name="page_delform" id="page_delform" action=" {{action('PagesController@delete')}} " method="post">
                                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-sm-2 npl">
                                                <select  name="remove" id="remove" class="form-control input-sm">
                                                    <option value="">Acties</option>
                                                    <option value="1">Work</option>
                                                    <option value="rm">Verwijderen</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-5 npall">
                                                <button class="btn btn-sm">Bevestigen</button>
                                            </div>

                                            <div class="col-sm-2">

                                            </div>

                                            <div class="col-sm-3">
                                                @if(count($data['pages']) > 0)
                                                 {!! $data['pages']->appends(Input::except('page'))->links() !!}
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

                                            <th width="20%"> Titel </th>
                                            <th width="20%"> Auteur </th>
                                            <th width="15%"> Opmerkingen </th>
                                            <th width="15%"> Datum </th>

                                        </tr>

                                        </thead>
                                        <tbody>
                                        @if(count($data['pages']) > 0)

                                            @foreach($data['pages'] as $page)
                                             <tr>
                                                    <td><input name="del[]" id="del['{!! $page['id'] !!}']" value="{!! $page['id'] !!}" type="checkbox" class="checkboxes"></td>

                                                    <td>
                                                        <a href="{{ route('pages.edit',$page['id']) }}"> {!! $page['title'] !!}  </a>

                                                        <div class="product_menu" id="">
                                                            <span><a href="{{ route('pages.edit',$page['id']) }}">Bewerken</a> </span>
                                                            <span><a href="{{ route('pages.edit',$page['id']) }}">Snel bewerken</a> </span>
                                                            <span><a href="#"   data-method="delete" data-token="{{csrf_token()}}" data-confirm="Weet je het zeker?">Verwijderen</a> </span>
                                                            <span><a href="#">Kopiëren</a> </span></div>


                                                    </td>
                                                    <td>{!! $page->getauthorName($page['author']) !!}    </td>

                                                    <td></td>
                                                   <td>{!! date('d F Y', strtotime($page['published_at'])) !!} </td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td align="center" colspan="8"><h4>Geen pagina's gevonden</h4></td>
                                            </tr>
                                        @endif


                                        </tbody>
                                    </table>
                                    @if(count($data['pages']) > 0)
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="pull-right">
                                                    {!! $data['pages']->appends(Input::except('page'))->links() !!}
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
