
@extends('layouts.app')

@section('content')
    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
        <h3 class="form-title font-green">Register</h3>
        <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">

            <div class="">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name">

                @if ($errors->has('name'))
                    <span class="help-block">
                        <span>{{ $errors->first('name') }}</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

            <div class="">
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username">

                @if ($errors->has('username'))
                    <span class="help-block">
                        <span>{{ $errors->first('username') }}</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

            <div class="">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <span>{{ $errors->first('email') }}</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">

            <div class="">
                <input type="password" class="form-control" name="password" placeholder="Password">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <span>{{ $errors->first('password') }}</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">

            <div class="">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <span>{{ $errors->first('password_confirmation') }}</span>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="form-actions">
                <a href="/" type="button" id="back-btn" class="btn btn-default">
                    <i class="fa fa-btn fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-success uppercase pull-right">
                    <i class="fa fa-btn fa-user"></i> Register
                </button>
            </div>
        </div>
    </form>
@endsection