@extends('layouts.master')

@section('css')
{!! HTML::style('assets/css/components-md.min.css') !!}
{!! HTML::style('assets/css/plugins-md.min.css') !!}
{!! HTML::style('assets/css/custom.css') !!}
@endsection

@section('content')

        <!-- BEGIN PAGE BAR -->


{{-- pae content --}}



<div class="row">
    <div class="col-md-12 text-center">
        <div class=" details">
            <h3>Unauthorized Access</h3>
            <p> Administrator has denied access to this page.</p>
        </div>
    </div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')

@endsection
