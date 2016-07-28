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


}
