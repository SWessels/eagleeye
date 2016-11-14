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
            <span>Employees</span>
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
                    <i class="fa fa-users"></i>Employees </div>
                <div class="actions">
                    <a href="{{ route('users.create') }}" class="btn orders btn-info">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-xs"> New Employee </span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                All({{ count($users) }})
                            </div>
                            <div class="col-sm-6">
                               <form class="pull-right col-sm-6 npall nmall">
                                   <div class="form-group">
                                       <div class="input-group input-group-sm hide">
                                           <input type="text" class="form-control" placeholder="Search">
                                                    <span class="input-group-btn">
                                                        <button class="btn red" type="button">Search</button>
                                                    </span>
                                       </div>
                                       <!-- /input-group -->
                                   </div>
                               </form>
                            </div>

                        </div>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_products">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="5%">
                                <input type="checkbox" class="group-checkable">
                            </th>
                            <th width="20%"> Name </th>
                            <th width="10%"> Username </th>
                            <th width="20%"> Email </th>
                            <th width="15%"> Role </th>
                            <th width="15%"> Status </th>
                            <th width="15%"> Actions </th>
                        </tr>

                        </thead>
                        <tbody>
                        @if(count($users) > 0)

                        @foreach($users as $user)

                            <tr>
                                <td><input type="checkbox" class="group-checkable"></td>
                                <td> {{ $user->name }}</td>
                                <td> {{ $user->username }}</td>
                                <td> {{ $user->email }}</td>
                                <td> {{ ucwords($role = App\UserRole::find($user->role_id)->role_name) }}  </td>
                                <td class="text-center">
                                 @if($user->status == 'active')
                                    <label class="label label-success">Active</label>
                                 @else
                                        <label class="label label-danger">Inactive</label>
                                 @endif
                                </td>
                                <td>
                                    <div class="actions">
                                        <div class="btn-group">
                                            <a class="btn btn-xs orders btn-default dropdown-toggle" href="javascript:;" data-toggle="dropdown">
                                                 <span class="hidden-xs"> Actions </span>
                                                <i class="fa fa-angle-down"></i>
                                            </a>
                                            <div class="dropdown-menu pull-right">
                                                <li>
                                                    <a href="{{ URL::to('users/' . $user->id . '/edit') }}"> <i class="fa fa-pencil"></i> Edit </a>
                                                </li>
                                               {{-- <li>
                                                    --}}{{--<a href="{!!  route('users.destroy', ['id' => $user->id]) !!}"> <i class="fa fa-remove"></i> Delete </a>--}}{{--
                                                    --}}{{--{!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['users.destroy', $user->id]])
                                                    !!}
                                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                    {!! Form::close() !!}--}}{{--
                                                </li>--}}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @else
                        <tr>
                            <d colspan="7"><h4>No Employee Found</h4></d>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                    @if(count($users) > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-right">
                                {!! $users->links() !!}
                            </div>

                        </div>
                    </div>
                     @endif
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
