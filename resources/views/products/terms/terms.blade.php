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
            <a href="{{ route('attributes.index') }}">  <span>Attributes</span></a>
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
                        <span class="caption-subject font-blue-sharp bold uppercase">Add New {{ $att_name }}</span>
                    </div>

                </div>
                <div class="portlet-body form">
                    <form role="form" id="tag_add"  method="post">
                        <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                        <input type="hidden" name="att_id" id="att_id" value="<?php echo $terms[0]->id ?>" >
                        <div class="form-body">
                            {{--action=" {{action('TermsController@store' )}} "--}}
                            <div class="form-group">
                                <label>Name</label>

                                    <input name="name" id="name" type="text" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label>Slug</label>

                                    <input name="slug" id="slug" type="text" class="form-control input-sm" >
                            </div>
                            <div class="form-group">
                                <label>Description</label>

                                    <textarea name="desc"  id="desc" class="form-control" rows="3" cols="6"></textarea>
                            </div>


                        </div>
                        <div class="form-actions">
                            <button  name="add_term" id="add_term" class="btn blue add_term">Submit</button>
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
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
            @endif

                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet light bordered">
                   <div class="portlet-body">

                       <div class="table-container">

                             <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                     All({{ count($terms[0]['Terms']) }})
                                </div>
                                <div class="col-sm-6">
                                    <form role="form" action=" {{action('TermsController@index')}} " method="get"  class="pull-right col-sm-6 npall nmall">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm">
                                                <input type="hidden" name="att_id" id="att_id" value="<?php echo $terms[0]->id ?>" >
                                                <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Search">
                                                    <span class="input-group-btn">
                                                        <input class="btn red" name="search" id="search" type="submit">Search</input>
                                                    </span>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        <!-- /input-group -->
                                 </div>
                                </form>
                            </div>

                        </div>

                             <form role="form" action=" {{action('TermsController@delete')}} " method="post">
                                <input type="hidden" name="att_id" id="att_id" value="<?php echo $terms[0]->attributes_id ?>" >
                                 <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                 <div class="form-group col-md-3">

                                     <select name="remove"  id="remove" class="form-control">
                                         <option value="">Actions</option>
                                         <option value="rm">Remove</option>

                                     </select>
                                 </div> <button type="submit" class="btn blue">Apply</button>
                            <table class="table table-striped table-bordered table-hover table-checkable sorted_table" id="datatable_products">


                        <thead>
                        <tr role="row" class="heading">
                           <th> <input type="checkbox"  class="group-checkable"  id="ck" /> </th>
                            <th width="30%"> Name </th>
                            <th width="25%"> Description </th>
                            <th width="25%"> Slug </th>



                        </tr>

                        </thead>
                        <tbody>

                        @if(count($terms[0]['Terms']) > 0)


                            @foreach($terms[0]['Terms'] as  $key => $term)
                     <tr data-id = "{{ $term->id }}"   data-index = "{{ $key }}" class="datarows" >
                                    <td><input name="del['{{ $term->id }}']" id="del['{{ $term->id }}']" value="{{ $term->id }}" type="checkbox" class="checkboxes" ></td>
                                    <td> <a href="  {{ route('terms.edit',$term->id) }}">{{ $term->name }}</a></td>
                                    <td>{{ $term->description }}</td>
                                    <td > {{ $term->slug }}</td>



                                </tr>

                            @endforeach
                        @else
                            <tr>
                                <td align="center" colspan="4"><h4>No Terms Found</h4></td>
                            </tr>
                        @endif
                        </tbody>

                    </table>
                            </form>

                            @if(count($terms) > 0)
                                <div class="row">
                                 <div class="col-md-12">
                                     <div class="pull-right">
                                         {{--  {!! $terms[0]['Terms']->appends(Input::except('page'))->links() !!}--}}

                                        {!! $terms->links() !!}
                                    </div>

                                </div>
                             @endif
                       </div>


                </div>
            </div>
    </div>
    <!-- End: life time stats -->
</div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')
@endsection

