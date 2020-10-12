<?php
use \MaxieSystems as MS;
if('POST' === $_SERVER['REQUEST_METHOD'])
// if(!empty($_POST))
 {
	require_once(MSSE_LIB_DIR.'/filesystemstorage.php');
	$conf = new MS\FileSystemStorageReadonly('storage/fs_config.php', ['root' => MSSE_INC_DIR]);
	if(count($conf))
	 {
		$indexes2string = function(array $array, Closure $callback, $prefix = '', $level = 0) use(&$indexes2string){
			foreach($array as $k => $v)
			 {
				$s = $prefix || $level ? $prefix."[$k]" : $k;
				if(is_array($v)) $indexes2string($v, $callback, $s, $level + 1);
				else $callback($s, $v, $level);
			 }
		};
		$is_file = function(array $fld){
			if(isset($fld[2]) && isset($fld[2]['type']) && 'File' === $fld[2]['type'] && isset($_FILES[$fld[0]])) return true;
		};
		$is_group = function(array $fld, &$fld_name){
			$fld_name = null;
			if('[]' === substr($fld[0], -2))
			 {
				$fld_name = substr($fld[0], 0, -2);
				return true;
			 }
		};
		$rows = [];
		$hits = [];
		$__post = null;
		// ---
		MS\Config::RequireFile('http');
		$base_url = new MS\URL(Settings::staticGet('donor_url') ?: MS\Config::GetScheme().MS\Config::GetHost());
		$url = new MS\URL($_SERVER['REQUEST_URI'], $base_url);
		if(empty($_SERVER['HTTP_REFERER'])) $r_url = $url;
		else
		 {
			$r_url = new MS\URL($_SERVER['HTTP_REFERER'], $base_url);
			$r_url->scheme = $base_url->scheme;
			$r_url->host = $base_url->host;
		 }
		$req_hdrs = new MS\HTTPRequestHeaders(["Referer: $r_url",], function($k, $v) use($url){
			if('Origin' === $k) return $url->Crop(null, 'port');
			return true;
		});
		if('application/json' === $req_hdrs->content_type)
		 {
			$req_body = file_get_contents('php://input');
			if($json = json_decode($req_body))
			 {
				foreach($json->fields as $k => $v) $_POST[$k] = $v->value;
			 }
		 }
		// ---
		foreach($conf as $k => $row)
		 {
			$row->id = $k;
			$row->hits = 0;
			foreach($row->fields as $fld)
			 {
				if($is_file($fld)) ++$row->hits;
				elseif($is_group($fld, $fld_name)) ++$row->hits;
				elseif(isset($_POST[$fld[0]])) ++$row->hits;
				elseif(null === $__post)
				 {
					$__post = [];
					$indexes2string($_POST, function($k, $v, $level) use(&$__post, &$fld, $row){
						if($level) $__post[$k] = $v;
						if($k === $fld[0]) ++$row->hits;
					});
				 }
				elseif(isset($__post[$fld[0]])) ++$row->hits;
			 }
			if($row->hits)
			 {
				$rows[$k] = $row;
				if(isset($hits[$row->hits])) ++$hits[$row->hits];
				else $hits[$row->hits] = 1;
			 }
		 }
		$row = false;
		if($n_rows = count($rows))
		 {
			if(1 === $n_rows)
			 {
				foreach($rows as $k => $row);
			 }
			else
			 {
				// if(1 === count($hits)) ;// это означает, что количество совпадений у всех обработчиков равно.
				$row = array_reduce($rows, function($carry, $item){
					if(null === $carry) return $item;
					elseif($carry->hits === $item->hits) return count($carry->fields) === $carry->hits ? $carry : $item;
					else return $carry->hits > $item->hits ? $carry : $item;
				});
			 }
		 }
		if($row)
		 {
			if($__post)
			 {
				$delete_indexes = function(array &$array, Closure $callback, $prefix = '', $level = 0) use(&$delete_indexes){
					foreach($array as $k => $v)
					 {
						$s = $prefix || $level ? $prefix."[$k]" : $k;
						if(is_array($v)) $delete_indexes($array[$k], $callback, $s, $level + 1);
						elseif(false === $callback($s, $v, $level)) unset($array[$k]);
					 }
				};
				$delete_indexes($_POST, function($s, $v, $level) use($__post){
					if($level && isset($__post[$s])) return false;
				});
			 }
			$fs_id = "fs_$row->id";
			foreach($row->fields as $k => $fld)
			 {
				$k0 = $fs_id.'_'.$k;
				if($is_file($fld))
				 {
					$_FILES[$k0] = $_FILES[$fld[0]];
				 }
				elseif($is_group($fld, $fld_name) && isset($_POST[$fld_name]))
				 {
					if(is_array($_POST[$fld_name]))
					 {
						$s = implode(PHP_EOL, $_POST[$fld_name]);
						unset($_POST[$fld_name]);
						$_POST[$k0] = $s;
					 }
				 }
				elseif(isset($_POST[$fld[0]]))
				 {
					$s = $_POST[$fld[0]];
					unset($_POST[$fld[0]]);
					$_POST[$k0] = $s;
				 }
				elseif(isset($__post[$fld[0]]))
				 {
					$_POST[$k0] = $__post[$fld[0]];
				 }
			 }
			$_REQUEST['__fs_id'] = $_POST['__fs_id'] = $fs_id;
			$_GET['__dolly_action'] = 'handle_form';
			require_once(MSSE_INC_DIR.'/actions.php');
		 }
	 }
 }
// MS\HTTP::Redirect('/');
?>