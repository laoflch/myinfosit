<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);

class ActivityController extends MahuaAppController implements NoModelController
{

	var $helpers = array('Javascript');

	var $components = array('Cookie','Session');

	var $uses = array('mahua.MahuaCookieKeySourceCode','mahua.MahuaActivityBasicInfo','mahua.MahuaActivityShowTimeInfo');

	//var $components = array('RequestHandler');

	

	
	
	function showActivityInfo(){
		if(isset($this->params["form"]['source_code'])
		&&!empty($this->params["form"]['source_code'])){
		//if(true){
			$key=session_id();
			$codelist=$this->MahuaCookieKeySourceCode->find("first",array('conditions' => array('cookie_key' =>$key) ));
			if(!$codelist
			||$this->params["form"]['source_code']!==$codelist[0]["MahuaCookieKeySourceCode"]["source_code"]){
			//if(true){
				$this->data["cookie_key"]=$key;
				$this->data["source_code"]=$this->params["form"]['source_code'];
				$codelist=$this->MahuaCookieKeySourceCode->save($this->data);
			}
			
			
			if($codelist){
				$this->set('pass',array("return_code"=>122,"return_message"=>"cookie key has save!","showActivityInfo"=>""));
					
			}
		}
	}
	
	function fetchActivityInfo(){
		if(isset($this->params["form"]['activity_id'])
		&&!empty($this->params["form"]['activity_id'])){
			//if(true){
			//$key=session_id();
			//$activitieslist=$this->MahuaActivityBasicInfo->find("first",array('conditions' => array('activity_id' =>$this->params["url"]['activity_id']) ));
			
			$activitieslist=$this->MahuaActivityBasicInfo->query("
					SELECT
					`MahuaActivityBasicInfo`.`activity_id`,
					`MahuaActivityBasicInfo`.`subject`,
					`MahuaActivityBasicInfo`.`single_price`,
					`MahuaActivityBasicInfo`.`default_count`,
					`MahuaActivityBasicInfo`.`total_times`,
					`MahuaActivityBasicInfo`.`theatre`,
					`MahuaActivityBasicInfo`.`address`,
					`MahuaActivityDescriptInfo`.`summry`,
					`MahuaActivityDescriptInfo`.`detail_descript`
					FROM
					`mahua_activity_basic_infos`
					AS `MahuaActivityBasicInfo`
					LEFT JOIN `mahua_activity_descript_infos` AS `MahuaActivityDescriptInfo`
					    ON (`MahuaActivityBasicInfo`.`activity_id` = `MahuaActivityDescriptInfo`.`activity_id`)
				    WHERE `MahuaActivityBasicInfo`.`activity_id`=".$this->params['form']['activity_id']
			);
				
			/* if(!$codelist
			||$this->params["form"]['source_code']!==$codelist[0]["MahuaCookieKeySourceCode"]["source_code"]){
				//if(true){
				$this->data["cookie_key"]=$key;
				$this->data["source_code"]=$this->params["form"]['source_code'];
				$codelist=$this->MahuaCookieKeySourceCode->save($this->data);
			} */
				
			if($activitieslist){
				$this->_clearClass($activitieslist);
				$this->set('pass',array("return_code"=>122,"return_message"=>"activity basic seccessfull","showActivityInfo"=>$activitieslist[0]));
					
			}else{
				$this->set('pass',array("return_code"=>123,"return_message"=>"acctivity basic failed","showActivityInfo"=>$activitieslist[0]));
			}
		}
	}
	
	function fetchBasicInfo(){
		if(isset($this->params["form"]['activity_id'])
		&&!empty($this->params["form"]['activity_id'])){
			//if(true){
			//$key=session_id();
			//$activitieslist=$this->MahuaActivityBasicInfo->find("first",array('conditions' => array('activity_id' =>$this->params["url"]['activity_id']) ));
				
			$activitieslist=$this->MahuaActivityBasicInfo->query("
					SELECT
					`MahuaActivityBasicInfo`.`activity_id`,
					`MahuaActivityBasicInfo`.`subject`,
					`MahuaActivityBasicInfo`.`single_price`,
					`MahuaActivityBasicInfo`.`default_count`,
					`MahuaActivityBasicInfo`.`total_times`,
					`MahuaActivityBasicInfo`.`theatre`,
					`MahuaActivityBasicInfo`.`address`
					FROM
					`mahua_activity_basic_infos`
					AS `MahuaActivityBasicInfo`
					WHERE `MahuaActivityBasicInfo`.`activity_id`=".$this->params['form']['activity_id']
			);
	
			/* if(!$codelist
				||$this->params["form"]['source_code']!==$codelist[0]["MahuaCookieKeySourceCode"]["source_code"]){
			//if(true){
			$this->data["cookie_key"]=$key;
			$this->data["source_code"]=$this->params["form"]['source_code'];
			$codelist=$this->MahuaCookieKeySourceCode->save($this->data);
			} */
	
			if($activitieslist){
				$this->_clearClass($activitieslist);
				$this->set('pass',array("return_code"=>122,"return_message"=>"activity basic seccessfull","showActivityInfo"=>$activitieslist[0]));
					
			}else{
				$this->set('pass',array("return_code"=>123,"return_message"=>"acctivity basic failed","showActivityInfo"=>$activitieslist[0]));
			}
		}
	}
	
	function fetchActivityTimeInfo(){
		/* if(isset($this->params["form"]['activity_id'])
		&&!empty($this->params["form"]['activity_id'])){ */
			//$showtimeslist=$this->MahuaActivityShowTimeInfo->find("all",array('conditions' => array('activity_id' =>$this->params["form"]['activity_id'])));
		$showtimeslist=$this->MahuaActivityShowTimeInfo->find("all",array('conditions' => array('activity_id' =>1)));
		$this->_clearClass($showtimeslist);
		$this->set('pass',array("return_code"=>122,"return_message"=>"activity basic seccessfull","showTimeInfo"=>$showtimeslist));
		/* } */
	}
	
	function getHappyShareActiviesList(){
		/* if(isset($this->params["form"]['account_open_id'])
			&&!empty($this->params["form"]['account_open_id'])){ */
		if(true){
			/* $conditions= array('fields' => array('HappytimeHappyShareActivit.activity_id','HappytimeActivity.activity_name','HappytimeActivity.issue_time','HappytimeActivity.content_id','WeixinRuleContentReplyMix.article_count',
							'WeixinRuleContentReplyMixItem.item_no','WeixinRuleContentReplyMixItem.title','WeixinRuleContentReplyMixItem.description','WeixinRuleContentReplyMixItem.pic_url',
					'WeixinRuleContentReplyMixItem.url'),
					'joins' => array(
							array(
									'alias' => 'HappytimeActivity',
									'table' => 'happytime_activities',
									'type' => 'INNER',
									'conditions' => '`HappytimeHappyShareActivity`.`activity_id` = `HappytimeActivity`.`activity_id`'
							),
							array(
									'alias' => 'WeixinRuleContentReplyMix',
									'table' => 'weixin_rule_content_reply_mixes',
									'type' => 'LEFT',
									'conditions' => '`HappytimeActivity`.`content_id` = `WeixinRuleContentReplyMix`.`content_id`'
							),
							array(
									'alias' => 'WeixinRuleContentReplyMixItem',
									'table' => 'weixin_rule_content_reply_mix_items',
									'type' => 'LEFT',
									'conditions' => '`WeixinRuleContentReplyMix`.`content_id` = `WeixinRuleContentReplyMixItem`.`content_id`'
							)
					),
					'order' => 'HappytimeHappyShareActivity.order_no DESC'
					'page'=>1,'limit'=>4
			);
			*/
			$offset=0;
		    $limit=0;
			if(isset($this->params["form"]['page_info'])&&!empty($this->params["form"]['page_info'])){
				$pageInfo=json_decode($this->params["form"]['page_info']);
				$pageInfo=(array)$pageInfo;
				if(isset($pageInfo["page"])
				&&!empty($pageInfo["page"])
				&&isset($pageInfo["limit"])
				&&!empty($pageInfo["limit"])
				){
					
				    $offset=($pageInfo["page"]-1)*$pageInfo["limit"];
				    $limit=$pageInfo["limit"];
					
				}
					
			}else{
				$pageInfo=array();
			} 
			//$happyShareActivitiesList=$this->HappytimeHappyShareActivity->find("all",array_merge($conditions,$pageInfo));
			$happyShareActivitiesList=$this->HappytimeHappyShareActivity->query("
					SELECT 
					`HappytimeHappyShareActivity`.`activity_id`, 
					`HappytimeActivity`.`activity_name`, 
					`HappytimeActivity`.`issue_time`, 
					`HappytimeActivity`.`content_id`, 
					`WeixinRuleContentReplyMix`.`article_count`, 
					`WeixinRuleContentReplyMixItem`.`item_no`, 
					`WeixinRuleContentReplyMixItem`.`title`, 
					`WeixinRuleContentReplyMixItem`.`description`, 
					`WeixinRuleContentReplyMixItem`.`pic_url`, 
					`WeixinRuleContentReplyMixItem`.`url` 
					FROM 
					(SELECT `activity_id`,`order_no` 
					 FROM 
					 `happytime_happy_share_activities` 
					 ORDER BY `order_no` DESC
					 LIMIT ".$offset.",".$limit." 
					) AS `HappytimeHappyShareActivity` 
					INNER JOIN `happytime_activities` AS `HappytimeActivity` 
					    ON (`HappytimeHappyShareActivity`.`activity_id` = `HappytimeActivity`.`activity_id`)
				    LEFT JOIN `weixin_rule_content_reply_mixes` AS `WeixinRuleContentReplyMix` 
					    ON (`HappytimeActivity`.`content_id` = `WeixinRuleContentReplyMix`.`content_id`) 
					LEFT JOIN `weixin_rule_content_reply_mix_items` AS `WeixinRuleContentReplyMixItem` 
					    ON (`WeixinRuleContentReplyMix`.`content_id` = `WeixinRuleContentReplyMixItem`.`content_id`)  
					");
			
			$this->_clearClass($happyShareActivitiesList);
			$returnhappyShareActivitiesList=array();
			foreach ($happyShareActivitiesList as $happyShareActivity){
				if($last_activity_id===$happyShareActivity["HappytimeHappyShareActivity_activity_id"]){
					if(isset($current_item)&&!empty($current_item)){
						$current_item[]=array("WeixinRuleContentReplyMixItem_item_no"=>$happyShareActivity["WeixinRuleContentReplyMixItem_item_no"],
								//"WeixinRuleContentReplyMixItem_title"=>implode(str_split($happyShareActivity["WeixinRuleContentReplyMixItem_title"],57),"\n"),
								"WeixinRuleContentReplyMixItem_title"=>$happyShareActivity["WeixinRuleContentReplyMixItem_title"],
								"WeixinRuleContentReplyMixItem_description"=>$happyShareActivity["WeixinRuleContentReplyMixItem_description"],
								"WeixinRuleContentReplyMixItem_pic_url"=>$happyShareActivity["WeixinRuleContentReplyMixItem_pic_url"],
								"WeixinRuleContentReplyMixItem_url"=>$happyShareActivity["WeixinRuleContentReplyMixItem_url"]);
					}
					;
				}else{
	
					$current_mix=array("HappytimePassActivity_activity_id"=>$happyShareActivity["HappytimePassActivity_activity_id"],
							"HappytimeActivity_activity_name"=>$happyShareActivity["HappytimeActivity_activity_name"],
							"HappytimeActivity_issue_time"=>$happyShareActivity["HappytimeActivity_issue_time"],
							"HappytimeActivity_content_id"=>$happyShareActivity["HappytimeActivity_content_id"],
							"WeixinRuleContentReplyMix_article_count"=>$happyShareActivity["WeixinRuleContentReplyMix_article_count"],
							"WeixinRuleContentReplyMixItems"=>array(array("WeixinRuleContentReplyMixItem_item_no"=>$happyShareActivity["WeixinRuleContentReplyMixItem_item_no"],
									"WeixinRuleContentReplyMixItem_first"=>($happyShareActivity["WeixinRuleContentReplyMixItem_item_no"]==1?true:false),
									//"WeixinRuleContentReplyMixItem_title"=>implode(str_split($happyShareActivity["WeixinRuleContentReplyMixItem_title"],57),"\n"),
									"WeixinRuleContentReplyMixItem_title"=>$happyShareActivity["WeixinRuleContentReplyMixItem_title"],
									"WeixinRuleContentReplyMixItem_description"=>$happyShareActivity["WeixinRuleContentReplyMixItem_description"],
									"WeixinRuleContentReplyMixItem_pic_url"=>$happyShareActivity["WeixinRuleContentReplyMixItem_pic_url"],
									"WeixinRuleContentReplyMixItem_url"=>$happyShareActivity["WeixinRuleContentReplyMixItem_url"]))
	
					);
					$current_item=&$current_mix["WeixinRuleContentReplyMixItems"];
					$returnhappyShareActivitiesList[]=$current_mix;
					//$current_item=null;
					$last_activity_id=$happyShareActivity["HappytimeHappyShareActivity_activity_id"];
	
	
	
				}
					
					
					
			}
			$this->set('pass',array("return_code"=>107,"return_message"=>"get passActiviesList successfully !","happyShareActiviesList"=>$returnhappyShareActivitiesList));
			$this->isRenderTemple=false;
		}
	}
	
	

	
}