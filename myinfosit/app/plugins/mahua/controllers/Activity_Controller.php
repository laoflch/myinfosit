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

	

	var $uses = array('happytime.MahuaShowActivity');

	//var $components = array('RequestHandler');

	

	
	
	function showActivityInfo(){
		/* if(isset($this->params["form"]['account_open_id'])
		&&!empty($this->params["form"]['account_open_id'])){ */
		if(true){
			
		/* 	$conditions= array('fields' => array('HappytimePassActivity.activity_id','HappytimeActivity.activity_name','HappytimeActivity.issue_time','HappytimeActivity.content_id','WeixinRuleContentReplyMix.article_count',
							'WeixinRuleContentReplyMixItem.item_no','WeixinRuleContentReplyMixItem.title','WeixinRuleContentReplyMixItem.description','WeixinRuleContentReplyMixItem.pic_url',
							'WeixinRuleContentReplyMixItem.url'),
							'joins' => array(
									array(
											'alias' => 'HappytimeActivity',
											'table' => 'happytime_activities',
											'type' => 'INNER',
											'conditions' => '`HappytimePassActivity`.`activity_id` = `HappytimeActivity`.`activity_id`'
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
				            'order' => 'HappytimePassActivity.order_no DESC');
			
			if(isset($this->params["form"]['page_info'])&&!empty($this->params["form"]['page_info'])){
				$pageInfo=json_decode($this->params["form"]['page_info']);
				$pageInfo=(array)$pageInfo;
					
			}else{
				$pageInfo=array();
			}
		$passActivitiesList=$this->HappytimePassActivity->find("all",array_merge($conditions,$pageInfo));
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
		$passActivitiesList=$this->MahuaShowActivity->query("
					SELECT
					`HappytimePassActivity`.`activity_id`,
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
					 `happytime_pass_activities`
					 ORDER BY `order_no` DESC
					 LIMIT ".$offset.",".$limit."
					) AS `HappytimePassActivity`
					INNER JOIN `happytime_activities` AS `HappytimeActivity`
					    ON (`HappytimePassActivity`.`activity_id` = `HappytimeActivity`.`activity_id`)
				    LEFT JOIN `weixin_rule_content_reply_mixes` AS `WeixinRuleContentReplyMix`
					    ON (`HappytimeActivity`.`content_id` = `WeixinRuleContentReplyMix`.`content_id`)
					LEFT JOIN `weixin_rule_content_reply_mix_items` AS `WeixinRuleContentReplyMixItem`
					    ON (`WeixinRuleContentReplyMix`.`content_id` = `WeixinRuleContentReplyMixItem`.`content_id`)
					");
			
		
		$this->_clearClass($passActivitiesList);
		$returnPassActivitiesList=array();
		foreach ($passActivitiesList as $passActivity){
			if($last_activity_id===$passActivity["HappytimePassActivity_activity_id"]){
				if(isset($current_item)&&!empty($current_item)){
					/* if($current_mix["WeixinRuleContentReplyMixItem_single"]){
						$current_mix["WeixinRuleContentReplyMixItem_single"]=false;
						
					} */
					$current_item[]=array("WeixinRuleContentReplyMixItem_item_no"=>$passActivity["WeixinRuleContentReplyMixItem_item_no"],
								                                //"WeixinRuleContentReplyMixItem_title"=>implode(str_split($passActivity["WeixinRuleContentReplyMixItem_title"],57),"\n"),
							                                    "WeixinRuleContentReplyMixItem_title"=>$passActivity["WeixinRuleContentReplyMixItem_title"],
								                                "WeixinRuleContentReplyMixItem_description"=>$passActivity["WeixinRuleContentReplyMixItem_description"],
								                                "WeixinRuleContentReplyMixItem_pic_url"=>$passActivity["WeixinRuleContentReplyMixItem_pic_url"],
								                                "WeixinRuleContentReplyMixItem_url"=>$passActivity["WeixinRuleContentReplyMixItem_url"]);
				}
				;
			}else{
				
				$current_mix=array("HappytimePassActivity_activity_id"=>$passActivity["HappytimePassActivity_activity_id"],
						/* "WeixinRuleContentReplyMixItem_single"=>true, */
						"HappytimeActivity_activity_name"=>$passActivity["HappytimeActivity_activity_name"],
						"HappytimeActivity_issue_time"=>$passActivity["HappytimeActivity_issue_time"],
						"HappytimeActivity_content_id"=>$passActivity["HappytimeActivity_content_id"],
						"WeixinRuleContentReplyMix_article_count"=>$passActivity["WeixinRuleContentReplyMix_article_count"],
						"WeixinRuleContentReplyMixItems"=>array(array("WeixinRuleContentReplyMixItem_item_no"=>$passActivity["WeixinRuleContentReplyMixItem_item_no"],
								                                "WeixinRuleContentReplyMixItem_first"=>($passActivity["WeixinRuleContentReplyMixItem_item_no"]==1?true:false),
								                                //"WeixinRuleContentReplyMixItem_title"=>implode(str_split($passActivity["WeixinRuleContentReplyMixItem_title"],57),"\n"),
								                                "WeixinRuleContentReplyMixItem_title"=>$passActivity["WeixinRuleContentReplyMixItem_title"],
								                                "WeixinRuleContentReplyMixItem_description"=>$passActivity["WeixinRuleContentReplyMixItem_description"],
								                                "WeixinRuleContentReplyMixItem_pic_url"=>$passActivity["WeixinRuleContentReplyMixItem_pic_url"],
								                                "WeixinRuleContentReplyMixItem_url"=>$passActivity["WeixinRuleContentReplyMixItem_url"]))
				
				);
				$current_item=&$current_mix["WeixinRuleContentReplyMixItems"];
				$returnPassActivitiesList[]=$current_mix;
				//$current_item=null;
				$last_activity_id=$passActivity["HappytimePassActivity_activity_id"];
				
				
				
			}
			
			
			
		}
		$this->set('pass',array("return_code"=>107,"return_message"=>"get passActiviesList successfully !","passActiviesList"=>$returnPassActivitiesList));
		$this->isRenderTemple=false;
		}
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