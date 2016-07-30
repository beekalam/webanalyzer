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
		// change to persian time
		foreach ($data as $item) {
			$item["login_time"] = $this->converttime(new \DateTime($item["login_time"]));
			$item["logout_time"] = $this->converttime(new \DateTime($item["logout_time"]));
		}
		$ret = ["data" => $data];
		return json_encode($ret);
	}

	public function showLogDetails($username, $page)
	{
		$per_page = $this->per_page;
		$per_page = 5;
		$offset = ($page * $per_page) - $per_page;

		$data = ConnectionLogDetail::where('name', '=', 'username')
									->where('value', '=', $username)
									->skip($offset)->take($per_page)->get();
		foreach($data as $item){
			$item["login_time"] = $this->converttime(new \DateTime($item["login_time"]));
			$item["logout_time"] = $this->converttime(new \DateTime($item["logout_time"]));
		}
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
								->where('name','ip_pool_assigned_ip')
								->first();

 		$session_ip = $session_log_details['value'];
		$login_time = $session_log_details['login_time'];
		$logout_time = $session_log_details['logout_time'];

		$data = Log::where('source','=', $session_ip)
					->whereBetween('login_time', [$login_time, $logout_time])
					// ->get();
					->skip($offset)->take(10)->get();

		foreach($data as $item){
			$item["login_time"] = $this->converttime(new \DateTime($item["login_time"]));
		}

		$ret = ["data" => $data,
				 "login_time" => $login_time,
				 "logout_time" => $logout_time
				];

		return $ret;
	}

	private function converttime($datetime){
			$fmt = new \IntlDateFormatter("fa_IR@calendar=persian", \IntlDateFormatter::SHORT,\IntlDateFormatter::NONE, 'Asia/Tehran',\IntlDateFormatter::TRADITIONAL);

			$date =  $fmt->format($datetime);
			$char_replace = array("ش","ه‍",".");
			$date = str_replace($char_replace, '', $date);
			$search = array('۱','۲','۳','۴','۵','۶','۷','۸','۹','۰');
			$replace = array('1','2','3','4','5','6','7','8','9','0');
			$date = str_replace($search,$replace, $date);
			$fmt = new \IntlDateFormatter("fa_IR@calendar=persian", \IntlDateFormatter::NONE, \IntlDateFormatter::FULL
				, 'Asia/Tehran', \IntlDateFormatter::TRADITIONAL);
			$time = $fmt->format($datetime);
			//todo : calculate 15 or unicode equiv
			$time =  mb_substr($time,0,15);
			$time = str_replace($search, $replace, $time);
			$time = (string)$time;
			$date = (string)$date;
			return trim($date) . " " . trim($time);
	}

}
