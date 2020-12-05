<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="shortcut icon" href="{{url('/')}}/assets/img/shortcut.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
            Neraca Saldo
        </title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <style>
            td {
                border: 1px solid black; border-collapse: collapse;
            }
            th, td {
                padding: 5px;
                text-align: left;
                font-size: 13px;
            }
        </style>
    </head>
    <body>
    @php
    $year = Request::segment(4);
    $month = Request::segment(5);

    setlocale(LC_ALL, 'id_ID');

    $jumlah_debit = 0;
    $jumlah_kredit = 0;
    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F'); // March
    @endphp
        <table style="border: 1px solid #000">
            <thead>
                <tr>
                    <th colspan="6" style="font-size: 13px; text-align: center;"><strong>Neraca Saldo</strong></th>
                </tr>
                <tr>
                    <th colspan="6" style="font-size: 18px; text-align: center;"><strong>{{$profil->name}}</strong></th>
                </tr>
                <tr>
                    <th colspan="6" style="font-size: 13px; text-align: center;"><strong>{{$business_profile}}</strong></th>
                </tr>
                <tr>
                    <th colspan="6" style="font-size: 11px; text-align: center;">Periode {{$monthName}} {{$year}}</th>
                </tr>
                <tr>
                    <th colspan="6"></th>
                </tr>
                <tr>
                    <th style="background-color: #8AC7EF; border: 1px solid black; border-collapse: collapse;" width="30" colspan="3">Nama Akun</th>
                    <th style="background-color: #8AC7EF; border: 1px solid black; border-collapse: collapse;" width="15">Posisi Normal</th>
                    <th style="background-color: #8AC7EF; border: 1px solid black; border-collapse: collapse;" width="15">Debit</th>
                    <th style="background-color: #8AC7EF; border: 1px solid black; border-collapse: collapse;" width="15">Kredit</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < sizeof($balance); $i++)
                <tr>
                    <td colspan="6" style="border: 1px solid black; border-collapse: collapse;">
                        <strong>{{$balance[$i]['parent_code']}} - {{$balance[$i]['parent_name']}}</strong>
                    </td>
                </tr>
                @if (isset($balance[$i]['classification']))
                    @for ($j = 0; $j < sizeof($balance[$i]['classification']); $j++)
                        <tr>
                            <td style="border: 1px solid black; border-collapse: collapse;" width="3"></td>
                            <td style="border: 1px solid black; border-collapse: collapse;" colspan="5"><strong>{{$balance[$i]['classification'][$j]['classification_name']}}</strong></td>
                        </tr>
                        @if (isset($balance[$i]['classification'][$j]['account']))
                            @for ($k = 0; $k < sizeof($balance[$i]['classification'][$j]['account']); $k++)
                                @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] != "0")
                                    <tr>
                                        <td colspan="2" width="3" style="border: 1px solid black; border-collapse: collapse;"></td>
                                        <td style="border: 1px solid black; border-collapse: collapse;">
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['account_code'] }} - {{ $balance[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                        </td>
                                        <td style="border: 1px solid black; border-collapse: collapse;">
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['position'] }}
                                        </td>
                                        <td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                                            @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == "Debit")
                                                {{$balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']}}
                                                @php
                                                    $jumlah_debit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']
                                                @endphp
                                            @else
                                            0
                                            @endif
                                        </td>
                                        <td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                                            @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == "Kredit")
                                                {{$balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']}}
                                                @php
                                                    $jumlah_kredit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']
                                                @endphp
                                            @else
                                            0
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endfor
                        @endif
                    @endfor
                @endif
            @endfor
            <tr>
                <td colspan="4" style="text-align: center; background-color: #8AC7EF; border: 1px solid black; border-collapse: collapse;"><strong>Total</strong></td>
                <td style="background-color: #8AC7EF; text-align: right; border: 1px solid black; border-collapse: collapse;"><strong>{{$jumlah_debit}}</strong></td>
                <td style="background-color: #8AC7EF; text-align: right; border: 1px solid black; border-collapse: collapse;"><strong>{{$jumlah_kredit}}</strong></td>
            </tr>
            </tbody>
        </table>
    </body>
</html>
