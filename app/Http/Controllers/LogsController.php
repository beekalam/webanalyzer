<?php

namespace App\Http\Controllers;

use App\Log;
use App\ConnectionLog;
use App\ConnectionLogDetail;

class LogsController
{
	private $per_page = 10;
	public function show($page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;
		$ret = Log::orderBy('log_id')->skip($offset)->take($per_page)->get();
		// return json_encode(["data"=> $ret]);
		return $ret;
	}

	public function showLogs($user_id, $page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;

		$data = ConnectionLog::where('user_id', '=', $user_id)->skip($offset)->take($per_page)->get();
		$ret = ["data" => $data];
		return json_encode($ret);
	}


}
