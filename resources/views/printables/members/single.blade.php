<html>
    <head>
        <style>
        td, th {
            padding: 5px;
        }
        .borders {
            border-collapse: collapse;
        }
        .borders th, .borders td {
            border-bottom: 1px solid black;
            padding: 5px;
        }
        </style>
    </head>
    <body>
        <h1>{{ $member->name }}</h1>
        <h2>Személyes adatok</h2>
        <table>
            <tbody>
                <tr>
                    <td>Szül.</td>
                    <td>{{ $member->birth_place}}, 
                    {{ (new DateTime($member->birth_date))->format('Y. m. d.') }}</td>
                </tr>
                <tr>
                    <td>Anyja neve</td>
                    <td>{{$member->mother_name}}</td>
                </tr>
                <tr>
                    <td>Lakcím</td>
                    <td>{{$member->address}}</td>
                </tr>
                <tr>
                    <td>Tagság kezdete</td>
                    <td>{{(new DateTime($member->member_since))->format('Y. m. d.')}}</td>
                </tr>
                <tr>
                    <td>Igazolás sorszáma</td>
                    <td>{{$member->card_id}}</td>
                </tr>
            </tbody>
        </table>
        <h2>Tagdíj</h2>
        @if($member->isPaid())
        <p>
            Utolsó fizetett tagdíj alapján tagsági viszonya <strong>{{ (new DateTime($member->getPaidUntil()))->format('Y. m. d.')}}-ig</strong> befizetve.
        </p>
        @else
        <p>
            A tagdíj befizetésre vár!
        </p>
        @endif
        <h2>Tagdíjfizetések</h2>
        <table class="borders">
            <thead>
                <tr>
                    <th>Fizetés dátuma</th>
                    <th>Tagdíj fizetve eddig</th>
                    <th>Összeg</th>
                </tr>
            </thead>
            <tbody>
                @foreach($member->payments as $payment)
                <tr>
                    <td>{{ (new DateTime($payment->paid_at))->format('Y. m. d.') }}</td>
                    <td>{{ (new DateTime($payment->paid_until))->format('Y. m. d.') }}</td>
                    <td>{{$payment->amount}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>