<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Exports\GeneralExport;
use App\Exports\GeneralViewExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-production', $this->data);
	}

	public function formProductionExport(Request $request)
	{
		// $exportType = "view";
		// if ($request->input('exportExcel')) {
		// 	$exportType = "excel";
		// } 

		$groupId 	= $request->input('groupId');
		$periodId	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;
		// dd($p);

		$sql = "SELECT reported_date, pr.po_number, pr.part_number, qty_output, pr.group_id, g.name group_name, part.price as part_price  
				FROM production_report pr 
					LEFT JOIN tblgroups g ON pr.group_id=g.id 
					LEFT JOIN vw_part_period_price part on part.part_number = pr.part_number AND part.period_id = ?
				WHERE reported_date BETWEEN ? AND ?  ";

		if ($groupId != null && $groupId != "") {
			$sql .= "AND pr.group_id = '".$groupId."' ";
		}
		
		$sql .= "ORDER BY reported_date, pr.group_id, pr.po_number ASC";
		// dd($sql);

		$data = DB::select($sql, array($periodId, $startDate, $endDate));

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

		$rekapUpahBoronganSQL = "SELECT mr.reported_date, 
				sum(ceil(t_sum_salary.total_gaji_group / t_sum_manhour.total_manhour_group) * mr.man_hour) as gaji_total
			FROM mandays_report mr 
				LEFT JOIN (
					SELECT pr.reported_date, pr.group_id, SUM(pr.qty_output * part.price) total_gaji_group
					FROM production_report pr
					INNER JOIN vw_part_period_price part ON part.part_number = pr.part_number AND part.period_id = ? 
					INNER JOIN tblgroups g2 ON g2.id = pr.group_id
					WHERE pr.reported_date BETWEEN ? AND ?
					GROUP BY pr.reported_date, pr.group_id
				) t_sum_salary 
					ON t_sum_salary.reported_date = mr.reported_date AND t_sum_salary.group_id = mr.group_id
				LEFT JOIN (
					SELECT mr0.reported_date, mr0.group_id, SUM(mr0.man_hour) total_manhour_group
					FROM mandays_report mr0
					WHERE mr0.reported_date BETWEEN ? AND ?
					GROUP BY mr0.reported_date, mr0.group_id
				) t_sum_manhour 
					ON t_sum_manhour.reported_date = mr.reported_date AND t_sum_manhour.group_id = mr.group_id
			WHERE mr.group_id = 1 AND mr.reported_date BETWEEN ? AND ?
			GROUP BY mr.reported_date 
			ORDER BY mr.reported_date ASC";

		$dataRekapUpahBorongan = DB::select($rekapUpahBoronganSQL, array($periodId, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate));
		
		$rekap = array();
		foreach ($dataRekapUpahBorongan as $key => $value) {
			$rekap[$value->reported_date] = $value->gaji_total;
		}
		// echo "<pre>"; print_r($dateList);
		// dd($rekap);

		// if ($exportType == "view") {
		// 	$this->data['groups'] = \App\Model\Group::all();
		// 	$this->data['data']	  = $data;
		// 	return view('modules.report.report-production', $this->data);
		// } else {
		$group = \App\Model\Group::find($groupId);
		$this->data['group']  	 = $group->name;
		$this->data['bagian'] 	 = $group->section;
		$this->data['startDate'] = $startDate;
		$this->data['endDate'] 	 = $endDate;
		$this->data['data']	  	 = $data;
		$this->data['dateList']	 = $dateList;
		$this->data['rekapUpah'] = $rekap;

		// dd($data);

		// if ($request->input('pdf')) {
		// 	$pdf = PDF::loadView('export.laporan_produksi', $this->data)->setPaper('a4', 'landscape');
		// 	return $pdf->download('production-report-'.(date('YmdHis')).'.pdf');			
		// } else if ($request->input('excel')) {
		// 	return Excel::download(new GeneralViewExport('export.laporan_produksi', $this->data), 'production-report-'.(date('YmdHis')).'.xlsx');
		// } else {
			return view('export.laporan_produksi', $this->data);
		// }
		// }
	}

	public function formMandays(Request $request)
	{
		$this->data['groups'] = \App\Model\Group::all();
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-mandays', $this->data);
	}
	
	public function formMandaysExport(Request $request)
	{
		$exportType = "view";
		if ($request->input('exportExcel')) {
			$exportType = "excel";
		}

		$groupId 	= $request->input('groupId');
		$periodId 	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;

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
			$this->data['periods'] = \App\Model\ReportPeriod::all();
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
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-group', $this->data);
	}
	
	public function formGroupExport(Request $request)
	{
		$groupId 	= $request->input('groupId');
		$periodId 	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;
		// $startDate 	= $request->input('startDate');
		// $endDate 	= $request->input('endDate');

		$totalPrice = DB::table('production_report')
			// ->join('part', 'part.part_number', '=', 'production_report.part_number')
			->join('vw_part_period_price as part', function($join) use ($periodId) {
                    $join->on('part.part_number', '=', 'production_report.part_number');
                    $join->where('part.period_id', '=', $periodId);					
				})
			->where('production_report.group_id', $groupId)
			->whereBetween('production_report.reported_date', [$startDate, $endDate])
			->sum(DB::raw('production_report.qty_output * part.price'));

		$totalManhour = DB::table('mandays_report')
			->where('mandays_report.group_id', $groupId)
			->whereBetween('mandays_report.reported_date', [$startDate, $endDate])
			->sum('mandays_report.man_hour');

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
			if (array_key_exists($v1->employee_nik, $export)) continue;

			$row = array();
			$row['NIK'] 	= $v1->employee_nik;
			$row['Name'] 	= $v1->employee_name;
			
			foreach ($dateList as $k2 => $v2) {
				$row['dateList'][$v2]['jam'] 	= 0;
				$row['dateList'][$v2]['gaji'] 	= 0;
				$row['dateList'][$v2]['totalGaji'] 	= DB::table('production_report')
					// ->join('part', 'part.part_number', '=', 'production_report.part_number')
					->join('vw_part_period_price as part', function($join) use ($periodId)  {
		                    $join->on('part.part_number', '=', 'production_report.part_number');
		                    $join->where('part.period_id', '=', $periodId);					
						})
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
					$price_per_hour = ceil($row['dateList'][$k2]['totalGaji'] / $row['dateList'][$k2]['totalManHour']);
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


		// if ($request->input('pdf')) {
		// 	$pdf = PDF::loadView('export.laporan_rekap_upah_borongan', $x)->setPaper('a4', 'landscape');
		// 	return $pdf->download('group-export-'.(date('YmdHis')).'.pdf');			
		// } else if ($request->input('excel')) {
		// 	return Excel::download(new GeneralViewExport('export.laporan_rekap_upah_borongan', $x), 'group-export-'.(date('YmdHis')).'.xlsx');
		// } else {
			return view('export.laporan_rekap_upah_borongan', $x);
		// }

	}

	public function formGroupSummary(Request $request)
	{
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-group-summary', $this->data);
	}

	public function formGroupSummaryExport(Request $request)
	{
		$periodId 	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;
		// $startDate 	= $request->input('startDate');
		// $endDate 	= $request->input('endDate');

		$sql = "SELECT group_id, group_name, bagian, employee_id, sum(t.gaji) as salary 
				FROM (
					SELECT mr.reported_date, man_hour, shift, 
					mr.group_id, g.name group_name, g.section bagian, employee_id,
					t_sum_salary.total_gaji_group, t_sum_manhour.total_manhour_group, 
					CEIL(t_sum_salary.total_gaji_group / t_sum_manhour.total_manhour_group) * man_hour as gaji
					FROM mandays_report mr
					LEFT JOIN tblgroups g ON mr.group_id=g.id
					LEFT JOIN (
						SELECT pr.reported_date, pr.group_id, SUM(pr.qty_output * part.price) total_gaji_group
						FROM production_report pr
						INNER JOIN vw_part_period_price part ON part.part_number = pr.part_number AND part.period_id = ?
						INNER JOIN tblgroups g2 ON g2.id = pr.group_id
						WHERE pr.reported_date BETWEEN ? AND ?
						GROUP BY pr.reported_date, pr.group_id
					) t_sum_salary 
						ON t_sum_salary.reported_date = mr.reported_date AND t_sum_salary.group_id = mr.group_id
					LEFT JOIN (
						SELECT mr0.reported_date, mr0.group_id, SUM(mr0.man_hour) total_manhour_group
						FROM mandays_report mr0
						WHERE mr0.reported_date BETWEEN ? AND ?
						GROUP BY mr0.reported_date, mr0.group_id
					) t_sum_manhour 
						ON t_sum_manhour.reported_date = mr.reported_date AND t_sum_manhour.group_id = mr.group_id
					WHERE mr.reported_date BETWEEN ? AND ?
					ORDER BY mr.reported_date, mr.group_id ASC
				) t 
				group by group_id, group_name, bagian, employee_id";
		
		$result = DB::select($sql, [$periodId, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);

		foreach ($result as $key => $value) {
			$value->gaji_bulat_100 = ($value->salary % 100 > 0) ? ($value->salary - ($value->salary % 100) + 100) : $value->salary;
		}

		// dd($result);
		$data = array();
		foreach ($result as $key => $value) {
			$row["group_id"]			= $value->group_id;
			$row["bagian"] 				= $value->bagian;
			$row["group_name"]			= $value->group_name;
			$row["karyawan"] 			= $value->employee_id;
			$row["gaji"] 				= $value->salary;
			$row["gaji_bulat_100"]		= $value->gaji_bulat_100;
			$data[] = $row;
		}
		// dd($data);

		$groupByGroupId = array();
		foreach ($data as $key => $value) {
			$groupByGroupId[$value['group_id']][] = $value;
		}

		$finalOutput = array();
		foreach ($groupByGroupId as $key => $value) {
			$row['group_id'] 		= $value[0]['group_id'];
			$row['bagian'] 			= $value[0]['bagian'];
			$row['group_name'] 		= $value[0]['group_name'];
			$row['jumlah_karyawan'] = count($value);
			$row['gaji'] 			= array_sum(array_column($value, 'gaji'));
			$row['gaji_bulat_100']	= array_sum(array_column($value, 'gaji_bulat_100'));

			$finalOutput[] = $row;
		}

		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;
		$x['data'] 		= $finalOutput;

		// if ($request->input('pdf')) {
		// 	$pdf = PDF::loadView('export.laporan_rekap_gaji_borongan', $x);
		// 	return $pdf->download('group-summary-export-'.(date('YmdHis')).'.pdf');			
		// } else if ($request->input('excel')) {
		// 	return Excel::download(new GeneralViewExport('export.laporan_rekap_gaji_borongan', $x), 'group-summary-export-'.(date('YmdHis')).'.xlsx');
		// } else {
			return view('export.laporan_rekap_gaji_borongan', $x);
		// }
	}

	public function formReceh(Request $request)
	{
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-receh', $this->data);
	}

	public function formRecehExport(Request $request)
	{
		$periodId 	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;
		// $startDate 	= $request->input('startDate');
		// $endDate 	= $request->input('endDate');

		$sql = "SELECT group_id, group_name, bagian, employee_id, sum(t.gaji) as salary 
				FROM (
					SELECT mr.reported_date, man_hour, shift, 
					mr.group_id, g.name group_name, g.section bagian, employee_id,
					t_sum_salary.total_gaji_group, t_sum_manhour.total_manhour_group, 
					CEIL(t_sum_salary.total_gaji_group / t_sum_manhour.total_manhour_group) * man_hour as gaji
					FROM mandays_report mr
					LEFT JOIN tblgroups g ON mr.group_id=g.id
					LEFT JOIN (
						SELECT pr.reported_date, pr.group_id, SUM(pr.qty_output * part.price) total_gaji_group
						FROM production_report pr
						INNER JOIN vw_part_period_price part ON part.part_number = pr.part_number AND part.period_id = ?
						INNER JOIN tblgroups g2 ON g2.id = pr.group_id
						WHERE pr.reported_date BETWEEN ? AND ?
						GROUP BY pr.reported_date, pr.group_id
					) t_sum_salary 
						ON t_sum_salary.reported_date = mr.reported_date AND t_sum_salary.group_id = mr.group_id
					LEFT JOIN (
						SELECT mr0.reported_date, mr0.group_id, SUM(mr0.man_hour) total_manhour_group
						FROM mandays_report mr0
						WHERE mr0.reported_date BETWEEN ? AND ?
						GROUP BY mr0.reported_date, mr0.group_id
					) t_sum_manhour 
						ON t_sum_manhour.reported_date = mr.reported_date AND t_sum_manhour.group_id = mr.group_id
					WHERE mr.reported_date BETWEEN ? AND ?
					ORDER BY mr.reported_date, mr.group_id ASC
				) t 
				group by group_id, group_name, bagian, employee_id";
		
		$result = DB::select($sql, [$periodId, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate]);

		foreach ($result as $key => $value) {
			$value->gaji_bulat_100 = ($value->salary % 100 > 0) ? ($value->salary - ($value->salary % 100) + 100) : $value->salary;
			$value->receh = $this->receh($value->gaji_bulat_100);
		}

		// dd($result);
		$data = array();
		foreach ($result as $key => $value) {
			$row["group_id"]			= $value->group_id;
			$row["bagian"] 				= $value->bagian;
			$row["group_name"]			= $value->group_name;
			$row["karyawan"] 			= $value->employee_id;
			$row["gaji"] 				= $value->salary;
			$row["gaji_bulat_100"]		= $value->gaji_bulat_100;
			foreach ($value->receh as $pecahan => $receh) {
				$row[$pecahan] = $receh;
			}
			$data[] = $row;
		}
		// dd($data);

		$groupByGroupId = array();
		foreach ($data as $key => $value) {
			$groupByGroupId[$value['group_id']][] = $value;
		}

		$finalOutput = array();
		foreach ($groupByGroupId as $key => $value) {
			$row['group_id'] 	= $value[0]['group_id'];
			$row['group_name'] 	= $value[0]['group_name'];
			$row['bagian'] 		= $value[0]['bagian'];
			$row['jumlah_karyawan'] = count($value);
			$row['gaji'] 		= array_sum(array_column($value, 'gaji'));
			$row['gaji_bulat_100']	= array_sum(array_column($value, 'gaji_bulat_100'));
			$row['100K'] 		= array_sum(array_column($value, '100K'));
			$row['50K'] 		= array_sum(array_column($value, '50K'));
			$row['20K'] 		= array_sum(array_column($value, '20K'));
			$row['10K'] 		= array_sum(array_column($value, '10K'));
			$row['5K'] 			= array_sum(array_column($value, '5K'));
			$row['2K'] 			= array_sum(array_column($value, '2K'));
			$row['1K'] 			= array_sum(array_column($value, '1K'));
			$row['500'] 		= array_sum(array_column($value, '500'));
			$row['200'] 		= array_sum(array_column($value, '200'));
			$row['100'] 		= array_sum(array_column($value, '100'));

			$finalOutput[] = $row;
		}

		// dd($finalOutput);

		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;
		$x['data'] 		= $finalOutput;
		// dd($data);

		// if ($request->input('pdf')) {
		// 	$pdf = PDF::loadView('export.laporan_receh', $x)->setPaper('a4', 'landscape');
		// 	return $pdf->download('receh-export-'.(date('YmdHis')).'.pdf');			
		// } else if ($request->input('excel')) {
		// 	return Excel::download(new GeneralViewExport('export.laporan_receh', $x), 'receh-export-'.(date('YmdHis')).'.xlsx');
		// } else {
			return view('export.laporan_receh', $x);
		// }

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
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-salary', $this->data);
	}

	public function formSalaryExport(Request $request)
	{
		$periodId 	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;
		// $startDate 	= $request->input('startDate');
		// $endDate 	= $request->input('endDate');

		
		$sql = "SELECT t.employee_nik, t.employee_name, t.employee_rekening, sum(t.gaji) as salary
				FROM (
					SELECT mr.reported_date, 
					employee_id, e.nik employee_nik, e.name employee_name, e.rekening employee_rekening,  man_hour, shift, 
					mr.group_id, g.name group_name, t_sum_salary.total_gaji_group, t_sum_manhour.total_manhour_group, 
					ceil(t_sum_salary.total_gaji_group / t_sum_manhour.total_manhour_group) * man_hour as gaji
					FROM mandays_report mr
					LEFT JOIN tblgroups g ON mr.group_id=g.id
					LEFT JOIN employee e ON mr.employee_id=e.id
					LEFT JOIN (
						SELECT pr.reported_date, pr.group_id, SUM(pr.qty_output * part.price) total_gaji_group
						FROM production_report pr						
						INNER JOIN vw_part_period_price part ON part.part_number = pr.part_number AND part.period_id = ?
						INNER JOIN tblgroups g2 ON g2.id = pr.group_id
						WHERE pr.reported_date BETWEEN ? AND ? 
						GROUP BY pr.reported_date, pr.group_id
					) t_sum_salary 
						ON t_sum_salary.reported_date = mr.reported_date AND t_sum_salary.group_id = mr.group_id
					LEFT JOIN (
						SELECT mr0.reported_date, mr0.group_id, SUM(mr0.man_hour) total_manhour_group
						FROM mandays_report mr0
						WHERE mr0.reported_date BETWEEN ? AND ? 
						GROUP BY mr0.reported_date, mr0.group_id
					) t_sum_manhour 
						ON t_sum_manhour.reported_date = mr.reported_date AND t_sum_manhour.group_id = mr.group_id
					WHERE mr.reported_date BETWEEN ? AND ? 
					ORDER BY mr.reported_date, mr.group_id ASC
				) t 
				GROUP BY employee_nik, employee_name, employee_rekening";

		$data = DB::select($sql, [$periodId, $startDate, $endDate,$startDate, $endDate,$startDate, $endDate]);

		$export = array();
		foreach ($data as $key => $value) {
			$row['nik'] 		= $value->employee_nik;
			$row['nama'] 		= $value->employee_name;
			$row['rekening'] 	= $value->employee_rekening;
			$row['bank'] 		= "UOB";
			$row['cabang']		= "JAKARTA";
			$row['gaji']  = ($value->salary % 100 > 0 ) ? $value->salary - ($value->salary % 100) + 100 : $value->salary;
			$row['tgl_debet'] = $endDate;
			$row['keterangan'] 	= "GAJI PT. FURNILAC PRIMAGUNA";
			$export[] = $row;
		}

		// dd($export);

		// $heading = array_keys(json_decode(json_encode($export[0]), true));
		// return Excel::download(new GeneralExport($heading, $export), 'salary-export-'.(date('YmdHis')).'.xlsx');

		$x['rows'] 		= json_decode(json_encode($export));
		$x['headings'] 	= array();//$heading;
		$x['startDate'] = $startDate;
		$x['endDate'] 	= $endDate;


		// if ($request->input('pdf')) {
		// 	$pdf = PDF::loadView('export.laporan_gaji', $x)->setPaper('a4', 'landscape');
		// 	return $pdf->download('salary-export-'.(date('YmdHis')).'.pdf');			
		// } else if ($request->input('excel')) {
		// 	return Excel::download(new GeneralViewExport('export.laporan_gaji', $x), 'salary-export-'.(date('YmdHis')).'.xlsx');
		// } else {
			return view('export.laporan_gaji', $x);	
		// }
	}



	public function formTandaTerima(Request $request)
	{
		$this->data['groups'] = \App\Model\Group::all();
		$this->data['periods'] = \App\Model\ReportPeriod::all();
		return view('modules.report.report-tanda-terima', $this->data);
	}
	
	public function formTandaTerimaExport(Request $request)
	{
		$groupId 	= $request->input('groupId');
		$periodId 	= $request->input('periodId');

		$p = \App\Model\ReportPeriod::find($periodId);
		$startDate 	= $p->start_period;
		$endDate 	= $p->end_period;
		// $startDate 	= $request->input('startDate');
		// $endDate 	= $request->input('endDate');

		$totalPrice = DB::table('production_report')
			// ->join('part', 'part.part_number', '=', 'production_report.part_number')
			->join('vw_part_period_price as part', function($join) use ($periodId) {
                    $join->on('part.part_number', '=', 'production_report.part_number');
                    $join->where('part.period_id', '=', $periodId);					
				})
			->where('production_report.group_id', $groupId)
			->whereBetween('production_report.reported_date', [$startDate, $endDate])
			->sum(DB::raw('production_report.qty_output * part.price'));

		$totalManhour = DB::table('mandays_report')
			->where('mandays_report.group_id', $groupId)
			->whereBetween('mandays_report.reported_date', [$startDate, $endDate])
			->sum('mandays_report.man_hour');

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
			if (array_key_exists($v1->employee_nik, $export)) continue;

			$row = array();
			$row['NIK'] 	= $v1->employee_nik;
			$row['Name'] 	= $v1->employee_name;
			
			foreach ($dateList as $k2 => $v2) {
				$row['dateList'][$v2]['jam'] 	= 0;
				$row['dateList'][$v2]['gaji'] 	= 0;
				$row['dateList'][$v2]['totalGaji'] 	= DB::table('production_report')
					// ->join('part', 'part.part_number', '=', 'production_report.part_number')					
					->join('vw_part_period_price as part', function($join) use ($periodId) {
		                    $join->on('part.part_number', '=', 'production_report.part_number');
		                    $join->where('part.period_id', '=', $periodId);					
						})
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
					$price_per_hour = ceil($row['dateList'][$k2]['totalGaji'] / $row['dateList'][$k2]['totalManHour']);
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


		// if ($request->input('pdf')) {
		// 	$pdf = PDF::loadView('export.laporan_tanda_terima_upah', $x)->setPaper('a4');
		// 	return $pdf->download('tanda-terima-upah-'.(date('YmdHis')).'.pdf');

		// } else if ($request->input('excel')) {
		// 	return Excel::download(new GeneralViewExport('export.laporan_tanda_terima_upah', $x), 'tanda-terima-upah-'.(date('YmdHis')).'.xlsx');
		// } else {
			return view('export.laporan_tanda_terima_upah', $x);
		// }

	}
}
