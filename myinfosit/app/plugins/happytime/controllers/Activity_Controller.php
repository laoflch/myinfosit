<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);

class ActivityController implements NoModelController
{

	var $helpers = array('Javascript');

	

	var $uses = array('happytime.HappytimeActivity','happytime.HappytimePassActivity','');

	//var $components = array('RequestHandler');

	

	
	
	function getPassActiviesList(){
		/* if(isset($this->params["form"]['account_open_id'])
		&&!empty($this->params["form"]['account_open_id'])){ */
		if(true){
		$passActiviesList=$this->HappytimePassActivity->find("all",array(
							'fields' => array('WeixinFriend.nick_name','WeixinFriend.fake_id','WeixinFriend.customer_id','WeixinFriendInfo.city'),
							'joins' => array(
									array(
											'alias' => 'WeixinFriendInfo',
											'table' => 'weixin_friend_infos',
											'type' => 'LEFT',
											'conditions' => '`WeixinFriendInfo`.`fake_id` = `WeixinFriend`.`fake_id`'
									)
							),
				            'page'=>1,'limit'=>4
					));
		$this->_clearClass($passActiviesList);
		$this->set('pass',array("return_code"=>107,"return_message"=>"get passActiviesList successfully !","passActiviesList"=>$passActiviesList));
		$this->isRenderTemple=false;
		}
	}
	
	

	
}