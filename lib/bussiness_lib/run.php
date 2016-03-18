<?php

class TP_Run {

	/**
	 * 框架运行核心函数
	 * 1. 设置参数
	 * 2. 获取controller
	 * 4. 运行Action
	 * @return file
	 */
	public function run() {
		$this->filter();
		//验证方法是否合法，如果请求参数不正确，则直接返回404
		$controllerObj = $this->check_request();
		$this->run_action($controllerObj); //正常流程Action
	}

	/**
	 * 验证请求是否合法
	 * 1. 如果请求参数m,c,a都为空，则走默认的
	 */
	private function check_request() {
		$controller  = isset($_GET['c']) ? $_GET['c'] : '';
		$module  = isset($_GET['m']) ? $_GET['m'] : '';

		$module = $module . DIRECTORY_SEPARATOR;
		$path = MODULE_PATH;
		$controllerClass = $controller;
		$controllerFilePath = $path . $module . $controllerClass . '.php';
        if(!file_exists($controllerFilePath)){
            if(substr($_SERVER['REQUEST_URI'],1) != 'favicon.ico'){
                TP_Log::error("controller file does not exist : ".$controllerFilePath);
            }
            TP_Core::return404();
        }
		include ($controllerFilePath);
		$controllerObj = new $controllerClass();
		return $controllerObj;
	}

	/**
	 * 框架运行控制器中的Action函数
	 * 1. 获取Action中的a参数
	 * 2. 检测是否在白名单中，不在则选择默认的
	 * 3. 检测方法是否存在，不存在则运行默认的
	 * 4. 运行函数
	 * @param object $controller 控制器对象
	 * @return file
	 */
	private function run_action($controller) {
		$action = trim($_GET['a']);
        if(!method_exists($controller,$action)){
            TP_Log::error("action method does not exist : ".$_GET['c'].'/'.$_GET['m'].'/'.$action);
            TP_Core::return404();
        }
        $controller->$action();
	}

	/**
	 *	m-c-a数据处理
	 *  @return string
	 */
	private function filter() {
		if (isset($_GET['m'])) {
			if (!$this->_filter($_GET['m'])) unset($_GET['m']);
		}
		if (isset($_GET['c'])) {
			if (!$this->_filter($_GET['c'])) unset($_GET['c']);
		}
		if (isset($_GET['a'])) {
			if (!$this->_filter($_GET['a'])) unset($_GET['a']);
		}
	}

	private function _filter($str) {
		return preg_match('/^[A-Za-z0-9_]+$/', trim($str));
	}


}