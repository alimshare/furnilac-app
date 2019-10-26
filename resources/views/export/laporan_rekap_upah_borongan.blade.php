<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		body {
			font-family: arial;
		}

		table tr{
			font-size: 0.8em;
		}
	</style>	
</head>
<body>

	<h2>LAPORAN REKAPITULASI UPAH BORONGAN</h2>
	<h3>{{ $bagian }} - {{ $group }}</h3>

	<h4>PERIODE : {{ $startDate }} - {{ $endDate }}</h4>

	<table border="1" cellpadding="8px" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th rowspan="2">NIK</th>
				<th rowspan="2">Nama</th>
				@foreach($dateList as $date)
					<th colspan="2">{{ $date }}</th>
				@endforeach
				<th rowspan="2">Total</th>
			</tr>
			<tr>
				@foreach($dateList as $date)
					<th>Jam</th>
					<th>Gaji</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			<?php $totals = array(); $grandTotal = 0; $grandTotalPembulatan = 0; $countDailyEmployee = array(); ?>
			@foreach($data as $o)
			<tr>
				<td>{{ $o['NIK'] }}</td>
				<td>{{ $o['Name'] }}</td>
				@foreach( $o['dateList'] as $k => $v)
					<?php 
						
						$totals[$k.'jam']  = (array_key_exists($k.'jam', $totals)) ? $totals[$k.'jam'] + $v['jam'] : $v['jam']; 
						$totals[$k.'gaji'] = (array_key_exists($k.'gaji', $totals)) ? $totals[$k.'gaji'] + $v['gaji'] : $v['gaji'];

						if (!array_key_exists($k, $countDailyEmployee)) {
							$countDailyEmployee[$k] = 0;
						}

						if ($v['jam'] > 0) {
							$countDailyEmployee[$k]++;	
						} 

					?>
					<td style="text-align: right;">{{ number_format($v['jam']) }}</td>
					<td style="text-align: right;">{{ number_format($v['gaji']) }}</td>
				@endforeach
				
				<?php 
					$totalGaji = array_sum(array_column($o['dateList'], 'gaji'));
					$grandTotal = $grandTotal + $totalGaji;
				?>
				<td style="text-align: right;">{{ number_format($totalGaji) }}</td>

			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">Total</td>
				@foreach ($totals  as $key => $total)
					<td style="text-align: right;">{{ number_format($total) }}</td>
				@endforeach
				<td style="text-align: right;">{{ number_format($grandTotal) }}</td>
			</tr>
			<tr>
				<td colspan="2">Jumlah Karyawan</td>
				<?php $totalKaryawan = 0; ?>
				@foreach ($countDailyEmployee  as $key => $o)
					<td colspan="2" style="text-align: right;">{{ number_format($o) }}</td>
					<?php $totalKaryawan = $totalKaryawan + $o ?>
				@endforeach
				<td style="text-align: right;">{{ number_format($totalKaryawan) }}</td>
			</tr>
			<tr>
				<td colspan="2">Gaji Per Jam</td>
				@foreach (array_column($data, 'dateList')[0]  as $key => $o)
					<?php $x = ($o['totalManHour'] == 0) ? 0 : ceil($o['totalGaji'] / $o['totalManHour']); ?>
					<td colspan="2" style="text-align: right;">{{ number_format($x) }}</td>
				@endforeach
				<td></td>
			</tr>
		</tfoot>
	</table>

</body>
</html>