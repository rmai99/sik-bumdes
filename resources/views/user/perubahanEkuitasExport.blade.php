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
        * {
        font-size: 100%;
        font-family: sans-serif;
        }
        .text-center{
            text-align: center
        }
        .text-right{
            text-align: right
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 5px;
            text-align: left;
            font-size: 13px;
        }
        th{
            background:#8AC7EF;
        }
        .company{
            font-weight: 400;
            margin:0px 0px 5px 0px;
            font-size:18px;
        }
        .uppercase{
            text-transform: uppercase;
        }
        .periode{
            font-weight: 400;
            font-size: 16px;
            margin: 0px 0px 15px 0px;
        }
    </style>
</head>
<body>
    @php
        if (Request::segment(4) != null) {
          $month = Request::segment(4);
          $dt = Request::segment(3);
        }else {
          $dt = $year;
        }
        setlocale(LC_ALL, 'id_ID');
        $modal_awal = 0;
        $prive = 0;
    @endphp
    <div>
        <div>
            <h3 class="text-center company uppercase">{{$company->name}}</h3>
            <h3 class="text-center company">Perubahan Ekuitas</h3>
            @php
                $dateObj   = DateTime::createFromFormat('!m', $month);
                $monthName = $dateObj->format('F'); // March
            @endphp
            <p class="text-center periode"><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
        </div>
        <div>
            <table cellspacing="0" width="100%" style="width:100%">
                <tbody>
                    <tr>
                        <td style="width:60%">
                            <strong>Modal Awal</strong>
                        </td>
                        <td></td>
                        <td style="width:10%" class="text-right">
                            @for ($i = 1; $i <= sizeof($equityArray); $i++)
                                @if ($equityArray[$i]['name'] == "Modal Disetor")
                                <strong>
                                    Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'])),3)))}}
                                </strong>
                                    @php
                                        $modal_awal += $equityArray[$i]['ending balance'];
                                    @endphp
                                @endif
                            @endfor
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%;padding-left: 1.5rem!important;">
                            Laba bersih
                        </td>
                        <td style="width:10%" class="text-right">
                            @if ($saldo_berjalan < 0)
                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$saldo_berjalan)),3)))}}
                            @else
                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan)),3)))}}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @for ($i = 1; $i <= sizeof($equityArray); $i++)
                        @if ($equityArray[$i]['name'] == "Prive")
                            <tr>
                                <td style="width:60%;padding-left: 1.5rem!important;">
                                    {{ $equityArray[$i]['name'] }}
                                </td>
                                <td class="text-right" style="width:10%">
                                    @if ($equityArray[$i]['ending balance'] < 0)
                                        -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$equityArray[$i]['ending balance'])),3)))}}
                                    @else
                                        Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'])),3)))}}
                                    @endif
                                    @php
                                        $prive += $equityArray[$i]['ending balance'];
                                    @endphp
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endfor
                    <tr>
                        <td style="width:60%">
                            <strong>Total Penambahan Modal</strong>
                        </td>
                        <td></td>
                        <td style="width:10%" class="text-right">
                            @if ($saldo_berjalan >= 0)
                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan - $prive)),3)))}}
                            @else
                                Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan + $prive)),3)))}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%">
                            <strong  class="text-danger">Modal Akhir</strong>
                        </td>
                        <td></td>
                        <td class="text-right" style="width:10%">
                            @if ($saldo_berjalan >= 0)
                                Rp{{strrev(implode('.',str_split(strrev(strval($modal_awal + $saldo_berjalan - $prive)),3)))}}
                            @else
                                Rp{{strrev(implode('.',str_split(strrev(strval($modal_awal - $saldo_berjalan + $prive)),3)))}}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>