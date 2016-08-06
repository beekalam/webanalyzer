<?php

namespace App\Http\Controllers;

use App\Log;
use App\ConnectionLog;
use App\LogView;
use App\WebLog;
use App\ConnectionLogDetail;
use Log as llog;

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

	public function showAllLogs($page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;

		$data =  WebLog::skip($offset)->take($per_page)->get();
		// change to persian time
		foreach ($data as $item) {
			$date_part = explode(' ',$item["visited_at"])[0];
			$item['visited_at'] = $this->convertdate(new \DateTime($date_part));
		}
		$ret = ["data" => $data];

		return json_encode($ret);
	}

	public function showLogs($username, $page)
	{
		$per_page = $this->per_page;
		$per_page = 5;
		$offset = ($page * $per_page) - $per_page;

		$data = WebLog::where('username', '=', $username)
						->orderBy('visited_at','asc')
						->skip($offset)
						->take($per_page)
						->get();

		foreach($data as $item){
			$date_part = explode(' ', $item['visited_at'])[0];
			$item['visited_at'] = $this->convertdate(new \DateTime($date_part));
		}
		$ret = ["data" => $data];
		return $ret;
	}

	public function showLogsByDate($username,$startdate, $enddate, $page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page)  - $per_page;
		$data = WebLog::where('username', '=', $username)
						->where('visited_at', '>=', $startdate)
						->where('visited_at', '<=', $enddate)
						->orderBy('visited_at','asc')
						->skip($offset)->take($per_page)->get();

		foreach($data as $item){
			$date_part = explode(' ', $item['visited_at'])[0];
			$item['visited_at'] = $this->convertdate(new \DateTime($date_part));
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
								->where('name','ippool_assigned_ip')
								->first();

 		$session_ip = $session_log_details['value'];
		$login_time = $session_log_details['login_time'];
		$logout_time = $session_log_details['logout_time'];

		$data = Log::where('source','=', $session_ip)
					->whereBetween('visited_at', [$login_time, $logout_time])
					->skip($offset)->take(10)->get();

		foreach($data as $item){
			$item["visited_at"] = $this->converttime(new \DateTime($item["login_time"]));
		}

		$ret = ["data" => $data,
				 "login_time" => $login_time,
				 "logout_time" => $logout_time
				];

		return $ret;
	}

	private function convertdate($datetime)
	{
		$fmt = new \IntlDateFormatter("fa_IR@calendar=persian", \IntlDateFormatter::SHORT,\IntlDateFormatter::NONE, 'Asia/Tehran',\IntlDateFormatter::TRADITIONAL);

			$date =  $fmt->format($datetime);
			llog::info("++++++++++++++++++++++++++++++++");
		   // $char_replace ="ا,ب,پ,ت,ث,ج,چ,ح,خ,د,ذ,ر,ز,س,ش,ط,ظ,ع,غ,ف,ق,ک,گ,ل,م,ن,و,ه,ی,ژ,ك,إ,ي,ئ,ؤ,.,),(";
			// $char_replace_arr = explode(',', $char_replace);
			// $date = str_replace($char_replace_arr, '', $date);
			$date = str_replace(['ا','ب','پ','ت','ث','ج','چ','ح','خ','د'], '', $date);
			$date = str_replace(['ذ','ر','ز','س','ش','ط','ظ','ع','غ'], '', $date);
			$date = str_replace(['ف','ق','ک','گ','ل','م','ن','و','ه','ی','.','(',')','ء','ص','ض','‍'],'', $date);
			$search = array('۱','۲','۳','۴','۵','۶','۷','۸','۹','۰');
			$replace = array('1','2','3','4','5','6','7','8','9','0');
			$date = str_replace($search,$replace, $date);

			preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$date);
			$date = (string)$date;
			return trim($date);
	}

	private function converttime($datetime){
			$fmt = new \IntlDateFormatter("fa_IR@calendar=persian", \IntlDateFormatter::SHORT,\IntlDateFormatter::NONE, 'Asia/Tehran',\IntlDateFormatter::TRADITIONAL);

			$date =  $fmt->format($datetime);
			llog::info("++++++++++++++++++++++++++++++++");
		   // $char_replace ="ا,ب,پ,ت,ث,ج,چ,ح,خ,د,ذ,ر,ز,س,ش,ط,ظ,ع,غ,ف,ق,ک,گ,ل,م,ن,و,ه,ی,ژ,ك,إ,ي,ئ,ؤ,.,),(";
			// $char_replace_arr = explode(',', $char_replace);
			// $date = str_replace($char_replace_arr, '', $date);
			$date = str_replace(['ا','ب','پ','ت','ث','ج','چ','ح','خ','د'], '', $date);
			$date = str_replace(['ذ','ر','ز','س','ش','ط','ظ','ع','غ'], '', $date);
			$date = str_replace(['ف','ق','ک','گ','ل','م','ن','و','ه','ی','.','(',')','ء','ص','ض','‍'],'', $date);
			$search = array('۱','۲','۳','۴','۵','۶','۷','۸','۹','۰');
			$replace = array('1','2','3','4','5','6','7','8','9','0');
			$date = str_replace($search,$replace, $date);

			preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$date);

			$fmt = new \IntlDateFormatter("fa_IR@calendar=persian", \IntlDateFormatter::NONE, \IntlDateFormatter::FULL
				, 'Asia/Tehran', \IntlDateFormatter::TRADITIONAL);
			$time = $fmt->format($datetime);
			$time = str_replace(['ا','ب','پ','ت','ث','ج','چ','ح','خ','د'], '', $time);
			$time = str_replace(['ذ','ر','ز','س','ش','ط','ظ','ع','غ'], '', $time);
			$time = str_replace(['ف','ق','ک','گ','ل','م','ن','و','ه','ی','.','(',')','ء','ص','ض','‍'],'', $time);
			$search = array('۱','۲','۳','۴','۵','۶','۷','۸','۹','۰');
			$replace = array('1','2','3','4','5','6','7','8','9','0');
			$time = str_replace($search, $replace, $time);
			preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u','',$time);
			$time = (string)$time;
			$date = (string)$date;
			return trim($date) . " " . trim($time);
	}

}
