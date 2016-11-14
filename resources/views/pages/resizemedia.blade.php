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
            <span>Resize Media</span>
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
            <div class="col-sm-12 resized_images">
                <div class="col-md-3 col-sm-6">
                    <strong>Wait till all images resizes ... </strong>
                </div>
                <div class="col-md-3 col-sm-6">
                    <strong>Max ID is <?php echo  $maxid ; ?>.</strong>
                </div>
                <div class="col-md-3 col-sm-6">
                    <strong>Total Records are <?php echo sizeof($ids ) ?>. </strong>
                </div>
                <div class="col-md-3 col-sm-6">
                    <strong>Estimated time required is <?php echo $estimatedtime; ?> minutes. </strong>
                </div>



            </strong>
                <br>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 resized_images_body"></div>
        </div>



    </div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')
    {!! HTML::script('assets/js/resizemedia.js') !!}
@endsection

<script>
    var arr = [<?php echo '"'.implode('","',  $ids ).'"' ?>];
    var maxid = <?php echo  $maxid ; ?>;

</script>
