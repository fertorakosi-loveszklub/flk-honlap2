@extends('layouts.master')

@section('content')
    <div class="content">
        <h1><i class="fa fa-fw fa-users"></i> Tag összekapcsolása</h1>
        <h2>{{ $user->name }}</h2>

        <div id="member-list">

            <div class="row vmargin">
                <div class="col-xs-12 col-sm-12">
                    <input type="text" class="form-control search" placeholder="Keresés (név vagy születési dátum)">
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="sort" data-sort="member-name">Név</th>
                        <th class="sort" data-sort="member-birthdate">Szül.</th>
                        <th>Összekapcsolás</th>
                    </tr>
                </thead>

                <tbody class="list">
                @foreach($members as $member)
                    <tr>
                        <td class="member-name">{{ $member->name }}</td>
                        <td class="member-birthdate">{{ (new DateTime($member->birth_date))->format('Y. m. d.') }}</td>
                        <td class="member-actions">
                            <a title="Összekapcsolás" href="/tagok/fb-osszekapcsolas-most/{{ $member->id }}/{{ $user->id }}">
                                <i class="fa fa-fw fa-chain"></i> Összekapcsolás
                            </a>
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
              valueNames: [ 'member-name', 'member-birthdate' ]
            };

            var memberList = new List('member-list', options);
        });
    </script>
@overwrite
