<html>
    <head>
        <style>
            table {
                font-size: 8pt;
                border-collapse: collapse;
            }
            th, td{
                border-bottom: 1px solid black;
                padding: 2px;
            }
        </style>
    </head>
    <body>
        <h1>FLK Tagnyilvántartás</h1>
        <p>Nyomtatva: {{ (new DateTime('now'))->format('Y. m. d.') }}</p>
        <p><strong>Tagok száma:</strong> {{ $members->count() }}</p>
        <hr/>
        <table>
            <thead>
                <th>Ig. szám</th>
                <th>Név</th>
                <th>Szül.</th>
                <th>Lakcím</th>
            </thead>
            <tbody>
                @foreach($members as $member)
                <tr>
                    <td>{{$member->card_id}}</td>
                    <td>{{$member->name}}</td>
                    <td>{{$member->birth_place}}, {{(new DateTime($member->birth_date))->format('Y. m. d.')}}</td>
                    <td>{{$member->address}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>