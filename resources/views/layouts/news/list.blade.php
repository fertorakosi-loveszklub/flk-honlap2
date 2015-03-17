@extends('layouts.master')

@section ('content')
    @foreach ($news as $n)
        <div class="content text-justify">
            <h2><a href="/hir/{{$n->id . '/' . App\News::urlFriendlify($n->title) }}">{{ $n->title }}</a></h2>
            <p class="meta">{{ $n->author->real_name }} - {{ (new DateTime($n->created_at))->format('Y. m. d.') }} </p>
            {!! $n->content !!}
        </div>
    @endforeach
@overwrite

@if(Session::has('admin'))
    @section('adminmenu')
        <li><a href="/hirek/uj"><i class="fa fa-plus fa-fw"></i> Új hír</a></li>
        <li class="nav-divider"></li>
    @overwrite
@endif
