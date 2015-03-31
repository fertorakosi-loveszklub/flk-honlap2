@extends('layouts.master')

@section('content')
    <div class="content">
        <h1><i class="fa fa-fw fa-users"></i> Tagnyilvántartás</h1>

        <div id="member-list">

            <div class="row vmargin">
                <div class="col-xs-8 col-sm-10">
                    <input type="text" class="form-control search" placeholder="Keresés (név, Facebook név vagy születési dátum)">
                </div>
                <div class="col-xs-4 col-sm-2">
                    <a class="btn btn-primary" href="/tagok/uj"><i class="fa fa-fw fa-user-plus"></i> Új tag</a>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="sort" data-sort="member-name">Név</th>
                        <th class="sort" data-sort="member-birthdate">Szül.</th>
                        <th>Tagdíj</th>
                        <th>Facebook profil</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>

                <tbody class="list">
                @foreach($members as $member)
                    <tr>
                        <td class="member-name">{{ $member->name }}</td>
                        <td class="member-birthdate">{{ (new DateTime($member->birth_date))->format('Y. m. d.') }}</td>
                        <td class="member-fee">
                            @if($member->isPaid())
                                <i class="fa fa-fw fa-check text-success"></i>
                            @else
                                <i class="fa fa-fw fa-times text-danger"></i>
                            @endif
                        </td>
                        <td class="member-facebook">
                            @if(is_null($member->user))
                                <a class="btn btn-primary btn-keep-size" href="/tagok/felhasznalo-osszekapcsolas/{{ $member->id }}"><i class="fa fa-fw fa-chain"></i> Összekapcsolás</a>
                            @else
                                <a class="btn btn-primary btn-keep-size" href="/tagok/fb-szetkapcsolas/{{ $member->id }}"><i class="fa fa-fw fa-chain-broken"></i> {{ $member->user->name }}</a>
                            @endif
                        </td>
                        <td class="member-actions">
                            <a class="btn btn-primary" title="Szerkesztés" href="/tagok/szerkesztes/{{ $member->id }}"><i class="fa fa-fw fa-pencil"></i></a>
                            <a class="btn btn-primary" title="Tagdíjfizetés könyvelése"
                            href="/tagdij/fizetes/{{ $member->id }}">
                                <i class="fa fa-fw fa-money"></i>
                            </a>
                            <a class="btn btn-primary confirm" href="/tagok/torles/{{ $member->id }}" title="Törlés"><i class="fa fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@overwrite

@section('scripts')
    <script type="text/javascript" src="/js/list.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var options = {
              valueNames: [ 'member-name', 'member-birthdate', 'member-facebook' ]
            };

            var memberList = new List('member-list', options);
        });
    </script>
@overwrite
