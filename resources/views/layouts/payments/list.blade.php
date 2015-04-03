@extends('layouts.master')

@section('content')
    <div class="content">
        <h1>Tagdíj befizetések</h1>

        <div class="payment-list">
            <div class="row vmargin ">
                <div class="col-xs-6 col-sm-8">
                    <input type="text" class="search form-control" placeholder="Keresés (név, dátum)">
                </div>
                <div class="col-xs-6 col-sm-4">
                    <a class="btn btn-primary" href="/nyomtatas/tagdij-attekintes" 
                        target="_blank">
                        <i class="fa fa-fw fa-print"></i> Áttekintés
                    </a>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="sort" data-sort="member-name">Tag neve</th>
                        <th class="sort" data-sort="paid-at">Fizetés dátuma</th>
                        <th class="sort" data-sort="paid-until">Fizetve eddig</th>
                        <th class="sort" data-sort="amount">Összeg</th>
                        <th>Törlés</th>
                    </tr>
                </thead>
                <tbody class="list">
            @foreach($payments as $payment)
                @if(!is_null($payment->member))
                    <tr>
                        <td class="member-name">
                            {{ $payment->member->name }} ({{ ((new DateTime($payment->member->birth_date))->format('Y. m. d.')) }})
                        </td>
                        <td class="paid-at">
                            {{ $payment->paid_at }}
                        </td>
                        <td class="paid-until">
                            {{ $payment->paid_until }}
                        </td>
                        <td class="Összeg">
                            {{ $payment->amount }}
                        </td>
                        <td>
                            <a href="/tagdij/torles/{{ $payment->id }}" class="confirm btn btn-primary"><i class="fa fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                @endif
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
              valueNames: [ 'member-name', 'paid-at', 'paid-until' ]
            };

            var memberList = new List('payment-list', options);
        });
    </script>
@overwrite