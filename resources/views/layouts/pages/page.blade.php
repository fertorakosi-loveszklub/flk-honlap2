@extends('layouts.master')

@section ('content')
    <div class="content text-justify">
        <h2>{{ $title }}</h2>
        @if(isset($before))
            {!! $before !!}
        @endif
        {!! $content !!}
        @if(isset($after))
            {!! $after !!}
        @endif
    </div>
@overwrite

@section('fb-og')
    <meta property="og:title" content="{{ $title }}" />
@overwrite

@section('title')
    {{ $title }} -
@overwrite

@if(isset($scripts))
    @section('scripts')
        {!! $scripts !!}
    @overwrite
@endif

@if(Session::has('admin'))
    @section('adminmenu')
        <li><a href="/rolunk/szerkesztes/{{ $id }}"><i class="fa fa-pencil fa-fw"></i> Oldal szerkesztése</a></li>
        <li class="nav-divider"></li>
    @overwrite
@endif
