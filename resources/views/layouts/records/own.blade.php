@extends('layouts.master')


@section('content')
    <div class="content">
        <h2><i class="fa fa-trophy fa-fw"></i> Saját eredményeim</h2>
        <div class="alert alert-danger error" role="alert">
            Hiba történt. <span id="error"></span>
        </div>

        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                @foreach($categories as $category)
                    <li role="presentation"><a href="#category-{{ $category->id }}" aria-controls="#category-{{ $category->id }}" role="tab" data-toggle="tab">{{ $category->title }}</a></li>
                @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                @foreach($categories as $category)
                    <div role="tabpanel" class="tab-pane fade" id="category-{{ $category->id }}">
                        <table class="recordtable table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Dátum</th>
                                    <th>Köregység</th>
                                    <th>Lövés</th>
                                    <th>Átlag (10)</th>
                                    <th>Nyilvános</th>
                                    <th>Kép</th>
                                    <th>Törlés</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->records as $record)
                                    <tr>
                                        <td>{{ date_format(date_create($record->shot_at), 'Y. m. d.') }}</td>
                                        <td>{{ $record->points }}</td>
                                        <td>{{ $record->shots }}</td>
                                        <td>{{ $record->shots_average }}</td>
                                        <td><button class="btn toggle-visibility" data-id="{{ $record->id }}"><i class="fa fa-fw {{ $record->is_public ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i></button></td>
                                        <td><a class="fancyimage" href="{{ $record->image_url }}">Kép</a></td>
                                        <td><a class="btn confirm" href="/rekordok/torles/{{ $record->id }}"><i class="fa fa-trash fa-fw"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if (count($category->records) > 1)
                            <a href="#progressData" data-id="{{ $category->id }}" class="myProgress btn btn-primary"><i class="fa fa-line-chart fa-fw"></i> Fejlődés</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div style="display:none"><div style='height:400px;width:600px' id="progressData"></div></div>
    </div>
@overwrite

@section('scripts')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    <script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="/js/myrecords.js"></script>
@overwrite