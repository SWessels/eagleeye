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
            <span>Tags</span>
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
                        <span class="caption-subject font-blue-sharp bold uppercase">Add New Tag</span>
                    </div>

                </div>
                <div class="portlet-body form">
                    <form role="form" id="tag_add" action=" {{action('TagsController@store')}} " method="post">
                        <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                        <div class="form-body">

                            <div class="form-group">
                                <label>Name</label>

                                    <input name="name" id="name" type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label>Slug</label>

                                    <input name="slug" id="slug" type="text" class="form-control input-sm" >
                            </div>

                            <div class="form-group">
                                <label>description</label>
                                <textarea name="desc"  id="desc" class="form-control" rows="3" cols="4"></textarea>
                            </div>

                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn blue">Submit</button>
                            {{--<button type="button" class="btn default">Cancel</button>--}}
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
        @if(Session::has('addtag'))
            <div class="alert alert-success">
                {{ Session::get('addtag') }}
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
                                    <form role="form" action=" {{action('TagsController@index')}} " method="get"  class="pull-right col-sm-6 npall nmall">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Search">
                                                    <span class="input-group-btn">
                                                        <input class="btn red" name="search" id="search" type="submit">Search</input>
                                                    </span>
                                            </div>
                                            <!-- /input-group -->
                                        </div>

                                    </form>
                                </div>

                            </div>
                        </div>
                        <form role="form" action=" {{action('TagsController@delete')}} " method="post">
                            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                            @if(count($tags) > 0)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group col-md-3">

                                            <select name="remove"  id="remove" class="form-control">
                                                <option value="">Actions</option>
                                                <option value="rm">Remove</option>

                                            </select>
                                        </div> <button type="submit" class="btn blue">Apply</button><div class="pull-right">
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
                                <th width="30%"> Name </th>
                                <th width="30%"> Description </th>
                                <th width="25%"> Slug </th>
                                <th width="3%"> Count </th>

                            </tr>

                            </thead>
                            <tbody> 
                            @if(count($tags) > 0)

                                @foreach($tags as $tag)

                                    <tr>
                                        <td><input name="del['{{ $tag->id }}']" id="del['{{ $tag->id }}']" value="{{ $tag->id }}" type="checkbox" class="checkboxes"></td>
                                        <td> <a href="  {{ route('tags.edit',$tag->id) }}">{{ $tag->name }}</a></td>
                                        <td> {{ $tag->description }}</td>
                                        <td> {{ $tag->slug }}</td>
                                        <td> {{ $tag->product_count }} </td>
                                        {{--<td>
                                            <div class="actions">
                                                <div class="btn-group">
                                                    <a class="btn btn-xs orders btn-default dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                                                        <span class="hidden-xs"> Actions </span>
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu pull-right">
                                                        <li>
                                                            <a href="{{ URL::to('tags/' . $tag->id . '/edit') }}"> <i class="fa fa-pencil"></i> Edit </a>
                                                        </li>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>--}}
                                    </tr>

                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="7"><h4>No Tags Found</h4></td>
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
