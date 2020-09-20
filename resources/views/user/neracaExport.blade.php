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

        .text-center {
            text-align: center
        }
        .text-right {
            text-align: right
        }
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th,
        td {
            padding: 5px;
            text-align: left;
            font-size: 13px;
        }
        th {
            background: #8AC7EF;
        }

        .company {
            font-weight: 400;
            margin: 0px 0px 5px 0px;
            font-size: 18px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .periode {
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
	$sum = 0;
	$sum_biaya = 0;
	$sum_ekuitas = 0;
    setlocale(LC_ALL, 'id_ID');
    @endphp
    <div>
        <div>
			<h3 class="text-center company uppercase">{{$company->name}}</h3>
            <h3 class="text-center company">Neraca</h3>
            @php
				$dateObj = DateTime::createFromFormat('!m', $month);
				$monthName = $dateObj -> format('F');
            @endphp
            <p class="text-center periode"><strong>Periode</strong> {{ strftime("%B", strtotime($monthName)) }} {{ $dt }} </p>
        </div>
        <div>
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <tbody>
                    <tr>
                        <td style="width:60%">Asset</td>
                        <td style="width:10%"></td>
                    </tr>
					@for ($i = 0; $i < sizeof($assetArray); $i++)
					<tr>
                        <td style="width:60%;padding-left: 1.5rem!important;">
                            <strong>{{$assetArray[$i]['classification']}}</strong>
                        </td>
                        <td style="width:15%"></td>
                    </tr>
					@if (isset($assetArray[$i]['name']))
					@for ($y = 0; $y < sizeof($assetArray[$i]['name']); $y++)
					@if ($assetArray[$i]['ending balance'][$y] !="0" ) 
                        <tr>
                            <td style="width:60%;padding-left: 3rem!important;">
                                {{$assetArray[$i]['code'][$y]}}- {{$assetArray[$i]['name'][$y]}}
                            </td>
                            <td class="text-right" style="width:10%">
                                @if ($assetArray[$i]['ending balance'][$y] < 0) -
                                    Rp{{strrev(implode('.',str_split(strrev(strval(-1*$assetArray[$i]['ending balance'][$y])),3)))}}
								@else
                                    Rp{{strrev(implode('.',str_split(strrev(strval($assetArray[$i]['ending balance'][$y])),3)))}}
								@endif
							</td>
						</tr>
					@endif
					@endfor
					@endif
					<tr>
						<td style="width:60%;padding-left: 1.5rem!important;">
							<b>Total {{$assetArray[$i]['classification']}}</b>
						</td>
						<td class="text-right" style="width:10%">
							<b>
								@if ($assetArray[$i]['sum'] < 0) -
									Rp{{strrev(implode('.',str_split(strrev(strval(-1*$assetArray[$i]['sum'])),3)))}}
								@else
									Rp{{strrev(implode('.',str_split(strrev(strval($assetArray[$i]['sum'])),3)))}}
								@endif 
								@php 
									$sum +=$assetArray[$i]['sum']; 
								@endphp
							</b>
						</td>
					</tr> 
					@endfor
					<tr>
						<td style="width:60%">
							<strong>Total Asset</strong>
						</td>
						<td class="text-right" style="width:10%">
							<strong>
								@if ($sum < 0) 
									- Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum)),3)))}}
								@else 
									Rp{{strrev(implode('.',str_split(strrev(strval($sum)),3)))}}
								@endif
							</strong>
						</td>
					</tr>
					<tr>
						<td style="width:60%">
							Liabilitas
						</td>
						<td style="width:10%"></td>
                    </tr>
					@for ($i = 0; $i < sizeof($liabilityArray); $i++)
					<tr>
						<td style="width:60%;padding-left: 1.5rem!important;">
							<strong>{{$liabilityArray[$i]['classification']}}</strong>
						</td>
						<td style="width:10%"></td>
					</tr>
					@if (isset($assetArray[$i]['name']))
					@for ($j = 0; $j < sizeof($liabilityArray[$i]['ending balance']); $j++) 
					@if ($liabilityArray[$i]['ending balance'][$j] !=0)
					<tr>
						<td style="width:60%;padding-left: 3rem!important;">
							{{$liabilityArray[$i]['code'][$j]}} - {{$liabilityArray[$i]['name'][$j]}}
						</td>
						<td class="text-right" style="width:10%">
							@if ($liabilityArray[$i]['ending balance'][$j] < 0) 
								- Rp{{strrev(implode('.',str_split(strrev(strval(-1*$liabilityArray[$i]['ending balance'][$j])),3)))}}
							@else
								Rp{{strrev(implode('.',str_split(strrev(strval($liabilityArray[$i]['ending balance'][$j])),3)))}}
							@endif
							@php
								$sum_biaya +=$liabilityArray[$i]['ending balance'][$j];
							@endphp
						</td> 
					</tr>
					@endif
					@endfor
					@endif
					<tr>
						<td style="width:60%;padding-left: 1.5rem!important;">
							<b>Total {{$liabilityArray[$i]['classification']}}</b>
						</td>
						<td style="width:10%" class="text-right">
							<b>
								@if ($liabilityArray[$i]['sum'] < 0) -
									Rp{{strrev(implode('.',str_split(strrev(strval(-1*$liabilityArray[$i]['sum'])),3)))}}
								@else
									Rp{{strrev(implode('.',str_split(strrev(strval($liabilityArray[$i]['sum'])),3)))}}
								@endif
							</b>
						</td>
					</tr>
					@endfor
					<tr>
						<td style="width:60%">
							<strong>Total Liabilitas</strong>
						</td>
						<td class="text-right" style="width:10%">
							<strong>
								@if ($sum_biaya < 0) 
									-Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum_biaya)),3)))}}
								@else 
									Rp{{strrev(implode('.',str_split(strrev(strval($sum_biaya)),3)))}}
								@endif
							</strong>
						</td>
					</tr>
					<tr>
						<td style="width:60%">
							Ekuitas
						</td>
						<td style="width:10%"></td>
					</tr>
					@for ($i = 0; $i < sizeof($equityArray); $i++) 
					<tr>
						<td style="width:60%;padding-left: 1.5rem!important;">
							<strong>{{$equityArray[$i]['classification']}}</strong>
						</td>
						<td style="width:10%"></td>
					</tr>
					@if (isset($assetArray[$i]['name']))
					@for ($j = 0; $j < sizeof($equityArray[$i]['ending balance']); $j++)
					@if ($equityArray[$i]['name'][$j] == "Modal Disetor")
					<tr>
						<td style="width:60%;padding-left: 3rem!important;">
							{{$equityArray[$i]['code'][$j]}} - {{$equityArray[$i]['name'][$j]}}
						</td>
						<td class="text-right" style="width:10%">
							@if ($equitas < 0)
								- Rp{{strrev(implode('.',str_split(strrev(strval(-1*$equitas)),3)))}}
							@else
								Rp{{strrev(implode('.',str_split(strrev(strval($equitas)),3)))}}
							@endif
							@php
								$sum_ekuitas += $equitas;
							@endphp
						</td>
					</tr>
					@endif
					@if ($equityArray[$i]['name'][$j] != "Modal Disetor" && $equityArray[$i]['name'][$j] != "Saldo Laba Tahun Berjalan" )
					<tr>
						<td style="width:60%;padding-left: 3rem!important;">
							{{$equityArray[$i]['code'][$j]}} - {{$equityArray[$i]['name'][$j]}}
						</td>
						<td class="text-right" style="width:10%">
							@if ($equityArray[$i]['ending balance'][$j] < 0)
								- Rp{{strrev(implode('.',str_split(strrev(strval(-1*$equityArray[$i]['ending balance'][$j])),3)))}}
							@else
								Rp{{strrev(implode('.',str_split(strrev(strval($equityArray[$i]['ending balance'][$j])),3)))}}
							@endif
							@php
								$sum_ekuitas += $equityArray[$i]['ending balance'][$j];
							@endphp
						</td> 
					</tr> @endif
					@endfor
					@endif
					<tr>
						<td style="width:60%;padding-left: 1.5rem!important;">
							Total {{$equityArray[$i]['classification']}}
						</td>
						<td style="width:10%" class="text-right"></td>
					</tr>
					@endfor
					<tr>
						<td style="width:60%">
							<strong>Total Ekuitas</strong>
						</td>
						<td class="text-right" style="width:10%">
							<strong>
								@if ($sum_ekuitas < 0) 
									-Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum_ekuitas)),3)))}}
								@else
									Rp{{strrev(implode('.',str_split(strrev(strval($sum_ekuitas)),3)))}}
								@endif
							</strong>
							</td>
					</tr>
					<tr>
						<td style="width:60%">
							<strong>Total Liabilitas dan Ekuitas</strong>
						</td>
						<td class="text-right" style="width:10%">
							<strong>
								@if ($sum_ekuitas+$sum_biaya < 0) 
									-Rp{{strrev(implode('.',str_split(strrev(strval(-1*($sum_ekuitas+$sum_biaya))),3)))}}
								@else
									Rp{{strrev(implode('.',str_split(strrev(strval($sum_ekuitas+$sum_biaya)),3)))}}
								@endif 
							</strong>
						</td>
					</tr>
				</tbody>
			</table>
        </div>
		<div class="row">
			<div class="col-lg-6 text-center">
				Total Aset
				<br>
				<strong>
					@if ($sum < 0)
						-Rp{{strrev(implode('.',str_split(strrev(strval(-1*$sum)),3)))}}
					@else
						Rp{{strrev(implode('.',str_split(strrev(strval($sum)),3)))}}
					@endif
				</strong>
			</div>
			<div class="col-lg-6 text-center">
				Total Liabilitas dan Ekuitas
				<br>
				<strong>
					@if ($sum_ekuitas+$sum_biaya < 0)
						- Rp{{strrev(implode('.',str_split(strrev(strval(-1*($sum_ekuitas+$sum_biaya))),3)))}}
					@else
						Rp{{strrev(implode('.',str_split(strrev(strval($sum_ekuitas+$sum_biaya)),3)))}}
					@endif
				</strong>
			</div>
		</div>
	</div>
</body>
</html>