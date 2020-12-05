<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="shortcut icon" href="{{url('/')}}/assets/img/shortcut.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Neraca
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
</head>

<body>
    @php
    if (Request::segment(4) != null) {
      $month = Request::segment(5);
      $dt = Request::segment(4);
    }else {
      $dt = $year;
	}
	$month;
	$dateObj   = DateTime::createFromFormat('!m', $month);
	$monthName = $dateObj->format('F'); // March
    $sum = 0;
	$sum_biaya = 0;
	$sum_ekuitas = 0;
    setlocale(LC_ALL, 'id_ID');
    @endphp
    
	<table>
		<thead>
			<tr>
				<th colspan="4" style="font-size: 13px; text-align: center;"><strong>Neraca</strong></th>
			</tr>
			<tr>
				<th colspan="4" style="font-size: 18px; text-align: center;"><strong>{{$profil->name}}</strong></th>
			</tr>
			<tr>
				<th colspan="4" style="font-size: 13px; text-align: center;"><strong>{{$business_profile}}</strong></th>
			</tr>
			<tr>
				<th colspan="4" style="font-size: 11px; text-align: center;">Periode {{$monthName}} {{$dt}}</th>
			</tr>
			<tr>
				<th width=3></th>
				<th width=3></th>
				<th width="30"></th>
				<th width=15></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="4" style="border: 1px solid black; border-collapse: collapse;">Asset</td>
			</tr>
			@for ($i = 0; $i < sizeof($assetArray); $i++)
			<tr aria-colspan="3">
				<td style="border: 1px solid black; border-collapse: collapse;"></td>
				<td style="border: 1px solid black; border-collapse: collapse;"><strong>{{$assetArray[$i]['classification']}}</strong></td>
			</tr>
			@if (isset($assetArray[$i]['name']))
				@for ($y = 0; $y < sizeof($assetArray[$i]['name']); $y++)
					@if ($assetArray[$i]['ending balance'][$y] !="0" ) 
						<tr>
							<td colspan="2" style="border: 1px solid black; border-collapse: collapse;"></td>
							<td style="border: 1px solid black; border-collapse: collapse;">
								{{$assetArray[$i]['code'][$y]}}- {{$assetArray[$i]['name'][$y]}}
							</td>
							<td style="text-align: right border: 1px solid black; border-collapse: collapse;" >
								{{$assetArray[$i]['ending balance'][$y]}}
							</td>
						</tr>
					@endif
				@endfor
			@endif
			<tr>
				<td></td>
				<td colspan="2" style="border: 1px solid black; border-collapse: collapse;">
					<b>Total {{$assetArray[$i]['classification']}}</b>
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
					<b>{{$assetArray[$i]['sum']}}</b>
				</td>
			</tr> 
			@endfor
			<tr>
				<td colspan="3" style="border: 1px solid black; border-collapse: collapse;">
					<strong>Total Asset</strong>
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
					<strong>{{$sum}}</strong>
				</td>
			</tr>
			<tr>
				<td colspan="4">Liabilitas</td>
			</tr>
			@for ($i = 0; $i < sizeof($liabilityArray); $i++)
			<tr>
				<td colspan="4" style="border: 1px solid black; border-collapse: collapse;">
					<strong>{{$liabilityArray[$i]['classification']}}</strong>
				</td>
			</tr>
			@if (isset($assetArray[$i]['name']))
				@for ($j = 0; $j < sizeof($liabilityArray[$i]['ending balance']); $j++) 
					@if ($liabilityArray[$i]['ending balance'][$j] !=0)
					<tr>
						<td style="border: 1px solid black; border-collapse: collapse;"></td>
						<td colspan="2" style="border: 1px solid black; border-collapse: collapse;">
							{{$liabilityArray[$i]['code'][$j]}} - {{$liabilityArray[$i]['name'][$j]}}
						</td>
						<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
							{{$liabilityArray[$i]['ending balance'][$j]}}
							@php
								$sum_biaya +=$liabilityArray[$i]['ending balance'][$j];
							@endphp
						</td> 
					</tr>
					@endif
				@endfor
			@endif
			<tr>
				<td></td>
				<td colspan="2" style="border: 1px solid black; border-collapse: collapse;">
					<b>Total {{$liabilityArray[$i]['classification']}}</b>
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;"><b>{{$liabilityArray[$i]['sum']}}</b></td>
			</tr>
			@endfor
			<tr>
				<td colspan="3" style="border: 1px solid black; border-collapse: collapse;">
					<strong>Total Liabilitas</strong>
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
					<strong>
						{{$sum_biaya}}
					</strong>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="border: 1px solid black; border-collapse: collapse;">
					Ekuitas
				</td>
			</tr>
			@for ($i = 0; $i < sizeof($equityArray); $i++) 
			<tr>
				<td colspan="4" style="border: 1px solid black; border-collapse: collapse;">
					<strong>{{$equityArray[$i]['classification']}}</strong>
				</td>
			</tr>
			@if (isset($assetArray[$i]['name']))
				@for ($j = 0; $j < sizeof($equityArray[$i]['ending balance']); $j++)
					@if ($equityArray[$i]['name'][$j] == "Modal Disetor")
					<tr>
						<td style="border: 1px solid black; border-collapse: collapse;"></td>
						<td colspan="2" style="border: 1px solid black; border-collapse: collapse;">
							{{$equityArray[$i]['code'][$j]}} - {{$equityArray[$i]['name'][$j]}}
						</td>
						<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
							{{$equitas}}
							@php
								$sum_ekuitas += $equitas;
							@endphp
						</td>
					</tr>
					@endif
					@if ($equityArray[$i]['name'][$j] != "Modal Disetor" && $equityArray[$i]['name'][$j] != "Saldo Laba Tahun Berjalan" )
					<tr>
						<td style="border: 1px solid black; border-collapse: collapse;"></td>
						<td colspan="2" style="border: 1px solid black; border-collapse: collapse;">
							{{$equityArray[$i]['code'][$j]}} - {{$equityArray[$i]['name'][$j]}}
						</td>
						<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
							{{$equityArray[$i]['ending balance'][$j]}}
							@php
								$sum_ekuitas += $equityArray[$i]['ending balance'][$j];
							@endphp
						</td> 
					</tr> 
					@endif
				@endfor
			@endif
			<tr>
				<td colspan="3" style="border: 1px solid black; border-collapse: collapse;">
					Total {{$equityArray[$i]['classification']}}
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">{{$sum_ekuitas}}</td>
			</tr>
			@endfor
			<tr>
				<td style="border: 1px solid black; border-collapse: collapse;">
					<strong>Total Ekuitas</strong>
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
					<strong>
						{{$sum_ekuitas}}
					</strong>
				</td>
			</tr>
			<tr>
				<td style="border: 1px solid black; border-collapse: collapse;">
					<strong>Total Liabilitas dan Ekuitas</strong>
				</td>
				<td style="text-align: right; border: 1px solid black; border-collapse: collapse;">
					<strong>
						{{$sum_ekuitas+$sum_biaya}}
					</strong>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center; border: 1px solid black; border-collapse: collapse;">TOTAL ASET</td>
				<td colspan="2" style="text-align: center; border: 1px solid black; border-collapse: collapse;">TOTAL LIABILITAS DAN ASET</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center; border: 1px solid black; border-collapse: collapse;">{{$sum}}</td>
				<td colspan="2" style="text-align: center; border: 1px solid black; border-collapse: collapse;">{{$sum_ekuitas+$sum_biaya}}</td>
			</tr>
		</tbody>
	</table>
</body>
</html>