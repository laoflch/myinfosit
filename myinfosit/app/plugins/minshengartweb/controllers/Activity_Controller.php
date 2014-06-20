<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('Controller', "MdController",false);


class ActivityController extends MdController implements NoModelController
{
    //var $uses = array('weixinopen.WeixinMessageText');
    
	
	var $components = array('RequestHandler');
    
	function activity() {
			
	}
	
	function passedActivity() {
			
	}
	
}
