<?php
class RouteClass {
    
	const VALID_PARAM = '/^[a-zA-Z][a-zA-Z0-9_]{0,255}$/';

	private $app;
	private $controller;
	private $action;
	
	
	public function getApp() {
		return $this->app;
	}
	public function getController() {
		return $this->controller;
	}
	public function getAction() {
		return $this->action;
	}

	/**
	 * 根据路由规则解析URI，获取app,controller,action
	 * @param string $uri
	 * @return Plus_Route
	 */
	public static function parseURI($uri) {
		$route = new Plus_Route();
		$route->app = 'default';
		$route->controller = 'Index';
		$route->action = 'default';
		$temp = explode('?', urldecode($uri));
		$temp = array_shift($temp);
		$uriArr = array_filter(explode('/', trim($temp, '/')));
		if (!empty($uriArr[0]) && self::appValid($uriArr[0])) {
			$route->app = array_shift($uriArr);
		}
		if (!empty($uriArr[0])) {
			$controller = self::convertToCamelHump($uriArr[0]);
			if (self::controllerValid($route->app, $controller)) {
				$route->controller = $controller;
				array_shift($uriArr);
			}
		}
		if (!empty($uriArr[0])) {
			$route->action = array_shift($uriArr);
		}
		for($i = 0; $i < count($uriArr); $i += 2) {
			if (!empty($uriArr[$i]) && isset($uriArr[$i + 1])) {
				$_REQUEST[$uriArr[$i]] = $_GET[$uriArr[$i]] = $uriArr[$i + 1];
			}
		}

        defined('DEBUG_FIREPHP') && Tools::debugFP(get_object_vars($route), 'route');
        defined('DEBUG_FIREPHP') && Tools::debugFP($_GET, 'route');
		if (!preg_match(self::VALID_PARAM, $route->app)) {
		    throw new Exception('ILLEGAL_APP');
		}
		if (!preg_match(self::VALID_PARAM, $route->controller)) {
		    throw new Exception('ILLEGAL_CONTROLLER');
		}
		if (!preg_match(self::VALID_PARAM, $route->action)) {
		    throw new Exception('ILLEGAL_ACTION');
		}

		return $route;
	}

	/**
	 * 检查app是否合法
	 * @param string $app
	 * @return boolean
	 */
	private static function appValid($app) {
		$appDir = sprintf("%s/apps/%s/", ROOT, $app);
		return is_dir($appDir);
	}

	/**
	 * 检查controller和$controller是否合法
	 * @param string $app
	 * @param string $controller
	 * @return boolean
	 */
	private static function controllerValid($app, $controller) {
		$controllerFile = sprintf("%sapps/%s/controllers/%s.controller.php", ROOT, $app, $controller);
		return file_exists($controllerFile);
	}

	/**
	 * 将字符串do_something转成DoSomething
	 * @param string $src
	 * @return string
	 */
	private static function convertToCamelHump($src) {
		$result = '';
		$srcArray = explode('_', $src);
		for($i = 0; $i < count($srcArray); $i++) {
			$result .= ucfirst($srcArray[$i]);
		}
		return $result;
	}
}
