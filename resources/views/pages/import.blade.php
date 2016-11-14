@extends('layouts.master')

@section('css')
@endsection

@section('content')
        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{!! url('/') !!}">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <span>Import</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}

<div class="row">
    <div class="col-md-12">
        <div class="portlet-title">
            <div class="caption">
               {{-- <i class="fa fa-user"></i> Add New Users--}}
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <a href="{{ url('importcat/cats') }}" target="_blank" class="btn btn-primary">Import Categories</a>
                <a href="{{ url('productcat/products') }}" target="_blank" class="btn btn-primary">Save Product Categories</a>
                <a href="{{ url('importtags/tags') }}" target="_blank" class="btn btn-primary">Import Tags</a>
                <a href="{{ url('producttags/products') }}" target="_blank" class="btn btn-primary">Save Product Tags</a>
                <a href="{{ url('importproducts/products') }}" target="_blank" class="btn btn-primary">Import Products and Save</a>
                <a href="{{ url('importposts/posts') }}" target="_blank" class="btn btn-primary">Import Posts</a>
                <a href="{{ url('importorders') }}" target="_blank" class="btn btn-primary">Import Orders</a>
                <a href="{{ url('create_summary_tables') }}" target="_blank" class="btn btn-primary">Create Summary Tables</a>

            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-sm-12">

                <a href="{{ url('resize_folder_images') }}" target="_blank" class="btn btn-primary">Resize Media Images (From DB)</a>
                <a href="{{ url('import_product_seo_details_db') }}" target="_blank" class="btn btn-primary">Import Seo Details (From DB)</a>
                <a href="{{ url('create_all_xml') }}" target="_blank" class="btn btn-primary">Create Sitemap XML</a>
            </div>
        </div>
    </div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')
@endsection
