@extends('layouts.albums.editor')

@section ('subcontent')
    <div class="content text-justify">
        <h2><i class="fa fa-spin fa-circle-o-notch" id="loadingicon"></i> {{$album->title}}</h2>
        <input type="hidden" id="hAlbumURL" value="{{ $album->album_url }}">
        <input type="hidden" id="hAlbumTitle" value="{{ $album->title }}">
        <input type="hidden" id="hAlbumId" value="{{ $album->id }}">

        <div id="images">

        </div>

        <script type="text/javascript">
            loadAlbum("{{$album->album_url}}");
        </script>
    </div>
@overwrite

@section('fb-og')
    <meta property="og:title" content="{{ $album->title }} - Képek" >
@overwrite

@if(Session::has('admin'))
@section('adminmenu')
    <li><a href="#" id="EditAlbum"><i class="fa fa-pencil fa-fw"></i> Galéria szerkesztése</a></li>
    <li><a href="/galeria/torles/{{ $album->id }}" class="confirm"><i class="fa fa-trash fa-fw"></i> Album törlése</a></li>
    <li class="nav-divider"></li>
@overwrite
@endif

@section('scripts')
    <script src="/js/imgurthumbnail.js"></script>
    <script src="/js/gallery.js"></script>
    <link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
@overwrite