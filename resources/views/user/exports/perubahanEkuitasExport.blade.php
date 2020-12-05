<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{url('/')}}/assets/img/shortcut.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Perubahan Ekuitas
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
</head>
<body>
    @php
        if (Request::segment(4) != null) {
          $month = Request::segment(5);
          $year = Request::segment(4);
        }else {
          $dt = $year;
        }
        setlocale(LC_ALL, 'id_ID');
        $modal_awal = 0;
        $prive = 0;
        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F'); // March
    @endphp
    <table>
        <thead>
            <tr>
                <th colspan="4" style="font-size: 13px; text-align: center;"><strong>Laporan Perubahan Ekuitas</strong></th>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 18px; text-align: center;"><strong>{{$profil->name}}</strong></th>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 13px; text-align: center;"><strong>{{$business_profile}}</strong></th>
            </tr>
            <tr>
                <th colspan="4" style="font-size: 11px; text-align: center;">Periode {{$monthName}} {{$year}}</th>
            </tr>
            <tr>
                <th width="3"></th>
                <th width="35"></th>
                <th width="15"></th>
                <th width="15"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid black; border-collapse: collapse;" colspan="2"><strong>Modal Awal</strong></td>
                <td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                    @for ($i = 1; $i <= sizeof($equityArray); $i++)
                        @if ($equityArray[$i]['name'] == "Modal Disetor")
                        <strong>
                            {{$equityArray[$i]['ending balance']}}
                        </strong>
                            @php
                                $modal_awal += $equityArray[$i]['ending balance'];
                            @endphp
                        @endif
                    @endfor
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; border-collapse: collapse;"></td>
                <td style="border: 1px solid black; border-collapse: collapse;">Laba bersih</td>
                <td style="text-align: right; border: 1px solid black; border-collapse: collapse;">{{$saldo_berjalan}}</td>
                <td style="border: 1px solid black; border-collapse: collapse;"></td>
            </tr>
            @for ($i = 1; $i <= sizeof($equityArray); $i++)
                @if ($equityArray[$i]['name'] == "Prive")
                    <tr>
                        <td style="border: 1px solid black; border-collapse: collapse;"></td>
                        <td style="border: 1px solid black; border-collapse: collapse;">{{ $equityArray[$i]['name'] }}</td>
                        <td style="border: 1px solid black; border-collapse: collapse;; text-align:right">{{$equityArray[$i]['ending balance']}}</td>
                            @php
                                $prive += $equityArray[$i]['ending balance'];
                            @endphp
                        <td style="border: 1px solid black; border-collapse: collapse;"></td>
                    </tr>
                @endif
            @endfor
            <tr>
                <td colspan="2" style="border: 1px solid black; border-collapse: collapse;"><strong>Total Penambahan Modal</strong></td>
                <td style="border: 1px solid black; border-collapse: collapse;"></td>
                <td style="border: 1px solid black; border-collapse: collapse; text-align:right">{{$saldo_berjalan - $prive}}</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; border-collapse: collapse;"><strong>Modal Akhir</strong></td>
                <td style="border: 1px solid black; border-collapse: collapse;"></td>
                <td style="text-align: right; background-color: #8AC7EF; border: 1px solid black; border-collapse: collapse;">
                    @if ($saldo_berjalan >= 0)
                        {{$modal_awal + $saldo_berjalan - $prive}}
                    @else
                        {{$modal_awal + $saldo_berjalan + $prive}}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>