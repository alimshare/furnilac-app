<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>RECEH</h2>

	<h4>PERIODE : {{ $startDate }} - {{ $endDate }}</h4>

	<table border="1" cellpadding="10" cellspacing="0">
		<thead>
			<tr>
				<td>Bagian</td>
				<td>Nama Group</td>
				<td>Gaji</td>
			</tr>
			<tr>
				@foreach($dateList as $date)
					<td>Jam</td>
					<td>Gaji</td>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach($data as $o)
			<tr>
				@foreach($o as $v)
					<td>{{ $v }}</td>
				@endforeach
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>