<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>LAPORAN REKAPITULASI UPAH BORONGAN</h2>
	<h3>{{ $bagian }} - {{ $group }}</h3>

	<h4>PERIODE : {{ $startDate }} - {{ $endDate }}</h4>

	<table border="1" cellpadding="10" cellspacing="0">
		<thead>
			<tr>
				<td rowspan="2">NIK</td>
				<td rowspan="2">Nama</td>
				@foreach($dateList as $date)
					<td colspan="2">{{ $date }}</td>
				@endforeach
				<td rowspan="2">Total</td>
				<td rowspan="2">Total Pembulatan</td>
			</tr>
			<tr>
				@foreach($dateList as $date)
					<td>Jam</td>
					<td>Gaji</td>
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
					<td>{{ $v['jam'] }}</td>
					<td>{{ $v['gaji'] }}</td>
				@endforeach
				
				<td>{{ $totalGaji = array_sum(array_column($o['dateList'], 'gaji')) }}</td>
				<td>{{ $totalGajiPembulatan = ($totalGaji % 100 > 0)  ? ($totalGaji - ($totalGaji % 100) + 100) : $totalGaji  }}</td>

				<?php 
					$grandTotal = $grandTotal + $totalGaji; 
					$grandTotalPembulatan = $grandTotalPembulatan + $totalGajiPembulatan; 
				?>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">TOTAL</td>
				@foreach ($totals  as $key => $total)
					<td>{{ $total }}</td>
				@endforeach
				<td>{{ $grandTotal }}</td>
				<td>{{ $grandTotalPembulatan  }}</td>
			</tr>
			<tr>
				<td colspan="2">Jumlah Karyawan</td>
				<?php $totalKaryawan = 0; ?>
				@foreach ($countDailyEmployee  as $key => $o)
					<td colspan="2" style="text-align: right;">{{ $o }}</td>
					<?php $totalKaryawan = $totalKaryawan + $o ?>
				@endforeach
				<td>{{ $totalKaryawan }}</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2">Gaji Per Jam</td>
				@foreach (array_column($data, 'dateList')[0]  as $key => $o)
					<td colspan="2" style="text-align: right;">{{ ($o['totalManHour'] == 0) ? 0 : ceil($o['totalGaji'] / $o['totalManHour']) }}</td>
				@endforeach
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>

</body>
</html>