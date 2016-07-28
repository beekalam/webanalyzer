<?php

namespace App\Http\Controllers;

use App\Log;
use App\ConnectionLog;
use App\ConnectionLogDetail;

class LogsController
{
	private $per_page = 10;
	public function showxx($page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;
		$ret = Log::orderBy('log_id')->skip($offset)->take($per_page)->get();
		// return json_encode(["data"=> $ret]);
		return $ret;
	}

	public function showLogs($page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;

		$data = ConnectionLog::skip($offset)->take($per_page)->get();
		$ret = ["data" => $data];
		return json_encode($ret);
	}

	public function showLogDetails($username, $page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;

		$data = ConnectionLogDetail::where('name', '=', 'username')
									->where('value', '=', $username)
									->get();

		$ret = ["data" => $data];
		return $ret;
	}

	public function showWebLogs($connection_log_id, $page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;

		// find all connection_log_details with the $user_id
		// $m = ['name' => 'username', 'value' => $user_name];
		$session_log_details = ConnectionLogDetail::where('connection_log_id', '=',$connection_log_id)
								->where('name','ip pool assigned ip')
								->first();

 		$session_ip = $session_log_details['value'];
		$login_time = $session_log_details['login_time'];
		$logout_time = $session_log_details['logout_time'];

		$data = Log::where('source','=', $session_ip)
					->whereBetween('login_time', [$login_time, $logout_time])
					// ->get();
					->skip($offset)->take(10)->get();

		$ret = ["data" => $data,
				 "login_time" => $login_time,
				 "logout_time" => $logout_time
				];

		return $ret;
	}

	private function converttime($datetime){
			$fmt = new IntlDateFormatter("fa_IR@calendar=persian", IntlDateFormatter::SHORT, IntlDateFormatter::NONE
				, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL);
			$date =  $fmt->format($datetime);
			$date = str_replace("ش",'',$date);
			$date = str_replace("ه‍",'', $date);
			$date = str_replace('.','', $date);

			$fmt = new IntlDateFormatter("fa_IR@calendar=persian", IntlDateFormatter::NONE, IntlDateFormatter::FULL
				, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL);
			$time = $fmt->format($datetime);
			//todo : calculate 15 or unicode equiv
			$time =  mb_substr($time,0,15);
			return $date . " " . $time;
	}

}
