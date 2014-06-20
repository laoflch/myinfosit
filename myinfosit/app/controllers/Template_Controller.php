<?php
App::import('Controller', "MdController",false);
App::import('Controller', "NoModelController",false);


class TemplateController extends MdController implements NoModelController{
	
	var $helpers = array('Javascript');


	/*json handler 2013-05-19*/
	var $components = array('RequestHandler');
	
	function loadTemplate(){
		$this->set('pass',array("return_code"=>109,"return_message"=>"login template successfully !"));
		
	}
}