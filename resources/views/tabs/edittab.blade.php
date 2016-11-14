@extends('layouts.master')

@section('css')

{!! HTML::style('assets/css/post_plugins.css') !!}
@endsection

@section('content')


    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
            <li><a href={{ route('tabs.index') }}>Tabs</a></li>
        </ul>

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> Tabs
        <small>Update Tab</small>
    </h3>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->

    {!! Form::open(array('method' => 'patch', 'id'=> 'tab-form' ,'action' => array('TabsController@update',$tab->id))) !!}
        <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
        <div class="row">
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
            <div class="col-md-9">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ $tab->name }}">
                </div>

                <div class="portlet box default">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>Tab description
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <textarea class="form-control" name="tab-editor" id="tab-editor">{{ $tab->description }}</textarea>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="portlet box default">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gear"></i>Actions
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <button name="save" id="save" type="submit"  class="btn btn-primary pull-left btn-sm ">Update Tab</button>
                        <a href="{{ route('tabs.destroy',$tab['id']) }}" class="btn btn-danger pull-right btn-sm " data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">Delete Tab</a>

                        <hr>
                        <div class="clearfix"></div>

                    </div>

                </div>

                <div class="portlet box default">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gear"></i>Type
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <select name="parent_id" class="form-control parent_product">
                            <option value="0" @if($tab->parent_id == 0) selected="selected" @endif >Global</option>
                            @foreach($products as $product)
                                @if($tab->parent_id == $product->id)
                                    <option value="{{ $product->id }}" selected="selected">{{ $product->sku }} - {{ $product->name }}</option>
                                @else
                                    <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <hr>
                        <div class="clearfix"></div>

                    </div>

                </div>



            </div>

        </div>
    </form>



@endsection

@section('scripts')
    <script>
        $(function() {
            CKEDITOR.replace('tab-editor');
            $(".parent_product").select2();
        });
    </script>
    {!! HTML::script('assets/js/post_plugins.js') !!}
    {!! HTML::script('assets/plugins/ckeditor/ckeditor.js') !!}
    {!! HTML::script('assets/js/global.js') !!}
    {!! HTML::script('assets/js/laravel-delete.js') !!}
@endsection