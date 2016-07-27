<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Pagination\Paginator;
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
}
