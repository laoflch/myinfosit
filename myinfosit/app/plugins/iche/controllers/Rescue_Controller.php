<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('Controller', "MdController",false);


class RescueController extends MdController implements NoModelController
{
    //var $uses = array('weixinopen.WeixinMessageText');
    
	
	var $components = array('RequestHandler');
    
	function index() {
			
	}	
	
	function confirm(){
		$this->set('phone_no',$this->params["form"]['phone_no']);
		
	}
	
	function location(){
		
	}
	
}
