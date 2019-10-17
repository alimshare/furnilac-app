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
			</tr>
			<tr>
				@foreach($dateList as $date)
					<td>Jam</td>
					<td>Gaji</td>
				@endforeach
			</tr>
		</thead>
		<tbody>
			<?php $totals = array(); $grandTotal = 0; ?>
			@foreach($data as $o)
			<tr>
				<td>{{ $o['NIK'] }}</td>
				<td>{{ $o['Name'] }}</td>
				@foreach( $o['dateList'] as $k => $v)
					<?php 
						
						$totals[$k.'jam']  = (array_key_exists($k.'jam', $totals)) ? $totals[$k.'jam'] + $v['jam'] : $v['jam']; 
						$totals[$k.'gaji'] = (array_key_exists($k.'gaji', $totals)) ? $totals[$k.'gaji'] + $v['gaji'] : $v['gaji'];

					?>
					<td>{{ $v['jam'] }}</td>
					<td>{{ $v['gaji'] }}</td>
				@endforeach
				<td>{{ $o['total'] }}</td>
				<?php $grandTotal = $grandTotal + $o['total']; ?>
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
			</tr>
		</tfoot>
	</table>

</body>
</html>