@extends('layouts.albums.editor')

@section ('subcontent')
    <div class="content text-justify">
        <h2>Galériák</h2>
        <table class="table table-hover table-striped">
            <tbody>
                @foreach($albums as $album)
                    <tr>
                        <td>{{ $album->title }}</td>
                        <td><a href="/album/{{ $album->id . '/' . App\Libraries\UrlBeautifier::beautify($album->title) }}">Megtekintés</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@overwrite

@section('fb-og')
    <meta property="og:title" content="Galéria" >
@overwrite

@if(Session::has('admin'))
@section('adminmenu')
    <li><a href="#" id="NewAlbum"><i class="fa fa-plus fa-fw"></i> Új galéria</a></li>
    <li class="nav-divider"></li>
@overwrite
@endif

@section('scripts')
    <script src="/js/gallery.js"></script>
@overwrite
