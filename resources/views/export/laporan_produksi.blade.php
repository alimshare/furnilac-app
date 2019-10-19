<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>LAPORAN PRODUKSI</h2>

	<h4>RANGE DATE : {{ $startDate }} - {{ $endDate }}</h4>
	<h4>Bagian : {{ $bagian }}</h4>
	<h4>Group Name : {{ $group }}</h4>

	<table border="1" cellpadding="10" cellspacing="0">
		<thead>
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
					<td>{{ $o->part_price }}</td>
					<?php foreach ($o->detail as $d): ?>
						<th>{{ $d }}</th>
					<?php endforeach ?>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">Total</td>
				<td>{{ array_sum(array_column($data, 'part_price')) }}</td>
				@foreach($data[0]->detail as $k => $d)
					<td>{{ array_sum(array_column(array_column($data, 'detail'), $k)) }}</td>
				@endforeach
			</tr>
		</tfoot>
	</table>

</body>
</html>