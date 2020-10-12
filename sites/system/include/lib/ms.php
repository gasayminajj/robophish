<?php
use \MaxieSystems\DB;

abstract class ms
{
	final public static function BaseConvert($str, $frombase = 10, $tobase = 36)
	 {
		if(intval($frombase) != 10)
		 {
			$len = strlen($str);
			$q = 0;
			for($i = 0; $i < $len; ++$i)
			 {
				$r = base_convert($str[$i], $frombase, 10);
				$q = bcadd(bcmul($q, $frombase), $r);
			 }
		 }
		else $q = $str;
		if(intval($tobase) != 10)
		 {
			$s = '';
			while(bccomp($q, '0', 0) > 0)
			 {
				$r = intval(bcmod($q, $tobase));
				$s = base_convert($r, 10, $tobase).$s;
				$q = bcdiv($q, $tobase, 0);
			 }
		 }
		else $s = $q;
		return $s;
	 }

	final public static function AddSysMsg($group, $text) { Relation::Get('sys_message')->Insert(['group' => $group, 'text' => $text]); }

	final public static function ConvertZipError($status)
	 {
		switch($status)
		 {
			case ZIPARCHIVE::ER_EXISTS: return 'ZIPARCHIVE::ER_EXISTS';
			case ZIPARCHIVE::ER_INCONS: return 'ZIPARCHIVE::ER_INCONS';
			case ZIPARCHIVE::ER_INVAL: return 'ZIPARCHIVE::ER_INVAL';
			case ZIPARCHIVE::ER_MEMORY: return 'ZIPARCHIVE::ER_MEMORY';
			case ZIPARCHIVE::ER_NOENT: return 'ZIPARCHIVE::ER_NOENT';
			case ZIPARCHIVE::ER_NOZIP: return 'ZIPARCHIVE::ER_NOZIP';
			case ZIPARCHIVE::ER_OPEN: return 'ZIPARCHIVE::ER_OPEN';
			case ZIPARCHIVE::ER_READ: return 'ZIPARCHIVE::ER_READ';
			case ZIPARCHIVE::ER_SEEK: return 'ZIPARCHIVE::ER_SEEK';
		 }
	 }

	final public static function CheckParentId(&$parent, &$row, $parent_rel_name, $rel_name, $ext_key = 'parent_id')// это должно быть в наследнике MSDocument; шаблон документа
	 {
		$pid = $id = $parent = $row = false;
		if(($id = Filter::NumFromGET('id')) && ($row = Relation::Get($rel_name)->GetAssocById($id))) $pid = $row[$ext_key];
		else $pid = Filter::NumFromGET('pid');
		if($pid && ($parent = Relation::Get($parent_rel_name)->GetAssocById($pid))) return true;
	 }

	final public static function LogHTTPError($status)
	 {
		$data = array('status' => $status, 'https' => empty($_SERVER['HTTPS']) || 'off' == $_SERVER['HTTPS'] ? 0 : 1, 'host' => @$_SERVER['HTTP_HOST'], 'uri' => @$_SERVER['REQUEST_URI'], 'referer' => @$_SERVER['HTTP_REFERER'], 'remote_host' => @$_SERVER['REMOTE_HOST'], 'user_agent' => @$_SERVER['HTTP_USER_AGENT']);
		if($ip = \MaxieSystems\Config::GetIP()) $data['remote_addr'] = $ip;
		DB::Insert('sys_server_error', $data, ['date_time' => 'NOW()']);
	 }

	final public static function CompareDateDiff(array $val, $sign, $num, $unit)// !!!
	 {
		
	 }

	final public static function SQLDateTimeToArray($val, $with_time = true)
	 {
		if(!$val) return ['year' => 0, 'month' => 0, 'day' => 0, 'hour' => 0, 'minute' => 0, 'second' => 0];
		$val = explode(' ', $val);
		$date = explode('-', $val[0]);
		$time = $with_time ? explode(':', @$val[1]) : null;
		return array('year' => (int)$date[0], 'month' => (int)$date[1], 'day' => (int)$date[2], 'hour' => (int)@$time[0], 'minute' => (int)@$time[1], 'second' => (int)@$time[2]);
	 }

	final public static function GetDateDiffArray(array $start, array $end)
	 {
		$diff = array();
		foreach($start as $key => $value) $diff[$key] = $end[$key] - $start[$key];
		if($diff['second'] < 0)
		 {
			--$diff['minute'];
			$diff['second'] += 60;
		 }
		if($diff['minute'] < 0)
		 {
			--$diff['hour'];
			$diff['minute'] += 60;
		 }
		if($diff['hour'] < 0)
		 {
			--$diff['day'];
			$diff['hour'] += 24;
		 }
		if($diff['day'] < 0)
		 {
			--$diff['month'];
			switch($start['month'])
			 {
				case 2: $n = $start['year'] % 4 ? 29 : 28; break;
				case 4:
				case 6:
				case 9:
				case 11: $n = 30; break;
				case 1:
				case 3:
				case 5:
				case 7:
				case 8:
				case 10:
				case 12: $n = 31; break;
				default: throw new Exception($start['month'].' — invalid month number.');
			 }
			$diff['day'] += $n;
		 }
		if($diff['month'] < 0)
		 {
			--$diff['year'];
			$diff['month'] += 12;
		 }
		return $diff;
	 }

	final public static function GetDateDiff($start, $end = null, $with_time = true)
	 {
		return self::GetDateDiffArray(self::SQLDateTimeToArray($start, $with_time), self::SQLDateTimeToArray($end ? $end : date('Y-m-j G:i:s'), $with_time));
	 }

	final public static function FormatDateDiff(array $d, $with_time = true, $null_if_zero = true)
	 {
		$d = array_filter($d);
		$words = array('year' => array('год', 'года', 'лет'), 'month' => array('месяц', 'месяца', 'месяцев'), 'day' => array('день', 'дня', 'дней'),
					   'hour' => array('час', 'часа', 'часов'), 'minute' => array('минуту', 'минуты', 'минут'), 'second' => array('секунду', 'секунды', 'секунд'));
		if(!$d) return $null_if_zero ? null : '0 '.$words[$with_time ? 'second' : 'day'][2];
		$keys = array_keys($d);
		$first = $keys[0];
		$last = $keys[count($keys) - 1];
		$ret_val = '';
		foreach($d as $key => $value) $ret_val .= ($key == $first ? '' : ($key == $last ? ' и ' : ' ')).$value.' '.Format::GetAmountStr($value, $words[$key][0], $words[$key][1], $words[$key][2]);
		return $ret_val;
	 }

	final public static function GetDateDiffF($start, $end = null, $with_time = true, $null_if_zero = true)
	 {
		return self::FormatDateDiff(self::GetDateDiff($start, $end, $with_time));
	 }

	final public static function str_replace_first($search, $replace, $subject)
	 {
		$pos = strpos($subject, $search);
		if($pos !== false) $subject = substr_replace($subject, $replace, $pos, strlen($search));
		return $subject;
	 }

	final public static function RandomStr($min, $max, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	 {
		if(is_int($keyspace))
		 {
			$k = $keyspace;
			$keyspace = '';
			if($k & 0b0001) $keyspace .= '0123456789';
			if($k & 0b0010) $keyspace .= 'abcdefghijklmnopqrstuvwxyz';
			if($k & 0b0100) $keyspace .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			if(!$keyspace) throw new Exception('Empty keyspace!');
		 }
		$length = mt_rand($min, $max);
		$str = '';
		$m = mb_strlen($keyspace, '8bit') - 1;
		for($i = 0; $i < $length; ++$i) $str .= $keyspace[mt_rand(0, $m)];
		return $str;
	 }

	final public static function Random($len)
	 {
		if(@is_readable('/dev/urandom'))
		 {
			$f = fopen('/dev/urandom', 'r');
			$urandom = fread($f, $len);
			fclose($f);
		 }
		$return = '';
		for($i = 0; $i < $len; ++$i)
		 {
			$rand = 33 + (isset($urandom) ? ord($urandom[$i]) : mt_rand()) % 94;
			$return .= chr($rand);
		 }
		return $return;
	 }

	final public static function OrdUTF8($c, &$bytes = null)
	 {
		$len = strlen($c);
		$bytes = 0;
		$h = ord($c{0});
		if($h <= 0x7F)
		 {
			$bytes = 1;
			return $h;
		 }
		elseif($h < 0xC2) return false;
		elseif($h <= 0xDF)
		 {
			$bytes = 2;
			return ($h & 0x1F) <<  6 | (ord($c{1}) & 0x3F);
		 }
		elseif($h <= 0xEF)
		 {
			$bytes = 3;
			return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6 | (ord($c{2}) & 0x3F);
		 }
		elseif($h <= 0xF4)
		 {
			$bytes = 4;
			return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12 | (ord($c{2}) & 0x3F) << 6 | (ord($c{3}) & 0x3F);
		 }
		else return false;
	 }

	final public static function GetStrStat($str, $enc = 'UTF-8')
	 {
		$ret_val = array('length' => mb_strlen($str, $enc), 'count' => 0, 'digit' => 0, 'letter' => 0, 'other' => 0);
		$chars = array();
		for($i = 0; $i < $ret_val['length']; ++$i)
		 {
			$c = mb_substr($str, $i, 1, 'UTF-8');
			if(isset($chars[$c])) ++$chars[$c];
			else $chars[$c] = 1;
			if(is_numeric($c)) ++$ret_val['digit'];
			else
			 {
				$code = self::OrdUTF8($c);
				if(($code >= 32 && $code <= 47) || ($code >= 58 && $code <= 64) || ($code >= 91 && $code <= 96) || ($code >= 123 && $code <= 126)) ++$ret_val['other'];
				else ++$ret_val['letter'];
			 }
		 }
		$ret_val['count'] = count($chars);
		return $ret_val;
	 }

	final public static function UpdatePosition($tbl_name, $ids, $condition = null, array $params = null, $order_by = null, $key_fld = 'id')
	 {
		$item_count = count($ids);
		$all_count = DB::COUNT($tbl_name, $condition, $params);
		$counter = 1;
		if($item_count == $all_count) foreach($ids as $id) DB::Update($tbl_name, ['position' => $counter++, '~id' => $id], "`$key_fld` = :id");
		else
		 {
			if(!$order_by) $order_by = '`position` ASC, `id` DESC';
			$res = DB::Select($tbl_name, "`$key_fld`", $condition, $params, $order_by);
			$n_rows = count($res);
			$tmp = null;
			foreach($res as $row)
			 {
				$id = $row->$key_fld;
				if(in_array($id, $ids))
				 {
					if(null === $tmp) $tmp = $counter;
					$position = $tmp + array_search($id, $ids);
				 }
				else
				 {
					$position = $counter;
					$tmp = null;
				 }
				++$counter;
				DB::Update($tbl_name, ['position' => $position, '~id' => $id], "`$key_fld` = :id");
			 }
		 }
	 }

	final public static function UpdatePos($tbl_name, $cond = null, array $params = null, $order_by = null, $key_fld = null, $ext_key_fld = null, $filter = 'is_numeric')
	 {
		if(empty($_POST['order'])) return null;
		else
		 {
			if(!$key_fld) $key_fld = 'id';
			$ids = explode('|', $_POST['order']);
			if(!is_array($ids)) return false;
			$ids = array_filter($ids, $filter);
			if(!$ids) return false;
			if(true === $cond)
			 {
				if(!$ext_key_fld) $ext_key_fld = 'parent_id';
				$f = "`$ext_key_fld`";
				$pid = DB::Select($tbl_name, $f, "`$key_fld` = :id", ['id' => reset($ids)])->Fetch()->$ext_key_fld;
				if(null === $pid) $cond = "$f IS NULL";
				 {
					$cond = "$f = :pid";
					$params = ['pid' => $pid];
				 }
			 }
			self::UpdatePosition($tbl_name, $ids, $cond, $params, $order_by, $key_fld);
			\MaxieSystems\Document::SendJSON(null, 'Порядок сохранён.');
		 }
	 }

	final public static function Percent($count, $all_count, $precision = 2) { return $all_count ? round($count / $all_count * 100, $precision) : 0; }

	final public static function ConcatIfNotEmpty($val, $str, $callback = null, $method = null)
	 {
		if($val)
		 if($callback)
		  if(func_num_args() > 4)
		   {
			$args = func_get_args();
			return call_user_func_array($method ? array($callback, $method) : $callback, $val, array_slice($args, 4)).$str;
		   }
		  else return call_user_func($method ? array($callback, $method) : $callback, $val).$str;
		 else return $val.$str;
		else return $val;
	 }

	final public static function ConcatIfNotEmptyR(&$val, $str, $callback = null, $method = null) { return $val = self::ConcatIfNotEmpty($val, $str, $callback, $method); }

	final public static function TruncateStrTo($str, $char) { return (false !== ($pos = strpos($str, $char))) ? substr($str, 0, $pos) : $str; }

 public static function GetExceptionAsXml($e)// check it!!!
  {
	$ret_val = '<data status="exception"><class>'.get_class($e).'</class><message>'.$e->getMessage().'</message><code>'.$e->getCode().'</code><filename>'.$e->getFile().'</filename><line>'.$e->getLine().'</line><trace>';
	$trace = $e->getTrace();
	foreach($trace as $call)
	 {
		$ret_val .= '<call>';
		foreach($call as $key => $param)
		 {
			$ret_val .= '<'.$key.'>';
			if('args' == $key) foreach($param as $arg) $ret_val .= '<arg>'.$arg.'</arg>';
			else $ret_val .= $param;
			$ret_val .= '</'.$key.'>';
		 }
		$ret_val .= '</call>';
	 }
	return $ret_val.'</trace></data>';
  }

	final public static function GetFloatAmountStr($num, $str1, $str2, $str3) { return $num - intval($num) ? $str3 : Format::GetAmountStr($num, $str1, $str2, $str3); }

 public static function Quotes2Arrows($str) // ???
  {
	$pos = 0;
	while(($pos = strpos($str, '"', $pos)) !== false)
	 {
		$str = substr_replace($str, '&laquo;', $pos, 1);
		if(($pos = strpos($str, '"', $pos)) !== false) $str = substr_replace($str, '&raquo;', $pos, 1);
		else break;
	 }
	return $str;
  }

	final public static function Dash2Ndash($str) { return preg_replace(array('/[\t\s]\-[\t\s]/', '/$\-[\t\s]/', '/[\t\s]\-/', '/\-[\t\s]/'), array('&nbsp;&ndash;&nbsp;', '&ndash;&nbsp;', '&nbsp;&ndash;&nbsp;', '&nbsp;&ndash;&nbsp;'), $str); }

	final public static function DateTimeStr($date = null, $div = ', ')// localization needed
	 {
		$month_names = array('января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		if(null === $date) $date = time();
		if(is_numeric($date))
		 {
			$d_arr = getdate($date);
			return $d_arr['mday'].' '.$month_names[$d_arr['mon'] - 1].' '.$d_arr['year'].' '.($d_arr['hours'] < 10 ? 0 : '').($div ? $div : ' ').$d_arr['hours'].':'.($d_arr['minutes'] < 10 ? 0 : '').$d_arr['minutes'];
		 }
		elseif($dt_arr = explode(' ', $date))
		 {
			$d_arr = explode('-', $dt_arr[0]);
			$t_arr = explode(':', $dt_arr[1]);
			$ret_val = '';
			if($d_arr[2] != '00') $ret_val .= $d_arr[2];
			if($d_arr[1] != '00') $ret_val .= ($ret_val ? ' ' : '').$month_names[(int)$d_arr[1]];
			if($d_arr[0] != '0000') $ret_val .= ($ret_val ? ' ' : '').$d_arr[0];
			$ret_val .= ($div ? $div : ' ').$t_arr[0].':'.$t_arr[1];
			return $ret_val ? $ret_val : '<em>&mdash; не определена &mdash;</em>';
		 }
		else return '';
	 }

	final public static function DateStr($date, $end = '')
	 {
		$month_names = array(1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		if(is_numeric($date))
		 {
			$d_arr = getdate($date);
			return $d_arr['mday'].' '.$month_names[$d_arr['mon']].' '.$d_arr['year'].' года';
		 }
		elseif($d_arr = explode('-', $date))
		 {
			$ret_val = '';
			if($d_arr[2] == '00') $month_names = array(1 => 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
			else $ret_val = (int)$d_arr[2];
			if($d_arr[1] != '00') $ret_val .= ($ret_val ? ' ' : '').$month_names[(int)$d_arr[1]];
			if($d_arr[0] != '0000') $ret_val .= ($ret_val ? ' ' : '').$d_arr[0];
			return $ret_val ? $ret_val : '<em>&mdash; не определена &mdash;</em>';
		 }
		else return '';
	 }

	final public static function GetStrBeginning($str, $count, $divider = ' ', $ending = '...')
	 {
		return (strlen($str) > $count && ($pos = strpos($str, $divider, $count)) !== false) ? substr($str, 0, $pos).$ending : $str;
	 }

	final public static function LinkEndedText($text, $link)
	 {
		$exploded_text = explode(' ', $text);
		$word_count = 0;
		for($j = count($exploded_text) - 1; $j >= 0; --$j) if(strlen($exploded_text[$j]) > 4 && ++$word_count > 3) break;
		$res_text = '';
		for($k = 0; $k < count($exploded_text); ++$k)
		 $res_text .= ($k == $j + 1 ? '<a href="'.$link.'">' : '').$exploded_text[$k].($k == count($exploded_text) - 1 ? '' : ' ');
		return $res_text.'</a>';
	 }

	final private function __construct() {}
}
?>