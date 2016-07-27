<?php

namespace App\Http\Controllers;

use App\Log;
use App\ConnectionLog;
use App\ConnectionLogDetail;
class LogsController
{
	public function show($page)	
	{
		$per_page = 10;
		$offset = ($page * $per_page) - $per_page;
		$ret = Log::orderBy('log_id')->skip($offset)->take($per_page)->get();
		// return json_encode(["data"=> $ret]);
		return $ret;
	}

	public function showt($user_id, $page)
	{
		// return ConnectionLog::all();
		return ConnectionLogDetail::all();
	}
}
