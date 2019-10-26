<!DOCTYPE html>
<html>
<head></head>
<body>

	<h2>LAPORAN GAJI</h2>

	<h4>RANGE DATE : {{ $startDate }} SD {{ $endDate }}</h4>

	<table border="1" cellpadding="8px" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>NIK</th>
				<th>Nama</th>
				<th>Rekening</th>
				<th>Bank</th>
				<th>Cabang</th>
				<th>Gaji</th>
				<th>Tanggal Debet</th>
				<th>Keterangan</th>
			</tr>
		</thead>
		<tbody>
			@foreach($rows as $o)
				<tr style="text-align: left;">
					<td>{{ $o->nik }}</td>
					<td>{{ $o->nama }}</td>
					<td>{{ $o->rekening }}</td>
					<td>{{ $o->bank }}</td>
					<td>{{ $o->cabang }}</td>
					<td style="text-align: right;">{{ number_format($o->gaji) }}</td>
					<td>{{ $o->tgl_debet }}</td>
					<td>{{ $o->keterangan }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>