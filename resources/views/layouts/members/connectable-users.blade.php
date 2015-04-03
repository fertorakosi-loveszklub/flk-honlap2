@extends('layouts.master')

@section('content')
    <div class="content">
        <h1><i class="fa fa-fw fa-chain"></i> Facebook fiók összekapcsolása</h1>

        <h2>{{ $member->name }}</h2>

        <div id="member-list">
            <div class="row vmargin">
                <div class="col-xs-12">
                    <input type="text" class="form-control search" placeholder="Keresés (Facebook név)">
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Profilkép</th>
                        <th>Facebook profil</th>
                        <th>Összekapcsolás</th>
                    </tr>
                </thead>
                <tbody class="list">
                    @foreach($users as $user)
                    <tr>
                        <td><img src="{!! $user->getProfilePicture($fb) !!}" alt="profilkép" width="50" height="50"/></td>
                        <td class="member-name">
                            <a href="https://www.facebook.com/app_scoped_user_id/{{ $user->id }}">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>
                            <a href="/tagok/fb-osszekapcsolas-most/{{ $member->id }}/{{ $user->id }}">
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
              valueNames: [ 'member-name' ]
            };

            var memberList = new List('member-list', options);
        });
    </script>
@overwrite
