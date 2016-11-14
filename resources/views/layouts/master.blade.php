<!doctype>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8" />
    <meta name="_token" content="{!! csrf_token() !!}"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    {!! HTML::style('assets/css/master_plugins.css') !!}
    {!! HTML::style('assets/css/master_css.css') !!}

    @yield('css')
</head>
<body  class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-md {{ Session::get('connection') }}">
<!-- BEGIN PAGE SPINNER -->
<div class="page-spinner-bar">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
</div>
@include('includes/header')

        <!-- BEGIN CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    @include('includes/sidebar')
            <!-- END SIDEBAR -->

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            @yield('content')
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->

</div>
<!-- END CONTAINER-->

{{-- BEGIN FOOTER --}}
<div class="page-footer">
    <div class="page-footer-inner"> {{ date('Y') }} &copy;

    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->

<!-- COMMON SCRIPTS -->
{!! HTML::script('assets/js/jquery.min.js') !!}
{!! HTML::script('assets/js/master_plugins.js') !!}
{!! HTML::script('assets/js/master_js.js') !!}
<!-- COMMON SCRIPTS -->

@yield('scripts')

</body>
</html>