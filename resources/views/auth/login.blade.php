<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    {!! HTML::style('assets/css/login_plugins.css') !!}
    {!! HTML::style('assets/css/login.css') !!}
</head>
<body class=" login">
<!-- BEGIN LOGO -->
<div class="logo">
    <a href="/">
        {{--{!!  HTML::image('assets/img/logo-big.png', 'logo')  !!}--}}
    </a>
</div>
<div class="content">

    <form class="form-horizontal" name="loginform" role="form" method="POST" action="{{ url('/login') }}" accept-charset="UTF-8">
        {!! csrf_field() !!}
        <h3 class="form-title font-green">Sign In</h3>
        @if(Session::has('flash_message'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger">
                        {{ Session::get('flash_message') }}
                    </div>
                </div>
            </div>
        @endif


        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username"><strong>Username or Email-address </strong></label>
            <input type="text" class="form-control" name="username" value="{{ old('username') }}">

            @if ($errors->has('username'))
                <span class="help-block">
                <span>{{ $errors->first('username') }}</span>
            </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="email"><strong>Password</strong></label>
            <input type="password" class="form-control" name="password">

            @if ($errors->has('password'))
                <span class="help-block">
                    <span>{{ $errors->first('password') }}</span>
                </span>
            @endif
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-xs-7 npall nmall">
                    <a href="#" id="forget-password" class="forget-password pull-left">Forgot Password?</a>
                    <label class="rememberme check"><input type="checkbox" name="remember" value="1" />Remember </label>

                </div>

                <div class="col-xs-5 npall nmall">
                    <input type="submit" name="submit" class="btn green uppercase pull-right" value="Login">
                </div>
            </div>

        </div>

    </form>

</div>
</body>
</html>