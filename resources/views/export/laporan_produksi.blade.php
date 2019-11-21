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

	<h2>LAPORAN PRODUKSI</h2>

	<h4>RANGE DATE : {{ $startDate }} - {{ $endDate }}</h4>
	<h4>Bagian : {{ $bagian }}</h4>
	<h4>Group Name : {{ $group }}</h4>

	<table border="1" cellpadding="4px" cellspacing="0" width="100%">
		<thead style="text-align: center;">
			<tr>
				<th rowspan="2">PO BUYER</th>
				<th rowspan="2">PART NUMBER</th>
				<th rowspan="2">PART NAME</th>
				<th rowspan="2">NET PRICE</th>
				<?php foreach ($dateList as $date): ?>
					<th colspan="2">{{ $date }}</th>
				<?php endforeach ?>
			</tr>
			<tr>
				<?php foreach ($dateList as $date): ?>
					<th>QTY</th>
					<th>RP</th>
				<?php endforeach ?>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $o)				
				<tr>
					<td>{{ $o->po_number }}</td>
					<td>{{ $o->part_number }}</td>
					<td>{{ $o->part_name }}</td>
					<td style="text-align: right;">{{ number_format($o->part_price) }}</td>
					<?php foreach ($o->detail as $d): ?>
						<td style="text-align: right;">{{ number_format($d) }}</td>
					<?php endforeach ?>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<?php $temp1 = array(); ?>
			<tr>
				<td colspan="3">Total</td>
				<td style="text-align: right;">{{ number_format(array_sum(array_column($data, 'part_price'))) }}</td>
				@if(count($data) > 0)
					@foreach($data[0]->detail as $k => $d)
						<?php $x = array_sum(array_column(array_column($data, 'detail'), $k)) ?>
						<td style="text-align: right;">{{ number_format($x) }}</td>
						<?php 
							if (strpos($k, 'total') !== false) {
								$temp1[] = $x;
							}
						 ?>
					@endforeach
				@endif
			</tr>
		<tr>			
			<?php $temp2 = array(); ?>
			<td colspan="4">Pembayaran Karyawan</td>
			@foreach($dateList as $date)
				<?php $x = array_key_exists($date, $rekapUpah) ? $rekapUpah[$date] : 0; $temp2[] = $x; ?>
				<td colspan="2" style="text-align: right;">{{  number_format($x) }}</td>
			@endforeach
		</tr>
		<tr>
			<?php $sumTemp = array(); ?>
			<td colspan="4">Selisih</td>
			@foreach($temp1 as $k => $t1)
				<?php 
					$x = $temp2[$k] > 0 ? ($temp2[$k] - $temp1[$k]) : 0; 
					$sumTemp[] = $x; 
				?>
				<td colspan="2" style="text-align: right;">{{ number_format($x) }}</td>
			@endforeach
		</tr>
		<tr>
			<td colspan="4">Total Selisih</td>
			<td colspan="{{ count($dateList) * 2 }}" style="text-align: right;">{{ number_format(array_sum($sumTemp)) }}</td>
		</tr>
		</tfoot>
	</table>

</body>
</html>