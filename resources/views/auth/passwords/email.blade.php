@extends('layouts.app')

        <!-- Main Content -->
@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @else
        <p>Enter your e-mail address below to reset your password.</p>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
        {!! csrf_field() !!}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

            <div class="">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address">

                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group">

            <div class="form-actions">
                <a href="/" type="button" id="back-btn" class="btn btn-default"> <i class="fa fa-btn fa-arrow-left"></i> Back</a>
                <button type="submit" class="btn btn-success uppercase pull-right"> <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link</button>
            </div>
        </div>
    </form>
@endsection