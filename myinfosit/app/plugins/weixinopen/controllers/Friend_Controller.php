<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('file', "weixinopen.RuleKeyAdapter",false);



class FriendController extends WeixinopenAppController implements NoModelController
{

	var $helpers = array('Javascript');

	

	var $uses = array('WeixinFriend','WeixinFriendInfo');

	var $components = array('RequestHandler','WeixinHandler');

	

	function friendInfo(){

		if(isset($this->params["form"]['customer_id'])
		&&!empty($this->params["form"]['customer_id'])){
		//if(true){
			$friend=$this->WeixinFriend->find("all",
					array('conditions' => array('WeixinFriend.customer_id' => $this->params["form"]['customer_id']),//$this->params["form"]['open_account_id']),
							'fields' => array('WeixinFriend.nick_name','WeixinFriend.fake_id','WeixinFriend.customer_id','WeixinFriendInfo.city'),
							'joins' => array(
									array(
											'alias' => 'WeixinFriendInfo',
											'table' => 'weixin_friend_infos',
											'type' => 'LEFT',
											'conditions' => '`WeixinFriendInfo`.`fake_id` = `WeixinFriend`.`fake_id`'
									)
							)
					));//=>$this->params["form"]['open_account_id']);
		}

		$this->_clearClass($friend);
		$this->set('pass',array("return_code"=>107,"return_message"=>"get friend info successfully !","friend_info_list"=>$friend));
		//echo $friend;

		//$this->WeixinFriendInfo-deleteAll();


			
	}
	
	function friendSynchronize(){
		if(isset($this->params["form"]['synchronize'])
		&&!empty($this->params["form"]['synchronize'])
		&&isset($this->params["form"]['customer_id'])
		&&!empty($this->params["form"]['customer_id'])){ 
			$this->__initConnet();
				
			if(isset($this->token)&&!empty($this->token)){
				
				$contactinfos=$this->WeixinHandler->fetch_all_user_info($this->token);
				$array_contact=$this->__detachContactinfo($contactinfos,"1");
				
				if(isset($array_contact[0])&&!empty($array_contact[0])&&is_array($array_contact[0])){
					$this->WeixinFriend->deleteAll(array("customer_id"=>$this->params["form"]['customer_id']),false,false);
					$content=$this->WeixinFriend->saveAll($array_contact[0],array('validate' => false));
					
					
				}
				
				if(isset($array_contact[1])&&!empty($array_contact[1])&&is_array($array_contact[1])){
					$this->WeixinFriendInfo->deleteAll(array("customer_id"=>$this->params["form"]['customer_id']),false,false);
					$content=$this->WeixinFriendInfo->saveAll($array_contact[1],array('validate' => false));
						
						
				}
			}
			
			//if(true){
			/* $friend=$this->WeixinFriend->find("all",
					array('conditions' => array('WeixinFriend.customer_id' => $this->params["form"]['customer_id']),//$this->params["form"]['open_account_id']),
							'fields' => array('WeixinFriend.nick_name','WeixinFriend.fake_id','WeixinFriend.customer_id','WeixinFriendInfo.city'),
							'joins' => array(
									array(
											'alias' => 'WeixinFriendInfo',
											'table' => 'weixin_friend_infos',
											'type' => 'LEFT',
											'conditions' => '`WeixinFriendInfo`.`fake_id` = `WeixinFriend`.`fake_id`'
									)
							)
					));//=>$this->params["form"]['open_account_id']); */
			$this->set('pass',array("return_code"=>107,"return_message"=>"get friend info successfully !"));
		} else{
			$this->set('pass',array("return_code"=>109,"return_message"=>"get template successfully !"));
		}
		
		
		
	}
	
	private function  __detachContactinfo($contactinfos,$customer_id){
		if(isset($contactinfos)&&!empty($contactinfos)){
			$friend_array=array();
			$friend_info_array=array();
			foreach($contactinfos as $contactinfo){
				$friend_array[]=array("fake_id"=>$contactinfo->id,"customer_id"=>$customer_id,"nick_name"=>$contactinfo->nick_name,
						              "remark_name"=>$contactinfo->remark_name,"groug_id"=>$contactinfo->group_id);
				
				$friend_info_array[]=array("fake_id"=>$contactinfo->info->fake_id,"customer_id"=>$customer_id,"nick_name"=>$contactinfo->info->nick_name,
						              "re_mark_name"=>$contactinfo->info->remark_name,"user_name"=>$contactinfo->info->user_name,"signature"=>$contactinfo->info->signature,
						              "city"=>$contactinfo->info->city,"province"=>$contactinfo->info->province,"country"=>$contactinfo->info->country,
						              "gender"=>$contactinfo->info->gender,"groug_id"=>$contactinfo->group_id);
					
			}
		}
		
		return array($friend_array,$friend_info_array);
	}

	


}