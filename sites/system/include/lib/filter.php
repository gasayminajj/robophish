<?php
use MaxieSystems\Containers;

abstract class Filter
{
	final public static function GetNumOrNull($val) { return is_numeric($val) ? $val : null; }
	final public static function GetFloatOrNull($val) { return (($val = str_replace(array(' ', ','), array('', '.'), trim($val))) !== '') && is_numeric($val) ? $val : null; }
	final public static function GetFloatOrZero($val) { return ($val = str_replace(array(' ', ','), array('', '.'), trim($val))) && is_numeric($val) ? $val : 0; }

	final public static function GetIntOrNull($val, $check = null)
	 {
		if('' !== ($val = trim($val)))
		 {
			$val = str_replace(' ', '', $val);
			if(is_numeric($val))
			 {
				$val = (int)$val;
				if(null === $check || self::CheckValue($val, $check)) return $val;
			 }
		 }
	 }

	final public static function GetIntOrZero($val, $check = null) { return (null === ($val = self::GetIntOrNull($val, $check))) ? 0 : $val; }

	final public static function NumFromGET($fld, $check = null) { return self::NumFrom($_GET, $fld, $check); }
	final public static function NumFromPOST($fld, $check = null) { return self::NumFrom($_POST, $fld, $check); }
	final public static function NumFromREQUEST($fld, $check = null) { return self::NumFrom($_REQUEST, $fld, $check); }
	final public static function NumExFromGET($fld) { return '' !== ($val = str_replace(' ', '', @$_GET[$fld])) && is_numeric($val) ? $val : null; }
	final public static function NumExFromPOST($fld) { return '' !== ($val = str_replace(' ', '', @$_POST[$fld])) && is_numeric($val) ? $val : null; }
	final public static function NumExFromREQUEST($fld) { return '' !== ($val = str_replace(' ', '', @$_REQUEST[$fld])) && is_numeric($val) ? $val : null; }
	final public static function FloatExFromGET($fld) { return '' !== ($val = str_replace(array(' ', ','), array('', '.'), @$_GET[$fld])) && is_numeric($val) ? $val : null; }
	final public static function FloatExFromPOST($fld) { return '' !== ($val = str_replace(array(' ', ','), array('', '.'), @$_POST[$fld])) && is_numeric($val) ? $val : null; }
	final public static function GetValidURLPart($val) { if('' !== $val && preg_match('/^[0-9a-z\-_\/]+$/i', $val)) return $val; }
	final public static function DateFromGET($field) { return empty($_GET[$field]) ? null : self::GetDateOrNull($_GET[$field]); }
	final public static function DateFromPOST($field) { return empty($_POST[$field]) ? null : self::GetDateOrNull($_POST[$field]); }
	final public static function TextAttribute($val) { return str_replace(array('"', '<', '>', "'"), array('&quot;', '&lt;', '&gt;', '&apos;'), $val); }
	final public static function GetNumArray($val) { return !empty($val) && is_array($val) ? array_filter($val, 'is_numeric') : []; }
	final public static function NumArrFromGET($fld) { if(!empty($_GET[$fld]) && is_array($_GET[$fld])) return array_filter($_GET[$fld], 'is_numeric'); }
	final public static function NumArrFromPOST($fld) { if(!empty($_POST[$fld]) && is_array($_POST[$fld])) return array_filter($_POST[$fld], 'is_numeric'); }

	final public static function GetEnum($v, array $allowed, array &$diff = null, array $o = null)
	 {
		$o = new Containers\Options($o, ['delimiter' => ['type' => 'string', 'value' => ','], 'keys' => ['type' => 'bool', 'value' => true]]);
		$v = "$v";
		if('' === $v) return ($diff = []);
		$v = explode($o->delimiter, $v);
		$v = array_combine($v, $v);
		$f = $o->keys ? 'array_diff_key' : 'array_diff';
		$diff = $f($v, $allowed);
		return $diff ? $f($v, $diff) : $v;
	 }

	final public static function GetDateOrNull($str)
	 {
		if(preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $str)) list($y, $m, $d) = explode('-', $str);
		else return null;
		return checkdate($m, $d, $y) ? "$y-$m-$d" : null;// checkdate(m, d, y)
	 }

	final public static function GetDateOrNullEx($str)
	 {
		if(preg_match('/^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}$/', $str)) list($d, $m, $y) = explode('.', $str);
		elseif(preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/', $str)) list($y, $m, $d) = explode('-', $str);
		else return null;
		return checkdate($m, $d, $y) ? "$y-$m-$d" : null;// checkdate(m, d, y)
	 }

	final public static function CopyFields(stdClass $dest = null, $src, ...$keys)
	 {
		if(null === $dest) $dest = new stdClass;
		if(is_object($src))
		 {
			foreach($keys as $key)
			 if(is_array($key)) foreach($key as $s => $d) $dest->$d = $src->$s;
			 else $dest->$key = $src->$key;
		 }
		else
		 {
			foreach($keys as $key)
			 if(is_array($key)) foreach($key as $s => $d) $dest->$d = $src[$s];
			 else $dest->$key = $src[$key];
		 }
		return $dest;
	 }

	final public static function CopyValues(array &$dest = null, $src, ...$keys)// метод почти дублирует self::GetValues - для сохранения производительности
	 {
		if(is_object($src))
		 {
			foreach($keys as $key)
			 if(is_array($key)) foreach($key as $s => $d) $dest[$d] = $src->$s;
			 else $dest[$key] = $src->$key;
		 }
		else
		 {
			foreach($keys as $key)
			 if(is_array($key)) foreach($key as $s => $d) $dest[$d] = $src[$s];
			 else $dest[$key] = $src[$key];
		 }
		return $dest;
	 }

	final public static function GetValues(array $src, ...$keys)// метод почти дублирует self::CopyValues - для сохранения производительности
	 {
		$dest = [];
		$m = 'copy_values';
		foreach($keys as $k => $key)
		 {
			if(0 === $k && is_bool($key))
			 {
				$m = $key ? 'copy_values_forced' : 'copy_values_skipped';
				continue;
			 }
			self::$m($dest, $src, $key);
		 }
		return $dest;
	 }

	final public static function GetValidPageId($var_name = 'id', $post = false)
	 {
		$src = $post ? $_POST : $_GET;
		return isset($src[$var_name]) && null !== ($val = self::GetValidURLPart($src[$var_name])) ? $val : null;
	 }

	final public static function InEnum($val, $default, ...$items) { return in_array($val, $items) ? $val : $default; }
	final public static function InEnumPOST($fld, $default, ...$items) { return isset($_POST[$fld]) ? self::InEnum($_POST[$fld], $default, ...$items) : $default; }

	final public static function Split($val, $div = '|', $filter = 'is_numeric', $sort_flags = SORT_NUMERIC)
	 {
		$val = explode($div, $val);
		if(!$val) return array();
		if(false !== $sort_flags) $val = array_unique($val, $sort_flags);
		return ($val = array_filter($val, $filter)) ? $val : array();
	 }

	final public static function GetNonEmptyLines($str, $request = false, array $o = null, &$request_data = null)
	 {
		if(false === $request || null === $request) $request_data = null;
		elseif('post' === $request)
		 {
			if(isset($_POST[$str]) && '' !== $_POST[$str]) $str = $request_data = $_POST[$str];
			else return null;
		 }
		elseif('get' === $request)
		 {
			if(isset($_GET[$str]) && '' !== $_GET[$str]) $str = $request_data = $_GET[$str];
			else return null;
		 }
		$r = array_map(isset($o['trim']) ? (false === $o['trim'] ? function($v){return trim($v, "\r");} : $o['trim']) : 'trim', explode("\n", $str));
		return empty($o['soft']) ? array_filter($r) : array_filter($r, function($v){return '' !== $v;});
	 }

	final public static function Tags($html, array $tags, $include = true)
	 {
		$doc = new DomDocument('1.0', 'utf-8');
		$doc->recover = $doc->validateOnParse = $doc->formatOutput = $doc->preserveWhiteSpace = false;
		@$doc->LoadHTML('<!DOCTYPE html>
<html>
	<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head>
	<body>'.$html.'</body>
</html>');
		$body = $doc->getElementsByTagName('body')->item(0);
		self::RemoveNodes($body, $tags, true);
		$html = $doc->SaveHTML();
		$html = str_replace(array('</body>', '</html>'), array('', ''), $html);
		return trim(substr($html, strpos($html, '<body>') + 6));
	 }

	final private static function NumFrom(array &$src, $fld, $check) { if(isset($src[$fld]) && '' !== ($val = trim($src[$fld])) && is_numeric($val) && (null === $check || self::CheckValue($val, $check))) return $val; }

	final private static function CheckValue($v, $check)
	 {
		switch("$check")
		 {
			case 'gt0': return $v > 0;
			case 'gte0': return $v >= 0;
			case 'lt0': return $v < 0;
			case 'lte0': return $v <= 0;
			default: throw new Exception("Invalid check name `$check`!");
		 }
	 }

	final private static function copy_values(array &$dest, array $src, $key)
	 {
		if(is_array($key)) foreach($key as $s => $d) $dest[$d] = $src[$s];
		else $dest[$key] = $src[$key];
	 }

	final private static function copy_values_forced(array &$dest, array $src, $key)
	 {
		if(is_array($key)) foreach($key as $s => $d) $dest[$d] = isset($src[$s]) ? $src[$s] : null;
		else $dest[$key] = isset($src[$key]) ? $src[$key] : null;
	 }

	final private static function copy_values_skipped(array &$dest, array $src, $key)
	 {
		if(is_array($key))
		 {
			foreach($key as $s => $d) if(array_key_exists($s, $src)) $dest[$d] = $src[$s];
		 }
		elseif(array_key_exists($key, $src)) $dest[$key] = $src[$key];
	 }

	final private static function RemoveNodes($node, array $tags, $include)
	 {
		$tags = array_fill_keys($tags, 1);
		for($i = $node->childNodes->length - 1; $i >= 0; --$i)
		 {
			$n = $node->childNodes->item($i);
			if(XML_ELEMENT_NODE == $n->nodeType)
			 {
				$in = isset($tags[strtolower($n->tagName)]);
				if($include) $in = !$in;
				if($in) $n->parentNode->removeChild($n);
				else self::RemoveNodes($n, $tags, $include);
			 }
		 }
	 }
}
?>