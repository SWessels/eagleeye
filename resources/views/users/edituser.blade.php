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
            <span>Edit User</span>
        </li>
    </ul>

</div>
<br>

{{-- page content --}}
<div class="row">
    <div class="col-md-12">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
         <div class="portlet ">
            <div class="portlet-title">
                <div class="caption">
                   {{ ucwords($userInfo->name) }}
                </div>
            </div>
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#general_tab" data-toggle="tab"> General Information </a>
                    </li>
                    <li>
                        <a href="#capabilities_tab" data-toggle="tab"> Capabilities </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="general_tab">
                        <div class="table-container">
                            <!-- BEGIN FORM-->
                            {!! Form::model($userInfo, array('method' => 'PATCH', 'route'=> ['users.update', $userInfo->id], 'class'=>'form-horizontal'))  !!}

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
                                    <label class="col-md-3 control-label">Role</label>
                                    <div class="col-md-4">
                                        {!! Form::select('role_id', $select, $userInfo->role_id, ['class' => 'form-control'])  !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label">Status</label>
                                    <div class="col-md-4">
                                        {!! Form::select('status', $status, $userInfo->status, ['class' => 'form-control'])  !!}
                                    </div>
                                </div>

                            </div>
                            <div class="form-actions bottom">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        {!! Form::hidden('action', 'update_info')  !!}
                                        {!! Form::button('Update', array('class' => 'btn green', 'type' => 'submit'))  !!}
                                        {!!  link_to('/users', $title = 'Cancel', $attributes = array('class' => 'btn btn-default'), $secure = null)  !!}

                                    </div>
                                </div>
                            </div>
                            {!! Form::close()  !!}
                                    <!-- END FORM-->

                        </div>
                    </div>
                    <div class="tab-pane fade" id="capabilities_tab">
                        {{-- capabilities --}}

                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cog"></i>Edit Capabilities</div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->

                                {!! Form::model($userInfo, array('method' => 'PATCH', 'route'=> ['users.update', $userInfo->id], 'class'=>'form-horizontal'))  !!}

                                    <div class="form-body">

                                        <div class="form-group">

                                            <div class="checkbox-list">
                                                <div class="row">

                                                    @foreach($capabilities as $cap)
                                                    <div class="col-sm-3">
                                                        <label class="checkbox-inline ">
                                                            <input type="checkbox" name="capabilities[]" value="{{ $cap->id }}" @if(in_array($cap->capability, $user_capabilities))  checked="checked" @endif > {{ ucwords(str_replace('_', ' ', $cap->capability)) }}
                                                        </label>
                                                    </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>




                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12 text-right">
                                                {!! Form::hidden('action', 'update_capabilities')  !!}
                                                {!! Form::button('Submit', array('class' => 'btn green', 'type' => 'submit'))  !!}
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close()  !!}
                                <!-- END FORM-->
                            </div>
                        </div>
                        {{-- capabilities --}}


                    </div>
                </div>


            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div>



{{-- end of page content --}}
@endsection

    @section('scripts')

    @endsection
