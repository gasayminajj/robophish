<?php
namespace MaxieSystems;

require_once(MSSE_LIB_DIR.'/traits.php');
require_once(MSSE_LIB_DIR.'/containers.php');

class EHTTP extends \Exception {}
	class EHTTPCURL extends EHTTP {}
	class EHTTPTooManyRedirects extends EHTTP {}

abstract class AbstractHTTPHeader
{
	public function __construct($name, $value, $header)
	 {
		$this->name = $name;
		$this->value = $value;
		$this->header = $header;
	 }

	final public function __get($name)
	 {
		if('name' === $name) return $this->name;
		elseif('lower_name' === $name)
		 {
			if(null === $this->lower_name) $this->lower_name = strtolower($this->name);
			return $this->lower_name;
		 }
		elseif('value' === $name) return $this->value;
		throw new \Exception("Undefined property: $name");
	 }

	final public function __toString() { return $this->header; }
	final public function __debugInfo() { return ['header' => $this->header]; }

	private $name;
	private $lower_name = null;
	private $value;
	private $header;
}

class HTTPHeader extends AbstractHTTPHeader
{
	final public function __construct($header)
	 {
		$value = explode(':', $header, 2);
		if(2 === count($value))
		 {
			$name = ucwords(strtolower($value[0]), '-');
			$value = ltrim($value[1]);
			parent::__construct($name, $value, "$name: $value");
		 }
		else parent::__construct('', $value[0], $value[0]);
	 }
}

class HTTPHeaderParsed extends AbstractHTTPHeader
{
	final public function __construct($name, $value)
	 {
		parent::__construct($name, $value, "$name: $value");
	 }
}

abstract class AbstractHTTPHeaders implements \Iterator, \JsonSerializable, \Countable
{
	abstract protected function Init();

	final public function __get($name)
	 {
		if(isset($this->index[$name])) return $this->index[$name];
		$name = strtolower($name);
		$n0 = str_replace('-', '_', $name);
		$n1 = str_replace('_', '-', $name);
		$name = ucwords($n1, '-');
		if(isset($this->index[$name])) return $this->index[$name];
		if(isset($this->index[$n0])) return $this->index[$n0];
		if(isset($this->index[$n1])) return $this->index[$n1];
		$header = [];
		foreach($this as $k => $v) if($k === $name) $header[] = $v->value;
		switch(count($header))
		 {
			case 0: $this->index[$name] = false; break;
			case 1: $this->index[$name] = $header[0]; break;
			default: $this->index[$name] = $header;
		 }
		$this->index[$n0] = &$this->index[$name];
		$this->index[$n1] = &$this->index[$name];
		return $this->index[$name];
	 }

	final public function Send($filter = null)
	 {
		if($filter) return $this->Traverse($filter, function($n, $v, $hdr){ header($hdr, true); });
		$ret_val = [];
		foreach($this as $h)
		 {
			$hdr = "$h";
			header($hdr, true);
			$ret_val[] = $hdr;
		 }
		return $ret_val;
	 }

	final public function Traverse($filter, $callback, ...$args)
	 {
		$ret_val = [];
		if(is_callable($filter)) $f = $filter;
		elseif(is_array($filter))
		 {
			$f = function($k, $v) use(&$filter){
				if(isset($filter[$k]) || (($k = $v->lower_name) && isset($filter[$k])))
				 {
					if(is_string($filter[$k])) return '' === $filter[$k] ? $v->value : $filter[$k];
					elseif(is_callable($filter[$k]))
					 {
						$hdr = call_user_func($filter[$k], $v->name, $v->value);
						if(true === $hdr) return $v->value;
						elseif(null === $hdr || false === $hdr) return;
						else return $hdr;
					 }
					else return $v->value;
				 }
			};
		 }
		else $f = false;
		if($f)
		 {
			if($callback)
			 {
				foreach($this as $k => $v)
				 {
					if($this->MkHdr($f, $k, $v, $hdr))
					 {
						$callback($k, is_string($v) ? $v : $v->value, $hdr, ...$args);
						$ret_val[] = $hdr;
					 }
				 }
			 }
			else
			 {
				foreach($this as $k => $v) if($this->MkHdr($f, $k, $v, $hdr)) $ret_val[] = $hdr;
			 }
		 }
		elseif(null === $filter)
		 {
			foreach($this as $v)
			 {
				$hdr = "$v";
				$callback($v->name, $v->value, $hdr, ...$args);
				$ret_val[] = $hdr;
			 }
		 }
		else throw new \Exception('Invalid filter: '.Config::GetVarType($filter));
		return $ret_val;
	 }

	final private function MkHdr(&$f, $k, AbstractHTTPHeader &$v, &$hdr)
	 {
		$r = $f($k, $v);
		if(true === $r)
		 {
			$hdr = "$v";
			return true;
		 }
		elseif(null === $r || false === $r)
		 {
			$hdr = null;
			return false;
		 }
		else
		 {
			$v = "$r";
			$hdr = '' === $k ? $v : "$k: $v";
			return true;
		 }
	 }

	final public function ToArray()
	 {
		if(null === $this->headers) $this->Init();
		return $this->headers ? array_map(function($v){return "$v";}, $this->headers) : [];
	 }

	final public function count()
	 {
		if(null === $this->headers) $this->Init();
		return count($this->headers);
	 }

	final public function rewind()
	 {
		if(null === $this->headers) $this->Init();
		reset($this->headers);
	 }

	final public function current() { if(false !== ($v = current($this->headers))) return $v; }
	final public function key() { if(false !== ($v = current($this->headers))) return $v->name; }
	final public function next() { next($this->headers); }
	final public function valid() { return null !== key($this->headers); }

	final public function __set($name, $value)
	 {
		$name = ucwords(str_replace('_', '-', strtolower($name)), '-');
		$this->headers[$name] = new HTTPHeaderParsed($name, $value);
	 }

	final public function __unset($name) { throw new \Exception('not implemented yet...'); }
	final public function __isset($name) { throw new \Exception('not implemented yet...'); }
	final public function jsonSerialize() { return $this->ToArray(); }
	final public function __debugInfo() { return $this->ToArray(); }
	final public function __toString() { return implode("\r\n", $this->ToArray()); }

	protected $headers = null;

	private $index = [];
}

class HTTPHeaders extends AbstractHTTPHeaders
{
	public function __construct(array $headers)
	 {
		$this->data = $headers;
	 }

	final protected function Init()
	 {
		$this->headers = [];
		foreach($this->data as $k => $v)
		 {
			$v = new HTTPHeader($v);
			// if(isset($this->headers[$v->name])) ;
			$this->headers[$v->name] = $v;
		 }
	 }

	private $data;
}

class HTTPRequestHeaders extends AbstractHTTPHeaders
{
	final public function __construct(array $headers, $filter)
	 {
		$this->add_hdrs = new \stdClass;
		$this->add_hdrs->num = $headers;
		$this->add_hdrs->idx = [];
		$this->filter = $filter;
	 }

	final protected function Init()
	 {
		$this->headers = [];
		foreach($this->add_hdrs->num as $k => $v)
		 {
			$v = new HTTPHeader($v);
			if(isset($this->add_hdrs->idx[$v->name])) throw new \Exception('not implemented yet...');
			else $this->add_hdrs->idx[$v->name] = $v;
		 }
		foreach($_SERVER as $k => $v)
		 {
			if(isset(self::$skip[$k])) continue;
			elseif($k = $this->GetHeaderName($k))
			 {
				if(isset($this->add_hdrs->idx[$k]))
				 {
					// if(isset($this->headers[$k])) ;
					$this->headers[$k] = $this->add_hdrs->idx[$k];
					unset($this->add_hdrs->idx[$k]);
				 }
				else
				 {
					if(null !== $this->filter)
					 {
						$r = call_user_func($this->filter, $k, $v);
						if(null === $r || false === $r) continue;
						elseif(true !== $r) $v = "$r";
					 }
					// if(isset($this->headers[$k])) ;
					$this->headers[$k] = new HTTPHeaderParsed($k, $v);
				 }
			 }
		 }
		foreach($this->add_hdrs->idx as $k => $v) $this->headers[] = $v;
	 }

	final private function GetHeaderName($k)
	 {
		static $hdrs = ['CONTENT_TYPE' => 'Content-Type', /* 'CONTENT_LENGTH' => 'Content-Length' */];
		if(isset($hdrs[$k])) return $hdrs[$k];
		elseif('HTTP_' === substr($k, 0, 5)) return ucwords(str_replace('_', '-', strtolower(substr($k, 5))), '-');
	 }

	private $filter = null;
	private $add_hdrs;

	private static $skip = ['HTTP_HOST' => 1, 'HTTP_COOKIE' => 1, 'HTTP_REFERER' => 1, 'HTTP_X_FORWARDED_FOR' => 1, 'HTTP_X_FORWARDED_PROTO' => 1];
}

class HTTPResponse implements \JsonSerializable
{
	final public function __construct($result, $hsize, array $data, array $headers, array $cookie)
	 {
		if($hsize)
		 {
			$this->headers_source = substr($result, 0, $hsize);
			$this->value = substr($result, $hsize);
		 }
		else $this->value = "$result";
		$this->data = $data;
		$this->headers = new HTTPHeaders($headers);
		$this->cookie = $cookie;
	 }

	final public function __get($name)
	 {
		if('url' === $name)
		 {
			if(null === $this->url) $this->url = new URL($this->data['url']);
			return $this->url;
		 }
		if('code' === $name) return $this->data['http_code'];
		if('mime' === $name || 'charset' === $name)
		 {
			if(null === $this->content_type)
			 {
				$this->content_type = new \stdClass;
				$this->content_type->mime = $this->content_type->charset = null;
				if(empty($this->data['content_type'])) return;
				$a = explode(';', $this->data['content_type'], 2);
				$this->content_type->mime = strtolower($a[0]);
				if(!empty($a[1]))
				 {
					$s = 'charset=';
					if(false !== ($pos = strpos($a[1], $s))) $this->content_type->charset = strtolower(trim(substr($a[1], $pos + strlen($s)), ' \'"'));
				 }
			 }
			return $this->content_type->$name;
		 }
		if('content_type' === $name) return $this->data['content_type'];
		if('cookie' === $name) return $this->cookie;
		if('headers' === $name) return $this->headers;
		if('headers_source' === $name) return $this->headers_source;
		throw new \Exception("Undefined property: $name");
	 }

	final public function __set($name, $value)
	 {
		throw new \Exception('Read only!');
	 }

	final public function __unset($name)
	 {
		throw new \Exception('Read only!');
	 }

	final public function __toString() { return "$this->value"; }
	final public function __debugInfo() { return ['url' => (string)$this->__get('url'), 'code' => $this->__get('code'), 'content_type' => $this->__get('content_type'), 'cookie' => $this->__get('cookie')]; }
	final public function jsonSerialize() { return ['data' => $this->data, 'headers' => $this->headers]; }

	private $value;
	private $headers_source = null;
	private $data;
	private $content_type = null;
	private $cookie;
	private $headers;
	private $url = null;
}

class HTTP
{
	use TOptions;

	final public function __construct(array $o = null)
	 {
		$this->AddOptionsMeta([
			'basic' => [],
			'ssl_verifypeer' => ['type' => 'bool', 'value' => true],
			'ssl_verifyhost' => ['type' => 'bool', 'value' => true],
			'user_agent' => ['type' => 'string,true', 'value' => ''],
			'referer' => ['type' => 'string', 'value' => ''],
			'accept_encoding' => ['type' => 'string,bool', 'value' => false],
			'cookie_file' => ['type' => 'string,array', 'value' => ''],
			'follow_location' => ['type' => 'int,gte0', 'value' => 0],
			'on_redirect' => ['type' => 'callable,null'],
			'connect_timeout' => ['type' => 'int,gte0', 'value' => 5],
			'proxy' => ['type' => 'iterator,array,null'],
			'e_too_many_redirects' => ['type' => 'bool', 'value' => true],
			'data' => ['type' => 'iterator,array', 'value' => []],
			'cookie' => ['type' => 'iterator,array', 'value' => []],
			'headers' => ['type' => 'iterator,array', 'value' => []],
		]);
		if($o) $this->SetOptionsData($o);
	 }

	final public function GET($url, array $data = [], array $o = null)
	 {
		if(($d = $this->GetOption('data')) && 0 < count($d)) $data = $this->MergeData($d, $data);
		if($data) $url .= '?'.http_build_query($data);
		$ch = $this->Init($url, ...$this->PrepareRequestArgs($o));
		return $this->Exec($ch, __FUNCTION__, $o);
	 }

	final public function POST($url, array $data = [], array $o = null)
	 {
		$ch = $this->Init($url, ...$this->PrepareRequestArgs($o));
		curl_setopt($ch, CURLOPT_POST, 1);
		if(($d = $this->GetOption('data')) && 0 < count($d)) $data = $this->MergeData($d, $data);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data ? http_build_query($data) : '');
		return $this->Exec($ch, __FUNCTION__, $o);
	 }

	final public function POSTJSON($url, $data, array $o = null)
	 {
		return $this->POSTJSONString($url, json_encode($data), $o);
	 }

	final public function POSTJSONString($url, $data, array $o = null)
	 {
		$data_string = "$data";
		$ch = $this->Init($url, ...$this->PrepareRequestArgs($o, 'application/json', strlen($data_string)));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		return $this->Exec($ch, __FUNCTION__, $o);
	 }

	final public function Resend($url, array $o = null)
	 {
		$o = new Containers\Options($o, ['data_filter' => ['type' => 'callable,null'], 'headers' => ['type' => 'array', 'value' => []], 'headers_filter' => ['type' => 'callable,null'], 'request_method' => ['type' => 'string,len_gt0', 'value' => $_SERVER['REQUEST_METHOD']]]);
		if('GET' === $o->request_method)
		 {
			$data = $_GET;
			if($o->data_filter) $data = array_filter($data, $o->data_filter, ARRAY_FILTER_USE_BOTH);
			return $this->GET($url, $data, ['headers' => new HTTPRequestHeaders($o->headers, $o->headers_filter)]);
		 }
		throw new \Exception('Undefined request method: '.$o->request_method);
	 }

	/* final public static function GetHostByAddr($ip, $dns, $timeout=1000)
	 {
		// random transaction number (for routers etc to get the reply back)
		$data = rand(0, 99);
		// trim it to 2 bytes
		$data = substr($data, 0, 2);
		// request header
		$data .= "\1\0\0\1\0\0\0\0\0\0";
		// split IP up
		$bits = explode(".", $ip);
		// error checking
		if (count($bits) != 4) return "ERROR";
		// there is probably a better way to do this bit...
		// loop through each segment
		for ($x=3; $x>=0; $x--)
		{
			// needs a byte to indicate the length of each segment of the request
			switch (strlen($bits[$x]))
			{
				case 1: // 1 byte long segment
					$data .= "\1"; break;
				case 2: // 2 byte long segment
					$data .= "\2"; break;
				case 3: // 3 byte long segment
					$data .= "\3"; break;
				default: // segment is too big, invalid IP
					return "INVALID";
			}
			// and the segment itself
			$data .= $bits[$x];
		}
		// and the final bit of the request
		$data .= "\7in-addr\4arpa\0\0\x0C\0\1";
		// create UDP socket
		$handle = @fsockopen("udp://$dns", 53);
		// send our request (and store request size so we can cheat later)
		$requestsize=@fwrite($handle, $data);

		@socket_set_timeout($handle, $timeout - $timeout%1000, $timeout%1000);
		// hope we get a reply
		$response = @fread($handle, 1000);
		@fclose($handle);
		if ($response == "")
			return $ip;
		// find the response type
		$type = @unpack("s", substr($response, $requestsize+2));
		if ($type[1] == 0x0C00)  // answer
		{
			// set up our variables
			$host="";
			$len = 0;
			// set our pointer at the beginning of the hostname
			// uses the request size from earlier rather than work it out
			$position=$requestsize+12;
			// reconstruct hostname
			do
			{
				// get segment size
				$len = unpack("c", substr($response, $position));
				// null terminated string, so length 0 = finished
				if ($len[1] == 0)
					// return the hostname, without the trailing .
					return substr($host, 0, strlen($host) -1);
				// add segment to our host
				$host .= substr($response, $position+1, $len[1]) . ".";
				// move pointer on to the next segment
				$position += $len[1] + 1;
			}
			while ($len != 0);
			// error - return the hostname we constructed (without the . on the end)
			return $ip;
		}
		return $ip;
	} */

	final public static function Redirect($url = false, $status = 302, $this_host = true)
	 {
		$host = $this_host ? Config::GetProtocol().Config::GetHost() : '';
		header('Location: '.$host.($url ?: $_SERVER['PHP_SELF']), true, $status);
		exit();
	 }

	final public static function Status($status, array $o = null)
	 {
		$o = new Containers\Options($o, [
			'exit' => ['type' => 'bool', 'value' => true],
			'protocol' => ['type' => 'string', 'value' => @$_SERVER['SERVER_PROTOCOL'] ?: 'HTTP/1.1'],// SERVER_PROTOCOL в некоторых случаях может быть не задан
		]);
		static $list = [
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			400 => 'Bad Request',// The server cannot or will not process the request due to an apparent client error (e.g., malformed request syntax, invalid request message framing, or deceptive request routing).
			401 => 'Unauthorized', // (RFC 7235) Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided. The response must include a WWW-Authenticate header field containing a challenge applicable to the requested resource. See Basic access authentication and Digest access authentication. 401 semantically means "unauthenticated", i.e. the user does not have the necessary credentials.
			402 => 'Payment Required', // Reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme, but that has not happened, and this code is not usually used. Google Developers API uses this status if a particular developer has exceeded the daily limit on requests.
			403 => 'Forbidden',// The request was a valid request, but the server is refusing to respond to it. 403 error semantically means "unauthorized", i.e. the user does not have the necessary permissions for the resource.
			404 => 'Not Found',// The requested resource could not be found but may be available in the future. Subsequent requests by the client are permissible.
			405 => 'Method Not Allowed',// A request method is not supported for the requested resource; for example, a GET request on a form which requires data to be presented via POST, or a PUT request on a read-only resource.
			406 => 'Not Acceptable',// The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request.
			500 => 'Internal Server Error',// A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
			501 => 'Not Implemented',// The server either does not recognize the request method, or it lacks the ability to fulfil the request. Usually this implies future availability (e.g., a new feature of a web-service API).
			503 => 'Service Unavailable',// The server is currently unavailable (because it is overloaded or down for maintenance). Generally, this is a temporary state.
			505 => 'HTTP Version Not Supported',// The server does not support the HTTP protocol version used in the request.
		];
		header("$o->protocol $status $list[$status]");
		if($o->exit) exit();
	 }

	final public static function GetClassMeta($name) { return $name ? self::$meta[$name] : self::$meta; }

	final private function FollowLocation($method, HTTPResponse &$r, $max, Containers\Data $o)
	 {
		static $n = 0;
		static $redirects = [301 => true, 302 => true, 303 => true, 307 => true, 308 => true];//303 ВСЕГДА меняет метод на GET. 307 НИКОГДА не меняет метод.
		if(isset($redirects[$r->code]))
		 {
			++$n;
			if($n > $max)
			 {
				if($this->GetOption('e_too_many_redirects')) throw new EHTTPTooManyRedirects("Maximum ($max) redirects followed");
				else HTTP::Status(500);
			 }
			if(!$r->headers->Location) throw new EHTTPCURL('Location header is undefined.');
			$url_1 = new URL($r->headers->Location, $r->url);
			if(($c = null === $o->on_redirect ? $this->GetOption('on_redirect') : $o->on_redirect) && (false === call_user_func($c, $r, $url_1, $n, $this))) return ($n = 0);
			if(303 === $r->code) $method = 'GET';
			$r = $this->$method("$url_1", [], $o->ToArray());
		 }
		else $n = 0;
	 }

	final private function Init($url, AbstractHTTPHeaders $headers = null, array $cookie = null, $content_type = false, $content_length = false)
	 {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if($o = $this->GetOption('basic'))
		 {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $o);
		 }
		if(!$this->GetOption('ssl_verifypeer')) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if(!$this->GetOption('ssl_verifyhost')) curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_HEADER, true);
		$this->cookies = $this->response_headers_array = [];
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $header_line){
			if(0 === strncasecmp($header_line, 'set-cookie:', 11)) $this->cookies[] = new HTTPCookie(substr($header_line, 11));
			if($s = trim($header_line)) $this->response_headers_array[] = $s;
			return strlen($header_line);
		});
		// if($o = $this->GetOption('referer')) curl_setopt($ch, CURLOPT_AUTOREFERER, $o);//TRUE to automatically set the Referer: field in requests where it follows a Location: redirect.
		if($o = $this->GetOption('user_agent'))
		 {
			if(true === $o) $o = empty($_SERVER['HTTP_USER_AGENT']) ? 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36' : $_SERVER['HTTP_USER_AGENT'];
			if($o) curl_setopt($ch, CURLOPT_USERAGENT, $o);
		 }
		if($o = $this->GetOption('referer')) curl_setopt($ch, CURLOPT_REFERER, $o);
		if($o = $this->GetOption('accept_encoding'))
		 {
			if(true === $o) $o = empty($_SERVER['HTTP_ACCEPT_ENCODING']) ? false : $_SERVER['HTTP_ACCEPT_ENCODING'];
			if($o) curl_setopt($ch, CURLOPT_ENCODING, $o);
		 }
		if($o = $this->GetOption('cookie_file'))
		 {
			if(is_array($o)) list($fwrite, $fread) = $o;
			else $fwrite = $fread = $o;
			curl_setopt($ch, CURLOPT_COOKIEJAR, $fwrite);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $fread);
		 }
		$this->config = [];
		if($o = $this->GetOption('proxy'))
		 {
			if(is_array($o)) $this->ConfigProxy($ch, (object)$o);
			elseif($c = count($o))
			 {
				if($c > 1)
				 {
					$servers = [];
					foreach($o as $k => $v) $servers[$k] = $v;
					$f = function($ch, $e_num) use(&$servers){
						static $k = null;
						if(null !== $e_num)
						 {
							if(56 !== $e_num) return;
							unset($servers[$k]);
						 }
						if(!count($servers)) return;
						$k = array_rand($servers);
						$this->ConfigProxy($ch, $servers[$k]);
						return true;
					};
					$f($ch, null);
					$this->config[] = $f;
				 }
				else
				 {
					$o->rewind();
					$this->ConfigProxy($ch, $o->current());
				 }
			 }
			else ;//throw new \Exception();
		 }
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->GetOption('connect_timeout'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(null !== $headers && count($headers))
		 {
			if($content_type)
			 {
				$headers->content_type = $content_type;
				if(false !== $content_length) $headers->content_length = $content_length;
			 }
			$h = $headers->Traverse(function($n, $v) use($ch){
				if('User-Agent' === $n)
				 {
					curl_setopt($ch, CURLOPT_USERAGENT, $v->value);
					return;
				 }
				elseif('Accept-Encoding' === $n)
				 {
					$o = $this->GetOption('accept_encoding');
					if(false !== $o) curl_setopt($ch, CURLOPT_ENCODING, '' === $o || true === $o ? $v->value : $o);
					return;
				 }
				elseif('Referer' === $n)
				 {
					curl_setopt($ch, CURLOPT_REFERER, $v->value);
					return;
				 }
				return true;
			}, null);
		 }
		else
		 {
			$h = [];
			if($content_type) $h[] = "Content-Type: $content_type";
			if(false !== $content_length) $h[] = "Content-Length: $content_length";
		 }
		if(($o = $this->GetOption('headers')) && 0 < count($o)) foreach($o as $v) $h[] = "$v";
		if($h) curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
		if(($o = $this->GetOption('cookie')) && 0 < count($o)) foreach($o as $k => $v) $cookie[$k] = "$v";
		if($cookie) curl_setopt($ch, CURLOPT_COOKIE, $this->ArrayToCookie($cookie));
		return $ch;
	 }

	final private function Exec($ch, $method, Containers\Options $o)
	 {
		if($this->config)
		 {
			do
			 {
				if(($result = curl_exec($ch)) === false)
				 {
					$e_num = curl_errno($ch);
					foreach($this->config as $c) if(true !== $c($ch, $e_num)) break 2;
				 }
			 }
			while(false === $result);
		 }
		else $result = curl_exec($ch);
		if($result === false)
		 {
			$e_msg = curl_error($ch);
			$e_num = curl_errno($ch);
			curl_close($ch);
			throw new EHTTPCURL($e_msg, $e_num);
		 }
		$r = new HTTPResponse($result, curl_getinfo($ch, CURLINFO_HEADER_SIZE), curl_getinfo($ch), $this->response_headers_array, $this->cookies);
		curl_close($ch);
		if($max = $this->GetOption('follow_location')) $this->FollowLocation($method, $r, $max, $o);
		return $r;
	 }

	final private function PrepareRequestArgs(array &$o = null, ...$args)
	 {
		$o = new Containers\Options($o, self::$meta['request_options']);
		$r = [$o->headers ? (is_array($o->headers) ? new HTTPHeaders($o->headers) : $o->headers) : null, true === $o->cookie ? $_COOKIE : $o->cookie];
		foreach($args as $v) $r[] = $v;
		return $r;
	 }

	final private function MergeData(...$args)
	 {
		$data = [];
		foreach($args as $d)
		 {
			foreach($d as $k => $v) $data[$k] = $v;
		 }
		return $data;
	 }

	final private static function ArrayToCookie(array $a)
	 {
		$s = '';
		foreach($a as $k => $v) $s .= ('' === $s ? '' : '; ').$k.'='.urlencode($v);
		return $s;
	 }

	final private static function ConfigProxy($ch, \stdClass $p)
	 {
		curl_setopt($ch, CURLOPT_PROXY, $p->host);
		if(!empty($p->port)) curl_setopt($ch, CURLOPT_PROXYPORT, $p->port);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $p->user ? "$p->user:$p->password" : '');
		if(isset($p->type))
		 {
			if(empty(self::$meta['proxy_types'][$p->type])) throw new \UnexpectedValueException('Invalid proxy type: '.Config::GetVarType($p->type));
			curl_setopt($ch, CURLOPT_PROXYTYPE, $p->type);
		 }
		else curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, !empty($p->tunnel));
		// CURLOPT_PROXYHEADER				An array of custom HTTP headers to pass to proxies.
		// CURLOPT_PROXY_SERVICE_NAME		The proxy authentication service name.
		// CURLOPT_PROXYAUTH				The HTTP authentication method(s) to use for the proxy connection. Use the same bitmasks as described in CURLOPT_HTTPAUTH. For proxy authentication, only CURLAUTH_BASIC and CURLAUTH_NTLM are currently supported.
	 }

	private $response_headers_array = [];
	private $cookies = [];
	private $config = null;

	private static $meta = [
		'proxy_types' => [CURLPROXY_HTTP => 'HTTP', CURLPROXY_SOCKS4 => 'SOCKS4', CURLPROXY_SOCKS5 => 'SOCKS5', CURLPROXY_SOCKS4A => 'SOCKS4A', CURLPROXY_SOCKS5_HOSTNAME => 'SOCKS5 HOSTNAME'],
		'request_options' => ['headers' => ['type' => 'array,iterator,null'], 'cookie' => ['type' => 'array,true,null'], 'on_redirect' => ['type' => 'callable,false,null']],
	];
}

class HTTPCookie implements \Iterator, \JsonSerializable
{
	final public function __construct($s)
	 {
		$idx = [
			'Expires' => (object)['name' => 'expires', 'hdlr' => 1],
			'Max-Age' => (object)['name' => 'max_age', 'hdlr' => 1],
			'Path' => (object)['name' => 'path', 'hdlr' => 1],
			'Domain' => (object)['name' => 'domain', 'hdlr' => 1],
			'Secure' => (object)['name' => 'secure', 'hdlr' => false],
			'HttpOnly' => (object)['name' => 'httponly', 'hdlr' => false],
		];
		foreach(explode(';', $s) as $i => $v)
		 {
			$v = explode('=', trim($v), 2);
			if($i)
			 {
				foreach($idx as $j => $k)
				 {
					if(0 === strcasecmp($v[0], $j))
					 {
						$this->data[$k->name] = false === $k->hdlr ? true : $v[1];
						unset($idx[$j]);
						break;
					 }
				 }
			 }
			else
			 {
				$this->data['name'] = $v[0];
				$this->data['value'] = urldecode($v[1]);
			 }
		 }
		foreach($idx as $k)
		 {
			if(null === $this->data[$k->name])
			 {
				if(false === $k->hdlr) $this->data[$k->name] = false;
			 }
			else throw new \Exception();
		 }
	 }

	final public function __get($name)
	 {
		if(array_key_exists($name, $this->data)) return $this->data[$name];
		throw new \Exception('Undefined property: '. __CLASS__ .'::$'.$name);
	 }

	// final public function __isset($name) {}
	final public function rewind() { reset($this->data); }
	final public function next() { next($this->data); }
	final public function current() { return current($this->data); }
	final public function valid() { return null !== key($this->data); }
	final public function key() { return key($this->data); }
	final public function jsonSerialize() { return ; }
	final public function __debugInfo() { return $this->data; }

	private $data = ['name' => null, 'value' => null, 'expires' => null, 'max_age' => null, 'path' => null, 'domain' => null, 'secure' => null, 'httponly' => null];
}
?>