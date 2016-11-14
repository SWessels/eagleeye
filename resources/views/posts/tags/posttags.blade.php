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
            <span>Bericht tags</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



<div class="row">
    <div class="col-md-4 ">
        <div class="portlet-body">
            <div id="context" data-toggle="context" data-target="#context-menu">
                <p> </p>
            </div>
        </div>
        <div class="col-md-12 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-social-dribbble font-blue-sharp"></i>
                        <span class="caption-subject font-blue-sharp bold uppercase">Nieuwe bericht tag toevoegen</span>
                    </div>

                </div>
                <div class="portlet-body form">
                    <form role="form" id="tag_add" action=" {{action('PostTagsController@store')}} " method="post">
                        <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                        <div class="form-body">

                            <div class="form-group">
                                <label>Naam</label>

                                <input name="name" id="name" type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label>Slug</label>

                                <input name="slug" id="slug" type="text" class="form-control input-sm" >
                            </div>

                            <div class="form-group">
                                <label>Beschrijving</label>
                                <textarea name="desc"  id="desc" class="form-control" rows="3" cols="4"></textarea>
                            </div>

                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn blue">Invoeren</button>
                            {{--<button type="button" class="btn default">Annuleren</button>--}}
                        </div>
                    </form>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
            <!-- BEGIN SAMPLE FORM PORTLET-->

            <!-- END SAMPLE FORM PORTLET-->
            <!-- BEGIN SAMPLE FORM PORTLET-->

            <!-- END SAMPLE FORM PORTLET-->
        </div>

    </div>
    <div class="col-md-8">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
            @endif

                    <!-- Begin: life time stats -->
            <div class="portlet ">

                <div class="portlet-body">

                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    All({{ count($tags) }})
                                </div>
                                <div class="col-sm-6">
                                    <form role="form" action=" {{action('PostTagsController@index')}} " method="get"  class="pull-right col-sm-6 npall nmall">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Search">
                                                    <span class="input-group-btn">
                                                        <input class="btn red" name="search" id="search" type="submit">Zoeken</input>
                                                    </span>
                                            </div>
                                            <!-- /input-group -->
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                        <form role="form" name="posttag_delform" id="posttag_delform" action=" {{action('PostTagsController@delete')}}" method="post">
                            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                            @if(count($tags) > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group col-md-3">

                                            <select name="remove"  id="remove" class="form-control">
                                                <option value="">Acties</option>
                                                <option value="rm">Verwijderen</option>

                                            </select>
                                        </div> <button type="submit" class="btn blue">Toepassen</button><div class="pull-right">
                                            {!!$tags->appends(Input::except('page'))->links() !!}
                                            {{-- {!! $tags->links() !!}--}}
                                        </div>

                                    </div>
                                </div>
                            @endif


                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_products">
                                <thead>
                                <tr role="row" class="heading">
                                    <th width="5%">
                                        <input type="checkbox"  class="group-checkable" id="ck">
                                    </th>
                                    <th width="30%"> Naam </th>
                                    <th width="30%"> Bescrijving </th>
                                    <th width="25%"> Slug </th>
                                    <th width="3%"> Aantal </th>

                                </tr>

                                </thead>
                                <tbody>
                                @if(count($tags) > 0)

                                    @foreach($tags as $tag)

                                        <tr>
                                            <td><input name="del[]" id="del['{{ $tag->id }}']" value="{{ $tag->id }}" type="checkbox" class="checkboxes"></td>
                                            <td> <a href="  {{ route('posttags.edit',$tag->id) }}">{{ $tag->name }}</a></td>
                                            <td> {{ $tag->description }}</td>
                                            <td> {{ $tag->slug }}</td>
                                            <td> {{ $tag->getCountPostsOfTags($tag->id) }} </td>
                                            {{--<td>
                                                <div class="actions">
                                                    <div class="btn-group">
                                                        <a class="btn btn-xs orders btn-default dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                                                            <span class="hidden-xs"> Acties </span>
                                                            <i class="fa fa-angle-down"></i>
                                                        </a>
                                                        <div class="dropdown-menu pull-right">
                                                            <li>
                                                                <a href="{{ URL::to('tags/' . $tag->id . '/edit') }}"> <i class="fa fa-pencil"></i> Wijzigen </a>
                                                            </li>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>--}}
                                        </tr>

                                    @endforeach
                                @else
                                    <tr>
                                        <td align="center" colspan="7"><h4>Geen tags gevonden</h4></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(count($tags) > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            {!!$tags->appends(Input::except('page'))->links() !!}
                                            {{-- {!! $tags->links() !!}--}}
                                        </div>

                                    </div>
                                </div>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
            <!-- End: life time stats -->
    </div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')

@endsection
