@extends('layouts.iframe')


@section('content') 
   @php
   // dd($_SERVER['HTTP_REFERER']);
   @endphp
   @include('partials.quiz_body.index', ['show_quiz_title' => false])

@endsection

@section('js')

@endsection

