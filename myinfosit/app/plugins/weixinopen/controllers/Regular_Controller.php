<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('file', "weixinopen.RuleKeyAdapter",false);



class RegularController extends WeixinopenAppController implements NoModelController
{

	var $helpers = array('Javascript');

	

	var $uses = array('weixinopen.WeixinRule','weixinopen.WeixinRuleKey','weixinopen.WeixinRuleAdapter');

	var $components = array('RequestHandler');

	

	function regularList(){

		if(isset($this->params["form"]['customer_id'])
		&&!empty($this->params["form"]['customer_id'])){
		//if(true){
			$regularList=$this->WeixinRule->find("all",
					array('conditions' => array('customer_id' =>$this->params["form"]['customer_id']) ,
					'fields' => array('WeixinRule.rule_id','WeixinRule.rule_name','WeixinRuleAdapter.rule_adapter_id','WeixinRuleAdapter.rule_adapter_name','WeixinRuleAdapter.rule_name'),
					'joins' => array(
							array(
									'alias' => 'WeixinRuleAdapter',
									'table' => 'weixin_rule_adapters',
									'type' => 'LEFT',
									'conditions' => '`WeixinRule`.`rule_adapter_id` = `WeixinRuleAdapter`.`rule_adapter_id`'
							)/* ,
							array(
									'alias' => 'WeixinRuleContentText',
									'table' => 'weixin_rule_content_texts',
									'type' => 'LEFT',
									'conditions' => '`WeixinRuleContentText`.`content_id` = `WeixinRuleKey`.`content_id`'
							) */
					) 
					)
					);//=>$this->params["form"]['open_account_id']);
		}

		$this->_clearClass($regularList);
		$this->set('pass',array("return_code"=>107,"return_message"=>"get regularList successfully !","regularList"=>$regularList));
		//echo $friend;

		//$this->WeixinFriendInfo-deleteAll();


			
	}
	
	function fetchRuleContent(){
		if(isset($this->params["form"]['rule_id'])
		&&!empty($this->params["form"]['rule_id'])&&isset($this->params["form"]['rule_adapter_name'])
		&&!empty($this->params["form"]['rule_adapter_name'])){
		//if(true){
			if($this->params["form"]['rule_adapter_name']==="RuleKeyAdapter"){
				$regularList=$this->WeixinRule->find("first",
						array('conditions' => array('WeixinRule.rule_id' =>$this->params["form"]['rule_id']) ,
								'fields' => array('WeixinRule.rule_id','WeixinRuleDetail.rule_key','WeixinRuleDetail.content_id','WeixinRuleDetail.content_type_code','WeixinContentType.content_type_name'),
								'joins' => array(
										array(
												'alias' => 'WeixinRuleDetail',
												'table' => 'weixin_rule_keys',
												'type' => 'LEFT',
												'conditions' => '`WeixinRuleDetail`.`rule_id` = `WeixinRule`.`rule_id`'
										),
										array(
												'alias' => 'WeixinContentType',
												'table' => 'weixin_content_types',
												'type' => 'LEFT',
												'conditions' => '`WeixinContentType`.`content_type_code` = `WeixinRuleDetail`.`content_type_code`'
										)/* ,
										array(
												'alias' => 'WeixinRuleContentText',
												'table' => 'weixin_rule_content_texts',
												'type' => 'LEFT',
												'conditions' => '`WeixinRuleContentText`.`content_type_code` = `WeixinRuleKey`.`content_type_code`'
										) */
								)
						)
				);
				
			}elseif($this->params["form"]['rule_adapter_name']==="RuleEventAdapter"){
				$regularList=$this->WeixinRule->find("first",
						array('conditions' => array('WeixinRule.rule_id' =>$this->params["form"]['rule_id']) ,
								'fields' => array('WeixinRule.rule_id','WeixinRuleDetail.event_key','WeixinRuleDetail.content_id','WeixinRuleDetail.content_type_code','WeixinContentType.content_type_name'),
								'joins' => array(
										array(
												'alias' => 'WeixinRuleDetail',
												'table' => 'weixin_rule_events',
												'type' => 'LEFT',
												'conditions' => '`WeixinRuleDetail`.`rule_id` = `WeixinRule`.`rule_id`'
										),
										array(
												'alias' => 'WeixinContentType',
												'table' => 'weixin_content_types',
												'type' => 'LEFT',
												'conditions' => '`WeixinContentType`.`content_type_code` = `WeixinRuleDetail`.`content_type_code`'
										)/* ,
										array(
												'alias' => 'WeixinRuleContentText',
												'table' => 'weixin_rule_content_texts',
												'type' => 'LEFT',
												'conditions' => '`WeixinRuleContentText`.`content_type_code` = `WeixinRuleKey`.`content_type_code`'
										) */
								)
						)
				);
				
			}
			$regularList=array($regularList);
			$this->_clearClass($regularList);
			$this->set('pass',array("return_code"=>110,"return_message"=>"get regularList successfully !","rule_content"=>$regularList));
			
			
		}
		
	}
	
	function saveRuleKey(){
		if(isset($this->params["form"]['rule_id'])
		&&!empty($this->params["form"]['rule_id'])){
			$this->data["rule_id"]=$this->params["form"]['rule_id'];
			$this->data["rule_key"]=$this->params["form"]['rule_key'];
			$this->data["content_type_code"]=$this->params["form"]['content_type_code'];
			$this->data["content_id"]=$this->params["form"]['content_id'];
			
			
			$weixinRuleKey=$this->WeixinRuleKey->save($this->data);
			
			if($weixinRuleKey){
			
			$this->set('pass',array("return_code"=>111,"return_message"=>"saveRuleKey successfully !"));
			}else{
				$this->set('pass',array("return_code"=>111,"return_message"=>"saveRuleKey failure !"));
			
			}
			$this->isRenderTemple=false;
		}else{
			
		}
		
	}
	
	function saveRule(){
		if(isset($this->params["form"]['rule_name'])
		&&!empty($this->params["form"]['rule_name'])){
			$this->data["rule_name"]=$this->params["form"]['rule_name'];
			$this->data["rule_adapter_id"]=$this->params["form"]['rule_adapter_id'];
			$this->data["customer_id"]=$this->params["form"]['customer_id'];
			$this->data["rule_type"]=$this->params["form"]['rule_type'];
			$this->data["rule_group_id"]=$this->params["form"]['rule_group_id'];
			
			/* $this->data["content_type_code"]=$this->params["form"]['content_type_code'];
			$this->data["content_id"]=$this->params["form"]['content_id'];
				 */
				 
				 //file_put_contents("test2.txt",$this->data["group_id"]);
				/*$this->WeixinRule->save($this->data);*/
			if($this->WeixinRule->save($this->data)){
				//$weixinRule=$this->WeixinRule->find();
			/* $this->data["rule_id"]=$this->$weixinRule->rule_id;
			$this->data["rule_key"]=$this->params["form"]['rule_key'];
			$this->data["content_id"]=$this->params["form"]['content_id'];
			$this->data["content_type_code"]=$this->params["form"]['content_type_code']; */
			
			$weixinRuleKey=$this->WeixinRuleKey->save(array("rule_id"=>$this->WeixinRule->id,
					"rule_key"=>$this->params["form"]['rule_key'],
					"content_id"=>$this->params["form"]['content_id'],
					"content_type_code"=>$this->params["form"]['content_type_code'],
			));
			};
				
			if($weixinRuleKey){
					
				$this->set('pass',array("return_code"=>111,"return_message"=>"saveRuleKey successfully !"));
			}else{
				$this->set('pass',array("return_code"=>112,"return_message"=>"saveRuleKey failure !"));
					
			}
			
			$this->isRenderTemple=false;
		}else{
				
		}
	
	}
	
	
	function ruleAdapterList(){
		if(true){
			$ruleAdapterList=$this->WeixinRuleAdapter->find("all");
			$this->_clearClass($ruleAdapterList);
			$this->set('pass',array("return_code"=>112,"return_message"=>"get regularList successfully !","ruleAdapterList"=>$ruleAdapterList));
			$this->isRenderTemple=false;
		}
	}

	


}
