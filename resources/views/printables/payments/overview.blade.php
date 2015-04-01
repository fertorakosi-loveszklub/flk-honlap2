<html>
    <head>
        <style>
            table {
                border-collapse: collapse;
            }
            tr, td {
                border-bottom: 1px solid black;
            }
        </style>
    </head>
    <body>
        <h1>Tagdíjfizetések áttekintése</h1>
        <p>
            Tagdijfizetések {{ (new DateTime($from))->format('Y. m. d.') }} és
            {{ (new DateTime($to))->format('Y. m. d.') }} között.
        </p>

        <table>
            <thead>
                <tr>
                    <th>Tag neve</th>
                    <th>Fizetés dátuma</th>
                    <th>Tagdíj befizetve eddig</th>
                    <th>Fizetett összeg</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->member->name }}</td>
                    <td>{{ (new DateTime($payment->paid_at))->format('Y. m. d.') }}</td>
                    <td>{{ (new DateTime($payment->paid_until))->format('Y. m. d.') }}</td>
                    <td>{{ $payment->amount }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3"><strong>Összesen</strong></td>
                    <td><strong>{{$sum}}</strong></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>