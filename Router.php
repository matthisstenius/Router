<?php

class Router {
	/**
	 * @var array 
	 */
	private $routes;

	/**
	 * @var array
	 */
	private $callbacks;

	/**
	 * @var string
	 */
	private $requestMethod;

	private static $uriIndex = "uri";

	public function __construct() {
		$this->routes = array();
		$this->callbacks = array();
	}

	/**
	 * @param string $route     route to be matched
	 * @param function $callbacks callbak to be run incase if match
	 */
	private function add($route, $callbacks, $action) {
		if ($action == "401") {
			$this->routes[$action] = trim($route, '/\//');
			$this->callbacks[$action] = $callbacks;
		}

		else {
			$this->routes[$action][] = trim($route, '/\//');
			$this->callbacks[$action][] = $callbacks;
		}

		$this->requestMethod = $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Adds GET routes and callbaks
	 * @param  string $route
	 * @param  function $callback
	 * @return void
	 */
	public function get($route, $callback) {
		$this->add($route, $callback, "GET");
	}

	/**
	 * Adds POST routes and callbaks
	 * @param  string $route
	 * @param  function $callback
	 * @return void
	 */
	public function post($route, $callback) {
		$this->add($route, $callback, "POST");
	}

	/**
	 * Adds PUT routes and callbaks
	 * @param  string $route
	 * @param  function $callback
	 * @return void
	 */
	public function put($route, $callback) {
		$this->add($route, $callback, "PUT");
	}

	/**
	 * Adds DELETE routes and callbaks
	 * @param  string $route
	 * @param  function $callback
	 * @return void
	 */
	public function delete($route, $callback) {
		$this->add($route, $callback, "DELETE");
	}

	public function notFound($route, $callback) {
		$this->add($route, $callback, "401");
	}
	/**
	 * Matches the incoming URI against an array of URI's
	 * @return void
	 */
	public function match() {
		$incomingUri = isset($_GET[self::$uriIndex]) ? $_GET[self::$uriIndex] : "/";;
		$incomingUri = htmlentities($incomingUri);
		$incomingUri = trim($incomingUri, '/\//');
		
		$routesByMethod = $this->routes[$this->requestMethod];
		$requestParams = array();

		foreach ($routesByMethod as $routeKey => $route) {
			$choppedRoute = explode("/", $route);
			$choppedIncomingUri = explode("/", $incomingUri);
			
			$choppedChangedParamsUri = preg_replace('/:{1}.+/', '\w+', $choppedRoute);
			$changedParamsUri = implode("/", $choppedChangedParamsUri);

			var_dump($choppedRoute);
			if (preg_match("#^$changedParamsUri$#", $incomingUri)) {

				foreach ($choppedChangedParamsUri as $key => $value) {
					if ($value == "\w+") {
						$requestParams[] = $choppedIncomingUri[$key];
					}
				}

				exit(call_user_func_array($this->callbacks[$this->requestMethod][$routeKey], $requestParams));
			}
		}

		// If no match is found call 404 callback
		call_user_func_array($this->callbacks["401"], array());
	}

}