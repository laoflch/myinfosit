<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);



class SelfMenuController extends WeixinopenAppController implements NoModelController
{

	var $helpers = array('Javascript');

	

	var $uses = array('weixinopen.WeixinAccessToken');

	var $components = array('RequestHandler','WeixinHandler');

	

	function updateSelfMenu(){

		/* if(isset($this->params["form"]['customer_id'])
		&&!empty($this->params["form"]['customer_id'])){ */
		if(true){
			$access_token=$this->WeixinHandler->getAccessToken($this,3);
			if($access_token&&isset($access_token)&&!empty($access_token)){
				$menu_str='{
     "button":[
     {	
          
          "name":"活动预告",
           "sub_button":[
           {	
               "type":"click",
               "name":"最近活动",
               "key":"MENU_01_01"
            },
            {
               "type":"view",
               "name":"往期活动",
               "url":"http://v.qq.com/"
            },
            {
               "type":"click",
               "name":"其他同城",
               "key":"V1001_GOOD"
            }]
      },
      {
           
           "name":"乐活",
            "sub_button":[
           {	
               "type":"view",
               "name":"乐购",
               "url":"http://www.soso.com/"
            },
            {
               "type":"view",
               "name":"乐旅",
               "url":"http://v.qq.com/"
            },
            {
               "type":"click",
               "name":"乐享",
               "key":"V1001_GOOD"
            }]
      },
      {
           "name":"我",
           "sub_button":[
           {	
               "type":"view",
               "name":"我的活动",
               "url":"http://www.soso.com/"
            },
            {
               "type":"view",
               "name":"订单查询",
               "url":"http://v.qq.com/"
            },
            {
               "type":"click",
               "name":"积分计划",
               "key":"V1001_GOOD"
            },
            {
               "type":"click",
               "name":"维权投诉",
               "key":"V1001_GOOD"
            }]
       }]
 }';
				$this->WeixinHandler->updateSelfMenu($access_token,$menu_str);
				
			}
		}
		//$this->_clearClass($friend);
		$this->set('pass',array("return_code"=>107,"return_message"=>"update Self Menu successfully !"));
		//echo $friend;

		//$this->WeixinFriendInfo-deleteAll();


			
	}
	
}