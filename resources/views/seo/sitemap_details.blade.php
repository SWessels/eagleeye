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
            <a href="/sitemap_index/">Sitemap</a>
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
                        {{$sitemap}}</div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    Deze XML sitemap <strong>{{$sitemap}}</strong> becat {{ count($data->url) }} sitemaps.
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-1"></div>
                            <div class="col-sm-9">
                                <table class="table table-striped table-bordered table-hover" id="datatable_products">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="25%"> Sitemap </th>
                                        <th width="15%"> Priority </th>
                                        <th width="10%"> Ch.Frequency </th>
                                        <th width="10%"> Last Modified </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data->url) > 0)
                                        @foreach($data->url as $urls)
                                            <?php
                                            $num = 100;
                                            $p = trim($urls->priority);
                                            $p1 = $p*$num; ?>
                                            <tr>
                                                <td><a href="#"> {{$urls->loc}}</a></td>
                                                <td>{{$p1}} %</td>
                                                <td>{{$urls->changefreq}}</td>
                                                <td>{{$urls->lastmod}}</td>
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
                            <div class="col-sm-2"></div>
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
