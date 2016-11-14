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
                <span>Sliders</span>
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
                            <i class="fa fa-Products"></i>Sliders </div>
                        <div class="actions">
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-container">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="tabbable tabbable-tabdrop">
                                        <ul class="nav nav-tabs">
                                            <li <?php if(isset($type) && $type == 'mobile' ){ echo " class='active'"; } ?> >
                                                <a href="#mobile_slider" data-toggle="tab">Mobiel Hoofdslider homepage</a>
                                            </li>
                                            <li <?php if(isset($type) && $type == 'desktop'){ echo " class='active'"; }?>>
                                                <a href="#desktop_slider" data-toggle="tab">Desktop Hoofdslider homepage</a>
                                            </li>
                                            <li <?php if(isset($type) && $type == 'mobile_homepage'){ echo " class='active'"; }?>>
                                                <a href="#mob_homepage" data-toggle="tab">Mobiel Overig homepage</a>
                                            </li>
                                            <li <?php if(isset($type) && $type == 'desktop_homepage'){ echo " class='active'"; }?>>
                                                <a href="#des_homepage" data-toggle="tab">Desktop Overig homepage</a>
                                            </li>
                                            <li <?php if(isset($type) && $type == 'product'){ echo " class='active'"; }?>>
                                                <a href="#products_slider" data-toggle="tab">Productslider homepage</a>
                                            </li>
                                            <li <?php if(isset($type) && $type == 'musthave_deal_widget'){ echo " class='active'"; }?>>
                                                <a href="#musthave_deal_widget" data-toggle="tab">Musthave Deal Widget</a>
                                            </li>

                                        </ul>
                                             <div class="tab-content">
                                               <div class="tab-pane <?php if(isset($type) && $type == 'mobile' ){ echo " active"; } ?>" id="mobile_slider">
                                                         <div class="col-md-6">
                                                             <h3> Slider Items
                                                             </h3>
                                                         </div>

                                                         <div class="col-md-12">
                                                         <form id="post-form" action="{{action('SlidersController@addMobileSliders')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <table class="table">
                                                                 <thead>
                                                                 <tr>
                                                                     <td width="40%">
                                                                         URL landingspagina
                                                                     </td>
                                                                     <td width="40%">
                                                                         URL afbeelding
                                                                     </td>
                                                                     <td width="20%">
                                                                         Thumbnail
                                                                     </td>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                 <?php

                                                                 for($c=0; $c<=3; $c++){  ?>
                                                                 <tr>
                                                                     <td>
                                                                         <input value="<?php if(isset($data['mobSliders'][$c])){ echo $data['mobSliders'][$c]['link_url'];} ?>" name="mob[{{$c}}][url]" class="form-control"  >
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="mob[{{$c}}][path]" id="mobpath-{{$c}}" class="form-control" value="<?php if(isset($data['mobSliders'][$c])){ echo url('')."/".$data['mobSliders'][$c]['media']['path'];} ?>" >
                                                                         <input type="hidden" name="mob[{{$c}}][imageId]" id="mob_imageId-{{$c}}" class="form-control" value="<?php if(isset($data['mobSliders'][$c])){ echo $data['mobSliders'][$c]['media_id'];} ?>" >
                                                                     </td>
                                                                    <?php
                                                                     if(isset($data['mobSliders'][$c])){
                                                                         if(isset($data['mobSliders'][$c]['media']['path'])){
                                                                             $img = $data['mobSliders'][$c]['media']['path'];}else{ $img = 'uploads/noimg.png';}
                                                                     }

                                                                     else{ $img =  'uploads/noimg.png';}
                                                                     ?>

                                                                     <td>
                                                                         <div  class=" slider_image display-{{$c}}" id="display-{{$c}}"  >
                                                                         {!!  HTML::image($img , 'alt', array('id' => "mobimg-$c", 'style' => "width:100%;height:100%" ) ) !!}

                                                                     </td>
                                                                     <td>
                                                                         <a class="btn btn-primary show-images-btn" data-title="slider" id="{{$c}}" data-type="mobile" data-toggle="modal" href="#full"><i class="fa fa-plus"></i> Afbeelding toevoegen</a>

                                                                     </td>
                                                                 </tr>
                                                                 <?php } ?>
                                                                 <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="mobsave"  class="btn btn-primary pull-right btn-sm ">Wijzigingen opslaan</button>
                                                                </span>
                                                                     </td></tr>
                                                                 </tbody>
                                                             </table>
                                                         </form>
                                                     </div>
                                                 </div>
                                                 <div class="tab-pane <?php if(isset($type) && $type == 'desktop'){ echo " active"; }?>" id="desktop_slider">
                                                     <div class="col-md-6">
                                                         <h3> Gegevens afbeeldingen
                                                         </h3>
                                                     </div>

                                                     <div class="col-md-12">
                                                         <form id="post-form" action="{{action('SlidersController@addDesktopSliders')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <table class="table">
                                                                 <thead>
                                                                 <tr>
                                                                     <td width="40%">
                                                                         URL landingspagina
                                                                     </td>
                                                                     <td width="40%">
                                                                         URL afbeelding
                                                                     </td>
                                                                     <td width="20%">
                                                                         Afbeelding
                                                                     </td>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                 <?php
                                                                 for($c=0; $c<=4; $c++){  ?>
                                                                 <tr>
                                                                     <td>
                                                                         <input value="<?php if(isset($data['desSliders'][$c])){echo $data['desSliders'][$c]['link_url'];} ?>" name="des[{{$c}}][url]" class="form-control"  >
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="des[{{$c}}][path]" id="despath-{{$c}}" class="form-control" value="<?php if(isset($data['desSliders'][$c])){echo url('')."/".$data['desSliders'][$c]['media']['path'];} ?>" >
                                                                         <input type="hidden" name="des[{{$c}}][imageId]" id="des_imageId-{{$c}}" class="form-control" value="<?php if(isset($data['desSliders'][$c])){ echo $data['desSliders'][$c]['media_id'];} ?>" >
                                                                     </td>
                                                                     <?php
                                                                     if(isset($data['desSliders'][$c])){
                                                                     if(isset($data['desSliders'][$c]['media']['path'])){
                                                                         $img = $data['desSliders'][$c]['media']['path'];}else{ $img = 'uploads/noimg.png';}
                                                                         } else{ $img = 'uploads/noimg.png';}
                                                                     ?>
                                                                     <td>
                                                                         <div  class=" slider_image display-{{$c}}" id="display-{{$c}}"  >
                                                                         {!!HTML::image($img , 'alt', array('id' => "desimg-$c", 'style' => "width:100%;height:100%" ) ) !!}

                                                                     </td>
                                                                     <td>
                                                                         <a class="btn btn-primary show-images-btn" data-title="slider" id="{{$c}}" data-type="desktop" data-toggle="modal" href="#full"><i class="fa fa-plus"></i> Afbeelding toevoegen</a>

                                                                     </td>
                                                                 </tr>
                                                                 <?php }  ?>
                                                                 <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="dessave"  class="btn btn-primary pull-right btn-sm ">Wijzigingen opslaan</button>
                                                                </span>
                                                                     </td></tr>
                                                                 </tbody>
                                                             </table>
                                                         </form>
                                                     </div>
                                                 </div>

                                                 <div class="tab-pane <?php if(isset($type) && $type == 'mobile_homepage'){ echo " active"; }?>" id="mob_homepage">
                                                     <div class="col-md-6">
                                                         <h3> Gegevens afbeeldingen
                                                         </h3>
                                                     </div>

                                                     <div class="col-md-12">
                                                         <form id="post-form" action="{{action('SlidersController@addMobileHomeSliders')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <table class="table">
                                                                 <thead>
                                                                 <tr>
                                                                     <td width="40%">
                                                                         URL landingspagina
                                                                     </td>
                                                                     <td width="40%">
                                                                         URL afbeelding
                                                                     </td>
                                                                     <td width="20%">
                                                                         Afbeelding
                                                                     </td>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                 <?php
                                                                 for($c=0; $c<=3; $c++){  ?>
                                                                 <tr>
                                                                     <td>
                                                                         <input value="<?php if(isset($data['mob_homeSliders'][$c])){echo $data['mob_homeSliders'][$c]['link_url'];} ?>" name="mobHome[{{$c}}][url]" class="form-control"  >
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="mobHome[{{$c}}][path]" id="mobHomepath-{{$c}}" class="form-control" value="<?php if(isset($data['mob_homeSliders'][$c])){echo url('')."/".$data['mob_homeSliders'][$c]['media']['path'];} ?>" >
                                                                         <input type="hidden" name="mobHome[{{$c}}][imageId]" id="mobHome_imageId-{{$c}}" class="form-control" value="<?php if(isset($data['mob_homeSliders'][$c])){ echo $data['mob_homeSliders'][$c]['media_id'];} ?>" >
                                                                     </td>
                                                                     <?php
                                                                     if(isset($data['mob_homeSliders'][$c])){
                                                                         if(isset($data['mob_homeSliders'][$c]['media']['path'])){
                                                                             $img = $data['mob_homeSliders'][$c]['media']['path'];}else{ $img = 'uploads/noimg.png';}
                                                                     } else{ $img = 'uploads/noimg.png';}
                                                                     ?>
                                                                     <td>
                                                                         <div  class=" slider_image display-{{$c}}" id="display-{{$c}}"  >
                                                                         {!!HTML::image($img , 'alt', array('id' => "mobHome_img-$c", 'style' => "width:100%;height:100%" ) ) !!}

                                                                     </td>
                                                                     <td>
                                                                         <a class="btn btn-primary show-images-btn" data-title="slider" id="{{$c}}" data-type="mobile_homepage" data-toggle="modal" href="#full"><i class="fa fa-plus"></i> Add Slide</a>

                                                                     </td>
                                                                 </tr>
                                                                 <?php }  ?>
                                                                 <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="dessave"  class="btn btn-primary pull-right btn-sm ">Wijzigingen opslaan</button>
                                                                </span>
                                                                     </td></tr>
                                                                 </tbody>
                                                             </table>
                                                         </form>
                                                     </div>
                                                 </div>
                                                 <div class="tab-pane <?php if(isset($type) && $type == 'desktop_homepage'){ echo " active"; }?>" id="des_homepage">
                                                     <div class="col-md-6">
                                                         <h3> Gegevens afbeeldingen
                                                         </h3>
                                                     </div>

                                                     <div class="col-md-12">
                                                         <form id="post-form" action="{{action('SlidersController@addDesktopHomeSliders')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <table class="table">
                                                                 <thead>
                                                                 <tr>



                                                                     <td width="25%">
                                                                         Title
                                                                     </td>
                                                                     <td width="25%">
                                                                         URL landingspagina
                                                                     </td>
                                                                     <td width="30%">
                                                                         URL afbeelding
                                                                     </td>
                                                                     <td width="20%">
                                                                         Afbeelding
                                                                     </td>
                                                                 </tr>
                                                                 </thead>
                                                                 <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                 <?php
                                                                 $homeimage = array("Left MusthaveDeal Image", "Right MusthaveDeal Image", "First Round", "Second Round"
                                                                 , "Thired Round", "Forth Round", "Happy Customers");
                                                                 for($c=0; $c<=6; $c++){  ?>
                                                                 <tr>
                                                                     <td>
                                                                         <input value="<?php echo $homeimage[$c]; ?>"  class="form-control" disabled >
                                                                     </td>


                                                                     <td>
                                                                         <input value="<?php if(isset($data['des_homeSliders'][$c])){echo $data['des_homeSliders'][$c]['link_url'];} ?>" name="desHome[{{$c}}][url]" class="form-control"  >
                                                                     </td>
                                                                     <td>
                                                                         <input type="text" name="desHome[{{$c}}][path]" id="desHomepath-{{$c}}" class="form-control" value="<?php if(isset($data['des_homeSliders'][$c])){echo url('')."/".$data['des_homeSliders'][$c]['media']['path'];} ?>" >
                                                                         <input type="hidden" name="desHome[{{$c}}][imageId]" id="desHome_imageId-{{$c}}" class="form-control" value="<?php if(isset($data['des_homeSliders'][$c])){ echo $data['des_homeSliders'][$c]['media_id'];} ?>" >
                                                                     </td>
                                                                     <?php
                                                                     if(isset($data['des_homeSliders'][$c])){
                                                                         if(isset($data['des_homeSliders'][$c]['media']['path'])){
                                                                             $img = $data['des_homeSliders'][$c]['media']['path'];}else{ $img = 'uploads/noimg.png';}
                                                                     } else{ $img = 'uploads/noimg.png';}
                                                                     ?>
                                                                     <td>
                                                                         <div  class=" slider_image display-{{$c}}" id="display-{{$c}}"  >
                                                                         {!!HTML::image($img , 'alt', array('id' => "desHome_img-$c", 'style' => "width:100%;height:100%" ) ) !!}

                                                                     </td>
                                                                     <td>
                                                                         <a class="btn btn-primary show-images-btn" data-title="slider" id="{{$c}}" data-type="desktop_homepage" data-toggle="modal" href="#full"><i class="fa fa-plus"></i> Add Slide</a>

                                                                     </td>
                                                                 </tr>
                                                                 <?php }  ?>
                                                                 <tr><td colspan="5">
                                                                 <span class="pull-right">
                                                                <button  id="dessave"  class="btn btn-primary pull-right btn-sm ">Wijzigingen opslaan</button>
                                                                </span>
                                                                     </td></tr>
                                                                 </tbody>
                                                             </table>
                                                         </form>
                                                     </div>
                                                 </div>
                                                 <div class="tab-pane <?php if(isset($type) && $type == 'product'){ echo " active"; }?>" id="products_slider">
                                                     <div class="col-md-6">
                                                         <h3> Gegevens afbeeldingen
                                                         </h3>
                                                     </div>

                                                     <div class="col-md-12">

                                                         <form id="post-form" action="{{action('SlidersController@addProductSliders')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <div id="no-more-tables">
                                                                 <table  class="table">
                                                                     <thead>
                                                                     <tr>
                                                                         <td width="20%">
                                                                             Product
                                                                         </td>

                                                                         <td width="20%">
                                                                             Afbeelding
                                                                         </td>
                                                                         <td width="20%">
                                                                             Product
                                                                         </td>

                                                                         <td width="20%">
                                                                             Afbeelding
                                                                         </td>
                                                                     </tr>
                                                                     </thead>
                                                                     <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                     <tr><?php

                                                                         for($c=0; $c<=13; $c++){

                                                                         if (fmod($c,2) == 0) { echo '</tr><tr>'; }
                                                                         ?>

                                                                         <td>
                                                                             <select  name="product[{{$c}}][id]" id="{{$c}}" class="form-control input-medium pselect2" >
                                                                                 <?php
                                                                                 if(isset($data['productSliders'][$c])){
                                                                                 ?> <option value="{{$data['productSliders'][$c]->media_id}}" selected >{{$data['productSliders'][$c]->name}}</option>
                                                                                 <?php  }
                                                                                 ?>
                                                                             </select>

                                                                         </td>
                                                                         <?php
                                                                         if(isset($data['productSliders'][$c])){
                                                                             if(isset($data['productSliders'][$c]) && $data['productSliders'][$c]->path != null)
                                                                             { $img = $data['productSliders'][$c]->path;}  else{ $img =  'uploads/noimg.png';}
                                                                         }
                                                                         else{ $img =  'uploads/noimg.png';}
                                                                         ?>

                                                                         <td>
                                                                             <div  class=" slider_image display-{{$c}}" id="display-{{$c}}"  >
                                                                             {!!  HTML::image($img , 'alt', array('id' => "product_img-$c", 'style' => "width:100%;height:100%" ) ) !!}

                                                                         </td>


                                                                         <?php
                                                                         } ?></tr>
                                                                     <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="productsave"  class="btn btn-primary pull-left btn-sm ">Wijzigingen opslaan</button>
                                                                </span>
                                                                         </td></tr>
                                                                     </tbody>
                                                                 </table>
                                                             </div>
                                                         </form>
                                                     </div>
                                                 </div>
                                                 <div class="tab-pane <?php if(isset($type) && $type == 'musthave_deal_widget'){ echo " active"; }?>" id="musthave_deal_widget">
                                                     <div class="col-md-6">
                                                         <h3> Musthave Deal Widget
                                                         </h3>
                                                     </div>

                                                     <div class="col-md-12">

                                                         <form id="post-form" action="{{action('SlidersController@addCategorySidebarSliders')}}"  method="post">
                                                             <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                                             <div id="no-more-tables">
                                                                 <table  class="table">
                                                                     <thead>
                                                                     <tr>
                                                                         <td width="20%">
                                                                             Product
                                                                         </td>

                                                                         <td width="20%">
                                                                             Afbeelding
                                                                         </td>
                                                                         <td width="20%">
                                                                             Product
                                                                         </td>

                                                                         <td width="20%">
                                                                             Afbeelding
                                                                         </td>
                                                                     </tr>
                                                                     </thead>
                                                                     <tbody><input type="hidden" name="connection" id="connection" class="form-control" value="{{Session::get('connection')}}" >
                                                                     <tr><?php

                                                                         for($c=0; $c<=4; $c++){

                                                                         if (fmod($c,2) == 0) { echo '</tr><tr>'; }
                                                                         ?>

                                                                         <td>
                                                                             <select  name="product[{{$c}}][id]" id="{{$c}}" class="form-control input-medium pselect2" >
                                                                                 <?php
                                                                                 if(isset($data['musthave_deal_widgetSliders'][$c])){
                                                                                 ?> <option value="{{$data['musthave_deal_widgetSliders'][$c]->media_id}}" selected >{{$data['musthave_deal_widgetSliders'][$c]->name}}</option>
                                                                                 <?php  }
                                                                                 ?>
                                                                             </select>

                                                                         </td>
                                                                         <?php
                                                                         if(isset($data['musthave_deal_widgetSliders'][$c])){
                                                                             if(isset($data['musthave_deal_widgetSliders'][$c]) && $data['musthave_deal_widgetSliders'][$c]->path != null)
                                                                             { $img = $data['musthave_deal_widgetSliders'][$c]->path;}  else{ $img =  'uploads/noimg.png';}
                                                                         }
                                                                         else{ $img =  'uploads/noimg.png';}
                                                                         ?>

                                                                         <td>
                                                                             <div  class=" slider_image display-{{$c}}" id="display-{{$c}}"  >
                                                                             {!!  HTML::image($img , 'alt', array('id' => "product_img-$c", 'style' => "width:100%;height:100%" ) ) !!}

                                                                         </td>


                                                                         <?php
                                                                         } ?></tr>
                                                                     <tr><td colspan="4">
                                                                 <span class="pull-right">
                                                                <button  id="productsave"  class="btn btn-primary pull-left btn-sm ">Wijzigingen opslaan</button>
                                                                </span>
                                                                         </td></tr>
                                                                     </tbody>
                                                                 </table>
                                                             </div>
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
    {!! HTML::script('assets/js/sliders.js') !!}

@endsection
