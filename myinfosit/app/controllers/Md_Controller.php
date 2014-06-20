<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */
class MdController extends AppController {

	var $__paths = array();

	var $_missingView;

	/**
	 * 是否渲染json模版
	 * @see Controller::beforeRender()
	 */
	var $isRenderTemple = true;


	/**
	 * 渲染的json视图集合
	 * @see Controller::beforeRender()
	 */
	var $view_outs;

	function beforeFilter(){
		$this->RequestHandler->setContent('json', 'text/x-json');
		$this->RequestHandler->setContent('mobile', array('text/html', '*/*'));
		
		if(isset($this->params["form"]) && isset($this->params["form"]['view_outs'])
		&& !empty($this->params["form"]['view_outs'])){
			//foreach ($this->params["form"]['view_config'] as $view_config){
				$this->view_outs = $this->params["form"]['view_outs'];
			//}
		}elseif(isset($this->params["url"]) && isset($this->params["url"]['view_outs'])
				&& !empty($this->params["url"]['view_outs'])){
			$this->view_outs = $this->params["url"]['view_outs'];
		}/* elseif(isset($this->params["url"]) && $this->params["url"]==="wap"){
			$this->layoutPath="wap";
			
		} */

		parent::beforeFilter();
	}

	/**
	 * 读取json的模版，并自动补充填充view的js代码
	 * @see Controller::beforeRender()
	 */
	function beforeRender(){
		if($this->isRenderTemple && isset($this->action) && preg_match("/^.*[\/\\\\]json$/",$this->viewPath)){
         /* 2013-09-06 
          * preg_match("/^.*[\/]json$/",$this->viewPath) 改成 preg_match("/^.*[\/\\\\]json$/",$this->viewPath) 
          * 为满足在window平台上使用
          * */
			
			
			if(!isset($this->view_outs)||empty($this->view_outs)){

				$this->view_outs = $this->action;
					
			}

			if(isset($this->viewVars["pass"])&&!empty($this->viewVars["pass"])){
					
				
				//echo $ember_temple;
				

					if(isset($this->view_outs)&&!empty($this->view_outs)){
						
						foreach($this->view_outs as $view_out ){
							
						@$ember_temple_str = file_get_contents($this->_getViewFileName($view_out));
						if(isset($ember_temple_str)&&!empty($ember_temple_str)){
						$ember_temple .="<script type=\"text/x-handlebars\" data-template-name=\"".$view_out."-template\">".$ember_temple_str."</script>";
						/* 
							$ember_temple .="MDEmber.viewSet[\"".lcfirst($view_out["view_name"])."\"]= MDEmber."
									.ucfirst($view_out["view_name"]).".create();";
							if(isset($view_out["target"])&&!empty($view_out["target"])){
								$ember_temple .="MDEmber.viewSet[\"".lcfirst($view_out["view_name"])."\"].appendTo(\"".$view_out["target"]."\");";
							}else{
								$ember_temple .="MDEmber.viewSet[\"".lcfirst($view_out["view_name"])."\"].appendTo(\"html\");";


							}*/
							}
						} 
						//}
						if(isset($ember_temple)&&!empty($ember_temple)){
						$this->viewVars["pass"] +=array("ember_temple"=>$ember_temple);
						}
						//$ember_temple .="</script>";
					}
					//echo $ember_temple;
					
				
			}


		}


	}
	
	

	/**
	 * 获取json模版文件全名
	 * @see Controller::beforeRender()
	 */
	function _getViewFileName($name = null) {
		$subDir = null;

		if ($name === null) {
			$name = $this->action;
		}
		$name = str_replace('/', DS, $name);



		if (strpos($name, DS) === false && $name[0] !== '.') {
			$name = "emviews" . DS . $subDir . Inflector::underscore($name);
		} elseif (strpos($name, DS) !== false) {
			if ($name{0} === DS || $name{1} === ':') {
				if (is_file($name)) {
					return $name;
				}
				$name = trim($name, DS);
			} else if ($name[0] === '.') {
				$name = substr($name, 3);
			} else {
				$name = "emviews" . DS . $name;
			}
		}
		$paths = $this->_paths(Inflector::underscore($this->plugin));

		$exts = array(".emtp");
		//if ($this->ext !== '.ctp') {
		//array_push($exts, '.ctp');
		//}
		foreach ($exts as $ext) {
			foreach ($paths as $path) {
				if (file_exists($path . $name . $ext)) {
					return $path . $name . $ext;
				}
			}
		}
		$defaultPath = $paths[0];

		if ($this->plugin) {
			$pluginPaths = App::path('plugins');
			foreach ($paths as $path) {
				if (strpos($path, $pluginPaths[0]) === 0) {
					$defaultPath = $path;
					break;
				}
			}
		}
		//return $this->_missingView=$defaultPath . $name . $this->ext;
	}

	/**
	 * 读取存放json的模版的路径
	 * @see Controller::beforeRender()
	 */
	function _paths($plugin = null, $cached = true) {
		if ($plugin === null && $cached === true && !empty($this->__paths)) {
			return $this->__paths;
		}
		$paths = array();
		$viewPaths = App::path('views');
		$corePaths = array_flip(App::core('views'));

		if (!empty($plugin)) {
			$count = count($viewPaths);
			for ($i = 0; $i < $count; $i++) {
				if (!isset($corePaths[$viewPaths[$i]])) {
					$paths[] = $viewPaths[$i] . 'plugins' . DS . $plugin . DS;
				}
			}
			$paths[] = App::pluginPath($plugin) . 'views' . DS;
		}
		$this->__paths = array_merge($paths, $viewPaths);
		return $this->__paths;
	}

}
