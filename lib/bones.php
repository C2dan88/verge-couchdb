<?php

define('BASE_URI', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', dirname(__DIR__ ));

function get($route, $callback)
{
	Bones::register($route, $callback, 'GET');
}

function post($route, $callback)
{
	Bones::register($route, $callback, 'POST');
}
function put($route, $callback)
{
	Bones::register($route, $callback, 'PUT');
}

function delete($route, $callback)
{
	Bones::register($route, $callback, 'DELETE');
}

class Bones
{
	private static $instance;
	private static $route_found = false;

	public $route = '';
	public $method = '';
	public $content = '';
	public $vars = array();

	public function __construct()
	{
		$this->route = $this->get_route();
		$this->method = $this->get_method();
	}

	public static function get_instance()
	{
		if(!isset(self::$instance)) {
			self::$instance = new Bones();
		}

		return self::$instance;
	}

	public static function register($route, $callback, $method)
	{
		$bones = static::get_instance();

		if($route == $bones->route && !static::$route_found && $bones->method == $method)
		{
			static::$route_found = true;
			echo $callback($bones);
		}
		else
			return false;
	}

	protected function get_route()
	{
		parse_str($_SERVER['QUERY_STRING'], $route);
		if($route)
		{
			return '/' . $route['request'];
		}

		return '/';
	}

	protected function get_method()
	{
		return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
	}

	public function set($index, $value)
	{
		$this->vars[$index] = $value;
	}

	public function form($key)
	{
		return $_POST[$key];
	}

	public function render($view, $layout = 'layout')
	{
		$this->content = ROOT. '/views/' . $view . '.php';
		foreach ($this->vars as $key => $value) {
			$$key = $value;
		}

		if(!$layout)
		{
			include $this->conrent;
		}
		else
		{
			include ROOT . '/views/' . $layout . '.php';
		}
	}

	public function make_route($path = '')
	{
		/*$url = explode('/', $_SERVER['PHP_SELF']);
		if($url[1] == 'index.php')
		{
			return $path;
		}
		else
		{
			return '/' . $url[1] . $path;
		}*/

		return BASE_URI . $path;
	}
}