<?php

namespace App\Http\Controllers;

use App\Log;
use App\ConnectionLog;
use App\LogView;
use App\WebLog;
use App\Nas;
use App\ExclusionRule;
use App\ConnectionLogDetail;
use Illuminate\Http\Request;
use Log as llog;

class LogsController
{
	private $per_page = 20;
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

		$count =  WebLog::count();
		$pagination = $this->pagination($count, $page);
		$data =  WebLog::orderBy('visited_at', 'desc')
						 ->skip($offset)
					     ->take($per_page)
					     ->get();
		// change to persian time
		foreach ($data as $item) {
			$item['visited_at'] = $this->getPersianDate($item['visited_at']);
		}
		$ret = ["status" => "success" , "data" => $data];
		$ret = array_merge($ret, $pagination);
		return $ret;
	}


	public function showLogs($username, $page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page) - $per_page;

			$query = WebLog::where('username', '=', $username);
		$count = $query->count();
		$data = $query->orderBy('visited_at','desc')
						->skip($offset)
						->take($per_page)
						->get();

		foreach($data as $item){
			$item['visited_at'] = $this->getPersianDate($item['visited_at']);
		}

		$pagination = $this->pagination($count, $page);
		$ret = ["status" => "scuess", "data" => $data];
		$ret = array_merge($ret,$pagination);
		return $ret;
	}

	public function showLogsByDate($username,$startdate, $enddate, $page)
	{
		$per_page = $this->per_page;
		$offset = ($page * $per_page)  - $per_page;

		$query = WebLog::where('username', '=', $username)
						->where('visited_at', '>=', $startdate)
						->where('visited_at', '<=', $enddate);
		$count = $query->count();
		$data = $query->orderBy('visited_at','desc')
					  ->skip($offset)->take($per_page)->get();

		foreach($data as $item){
			$item['visited_at'] = $this->getPersianDate($item['visited_at']);
		}
		$pagination = $this->pagination($count, $page);
		$ret = ["status" => "success" , "data" => $data];
		$ret = array_merge($ret, $pagination);
		return $ret;
	}

	public function showNases()
	{
		$data = Nas::all();

		$ret = ["status" => "success", "data" => $data];
		return $ret;
	}

	public function addNas(Request $request)
	{
		if (! $request->has('nasip'))
		{
			$error = "nas ip is empty";
			return ["status" => "error", "msg" => $error];
		}
		else
		{
			// check for unique nases when inserting to db
			$nas_exists = Nas::where('nasip', '=', $request->input('nasip'))->first();
			if (!is_null($nas_exists))
			{
				return ["status" => "error", "msg" => "nas ip already exists"];
			}
		}

		if ($request->has('user') && !$request->has('password') )
		{
			$error = "password is empty";
			return ["status" => "error", "msg" => $error];
		}
		if ($request->has('password') && ! $request->has('username'))
		{
			$error = "username is empty";
			return ["status" => "error" ,"msg" => $error];
		}
		

		$nas = new Nas;
		$nas->nasip = $request->input('nasip');
		$nas->username = $request->has('username') ? $request->input('username') : '';
		$nas->password = $request->has('password') ?  $request->input('password') : '';
		$nas->description =$request->has('description') ? $request->input('description') : '';

		
		$nas->save();

		return ["status" => "success"];
	}

	public function deleteNas($id)
	{
		// check that there is at least on nas available on delete
		$nas_count  = Nas::count();
		if ($nas_count == 1)
		{
			return ["status" => "error" , "msg" => "there should be at least one nas available"];
		}
		// fixme : use exceptions here
		$nas = Nas::where('nas_id', '=', $id)->first();
		if ($nas)
		{
			$nas->delete();
			return ["status" => "success"];
		}

		// fixme: what went wrong here
		return ["status" => "error"];
	}
	//======================================================================
	public function getRules()
	{
		$all_rules = ExclusionRule::all();
		$ret = ["status" => "success" ,"data" => $all_rules];
		return $ret;
	}

	public function deleteRule($id)
	{
		$rule = ExclusionRule::where('exclusion_rules_id', '=', $id)->first();
		if ($rule)
		{
			$rule->delete();
			return ["status" => "scuess"];
		}

		//fixme: what went wrong
		return ["status" => "error"];
	}

	public function createRule(Request $request)
	{
		$msg = $this->validateRule($request);
		if($msg["haserror"])
		{
			return ["status" => "error", "msg" => $msg["error"]];
		}
		$rule_name = $request->input('exclusion_name');
		$rule_value  = $request->input('exclusion_value');

		$rule = new Exclusionrule;
		$rule->exclusion_name = $rule_name;
		$rule->exclusion_value = $rule_value;
		$rule->save();

		return ["status" => "success"];
	}

	private function validateRule(Request $request)
	{
		$make_message  = function($has_error, $error_msg){
			return ["haserror" => $has_error, "error" => $error_msg];
		};

		if ( ! $request->has('exclusion_name') )
		{
			$error = "rule name not provided";
			return $make_message(true, $error);
		}

		if (! $request->has('exclusion_value'))
		{
			$error = "rule value not provided";
			return $make_message(true, $erro);
		}

		$exclusion_name = $request->input('exclusion_name');
		$exclusion_value = $request->input('exclusion_value');

		if ( $exclusion_name === '')
		{
			$error = "rule name is empty";
			return $make_message(true, $error);
		}

		if ( $exclusion_value === '')
		{
			$error = "rule value is empty";
			return $make_message(true, $error);
		}

		$rule_exists = ExclusionRule::where('exclusion_value' , '=', $exclusion_value)->first();
		if ($rule_exists)
		{
			$error = "rule value already in db";
			return $make_message(true, $error);
		}

		return $make_message(false,"");
	}
	//======================================================================
	private function getPersianDate($datetime)
	{
		list($date_part, $time_part) = explode(' ',$datetime);
		$time_part = explode('.', $time_part);
		$time_part = isset($time_part[0]) ? $time_part[0] : '';
		$c_date = $this->convertdate(new \DateTime($date_part));
		return $c_date . " " . $time_part; 
	}

	private function pagination($count,$page)
	{
		$total = 0;
		if ($count != 0)
		{
			$total = (int)($count / $this->per_page);
			if( $count % $this->per_page > 0)
			{
				$total += 1;
			}
		}
		$hasNext = "true";
		if ($page == $total || $count == 0)
		{
			$hasNext = "false";
		}
		$hasPrev = "true";
		if ($page == 1 || $count == 0)
		{
			$hasPrev = "false";
		}
		return ["total" => $total, "hasNext" => $hasNext, "hasPrev" => $hasPrev];
	}

	private function convertdate($datetime,$separator="-")
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
			$date = trim($date);
			$date = str_replace('/', '-', $date);
			return $date;
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
