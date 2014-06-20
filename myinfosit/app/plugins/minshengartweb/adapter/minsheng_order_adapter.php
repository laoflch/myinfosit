<?php
App::import('file',"weixinopen.OrderAdapter");
//App::import('file',"weixinopen.AbstractContentTypeAdapter");


class MinshengOrderAdapter implements OrderAdapter
{
	var $model;
	public function order($postObj,$contentId){
		//$this->model=new WeixinRuleContentText();

		//$this->model;
		if(isset($postObj)&&!empty($postObj)&&isset($contentId)&&!empty($contentId)){
			if (!PHP5) {
				$this->model =& ClassRegistry::init(array(
						'class' => "Minshengartweb.MinshengRuleContentOrder", 'alias' => "MinshengRuleContentOrder", 'id' => null
				));
			} else {
				$this->model = ClassRegistry::init(array(
						'class' => "Minshengartweb.MinshengRuleContentOrder", 'alias' => "MinshengRuleContentOrder", 'id' => null
				));
			}

			if(isset($this->model)&&!empty($this->model)){
				//$ruleContentOrder=$this->model->findByContentId($contentId);
				$ruleContentOrders=$this->model->find('all',
						array('conditions' => array('MinshengRuleContentOrder.content_id' => $contentId),
								'order' => 'MinshengRuleContentOrder.order_no ASC'));


			}

			if(isset($ruleContentOrders)&&!empty($ruleContentOrders)){

				/* $return_array=array("ToUserName"=>(String)$postObj->FromUserName,
						"FromUserName"=>(String)$postObj->ToUserName,
						"CreateTime"=>time(),
						"MsgType"=>"news",
						"ArticleCount"=>intval($contentReplyMix["WeixinRuleContentReplyMix"]["article_count"])); */
				//$aticle=array();
				foreach ($ruleContentOrders as $ruleContentOrder){
					//$ruleContentOrder["MinshengRuleContentOrder"]["text"];//$postObj
					if(preg_match("/".$ruleContentOrder["MinshengRuleContentOrder"]["key_word"]."/",(String)$postObj->Content,$match)){
						//if(false){
					
						//if($matchRule->){
						if (!PHP5) {
					$this->model =& ClassRegistry::init(array(
							'class' => "Minshengartweb.MinshengOrder", 'alias' => "MinshengOrder", 'id' => null
					));
				} else {
					$this->model = ClassRegistry::init(array(
							'class' => "Minshengartweb.MinshengOrder", 'alias' => "MinshengOrder", 'id' => null
					));
				}
				
				$order=$this->model->save(array("from_fake_id"=>(String)$postObj->FromUserName,"activity_id"=>$ruleContentOrder["MinshengRuleContentOrder"]["activity_id"]));
				
				
				if($order){
					$order_code=substr("0".(string)$order["MinshengOrder"]["activity_id"],-2).substr("000".(string)$this->model->id,-4);
					$order=$this->model->save($order["MinshengOrder"]+array("order_code"=>$order_code));
					if($order){
						if (!PHP5) {
							$this->model =& ClassRegistry::init(array(
									'class' => "Minshengartweb.MinshengActivitie", 'alias' => "MinshengActivitie", 'id' => null
							));
						} else {
							$this->model = ClassRegistry::init(array(
									'class' => "Minshengartweb.MinshengActivitie", 'alias' => "MinshengActivitie", 'id' => null
							));
						}
					$activity=$this->model->findByActivityId($order["MinshengOrder"]["activity_id"]);
					return "您预约".$activity["MinshengActivitie"]["activity_name"]."活动已经成功，预约号为：".$order_code;
					}
				}
				
				return false;
					
					}
					return false;
				}
			
				
				
			}

		}

		return false;
	}	

}