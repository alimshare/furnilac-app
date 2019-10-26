<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>TANDA TERIMA UPAH BORONGAN</h2>
	<h3>{{ $bagian }} - {{ $group }}</h3>

	<h4>PERIODE : {{ $startDate }} - {{ $endDate }}</h4>

	<table border="1" cellpadding="10" cellspacing="0">
		<thead>
			<tr>
				<td>NIK</td>
				<td>Nama</td>
				<td>Total</td>
				<td>Total Pembulatan</td>
				<td>TTD</td>
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
				@endforeach
				
				<td>{{ $totalGaji = array_sum(array_column($o['dateList'], 'gaji')) }}</td>
				<td>{{ $totalGajiPembulatan = ($totalGaji % 100 > 0)  ? ($totalGaji - ($totalGaji % 100) + 100) : $totalGaji  }}</td>
				<td></td>

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
				<td>{{ $grandTotal }}</td>
				<td>{{ $grandTotalPembulatan  }}</td>
				<td></td>
			</tr>
		</tfoot>
	</table>

</body>
</html>