@extends('layouts.master')

    @section('css')
        {!! HTML::style('assets/css/dashboard_plugins.css') !!}
    @endsection

    @section('js')
        {!! HTML::script('assets/js/dashboard_plugins.js') !!}
        {!! HTML::script('assets/js/dashboard_js.js') !!}

        {!! HTML::script('assets/js/wssn.js') !!}
    @endsection