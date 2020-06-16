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
        $month = Request::segment(4);
        $dt = Request::segment(3);
        setlocale(LC_ALL, 'id_ID');
        $modal_awal = 0;
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
                            <strong>LABA DITAHAN PERIODE SEBELUMNYA</strong>
                        </td>
                        <td style="width:10%"></td>
                    </tr>
                    @for ($i = 1; $i <= sizeof($equityArray); $i++)
                        @if ($equityArray[$i]['name'] != "Modal Disetor" && $equityArray[$i]['name'] != "Modal Usaha")
                            <tr>
                                <td style="width:60%;">
                                    {{ $equityArray[$i]['code'] }} - {{ $equityArray[$i]['name'] }}
                                </td>
                                <td class="text-right" style="width:10%">
                                    Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'])),3)))}}
                                    @php
                                        $modal_awal += $equityArray[$i]['ending balance'];
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endfor
                    <tr>
                        <td style="width:60%">
                            <strong>PENAMBAHAN/PENGURANGAN LABA DITAHAN</strong>
                        </td>
                        <td style="width:10%"></td>
                    </tr>
                    @for ($i = 1; $i <= sizeof($equityArray); $i++)
                        @if ($equityArray[$i]['name'] == "Saldo Laba Tahun Berjalan" || $equityArray[$i]['name'] == "Laba Ditahan")
                            <tr>
                                <td style="width:60%;">
                                    {{ $equityArray[$i]['code'] }} - {{ $equityArray[$i]['name'] }}
                                </td>
                                <td class="text-right" style="width:10%">
                                    Rp{{strrev(implode('.',str_split(strrev(strval($saldo_berjalan)),3)))}}
                                </td>
                            </tr>
                        @endif
                    @endfor
                    <tr>
                        <td style="width:60%">
                            <strong  class="text-danger">TOTAL EKUITAS AKHIR PERIODE</strong>
                        </td>
                        <td class="text-right" style="width:10%">
                            Rp{{strrev(implode('.',str_split(strrev(strval($modal_awal + $saldo_berjalan)),3)))}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>