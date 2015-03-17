@extends('layouts.master')


@section('content')
    <div class="content">
        <h2><i class="fa fa-trophy fa-fw"></i> Egyéni rekordok</h2>

        <div class="alert alert-danger error" role="alert">
            Hiba történt a rekordok betöltése közben. <span id="error"></span>
        </div>

        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                @foreach($categories as $category)
                    <li role="presentation"><a href="#category-{{ $category->id }}" data-category="{{ $category->id }}" aria-controls="#category-{{ $category->id }}" role="tab" data-toggle="tab">{{ $category->title }}</a></li>
                @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                @foreach($categories as $category)
                    <div role="tabpanel" class="tab-pane fade" id="category-{{ $category->id }}">

                        <div class="loading row">
                            <div class="col-xs-12 text-center">
                                <h1><i class="fa fa-spin fa-circle-o-notch"></i></h1>
                            </div>
                        </div>

                        <table class="recordtable table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Név</th>
                                    <th>Dátum</th>
                                    <th>Köregység</th>
                                    <th>Lövések száma</th>
                                    <th>10 lövés átlaga</th>
                                    <th>Kép</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@overwrite

@section('scripts')
    <link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="/js/records.js"></script>
@overwrite

@section('og')
    <meta property="og:title" content="Egyéni rekordok" />
@overwrite