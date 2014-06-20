<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('file', "weixinopen.RuleKeyAdapter",false);



class OpenaccountController extends WeixinopenAppController implements NoModelController
{

	var $helpers = array('Javascript');

	

	var $uses = array('WeixinOpenAccount');

	var $components = array('RequestHandler');

	

	function openaccountList(){
        

		$openaccount_list=$this->WeixinOpenAccount->find("all");
		
		$this->_clearClass($openaccount_list,"WeixinOpenAccount");
		
		$result_array=array();
		foreach($openaccount_list as $openaccount){
			
			$result_array[intval($openaccount["WeixinOpenAccount_customer_id"])-1]=$openaccount;
		}
		
		$this->set('pass',array("return_code"=>104,"return_message"=>"login failure !","openaccount_list"=>$result_array));
				//$this->view_outs[] = array("view_name"=>"tool_nav","target"=>"#container-2");
				//return $this->render(null,null,'index');
	    $this->isRenderTemple=false;

		//$this->WeixinFriendInfo-deleteAll();


			
	}

	


}