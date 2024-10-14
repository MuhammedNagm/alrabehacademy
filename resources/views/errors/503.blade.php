@extends('errors.layout')

@section('title', trans('Modules::messages.down_for_maintenance_title'))

@section('content')
    <h1 style="color: orangered;">@lang('Modules::messages.down_for_maintenance_title')</h1>
    <div class="title m-b-md">
    @lang('Modules::messages.down_for_maintenance_message')
    </div>
@endsection