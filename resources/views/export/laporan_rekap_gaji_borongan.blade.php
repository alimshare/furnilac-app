<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>REKAP GAJI BORONGAN</h2>

	<h4>RANGE DATE : {{ $startDate }} SD {{ $endDate }}</h4>

	<table border="1" cellpadding="10" cellspacing="0">
		<thead>
			<tr>
				<th>Bagian</th>
				<th>Nama Group</th>
				<th>Jumlah Orang</th>
				<th>Gaji</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $o)
			<tr style="text-align: right;">
				<td style="text-align: left;">{{  $o['bagian'] }}</td>
				<td style="text-align: left;">{{  $o['group_name'] }}</td>
				<td>{{  $o['jumlah_karyawan'] }}</td>
				<td>{{  $o['gaji'] }}</td>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr style="text-align: right;">				
				<th colspan="2" style="text-align: center;">Total</th>
				<th>{{ array_sum(array_column($data, 'jumlah_karyawan')) }}</th>
				<th>{{ array_sum(array_column($data, 'gaji')) }}</th>
			</tr>
		</tfoot>
	</table>

</body>
</html>