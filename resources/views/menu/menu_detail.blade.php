@extends('layouts.master')

@section('css')
{!! HTML::style('assets/css/menu_plugins.css') !!}
@endsection

@section('content')


<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('menu.index') }}>Menu</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Menu Details
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->


<form id="menu-form"  method="post" action="{{action('MenuController@showDetail')}}" method="post">
    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
    <div class="form-actions">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group pull-right">
                <label>Kies een menu om te bewerken: </label>

            </div>
        </div>
        <div class="col-md-4">
            <select name="mainmenu"  id="mainmenu" class="form-control">

                @if (count($data['all_menu']) > 0)
                    @foreach ($data['all_menu'] as $menu)
                        @include('chunks.mainMenuOptions', $menu)
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-1">
            <button  id="select_menu"    class="btn  pull-left btn-sm selectMenu">Kies</button>
        </div>
        <div class="col-md-5">
            <a href="{{route('menu.create')}}" class="nav-link underline ">
                <span class="title"><u>of maak een nieuw menu</u></span>
            </a>
        </div>

    </div></div>

    <hr>

    <div class="row">
        <div class="showmsg">
        </div>
        <div class="col-md-3">
            <div class="portlet box default"  >
                <div class="portlet-title" >
                    <div class="caption">
                        </i>Pagina's
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body  collapse" >

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">Bekijk alle</a>
                            </li>
                            <li>
                                <a href="#searchpage" data-toggle="tab">Zoek</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active " id="tab1">
                                <div class="row category-checkbox-list">  @if($data['pages'] > 0)
                                    <ul  class="all-post-categories">
                                        @foreach($data['pages'] as $pages)
                                            <li class="parent" id="page{{ $pages['id'] }}" data-url="{{$pages['slug']}}"  data-parent-value="{{ $pages['id'] }}">
                                                <input class="product-category select_pages"   name="checkpg[]"  type="checkbox" value="{{ $pages['id'] }}" >{{$pages['title']}}</li>
                                        @endforeach
                                    </ul> @endif  </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6"><input type="checkbox" id="select_all_pages">Selecteer alle</div>
                                    <div class="col-md-6"><button  id="add_pages_to_menu" type="button"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="searchpage" style="min-height:100px" >
                                <input type="text" class="form-control"  name="search_page" id="search_page" />
                                <div class="show_search_page row category-checkbox-list">
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6"><input type="checkbox" id="select_all_pages_search">Selecteer alle</div>
                                    <div class="col-md-6"><button  id="add_pages_to_menuSearch" type="button"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                                </div>
                            </div>



                        </div>


                </div>

            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        </i>Bericht
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body  collapse">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab2" data-toggle="tab">Bekijk alle</a>
                            </li>
                            <li>
                                <a href="#search_postcat" data-toggle="tab">Zoek</a>
                            </li>

                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="tab2">
                                <div class="row category-checkbox-list">  @if($data['postscat'] > 0)
                                    <ul class="all-post-categories">
                                        @foreach($data['postscat'] as $postscats)
                                            <li class="parent" id="post{{$postscats['category_id']}}" data-url="{{$postscats['category_slug']}}" data-parent-value="{{ $postscats['category_id'] }}">
                                                <input class="product-category select_posts" name="checkposts[]"  type="checkbox" id="{{ $postscats['category_id'] }}"  value="{{ $postscats['category_id'] }}">{{$postscats['category_name']}}</li>

                                        @endforeach
                                    </ul> @endif </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6"><input type="checkbox" id="select_all_posts">Selecteer alle</div>
                                    <div class="col-md-6"><button  id="add_posts_to_menu"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="search_postcat" style="min-height:100px" >
                                <input type="text" class="form-control"  name="search_post" id="search_post" />
                                <div class="show_search_post category-checkbox-list">


                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6"><input type="checkbox" id="select_all_posts_search">Selecteer alle</div>
                                    <div class="col-md-6"><button  id="add_posts_to_menuSearch"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                                </div>
                            </div>

                        </div>

                </div>

            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        </i>Aangepaste link
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body collapse " style="line-height:30px">
                    <div class="row">
                        <label> URL: </label><input type="text" class="form-control"  name="custom_url" id="custom_url" value="http://" />
                    </div>
                    <div class="row">
                        <label> Titel: </label><input type="text" class="form-control"  name="custom_title" id="custom_title" />
                    </div>
                    <div class="row">
                        <div class="col-md-6"></div>
                        <div class="col-md-6"><button  id="add_custom_to_menu"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                    </div>
                </div>

            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        </i>Categorieën
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body collapse">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab3" data-toggle="tab">Bekijk alle</a>
                            </li>
                            <li>
                                <a href="#search_cat" data-toggle="tab">Zoek</a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab3">
                                <div class="row category-checkbox-list">
                                    {{--{{array_count_values($categories['selected'])}}--}}
                                    @if (count($data['categories']) > 0)
                                            <ul class="all-post-categories">
                                            @foreach ($data['categories']  as $category)

                                                <li class="parent" id="cat{{$category['category_id']}}" data-url="{{$category['category_slug']}}" data-parent-value="{{ $category['category_id'] }}">

                                                    <input name="categories[]" id="categories[]" class="post-category select_categories" type="checkbox"  value="{{ $category['category_id'] }}" > {{ $category['category_name'] }}</li>

                                            @endforeach
                                        </ul>

                                    @endif
                               </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6"><input type="checkbox" id="select_all_categories">Selecteer alle</div>
                                    <div class="col-md-6"><button  id="add_cats_to_menu"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="search_cat" style="min-height:100px" >
                                <input type="text" class="form-control" name="search_category" id="search_category" />
                                <div class="show_search_category category-checkbox-list">


                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6"><input type="checkbox" id="select_all_categories_search">Selecteer alle</div>
                                    <div class="col-md-6"><button  id="add_cats_to_menuSearch"  class="btn  pull-left btn-sm publish-post">Toevoegen aan menu</button></div>
                                </div>
                            </div>

                        </div>



                </div>

            </div>




        </div>
        <div class="col-md-9">
            <div class="portlet box  blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                      </i>Menustructuur</div>

                    <div class="tools">
                        <a href="javascript:;" class="collapse"> </a>

                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->

                        <div class="form-actions" >
                            <div class="row">

                            <div class="col-md-8  left"> Menu naam<input value="" class="form-control inline input-medium" type="text" id="menu_name" name="menu_name" /></div>

                            <div class="col-md-2   pull-right"><button  id="save_menu"  class="btn green">Menu opslaan</button></div></div>

                        </div>
                        <div class="form-body" style="min-height: 500px">
                            <div class="show_menu">
                                <div class="row">
                                <div class="cf nestable-lists">

                                    <div class="dd" id="nestable3">
                                        <ol class="dd-list">
                                            <?php if(isset($data['menu_detail'])){ ?>
                                            @if(count($data['menu_detail']) > 0 )
                                                <?php ?>
                                                @foreach($data['menu_detail'] as $key => $m_detail)

                                                    <li class="dd-item dd3-item"  data-id="{{$m_detail['sub_menu_id']}}" data-type ="{{$m_detail['type']}}" data-title ="{{$m_detail['title']}}" data-url ="{{$m_detail['url']}}">
                                                        <div class="dd-handle dd3-handle"  style="height: 41px">Slepen</div><div class="portlet box default"  style="width:500px" >
                                                            <div class="portlet-title ">
                                                                <div class="caption " style="padding-left:29px">
                                                                    </i>
                                                                    @if($m_detail['type'] == 'page')
                                                                        <div class="new_pageTitle">{{$m_detail['title']}}</div>
                                                                        @elseif($m_detail['type'] == 'post')
                                                                        <div class="new_postTitle">{{$m_detail['title']}}</div>
                                                                        @elseif($m_detail['type'] == 'category')
                                                                        <div class="new_catTitle">{{$m_detail['title']}}</div>
                                                                    @elseif($m_detail['type'] == 'custom')
                                                                        <div class="new_customTitle">{{$m_detail['title']}}</div>
                                                                        @endif
                                                                </div>
                                                                <div class="tools">{{$m_detail['type']}}
                                                                    <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body  collapse">
                                                               @if($m_detail['type'] == 'page')
                                                                <div class="row">Navigatielabel<input data-menu_id ="{{$m_detail['menu_id']}}" value="{{$m_detail['title']}}" type="text" class="form-control edit_pageTitle"  name="edit_page" id="{{$m_detail['sub_menu_id']}}">
                                                                    </div>
                                                                    <div class="row">
                                                                        <a data-menu_id ="{{$m_detail['menu_id']}}" id="{{$m_detail['sub_menu_id']}}" class="remove_menu" href="#">Verwijderen</a> | <a id="{{$m_detail['sub_menu_id']}}" href="#"  class="collapse_cancel">Annuleren</a> </div>
                                                                @elseif($m_detail['type'] == 'post')

                                                                    <div class="row">Navigatielabel<input data-menu_id ="{{$m_detail['menu_id']}}" value="{{$m_detail['title']}}" type="text" class="form-control edit_postTitle"  name="edit_page" id="{{$m_detail['sub_menu_id']}}">
                                                                    </div>
                                                                    <div class="row">
                                                                        <a data-menu_id ="{{$m_detail['menu_id']}}" id="{{$m_detail['sub_menu_id']}}" class="remove_menu" href="#">Verwijderen</a> | <a id="{{$m_detail['sub_menu_id']}}" href="#"  class="collapse_cancel">Annuleren</a> </div>

                                                                @elseif($m_detail['type'] == 'custom')
                                                                    <div class="row">URL<input name="edit_URL" class="form-control edit_customURL" value="{{$m_detail['url']}}" type="text" ></div>
                                                                    <div class="row">Navigatielabel<input data-menu_id ="{{$m_detail['menu_id']}}" value="{{$m_detail['title']}}" type="text" class="form-control edit_customTitle"  name="edit_page" id="{{$m_detail['sub_menu_id']}}">
                                                                    </div>
                                                                    <div class="row">
                                                                        <a data-menu_id ="{{$m_detail['menu_id']}}" id="{{$m_detail['sub_menu_id']}}" class="remove_menu" href="#">Verwijderen</a> | <a id="{{$m_detail['sub_menu_id']}}" href="#"  class="collapse_cancel">Annuleren</a> </div>

                                                                @elseif($m_detail['type'] == 'category')
                                                                    <div class="row">Navigatielabel<input data-menu_id ="{{$m_detail['menu_id']}}" value="{{$m_detail['title']}}" type="text" class="form-control edit_catTitle"  name="edit_page" id="{{$m_detail['sub_menu_id']}}">
                                                                    </div>
                                                                    <div class="row">
                                                                        <a data-menu_id ="{{$m_detail['menu_id']}}" id="{{$m_detail['sub_menu_id']}}" class="remove_menu" href="#">Verwijderen</a> | <a id="{{$m_detail['sub_menu_id']}}" href="#"  class="collapse_cancel">Annuleren</a> </div>
                                                                   @endif
                                                            </div></div>
                                                          <?php

                                                            if (array_key_exists('children', $m_detail)) {
                                                            echo '<ol class="dd-list">';
                                                            foreach($m_detail['children'] as $mdetail_child){

                                                                $menu_obj = new  \App\Menu;
                                                                $menu_obj->displayMenuTree($mdetail_child);
                                                            }
                                                            echo "</li></ol>";
                                                        }

                                                        ?>
                                                    </li>
                                                @endforeach </ol>
                                            @endif
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-2  left"><a id="del_menu" href="#">Menu verwijderen</a></div>
                                <div class="col-md-3  left"><input type="checkbox" @if(isset($data['selected_menu']['menu_type']) && $data['selected_menu']['menu_type'] == 'sticky') checked @endif name="is_sticky" id="is_sticky" >Sticky menu?</div>
                                <div class="col-md-4  left"><input type="checkbox" @if(isset($data['selected_menu']['is_primary']) && $data['selected_menu']['is_primary'] == 'yes') checked @endif name="is_primary" id="is_primary" >Hoofdmenu?</div>
                                <div class="col-md-2   pull-right"><button  id="save_menu2"  class="btn green">Menu opslaan</button></div></div>

                        </div>

                    <!-- END FORM-->
                </div>
            </div>



    </div>   <input style="width:840px" type="hidden" name="nestable-output" id="nestable-output" />
</div>
</form>



@endsection

@section('scripts')
{!! HTML::script('assets/js/menu_plugins.js') !!}
{!! HTML::script('assets/plugins/ckeditor/ckeditor.js') !!}
{!! HTML::script('assets/js/menu.js') !!}
@endsection