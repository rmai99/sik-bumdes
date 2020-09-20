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
            text-transform: uppercase;
            margin:0px;
            font-size:18px;
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

    $jumlah_debit = 0;
    $jumlah_kredit = 0;

@endphp
<div>
    <div>
        <h3 class="text-center company">{{$company->name}}</h3>
        <h3 class="text-center company">Neraca Saldo</h3>
        @php
            $dateObj   = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F'); // March
        @endphp
        <p class="text-center" style="margin:5px; margin-bottom:10px"><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
    </div>
    <div>
        <table cellspacing="0" width="100%" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center" style="width:40%">Nama Akun</th>
                    <th class="text-center" style="width:10%">Posisi Normal</th>
                    <th class="text-center" style="width:20%">Debit</th>
                    <th class="text-center" style="width:20%">Kredit</th>
                </tr>
            </thead>
            <tbody>
            @for ($i = 0; $i < sizeof($balance); $i++)
                <tr>
                    <td>
                        <strong>{{$balance[$i]['parent_code']}} - {{$balance[$i]['parent_name']}}</strong>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @if (isset($balance[$i]['classification']))
                    @for ($j = 0; $j < sizeof($balance[$i]['classification']); $j++)
                        <tr>
                            <td style="padding-left: 1.5rem!important;">
                                {{$balance[$i]['classification'][$j]['classification_name']}}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if (isset($balance[$i]['classification'][$j]['account']))
                            @for ($k = 0; $k < sizeof($balance[$i]['classification'][$j]['account']); $k++)
                                @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] != "0")
                                    <tr class="accordian-body collapse show accordion-toggle pl-3 dataAkun">
                                        <td style="padding-left: 3rem!important;">
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['account_code'] }} - {{ $balance[$i]['classification'][$j]['account'][$k]['account_name'] }}
                                        </td>
                                        <td class="text-center">
                                            {{ $balance[$i]['classification'][$j]['account'][$k]['position'] }}
                                        </td>
                                        <td class="text-right">
                                            @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == "Debit")
                                                @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                    -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                @else
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                @endif
                                                @php
                                                    $jumlah_debit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']
                                                @endphp
                                            @else
                                            Rp0
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($balance[$i]['classification'][$j]['account'][$k]['position'] == "Kredit")
                                                @if ($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'] < 0)
                                                    - Rp{{strrev(implode('.',str_split(strrev(strval(-1*$balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                @else 
                                                    Rp{{strrev(implode('.',str_split(strrev(strval($balance[$i]['classification'][$j]['account'][$k]['saldo_akhir'])),3)))}}
                                                @endif
                                                @php
                                                    $jumlah_kredit += $balance[$i]['classification'][$j]['account'][$k]['saldo_akhir']
                                                @endphp
                                            @else
                                            Rp0
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
                <td><strong>Total</strong></td>
                <td><strong></strong></td>
                <td class="text-right"><strong>Rp{{strrev(implode('.',str_split(strrev(strval($jumlah_debit)),3)))}} </strong></td>
                <td class="text-right"><strong>Rp{{strrev(implode('.',str_split(strrev(strval($jumlah_kredit)),3)))}} </strong></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- end content-->
</div>
</body>
</html>