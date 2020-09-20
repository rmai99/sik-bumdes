<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{url('/')}}/assets/img/shortcut.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Laporan Laba Rugi
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
    @endphp
<div>
    <div>
        <div>
            <h3 class="text-center company uppercase">{{$company->name}}</h3>
            <h3 class="text-center company">Laba Rugi</h3>
            @php
                $dateObj   = DateTime::createFromFormat('!m', $month);
                $monthName = $dateObj->format('F');
            @endphp
            <p class="text-center periode"><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
        </div>
        <div>
            <table cellspacing="0" width="100%" style="width:100%">
                <tbody>
                    <tr>
                        <td style="width:60%">
                            Pendapatan
                        </td>
                        <td style="width:10%"></td>
                    </tr>
                    @for ($i = 0; $i < sizeof($incomeArray); $i++)
                        <tr>
                            <td style="width:60%;padding-left: 1.5rem!important;">
                                <strong>{{$incomeArray[$i]['classification']}}</strong>
                            </td>
                            <td style="width:10%"></td>
                        </tr>
                        @if (isset($incomeArray[$i]['name']))
                            @for ($y = 0; $y < sizeof($incomeArray[$i]['name']); $y++) 
                                @if($incomeArray[$i]['ending balance'][$y] !="0" )
                                    <tr>
                                        <td style="width:60%;padding-left: 3rem!important;">
                                            {{$incomeArray[$i]['code'][$y]}}- {{$incomeArray[$i]['name'][$y]}}
                                        </td>
                                        <td class="text-right" style="width:10%">
                                            @if ($incomeArray[$i]['ending balance'][$y] < 0)
                                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*$incomeArray[$i]['ending balance'][$y])),3)))}}
                                            @else
                                                Rp{{strrev(implode('.',str_split(strrev(strval($incomeArray[$i]['ending balance'][$y])),3)))}}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endfor
                        @endif
                        <tr>
                            <td style="width:60%;padding-left: 1.5rem!important;">
                                Total {{$incomeArray[$i]['classification']}}
                            </td>
                            <td class="text-right" style="width:10%"></td>
                        </tr>
                    @endfor
                    <tr>
                        <td style="width:60%">
                            <strong>Total Pendapatan</strong>
                        </td>
                        <td class="text-right" style="width:10%">
                            Rp{{strrev(implode('.',str_split(strrev(strval($income)),3)))}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%">Biaya</td>
                        <td style="width:10%"></td>
                    </tr>
                    @for ($i = 0; $i < sizeof($expenseArray); $i++)
                        <tr>
                            <td style="width:60%;padding-left: 1.5rem!important;">
                                <strong>{{$expenseArray[$i]['classification']}}</strong>
                            </td>
                            <td style="width:10%"></td>
                        </tr>
                        @if (isset($incomeArray[$i]['name']))
                            @for ($j = 0; $j < sizeof($expenseArray[$i]['ending balance']); $j++)
                                @if($expenseArray[$i]['ending balance'][$j] !=0)
                                    <tr>
                                        <td style="width:60%;padding-left: 3rem!important;">
                                            {{$expenseArray[$i]['code'][$j]}} -
                                            {{$expenseArray[$i]['name'][$j]}}
                                        </td>
                                        <td class="text-right" style="width:10%">
                                            Rp{{strrev(implode('.',str_split(strrev(strval($expenseArray[$i]['ending balance'][$j])),3)))}}
                                        </td>
                                    </tr>
                                @endif
                            @endfor
                        @endif
                        <tr>
                            <td style="width:60%;padding-left: 1.5rem!important;">
                                Total {{ $expenseArray[$i]['classification'] }}
                            </td>
                            <td style="width:10%" class="text-right"></td>
                        </tr>
                    @endfor
                    <tr>
                        <td style="width:60%">
                            <strong>Total Biaya</strong>
                        </td>
                        <td class="text-right" style="width:10%">
                            @if ($expense < 0)
                                <strong>-Rp{{strrev(implode('.',str_split(strrev(strval(-1*$expense)),3)))}}</strong>
                            @else
                                <strong>Rp{{strrev(implode('.',str_split(strrev(strval($expense)),3)))}}</strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%">
                            <strong class="text-danger">Laba Usaha</strong>
                        </td>
                        <td class="text-right" style="width:10%">
                            <strong class="text-danger">
                                @if (($income - $expense) < 0)
                                    -Rp{{strrev(implode('.',str_split(strrev(strval(-1*($income - $expense))),3)))}}
                                @else
                                    Rp{{strrev(implode('.',str_split(strrev(strval($income - $expense)),3)))}}
                                @endif
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:60%">Pendapatan dan Biaya Lainnya</td>
                        <td style="width:10%"></td>
                    </tr>
                    @for ($i = 0; $i < sizeof($othersIncomeArray); $i++)
                        <tr>
                            <td style="width:60%;padding-left: 1.5rem!important;">
                                <strong>{{$othersIncomeArray[$i]['classification']}}</strong>
                            </td>
                            <td class="text-right" style="width:10%">
                                {{$othersIncome}}
                            </td>
                        </tr>
                    @endfor
                    @for ($i = 0; $i < sizeof($othersExpenseArray); $i++)
                        <tr>
                            <td style="width:60%;padding-left: 1.5rem!important;">
                                <strong>{{$othersExpenseArray[$i]['classification']}}</strong>
                            </td>
                            <td class="text-right" style="width:10%">
                                {{$othersExpense}}
                            </td>
                        </tr>
                    @endfor
                    <tr>
                        <td style="width:60%">
                            <strong>Total Pendapatan dan Biaya Lainnya</strong>
                        </td>
                        <td class="text-right" style="width:10%">{{ $othersIncome - $othersExpense}}</td>
                    </tr>
                    <tr>
                        <td style="width:60%">
                            <strong>SALDO LABA/RUGI TAHUN BERJALAN</strong>
                        </td>
                        <td class="text-right" style="width:10%">
                            @if (($income + $othersIncome - $expense - $othersExpense) < 0)
                                -Rp{{strrev(implode('.',str_split(strrev(strval(-1*($income + $othersIncome - $expense - $othersExpense))),3)))}}
                            @else
                                Rp{{strrev(implode('.',str_split(strrev(strval($income + $othersIncome - $expense - $othersExpense)),3)))}}
                            @endif 
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>