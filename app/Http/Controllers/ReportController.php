<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

    public function form()
    {
		$this->data['employees'] = \App\Model\Employee::all();
		return view('modules.report.salary', $this->data);
    }

    public function export(Request $request)
    {
    	// dd($request->all());
    	$reporterId = $request->input('reporterId');
    	$startDate 	= $request->input('startDate');
    	$endDate 	= $request->input('endDate');

    	$dateList = array();
    	$currentDate = $startDate;
		while (strtotime($currentDate) <= strtotime($endDate)) {
		    $dateList[] = $currentDate;
		    $currentDate = date ("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
		}

		$sql = "select reported_date, e1.name as reporter, e2.name as employee, man_hour
				from mandays_report mr 
					left join employee e1 on e1.id = mr.reported_by
					left join employee e2 on e2.id = mr.employee_id
				where reported_by='1' and reported_date between :startDate and :endDate 
				order by reported_date asc";
		$result = DB::select($sql, ['startDate'=>$startDate, 'endDate'=>$endDate]);
		// dd($result);

		$merge = array();
		foreach ($result as $key => $data) {
			$merge[$data->employee][$data->reported_date] = $data->man_hour;
		}
		// echo "<pre>";
		// print_r($dateList);
		foreach ($merge as $name => $data) {
			// echo "<pre>";
			// print_r(array_keys($merge[$name]));
			// echo "<br>";
			// print_r($dateList);
			$diff = array_diff($dateList, array_keys($merge[$name]));
			// dd($diff);
			foreach ($diff as $tgl) { // not working date
				$merge[$name][$tgl] = 0;
			}
		}


		// dd($merge);
		// $outputData = array();
		// foreach ($result as $key => $value) {
		// 	# code...
		// }

		$this->data['dateList'] = $dateList;
		$this->data['data'] 	= $merge;
		return view('modules.report.salary-summary', $this->data);
    }
}
