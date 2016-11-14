<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    {!! HTML::style('assets/plugins/font-awesome/css/font-awesome.min.css') !!}
    {!! HTML::style('assets/plugins/simple-line-icons/simple-line-icons.min.css') !!}
    {!! HTML::style('assets/plugins/bootstrap/css/bootstrap.min.css') !!}
    {!! HTML::style('assets/plugins/uniform/css/uniform.default.css') !!}
    {!! HTML::style('assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css') !!}
            <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->

    {!! HTML::style('assets/plugins/select2/css/select2.min.css') !!}
    {!! HTML::style('assets/plugins/select2/css/select2-bootstrap.min.css') !!}
            <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    {!! HTML::style('assets/css/components-md.css') !!}
    {!! HTML::style('assets/css/plugins-md.min.css') !!}
            <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    {!! HTML::style('assets/css/login.css') !!}
            <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->

</head>
<body class=" login">
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="/">
        {{--{!!  HTML::image('assets/img/logo-big.png', 'logo')  !!}--}}
    </a>
</div>
<div class="content">

    @yield('content')

</div>

<!-- BEGIN CORE PLUGINS -->

</body>
</html>