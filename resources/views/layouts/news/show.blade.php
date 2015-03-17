@extends('layouts.master')

@section ('content')
    <div class="content text-justify">
        <h2>{{ $news->title }}</h2>
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <p class="meta">{{ $news->author->real_name }} - {{ (new DateTime($news->created_at))->format('Y. m. d.') }}</p>
            </div>
            <div class="hidden-xs hidden-sm col-md-4 text-right">
                <span class="fb-share-button" data-href="{{Request::url()}}" data-layout="button_count"></span>
            </div>
        </div>
        {!! $news->content !!}
    </div>
@overwrite

@section('fb-og')
    <meta property="og:title" content="{{ App\News::urlNormalize($news->title) }}" />
@overwrite

@section('title')
    {{App\News::urlNormalize($news->title)}} -
@overwrite

@if(Session::has('admin'))
    @section('adminmenu')
        <li><a href="/hirek/szerkesztes/{{ $news->id }}"><i class="fa fa-pencil fa-fw"></i> Hír szerkesztése</a></li>
        <li><a href="/hirek/torles/{{ $news->id }}" class="confirm"><i class="fa fa-trash fa-fw"></i> Hír törlése</a></li>
        <li class="nav-divider"></li>
    @overwrite
@endif
