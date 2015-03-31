@extends('layouts.master')

@section('content')
    <div class="content">
        <h1>Tagdíj befizetések</h1>

        <div class="payment-list">
            <input type="text" class="vmargin search form-control" placeholder="Keresés (név, dátum)">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="sort" data-sort="member-name">Tag neve</th>
                        <th class="sort" data-sort="paid-at">Fizetés dátuma</th>
                        <th class="sort" data-sort="paid-until">Fizetve eddig</th>
                        <th>Törlés</th>
                    </tr>
                </thead>
                <tbody class="list">
            @foreach($payments as $payment)
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
                    <td>
                        <a href="/tagdij/torles/{{ $payment->id }}" class="confirm btn btn-primary"><i class="fa fa-fw fa-trash"></i></a>
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
              valueNames: [ 'member-name', 'paid-at', 'paid-until' ]
            };

            var memberList = new List('payment-list', options);
        });
    </script>
@overwrite