<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('Controller', "MdController",false);


class SubscribeController extends MdController implements NoModelController
{
    //var $uses = array('weixinopen.WeixinMessageText');
    
	
	var $components = array('RequestHandler');
    
	function subscribe() {
			
	}
	
		
}
