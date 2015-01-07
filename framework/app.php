<?php
error_reporting(E_ALL & ~E_NOTICE);

if(!defined(FRAMEWORK_ROOT)){
	define("FRAMEWORK_ROOT" , dirname(__FILE__));
}
require_once FRAMEWORK_ROOT.'/config/class.inc.php';

class App{
	private $_config;
	
	private static $instance;
		
	public static function getInstance($mainConfig){
		if(!(self::$instance instanceof FreeApp)){
			self::$instance = new self($mainConfig);
		}
		return self::$instance;
	}
	
	private function __construct($mainConfig){
		$this -> _config = $mainConfig;
		
	}
	
	public function run(){
		$route = $_REQUEST;
		$routeClass = RouteClass::getInstance($route);
		$routeClass -> analysisRoute();
		$routeClass -> runController();
		$routeClass -> runAction();
	}
	
}