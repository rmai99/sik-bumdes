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
    $year = Request::segment(4);
    $month = Request::segment(5);

    setlocale(LC_ALL, 'id_ID');

    $jumlah_debit = 0;
    $jumlah_kredit = 0;
    $dateObj   = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F'); // March
    @endphp
        <table>
            <thead>
                <tr>
                    <th colspan="4" style="font-size: 13px; text-align: center;"><strong>Laporan Laba Rugi</strong></th>
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
                    <th width="3"></th>
                    <th width="40"></th>
                    <th width="15"></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="4" style="border: 1px solid black; border-collapse: collapse;">
                    Pendapatan
                </td>
            </tr>
            @for ($i = 0; $i < sizeof($incomeArray); $i++)
                <tr>
                    <td style="border: 1px solid black; border-collapse: collapse;"></td>
                    <td style="border: 1px solid black; border-collapse: collapse;" colspan="3">
                        <strong>{{$incomeArray[$i]['classification']}}</strong>
                    </td>
                </tr>
                @if (isset($incomeArray[$i]['name']))
                    @for ($y = 0; $y < sizeof($incomeArray[$i]['name']); $y++) 
                        @if($incomeArray[$i]['ending balance'][$y] !="0" )
                            <tr>
                                <td colspan="2" style="border: 1px solid black; border-collapse: collapse;"></td>
                                <td style="border: 1px solid black; border-collapse: collapse;">
                                    {{$incomeArray[$i]['code'][$y]}}- {{$incomeArray[$i]['name'][$y]}}
                                </td>
                                <td style="border: 1px solid black; border-collapse: collapse; text-align : right">
                                    {{$incomeArray[$i]['ending balance'][$y]}}
                                </td>
                            </tr>
                        @endif
                    @endfor
                @endif
                <tr>
                    <td style="border: 1px solid black; border-collapse: collapse;"></td>
                    <td style="border: 1px solid black; border-collapse: collapse; text-align : right" colspan="2">
                        Total {{$incomeArray[$i]['classification']}}
                    </td>
                    <td></td>
                </tr>
            @endfor
            <tr>
                <td colspan="3" style="border: 1px solid black; border-collapse: collapse;"><strong>Total Pendapatan</strong></td>
                <td style="border: 1px solid black; border-collapse: collapse; text-align : right">{{$income}}</td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black; border-collapse: collapse;">Biaya</td>
            </tr>
            @for ($i = 0; $i < sizeof($expenseArray); $i++)
                <tr>
                    <td></td>
                    <td style="border: 1px solid black; border-collapse: collapse;" colspan="3">
                        <strong>{{$expenseArray[$i]['classification']}}</strong>
                    </td>
                </tr>
                @if (isset($incomeArray[$i]['name']))
                    @for ($j = 0; $j < sizeof($expenseArray[$i]['ending balance']); $j++)
                        @if($expenseArray[$i]['ending balance'][$j] !=0)
                            <tr>
                                <td colspan="2" style="border: 1px solid black; border-collapse: collapse;"></td>
                                <td style="border: 1px solid black; border-collapse: collapse;">
                                    {{$expenseArray[$i]['code'][$j]}} - {{$expenseArray[$i]['name'][$j]}}
                                </td>
                                <td style="border: 1px solid black; border-collapse: collapse; text-align : center;">
                                    {{$expenseArray[$i]['ending balance'][$j]}}
                                </td>
                            </tr>
                        @endif
                    @endfor
                @endif
                <tr>
                    <td colspan="4" style="border: 1px solid black; border-collapse: collapse;">
                        Total {{ $expenseArray[$i]['classification'] }}
                    </td>
                </tr>
            @endfor
            <tr>
                <td colspan="3" style="border: 1px solid black; border-collapse: collapse;">
                    <strong>Total Biaya</strong>
                </td>
                <td style="border: 1px solid black; border-collapse: collapse; align-text : left">
                    {{$expense}}
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid black; border-collapse: collapse;">
                    <strong>Laba Usaha</strong>
                </td>
                <td style="border: 1px solid black; border-collapse: collapse; text-align : right">
                    <strong>{{$income - $expense}}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid black; border-collapse: collapse;">Pendapatan dan Biaya Lainnya</td>
            </tr>
            @for ($i = 0; $i < sizeof($othersIncomeArray); $i++)
                <tr>
                    <td colspan="3" style="border: 1px solid black; border-collapse: collapse;">
                        <strong>{{$othersIncomeArray[$i]['classification']}}</strong>
                    </td>
                    <td style="border: 1px solid black; border-collapse: collapse; text-align:right">
                        {{$othersIncome}}
                    </td>
                </tr>
            @endfor
            @for ($i = 0; $i < sizeof($othersExpenseArray); $i++)
                <tr>
                    <td style="border: 1px solid black; border-collapse: collapse;" colspan="3">
                        <strong>{{$othersExpenseArray[$i]['classification']}}</strong>
                    </td>
                    <td style="border: 1px solid black; border-collapse: collapse; text-align:right">
                        {{$othersExpense}}
                    </td>
                </tr>
            @endfor
            <tr>
                <td style="border: 1px solid black; border-collapse: collapse;" colspan="3">
                    <strong>Total Pendapatan dan Biaya Lainnya</strong>
                </td>
                <td style="border: 1px solid black; border-collapse: collapse; text-align:right">
                    {{ $othersIncome - $othersExpense}}
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid black; border-collapse: collapse; background-color: #8AC7EF;" colspan="3">
                    <strong>SALDO LABA/RUGI TAHUN BERJALAN</strong>
                </td>
                <td style="border: 1px solid black; border-collapse: collapse; text-align : right; background-color: #8AC7EF">
                    {{$income + $othersIncome - $expense - $othersExpense}}
                </td>
            </tr>
            </tbody>
        </table>
    </body>
</html>
