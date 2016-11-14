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

                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                      XML Sitemap</div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    Deze XML sitemap bevat {{ count($data) }} sitemaps.
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-6">
                                <table width="60%" class="table table-striped table-bordered table-hover" id="datatable_products">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="20%"> Sitemap </th>
                                        <th width="25%"> Last Modified </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data) > 0)
                                        @foreach($data as $sitemap)
                                            <?php $path_parts = pathinfo($sitemap['sitemap']);
                                                  $base       = $path_parts['basename']?>
                                            <tr>
                                                <td><a href="{{ url('sitemap_index_details/'.$base) }}"> {{$sitemap['sitemap']}}</a></td>
                                                <td> {{$sitemap['last_modified']}}</td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td align="center" colspan="7"><h4>Niet gevonden</h4></td>
                                            </tr>
                                    @endif
                                </tbody>
                            </table>
                            </div>
                            <div class="col-sm-4"></div>
                            <div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
    </div>
</div>

{{-- end of page content --}}
@endsection


@section('scripts')
    {!! HTML::script('assets/js/post_category.js') !!}
@endsection
