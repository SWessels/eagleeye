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
            <h3>Record Not Found</h3>
            <p> The record not found in database.</p>
        </div>
    </div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')

@endsection
