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
		$groupId 	= $request->input('groupId');
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		$sql = "SELECT reported_date, pr.po_number, part_number, qty_output, pr.group_id, g.name group_name
				FROM production_report pr 
					LEFT JOIN tblgroups g ON pr.group_id=g.id
				WHERE reported_date BETWEEN ? AND ?  ";

		if ($groupId != null && $groupId != "") {
			$sql .= "AND pr.group_id = '".$groupId."' ";
		}
		
		$sql .= "ORDER BY reported_date, pr.group_id, pr.po_number ASC";
		// dd($sql);

		$data = DB::select($sql, array($startDate, $endDate));

		// dd($data);		
		$this->data['groups'] = \App\Model\Group::all();
		$this->data['data']	  = $data;
		return view('modules.report.report-production', $this->data);
	}

	public function formMandays(Request $request)
	{
		$this->data['groups'] = \App\Model\Group::all();
		return view('modules.report.report-mandays', $this->data);
	}
	
	public function formMandaysExport(Request $request)
	{
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
		$this->data['groups'] = \App\Model\Group::all();
		$this->data['data']	  = $data;
		return view('modules.report.report-mandays', $this->data);
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
		foreach ($data as $k1 => $v1) {
			$row = array();
			$row['NIK'] 	= $v1->employee_nik;
			$row['Name'] 	= $v1->employee_name;
			
			$totalGaji = 0;
			foreach ($dateList as $k2 => $v2) {
				if ($v2 == $v1->reported_date) {
					$row['dateList'][$v2]['jam'] 	= $v1->man_hour;
					$row['dateList'][$v2]['gaji'] 	= $v1->salary;
					$totalGaji = $totalGaji + $v1->salary;
				} else {
					$row['dateList'][$v2]['jam'] 	= 0;
					$row['dateList'][$v2]['gaji'] 	= 0;
				}
			}

			$row['total'] = $totalGaji;

			$export[] = $row; 
		}

		$group = \App\Model\Group::find($groupId);

		$x['bagian'] 	= $group->section;
		$x['group'] 	= $group->name;
		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;
		$x['dateList']	= $dateList;
		$x['data']		= $export;
		// dd($x);
		// return view('export.laporan_rekap_upah_borongan', $x);
		return Excel::download(new GeneralViewExport('export.laporan_rekap_upah_borongan', $x), 'group-export-'.(date('YmdHis')).'.xlsx');

	}

	public function formGroupSummary(Request $request)
	{
		return view('modules.report.report-group-summary', $this->data);
	}

	public function formGroupSummaryExport(Request $request)
	{
		// return view('modules.report.report-group-summary', $this->data);
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');
		
		$sql = "SELECT pr.reported_date, pr.group_id, g.name, sum(pr.qty_output * part.price) total
				FROM production_report pr 
					INNER JOIN part ON part.part_number = pr.part_number
					INNER JOIN tblgroups g ON g.id = pr.group_id
				WHERE pr.reported_date BETWEEN ? AND ? 
				GROUP BY pr.reported_date, pr.group_id, g.name
				";

		$data = DB::select($sql, [$startDate, $endDate]);

		// foreach ($data as $key => $value) {
		// 	$value->price_per_hour = ($totalPrice / $totalManhour);
		// 	$value->salary = $value->price_per_hour * $value->man_hour;
		// }

		// dd($data);
		$heading = array_keys(json_decode(json_encode($data[0]), true));
		return Excel::download(new GeneralExport($heading, $data), 'group-summary-export-'.(date('YmdHis')).'.xlsx');

		// $total = $data 
	}

	public function formReceh(Request $request)
	{
		return view('modules.report.report-receh', $this->data);
	}

	public function formRecehExport(Request $request)
	{
		$startDate 	= $request->input('startDate');
		$endDate 	= $request->input('endDate');

		$sql = "SELECT t1.*, g.name group_name, g.section bagian, t2.total_mh, mr.employee_id, e.name employee_name,e.nik employee_nik, mr.man_hour
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
					INNER JOIN tblgroups g ON g.id = mr.group_id ";
		
		$result = DB::select($sql, [$startDate, $endDate, $startDate, $endDate]);

		foreach ($result as $key => $value) {
			$value->price_per_hour = ($value->total / $value->total_mh);
			$value->salary = $value->price_per_hour * $value->man_hour;

			$value->receh = $this->receh($value->salary);
		}

		foreach ($result as $key => $value) {
			$row["Bagian"] 		= $value->bagian;
			$row["NIK"] 		= $value->employee_nik;
			$row["Karyawan"] 	= $value->employee_name;
			$row["Gaji"] 		= $value->salary;
			foreach ($value->receh as $pecahan => $receh) {
				$row[$pecahan] = $receh;
			}
			$data[] = $row;
		}

		dd($data);
		$heading = array_keys(json_decode(json_encode($data[0]), true));
		return Excel::download(new GeneralExport($heading, $data), 'receh-export-'.(date('YmdHis')).'.xlsx');

	}

	function receh($amount = 0) {
		// echo "Amout : ".$amount."<br>";

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
