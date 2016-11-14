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
            <span>Add New User</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}

<div class="row">
    <div class="col-md-12">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i> Add New Users
            </div>
        </div>
        <hr>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- BEGIN FORM-->
                  {!! Form::open([
                    'route' => 'users.store',
                    'class' => 'form-horizontal'
                ]) !!}


            <div class="form-body">
                <div class="form-group">

                    {!!  Form::label('name', 'Name', array('class' => 'col-md-3 control-label'))  !!}

                    <div class="col-md-4">
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => 'Name'))  !!}
                         <span class="help-block"> </span>
                    </div>
                </div>

                <div class="form-group">

                    {!!  Form::label('username', 'Username', array('class' => 'col-md-3 control-label'))  !!}

                    <div class="col-md-4">
                        {!! Form::text('username', Input::old('username'), array('class' => 'form-control', 'placeholder' => 'Username'))  !!}
                        <span class="help-block"> </span>
                    </div>
                </div>

                <div class="form-group">
                    {!!  Form::label('name', 'Email Address', array('class' => 'col-md-3 control-label'))  !!}
                    <div class="col-md-4">
                        {!! Form::email('email', Input::old('email'), array('class' => 'form-control', 'placeholder' => 'Email Address'))  !!}
                    </div>
                </div>

                <div class="form-group">
                    {!!  Form::label('name', 'Password', array('class' => 'col-md-3 control-label'))  !!}
                    <div class="col-md-4">
                        {!!  Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password'))  !!}
                    </div>
                </div>

                <div class="form-group">
                    {!!  Form::label('name', 'Confirm Password', array('class' => 'col-md-3 control-label'))  !!}
                    <div class="col-md-4">
                        {!!  Form::password('password_confirmation', array('class' => 'form-control', 'placeholder' => 'Confirm Password'))  !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Role</label>
                    <div class="col-md-4">
                        {!! Form::select('role', $rolesArray, NULL, ['class' => 'form-control'])  !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Status</label>
                    <div class="col-md-4">
                        {!! Form::select('status', $status, NULL, ['class' => 'form-control'])  !!}
                    </div>
                </div>

            </div>
            <div class="form-actions bottom">
                <div class="row">
                    <div class="col-md-offset-3 col-md-9">
                        {!! Form::button('Submit', array('class' => 'btn green', 'type' => 'submit'))  !!}

                        {!!  link_to('/users', $title = 'Cancel', $attributes = array('class' => 'btn btn-default'), $secure = null)  !!}

                    </div>
                </div>
            </div>
        {!!  Form::close()  !!}
        <!-- END FORM-->
    </div>
</div>

{{-- end of page content --}}
@endsection

    @section('scripts')

    @endsection
