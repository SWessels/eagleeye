@extends('layouts.master')

@section('css')

@endsection

    @section('content')

            <!-- BEGIN tab BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ route('home') }}}">Home</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <span>Category products sorting</span>
            </li>
        </ul>

        <div id="msg_area" class="pull-right"></div>

    </div>
    <br>
 

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
                         <div class="col-sm-3" >
                             <select id="sotrable_category" class="form-control">
                                <option value=""> Select Category </option>
                                 @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if($list === true) @if($category->id == $catid) selected="selected" @endif @endif> {{ $category->name }} </option>
                                 @endforeach
                             </select>
                         </div>
                         <div class="col-sm-3">
                            <button type="button" onclick="reloadWithID()" class="btn btn-primary">Load Products</button>
                         </div>
                         <div class="col-sm-3 text-right">
                             <div class="actions">
                                            <div class="btn-group">
                                                <a class="btn btn-sm default dropdown-toggle" href="javascript:;" data-toggle="dropdown" aria-expanded="false"> Layout
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="javascript:;" onclick="removeClass('layout_3_columns')"> 
                                                            4 items per row
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" onclick="addClass('layout_3_columns')"> 
                                                            3 items per row
                                                        </a> 
                                                    </li>
                                                     
                                                </ul>
                                            </div>
                                        </div>
                         </div>
                         <div class="col-sm-3 text-right"> 
                            <input type="hidden" name="main_sorted_data" id="main_sorted_data" value="">
                            <input type="hidden" name="left_reserved_data" id="left_reserved_data" value="">
                            <input type="hidden" name="right_reserved_data" id="right_reserved_data" value="">
                            <input type="hidden" name="is_changed" id="is_changed" value="0">
                            <button type="button" class="btn btn-default" onclick="draftSortedData('draft');">Save as draft</button>    
                            <button type="button" class="btn btn-primary" onclick="draftSortedData('publish');">Publish</button>    
                             
                         </div>
                    </div>
                    <div class="portlet-body">
                        <div class="sortable-container">
                            @if($list === true)
                                <div class="row">
                                     <section> 
                                        <ul id="left_reserve" class="connected col-md-3">  
                                            @if(!empty($products)) 
                                                @for($i = 0; $i <= (ceil((count($products) / 3 )) * 2); $i++)  
                                                <?php  
                                                      if(($i % 60) == 0) { 
                                                            echo "<div class='devider_class'></div>";  
                                                        } 
                                                 ?>
                                                     <li> </li>  
                                                @endfor
                                            @endif
                                                
                                        </ul>
                                        <ul id="main_sortable" class="connected col-md-6">

                                            @if( count($products) > 0)
                                            <?php   $p = 0 ;   ?> 
                                                @foreach($products as $product)  
                                                @if($product->sort_index != -1 && $product->sort_index != -2)
                                                <?php   
                                                    
                                                      if(($p % 120) == 0) { 
                                                            echo "<div class='devider_class'></div>";  
                                                        }  
                                                    $p++ ;    
                                                 ?>
                                                     <li data-id="{{ $product->id }}"  >
                                                        <div class="sortable_product_img"> 
                                                            @if($loadfrom == 'main')
                                                                @if($product->media_featured_image)
                                                                    {!! HTML::image(productImage('small', $product->media_featured_image->path), $product->name, array('class' => '') ) !!} 
                                                                @else
                                                                    {!! HTML::image(App::make('url')->to('assets/img/placeholder-110x117.jpg'), $product->name, array('class' => '') ) !!}
                                                                @endif
                                                            @else
                                                                {!! HTML::image(productImage('small', $product->path), $product->name, array('class' => '') ) !!}
                                                            @endif    
                                                        </div>
                                                        <div class="sortable_product_name">
                                                            {{ $product->name }}
                                                        </div>
                                                        <div class="sortable_product_price">
                                                            {{ money($product->regular_price) }}
                                                        </div>
                                                    </li> 
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="alert alert-info alert-wide">No producs found </span>
                                            @endif
                                            <li>
                                        </ul>

                                        <ul id="right_reserve" class="connected col-md-3">
                                            @if(!empty($products)) 
                                                @for($i = 0; $i <= (ceil((count($products) / 3 )) * 2); $i++)  
                                                 <?php  
                                                      if(($i % 60) == 0) { 
                                                            echo "<div class='devider_class'></div>";  
                                                        } 
                                                 ?>
                                                     <li> </li>  
                                                @endfor
                                            @endif
                                             
                                             
                                        </ul>
                                    </section>
                                </div>
                            @else
                                <h3>
                                    Select Category and laod products
                                </h3>
                            @endif

                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
        </div>

         
    </div>

    {{-- end of tab content --}}
@endsection

@section('scripts') 
    {!! HTML::script('assets/js/jquery.sortable.js') !!} 
@endsection
