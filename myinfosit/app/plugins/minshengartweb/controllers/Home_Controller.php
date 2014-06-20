<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('Controller', "MdController",false);


class HomeController extends MdController implements NoModelController
{
    //var $uses = array('weixinopen.WeixinMessageText');
    
	
	var $components = array('RequestHandler');
    
	function index() {
			
	}
	
	function introduce(){
		
	} 
	
	function transportation(){
		
	}
	
	function opentime(){
		
	}

	function pricediscount(){
		
	}
	
}
