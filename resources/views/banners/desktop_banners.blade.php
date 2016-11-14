@extends('layouts.master_angular')

    @section('css')
        {!! HTML::style('assets/css/sliders_plugins.css') !!}
    @endsection

    @section('content')

            <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ route('home') }}">Home</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <span>Desktop Banners</span>
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
                            <i class="fa fa-Products"></i>Desktop Banners</div>
                        <div class="actions">
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="tabbable tabbable-tabdrop">
                                        <ul class="nav nav-tabs">
                                            <li  <?php if(isset($type) && $type == 'desktop-product-category' ){ echo " class='active'"; } ?> >
                                                <a href="#desktop_category_banners" data-toggle="tab">Product Category ({{count($categories)}})</a>
                                            </li>
                                            <li  <?php if(isset($type) && $type == 'desktop-pages' ){ echo " class='active'"; } ?> >
                                                <a href="#desktop_page_banners" data-toggle="tab">Pages ({{count($pages)}})</a>
                                            </li>

                                        </ul>
                                             <div class="tab-content">
                                               <div class="tab-pane  <?php if(isset($type) && $type == 'desktop-product-category' ){ echo "active"; } ?> " id="desktop_category_banners">
                                                         <div class="col-md-6">
                                                             <h3>Product Category Banners
                                                             </h3>
                                                         </div>

                                                         <div class="col-md-12">
                                                         <form id="post-form" action="{{action('BannersController@addDesktopCategoryBanners')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <table class="table">
                                                                 <thead>
                                                                 <tr>
                                                                     <td width="40%">
                                                                        Title
                                                                     </td>
                                                                     <td width="40%">
                                                                         Path
                                                                     </td>
                                                                     <td width="20%">
                                                                         Image
                                                                     </td>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                 <?php

                                                                 foreach($categories as $category){  ?>
                                                                 <tr>
                                                                     <td><input type="hidden" value=" {{$category->id}}" name="desProdCat[{{$category->id}}][page_id]" class="form-control"  >
                                                                         {{$category->name}}
                                                                     </td>
                                                                      <td>
                                                                         <input type="text" name="desProdCat[{{$category->id}}][path]" id="desProdCatpath-{{$category->id}}" class="form-control" value="<?php if(isset($data['desProdCatBanner'][$category->id])){ echo $data['desProdCatBanner'][$category->id]['path'];} ?>" >
                                                                         <input type="hidden" name="desProdCat[{{$category->id}}][imageId]" id="desProdCat_imageId-{{$category->id}}" class="form-control" value="<?php if(isset($data['desProdCatBanner'][$category->id])){ echo $data['desProdCatBanner'][$category->id]['image_id'];} ?>" >


                                                                     </td>
                                                                     <?php
                                                                     if(isset($data['desProdCatBanner'][$category->id])){
                                                                         if(isset($data['desProdCatBanner'][$category->id]['path'])){
                                                                             $img = $data['desProdCatBanner'][$category->id]['path'];}else{ $img = 'uploads/noimg.png';}
                                                                     }

                                                                     else{ $img =  'uploads/noimg.png';}
                                                                     ?>

                                                                     <td>
                                                                         <div  class=" slider_image display-{{$category->id}}" id="display-{{$category->id}}"  >
                                                                         {!!  HTML::image($img , 'alt', array('id' => "desProdCatimg-$category->id", 'style' => "width:100%;height:100%" ) ) !!}
                                                                     </td>
                                                                     <td>
                                                                         <a class="btn btn-primary show-images-btn" data-title="banner" id="{{$category->id}}" data-type="desktop-product_cat" data-toggle="modal" href="#full"><i class="fa fa-plus"></i> Add Banner</a>

                                                                     </td>
                                                                 </tr>
                                                                 <?php } ?>
                                                                 <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="mobsave"  class="btn btn-primary pull-right btn-sm ">Save All Banners</button>
                                                                </span>
                                                                     </td></tr>
                                                                 </tbody>
                                                             </table>
                                                         </form>
                                                     </div>
                                                 </div>
                                                 <div class="tab-pane  <?php if(isset($type) && $type == 'desktop-pages' ){ echo "active"; } ?> " id="desktop_page_banners">
                                                     <div class="col-md-6">
                                                         <h3>Page Banners
                                                         </h3>
                                                     </div>

                                                     <div class="col-md-12">
                                                         <form id="post-form" action="{{action('BannersController@addDesktopPageBanners')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <table class="table">
                                                                 <thead>
                                                                 <tr>
                                                                     <td width="40%">
                                                                        Title
                                                                     </td>
                                                                     <td width="40%">
                                                                         Path
                                                                     </td>
                                                                     <td width="20%">
                                                                         Image
                                                                     </td>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                 <?php

                                                                 foreach($pages as $page){  ?>
                                                                 <tr>
                                                                     <td><input type="hidden" value=" {{$page->id}}" name="desPage[{{$page->id}}][page_id]" class="form-control"  >
                                                                         {{$page->title}}
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="desPage[{{$page->id}}][path]" id="desPagepath-{{$page->id}}" class="form-control" value="<?php if(isset($data['desPageBanner'][$page->id])){ echo $data['desPageBanner'][$page->id]['path'];} ?>" >
                                                                         <input type="hidden" name="desPage[{{$page->id}}][imageId]" id="desPage_imageId-{{$page->id}}" class="form-control" value="<?php if(isset($data['desPageBanner'][$page->id])){ echo $data['desPageBanner'][$page->id]['image_id'];} ?>" >


                                                                     </td>
                                                                     <?php
                                                                     if(isset($data['desPageBanner'][$page->id])){
                                                                         if(isset($data['desPageBanner'][$page->id]['path'])){
                                                                             $img = $data['desPageBanner'][$page->id]['path'];}else{ $img = 'uploads/noimg.png';}
                                                                     }

                                                                     else{ $img =  'uploads/noimg.png';}
                                                                     ?>

                                                                     <td>
                                                                         <div  class=" slider_image display-{{$page->id}}" id="display-{{$page->id}}"  >
                                                                         {!!  HTML::image($img , 'alt', array('id' => "desPageimg-$page->id", 'style' => "width:100%;height:100%" ) ) !!}
                                                                     </td>
                                                                     <td>
                                                                         <a class="btn btn-primary show-images-btn" data-title="banner" id="{{$page->id}}" data-type="desktop-pages" data-toggle="modal" href="#full"><i class="fa fa-plus"></i> Add Banner</a>

                                                                     </td>
                                                                 </tr>
                                                                 <?php } ?>
                                                                 <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="mobsave"  class="btn btn-primary pull-right btn-sm ">Save All Banners</button>
                                                                </span>
                                                                     </td></tr>
                                                                 </tbody>
                                                             </table>
                                                         </form>
                                                     </div>
                                                 </div>

                                             </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
        </div>
    </div>


            @extends('includes.gallery')

    {{-- end of page content --}}
@endsection

@section('scripts')

    {!! HTML::script('assets/js/angular_js.js') !!}
    {!! HTML::script('assets/js/media_angularjs.js') !!}
    {!! HTML::script('assets/js/slider_plugins.js') !!}
    {!! HTML::script('assets/js/slider_js.js') !!}
    {!! HTML::script('assets/js/banners.js') !!}

@endsection
