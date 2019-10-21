<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Exports\GeneralExport;
use App\Exports\GeneralViewExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

	protected $data = array();

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    } 
	
	public function formProduction(Request $request)
	{
		$this->data['groups'] = \App\Model\Group::all();
		return view('modules.report.report-production', $this->data);
	}

	public function formProductionExport(Request $request)
	{
		$exportType = "view";
		if ($request->input('exportExcel')) {
			$exportType = "excel";
		} 

		$groupId 	= $request->input('groupId');
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		$sql = "SELECT reported_date, pr.po_number, pr.part_number, qty_output, pr.group_id, g.name group_name, part.price as part_price, part.part_name  
				FROM production_report pr 
					LEFT JOIN tblgroups g ON pr.group_id=g.id 
					LEFT JOIN part on part.part_number = pr.part_number
				WHERE reported_date BETWEEN ? AND ?  ";

		if ($groupId != null && $groupId != "") {
			$sql .= "AND pr.group_id = '".$groupId."' ";
		}
		
		$sql .= "ORDER BY reported_date, pr.group_id, pr.po_number ASC";
		// dd($sql);

		$data = DB::select($sql, array($startDate, $endDate));

		// dd($data);
		
		$dateList = array();
    	$currentDate = $startDate;
		while (strtotime($currentDate) <= strtotime($endDate)) {
		    $dateList[] = $currentDate;
		    $currentDate = date ("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
		}

		// dd($dateList);
		$detail = array();
		foreach ($data as $key => $value) {
			$reported_date = $value->reported_date;
			$row = array();
			foreach ($dateList as $date) {
				// echo date('Ymd', strtotime($date))."<br>";
				$key1 = date('Ymd', strtotime($date)).'qty';
				$key2 = date('Ymd', strtotime($date)).'total';

				if ($reported_date == $date) {
					$row[$key1] = $value->qty_output;
					$row[$key2] = $value->qty_output * $value->part_price;
				}else {
					$row[$key1] = 0;
					$row[$key2] = 0;
				}

			}
			// $detail[] = $row;
			$value->detail = $row;
		}

		if ($exportType == "view") {
			$this->data['groups'] = \App\Model\Group::all();
			$this->data['data']	  = $data;
			return view('modules.report.report-production', $this->data);
		} else {
			$group = \App\Model\Group::find($groupId);
			$this->data['group']  	 = $group->name;
			$this->data['bagian'] 	 = $group->section;
			$this->data['startDate'] = $startDate;
			$this->data['endDate'] 	 = $endDate;
			$this->data['data']	  	 = $data;
			$this->data['dateList']	 = $dateList;

			// return view('export.laporan_produksi', $this->data);
			return Excel::download(new GeneralViewExport('export.laporan_produksi', $this->data), 'production-report-'.(date('YmdHis')).'.xlsx');
		}
	}

	public function formMandays(Request $request)
	{
		$this->data['groups'] = \App\Model\Group::all();
		return view('modules.report.report-mandays', $this->data);
	}
	
	public function formMandaysExport(Request $request)
	{
		$exportType = "view";
		if ($request->input('exportExcel')) {
			$exportType = "excel";
		}

		$groupId 	= $request->input('groupId');
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		$sql = "SELECT reported_date, employee_id, e.nik employee_nik, e.name employee_name, man_hour, shift, mr.group_id, g.name group_name
				FROM mandays_report mr 
					LEFT JOIN tblgroups g ON mr.group_id=g.id
					LEFT JOIN employee e ON mr.employee_id=e.id
				WHERE reported_date BETWEEN ? AND ? ";
				
		if ($groupId != null && $groupId != "") {
			$sql .= "AND mr.group_id = '".$groupId."' ";
		}
		
		$sql .= " ORDER BY reported_date, group_id ASC";

		$data = DB::select($sql, array($startDate, $endDate));

		// dd($data);

		if ($exportType == "view") {
			$this->data['groups'] = \App\Model\Group::all();
			$this->data['data']	  = $data;
			return view('modules.report.report-mandays', $this->data);
		} else {
			$heading = array_keys(json_decode(json_encode($data[0]), true));
			return Excel::download(new GeneralExport($heading, $data), 'mandays-report-'.(date('YmdHis')).'.xlsx');			
		}
	}

	public function formGroup(Request $request)
	{
		$this->data['groups'] = \App\Model\Group::all();
		return view('modules.report.report-group', $this->data);
	}
	
	public function formGroupExport(Request $request)
	{
		// $this->data['groups'] = \App\Model\Group::all();
		// return view('modules.report.report-group', $this->data);
		$groupId 	= $request->input('groupId');
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		$totalPrice = DB::table('production_report')
			->join('part', 'part.part_number', '=', 'production_report.part_number')
			->where('production_report.group_id', $groupId)
			->whereBetween('production_report.reported_date', [$startDate, $endDate])
			->sum(DB::raw('production_report.qty_output * part.price'));

		// echo $totalPrice;
		// echo "<br>";

		$totalManhour = DB::table('mandays_report')
			->where('mandays_report.group_id', $groupId)
			->whereBetween('mandays_report.reported_date', [$startDate, $endDate])
			->sum('mandays_report.man_hour');

		// echo $totalManhour;
		// echo "<br>";
		// echo $totalPrice / $totalManhour;
		// echo "<br>";
		
		$sql = "SELECT reported_date, 
			employee_id, e.nik employee_nik, e.name employee_name, man_hour, shift, 
			mr.group_id, g.name group_name
			FROM mandays_report mr 
				LEFT JOIN tblgroups g ON mr.group_id=g.id
				LEFT JOIN employee e ON mr.employee_id=e.id
			WHERE mr.group_id = ? AND reported_date BETWEEN ? AND ? 
			ORDER BY reported_date, group_id ASC ";

		$data = DB::select($sql, [$groupId, $startDate, $endDate]);

		foreach ($data as $key => $value) {
			$value->price_per_hour = ($totalPrice / $totalManhour);
			$value->salary = $value->price_per_hour * $value->man_hour;
		}
		// dd($data);
		
		$dateList = array();
    	$currentDate = $startDate;
		while (strtotime($currentDate) <= strtotime($endDate)) {
		    $dateList[] = $currentDate;
		    $currentDate = date ("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
		}

		$export = array();

		// dd(array_column($data, 'employee_nik'));

		foreach ($data as $k1 => $v1) {
			if (array_key_exists($v1->employee_nik, $export)) continue;

			$row = array();
			$row['NIK'] 	= $v1->employee_nik;
			$row['Name'] 	= $v1->employee_name;
			
			foreach ($dateList as $k2 => $v2) {
				$row['dateList'][$v2]['jam'] 	= 0;
				$row['dateList'][$v2]['gaji'] 	= 0;
				$row['dateList'][$v2]['totalGaji'] 	= DB::table('production_report')
					->join('part', 'part.part_number', '=', 'production_report.part_number')
					->where('production_report.group_id', $groupId)
					->where('production_report.reported_date', $v2)
					->sum(DB::raw('production_report.qty_output * part.price'));
				$row['dateList'][$v2]['totalManHour'] 	= DB::table('mandays_report')
					->where('mandays_report.group_id', $groupId)
					->where('mandays_report.reported_date', $v2)
					->sum('mandays_report.man_hour');
			}			

			$export[$v1->employee_nik] = $row; 
		}

		// dd($export);
		// dd($data);
		foreach ($data as $k1 => $v1) {

			$row = $export[$v1->employee_nik];
			foreach ($row['dateList'] as $k2 => $v2) 
			{
				if ($k2 == $v1->reported_date) {
					$price_per_hour = ($row['dateList'][$k2]['totalGaji'] / $row['dateList'][$k2]['totalManHour']);
					$salary = $price_per_hour * $v1->man_hour;

					$row['dateList'][$k2]['jam'] 	= $v1->man_hour;
					$row['dateList'][$k2]['gaji'] 	= $salary;
				}
			}

			$export[$v1->employee_nik] = $row; 
		}

		// dd($export);

		$group = \App\Model\Group::find($groupId);

		$x['bagian'] 	= $group->section;
		$x['group'] 	= $group->name;
		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;
		$x['dateList']	= $dateList;
		$x['data']		= $export;

		// return view('export.laporan_rekap_upah_borongan', $x);
		return Excel::download(new GeneralViewExport('export.laporan_rekap_upah_borongan', $x), 'group-export-'.(date('YmdHis')).'.xlsx');

	}

	public function formGroupSummary(Request $request)
	{
		return view('modules.report.report-group-summary', $this->data);
	}

	public function formGroupSummaryExport(Request $request)
	{
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');


		$sql = "SELECT t1.*, g.name group_name, g.section bagian, count(distinct(mr.employee_id)) jumlah_karyawan
				FROM (
					SELECT pr.group_id, sum(pr.qty_output * part.price) total
					FROM production_report pr 
						INNER JOIN part ON part.part_number = pr.part_number
						INNER JOIN tblgroups g ON g.id = pr.group_id
					WHERE pr.reported_date BETWEEN ? AND ?
					GROUP BY group_id
				) t1 
				INNER JOIN 
				(
					SELECT group_id, sum(man_hour) total_mh
					FROM mandays_report mr
					WHERE mr.reported_date BETWEEN ? AND ?
					GROUP BY group_id
				) t2 on t1.group_id = t2.group_id
				INNER JOIN mandays_report mr ON mr.group_id = t1.group_id
				INNER JOIN tblgroups g ON g.id = mr.group_id
				GROUP BY group_id, total, g.name, g.section;
		";
		
		$result = DB::select($sql, [$startDate, $endDate, $startDate, $endDate]);		

		foreach ($result as $key => $value) {
			$row["bagian"] 				= $value->bagian;
			$row["group_name"]			= $value->group_name;
			$row["jumlah_karyawan"] 	= $value->jumlah_karyawan;
			$row["gaji"] 				= $value->total;
			$data[] = $row;
		}

		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;
		$x['data'] 		= $data;

		// return view('export.laporan_rekap_gaji_borongan', $x);
		return Excel::download(new GeneralViewExport('export.laporan_rekap_gaji_borongan', $x), 'group-summary-export-'.(date('YmdHis')).'.xlsx');
	}

	public function formReceh(Request $request)
	{
		return view('modules.report.report-receh', $this->data);
	}

	public function formRecehExport(Request $request)
	{
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		// $sql = "SELECT t1.*, g.name group_name, g.section bagian, t2.total_mh, mr.employee_id, e.name employee_name,e.nik employee_nik, mr.man_hour
		// 		FROM (
		// 			SELECT pr.reported_date, pr.group_id, sum(pr.qty_output * part.price) total
		// 			FROM production_report pr 
		// 				INNER JOIN part ON part.part_number = pr.part_number
		// 				INNER JOIN tblgroups g ON g.id = pr.group_id
		// 			WHERE pr.reported_date BETWEEN ? AND ?
		// 			GROUP BY reported_date, group_id
		// 		) t1 
		// 			INNER JOIN 
		// 		(
		// 			SELECT reported_date, group_id, sum(man_hour) total_mh
		// 			FROM mandays_report mr
		// 			WHERE mr.reported_date BETWEEN ? AND ?
		// 			GROUP BY reported_date, group_id
		// 		) t2 on t1.reported_date = t2.reported_date AND t1.group_id = t2.group_id
		// 			INNER JOIN mandays_report mr ON mr.reported_date = t1.reported_date AND mr.group_id = t1.group_id
		// 			INNER JOIN employee e ON e.id = mr.employee_id
		// 			INNER JOIN tblgroups g ON g.id = mr.group_id ";
		$sql = "SELECT t1.*, g.name group_name, g.section bagian, count(distinct(mr.employee_id)) jumlah_karyawan
				FROM (
					SELECT pr.group_id, sum(pr.qty_output * part.price) total
					FROM production_report pr 
						INNER JOIN part ON part.part_number = pr.part_number
						INNER JOIN tblgroups g ON g.id = pr.group_id
					WHERE pr.reported_date BETWEEN ? AND ?
					GROUP BY group_id
				) t1 
				INNER JOIN 
				(
					SELECT group_id, sum(man_hour) total_mh
					FROM mandays_report mr
					WHERE mr.reported_date BETWEEN ? AND ?
					GROUP BY group_id
				) t2 on t1.group_id = t2.group_id
				INNER JOIN mandays_report mr ON mr.group_id = t1.group_id
				INNER JOIN tblgroups g ON g.id = mr.group_id
				GROUP BY group_id, total, g.name, g.section;
		";
		
		$result = DB::select($sql, [$startDate, $endDate, $startDate, $endDate]);
		// dd($result);
		foreach ($result as $key => $value) {
			// $value->price_per_hour = ($value->total / $value->total_mh);
			// $value->salary = $value->price_per_hour * $value->man_hour;

			$value->receh = $this->receh($value->total);
		}

		foreach ($result as $key => $value) {
			$row["bagian"] 				= $value->bagian;
			$row["group_name"]			= $value->group_name;
			$row["jumlah_karyawan"] 	= $value->jumlah_karyawan;
			$row["gaji"] 				= $value->total;
			foreach ($value->receh as $pecahan => $receh) {
				$row[$pecahan] = $receh;
			}
			$data[] = $row;
		}

		// dd($data);
		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;
		$x['data'] 		= $data;
		// dd($data);
		// return view('export.laporan_receh', $x);
		return Excel::download(new GeneralViewExport('export.laporan_receh', $x), 'receh-export-'.(date('YmdHis')).'.xlsx');

		// $heading = array_keys(json_decode(json_encode($data[0]), true));
		// return Excel::download(new GeneralExport($heading, $data), 'receh-export-'.(date('YmdHis')).'.xlsx');

	}

	function receh($amount = 0) {
		// echo "Amout : ".$amount."<br>";
// 
		$pecahan = array(
			'100K'	=>100000,
			'50K'	=>50000,
			'20K'	=>20000,
			'10K'	=>10000,
			'5K'	=>5000,
			'2K'	=>2000,
			'1K'	=>1000,
			'500'	=>500,
			'200'	=>200,
			'100'	=>100,
		);

		$result = array();
		foreach ($pecahan as $key => $amt){
			$lembar = 0;
			if ($amount >= $amt) {
				$lembar = intVal($amount / $amt);
				if ($lembar > 0) {
					$amount = $amount - ($lembar * $amt);
				}
			}
			$result[$key] = $lembar;
		}

		if ($amount > 0 && $amount < 100) {
			$result['100'] = $result['100'] + 1;
		} 

		// dd($result);
		// echo "<pre>"; print_r($result); echo "<br>";
		return $result;
	}

	public function formSalary(Request $request)
	{
		return view('modules.report.report-salary', $this->data);
	}

	public function formSalaryExport(Request $request)
	{
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		$sql = "SELECT t1.*, g.name group_name, t2.total_mh, mr.employee_id, e.nik employee_nik, 
					e.name employee_name, e.nik employee_nik, e.rekening employee_rekening, 
					mr.man_hour
				FROM (
					SELECT pr.reported_date, pr.group_id, sum(pr.qty_output * part.price) total
					FROM production_report pr 
						INNER JOIN part ON part.part_number = pr.part_number
						INNER JOIN tblgroups g ON g.id = pr.group_id
					WHERE pr.reported_date BETWEEN ? AND ?
					GROUP BY reported_date, group_id
				) t1 
					INNER JOIN 
				(
					SELECT reported_date, group_id, sum(man_hour) total_mh
					FROM mandays_report mr
					WHERE mr.reported_date BETWEEN ? AND ?
					GROUP BY reported_date, group_id
				) t2 on t1.reported_date = t2.reported_date AND t1.group_id = t2.group_id
					INNER JOIN mandays_report mr ON mr.reported_date = t1.reported_date AND mr.group_id = t1.group_id
					INNER JOIN employee e ON e.id = mr.employee_id
					INNER JOIN tblgroups g ON g.id = mr.group_id
				ORDER BY g.name, e.name ";
		
		$result = DB::select($sql, [$startDate, $endDate, $startDate, $endDate]);

		foreach ($result as $key => $value) {
			$value->price_per_hour = ($value->total / $value->total_mh);
			$value->salary = $value->price_per_hour * $value->man_hour;
		}

		foreach ($result as $key => $value) {
			$row['NIK'] 		= $value->employee_nik;
			$row['Nama'] 		= $value->employee_name;
			$row['Rekening'] 	= $value->employee_rekening;
			$row['Bank'] 		= "UOB";
			$row['Cabang']		= "JAKARTA";
			$row['Total Gaji'] = $value->salary;
			$row['Tanggal Debet'] = $endDate;
			$row['Keterangan'] = "GAJI PT. FURNILAC PRIMAGUNA";
			$row['Nama Group'] = $value->group_name;
			$data[] = $row;
		}

		// dd($data);
		$heading = array_keys(json_decode(json_encode($data[0]), true));
		return Excel::download(new GeneralExport($heading, $data), 'salary-export-'.(date('YmdHis')).'.xlsx');
		// echo "<pre>"; print_r($data); echo "<br>";
	}
}
