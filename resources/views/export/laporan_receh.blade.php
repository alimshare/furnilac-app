<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>RECEH</h2>

	<h4>RANGE DATE : {{ $startDate }} SD {{ $endDate }}</h4>

	<table border="1" cellpadding="10" cellspacing="0">
		<thead>
			<tr>
				<th>Bagian</th>
				<th>Nama Group</th>
				<th>Gaji Normal</th>
				<th>Gaji Pembulatan</th>
				<th>Jumlah Orang</th>
				<th>100,000</th>
				<th>50,000</th>
				<th>20,000</th>
				<th>10,000</th>
				<th>5,000</th>
				<th>2,000</th>
				<th>1,000</th>
				<th>500</th>
				<th>200</th>
				<th>100</th>
			</tr>
		</thead>
		<tbody>
			<?php $totals = array(); $grandTotal = 0; ?>
			@foreach($data as $o)
			<tr style="text-align: right;">
				<td style="text-align: left;">{{  $o['bagian'] }}</td>
				<td style="text-align: left;">{{  $o['group_name'] }}</td>
				<td>{{  number_format($o['gaji']) }}</td>
				<td>{{  number_format($o['gaji_bulat_100']) }}</td>
				<td>{{  $o['jumlah_karyawan'] }}</td>
				<td>{{  $o['100K'] }}</td>
				<td>{{  $o['50K'] }}</td>
				<td>{{  $o['20K'] }}</td>
				<td>{{  $o['10K'] }}</td>
				<td>{{  $o['5K'] }}</td>
				<td>{{  $o['2K'] }}</td>
				<td>{{  $o['1K'] }}</td>
				<td>{{  $o['500'] }}</td>
				<td>{{  $o['200'] }}</td>
				<td>{{  $o['100'] }}</td>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr style="text-align: right;">				
				<th colspan="2" style="text-align: left;">Total</th>
				<th>{{ number_format(array_sum(array_column($data, 'gaji')), 0) }}</th>
				<th>{{ number_format(array_sum(array_column($data, 'gaji_bulat_100'))) }}</th>
				<th>{{ array_sum(array_column($data, 'jumlah_karyawan')) }}</th>
				<th>{{ array_sum(array_column($data, '100K')) }}</th>
				<th>{{ array_sum(array_column($data, '50K')) }}</th>
				<th>{{ array_sum(array_column($data, '20K')) }}</th>
				<th>{{ array_sum(array_column($data, '10K')) }}</th>
				<th>{{ array_sum(array_column($data, '5K')) }}</th>
				<th>{{ array_sum(array_column($data, '2K')) }}</th>
				<th>{{ array_sum(array_column($data, '1K')) }}</th>
				<th>{{ array_sum(array_column($data, '500')) }}</th>
				<th>{{ array_sum(array_column($data, '200')) }}</th>
				<th>{{ array_sum(array_column($data, '100')) }}</th>
			</tr>
		</tfoot>
	</table>

</body>
</html>