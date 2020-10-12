<?php
namespace MaxieSystems;

Config::RequireFile('containers');

class EEvents extends \Exception {}
	class EEventsDispatcherRequired extends EEvents {}
	class EEventsNotRegistered extends EEvents {}

class EventData extends Containers\Data
{
	public function __construct(array $data, array $meta = [])
	 {
		if(!$this->CheckArrayKeys($meta, $data, $diff)) throw new \Exception(get_class($this).'::'. __FUNCTION__ .'. Undefined meta data: '.implode(', ', $diff));
		foreach($data as $k => &$v)
		 if(isset($meta[$k])) $meta[$k]['value'] = &$v;
		 else $meta[$k] = ['value' => &$v];
		parent::__construct($meta);
	 }
}

class Events
{
	final public static function BindTo($name, $handler)
	 {
		if(!isset(self::$handlers[$name])) self::$handlers[$name] = [];
		self::$handlers[$name][] = $handler;
	 }

	final public static function Remove($name)
	 {
		unset(self::$handlers[$name]);
	 }

	final public static function Dispatch($name, $required = false, array $data, array $meta = [])
	 {
		$data = new EventData($data, $meta);
		if(self::Exists($name))
		 {
			foreach(self::$handlers[$name] as $h) call_user_func($h, $data, $name);
		 }
		elseif($required) throw new EEventsDispatcherRequired("Event `$name` requires at least one dispatcher!");
		return $data;
	 }

	final public static function Exists($name) { return isset(self::$handlers[$name]); }

	private static $handlers = [];
}

trait TEvents
{
	final public function BindToEvent($name, $handler)
	 {
		if(is_array($name)) foreach($name as $n) $this->ValidateEventHandlers($n)[] = $handler;
		else $this->ValidateEventHandlers($name)[] = $handler;
		return $this;
	 }

	final public function GetRegisteredEvents()
	 {
		$r = [];
		foreach($this->events_registered as $n => $v) $r[$n] = isset($this->event_handlers[$n]) ? count($this->event_handlers[$n]) : 0;
		return $r;
	 }

	final public function HandlerExists($name) { return isset($this->event_handlers[$name]); }

	final public function RemoveEvent($name)
	 {
		unset($this->event_handlers[$name]);
		return $this;
	 }

	final protected function DispatchEvent($name, $required = false, array $data, array $meta = []) { return $this->DispatchEventData($name, $required, new EventData($data, $meta)); }

	final protected function DispatchEventData($name, $required = false, Containers\Data $data)
	 {
		if($handlers = $this->ValidateEventHandlers($name))
		 {
			foreach($handlers as $h) call_user_func($h, $data, $name);
		 }
		elseif($required) throw new EEventsDispatcherRequired("Event `$name` requires at least one dispatcher!");
		return $data;
	 }

	final protected function RegisterEvents(...$args)
	 {
		foreach($args as $n)
		 if(isset($this->events_registered[$n])) throw new \Exception(get_class($this).'::'. __FUNCTION__ ."(). Event `$n` already registered!");
		 else $this->events_registered[$n] = true;
		return $this;
	 }

	final private function &ValidateEventHandlers($name)
	 {
		if(!empty($this->events_registered) && empty($this->events_registered[$name])) throw new EEventsNotRegistered('Instance of `'.get_class($this)."`: invalid event name `$name`! ".($this->events_registered ? count($this->events_registered).' registered: '.implode(', ', array_keys($this->events_registered)) : 'No events registered').'.');
		if(!isset($this->event_handlers[$name])) $this->event_handlers[$name] = [];
		return $this->event_handlers[$name];
	 }

	private $event_handlers = [];
	private $events_registered = [];
}
?>