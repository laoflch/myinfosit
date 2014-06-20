<?php
App::import('file',"weixinopen.RuleAdapter");
App::import('file',"weixinopen.AbstractRuleAdapter");
App::import('file',"weixinopen.FsocketAdapter",false);


class RuleEventAdapter extends AbstractRuleAdapter implements RuleAdapter
{
	public static  $instance;



	public static  function instance(){

			
		if(!isset(self::$instance)||empty(self::$instance)){
			self::$instance=new RuleEventAdapter();

		}

		return self::$instance;

	}

	public function parse($postObj,$matchRule,$weixinController){
		//if(preg_match("/".$matchRule["WeixinRuleKey"]["rule_key"]."/",(String)$postObj->Content,$match)){
		/* $strtest = "yyg中文字符yyg";
		//$pregstr = "/[\x{4e00}-\x{9fa5}]+/u";
		$pregstr = "/你好/";
		if(preg_match($pregstr,$strtest,$matchArray)){
			echo $matchArray[0];
		} */
		if(isset($matchRule)&&!empty($matchRule)
		&&isset($matchRule["rule_id"])&&!empty($matchRule["rule_id"])){
			$matchRuleEvent=$weixinController->WeixinRuleEvent->findByRuleId($matchRule["rule_id"]);
		}
		
		
		if(isset($matchRuleEvent)&&!empty($matchRuleEvent)){
			if((string)$postObj->Event===$matchRuleEvent["WeixinRuleEvent"]["event"]){
	    if((string)$postObj->EventKey===$matchRuleEvent["WeixinRuleEvent"]["event_key"]||empty($matchRuleEvent["WeixinRuleEvent"]["event_key"])){
		//if(preg_match("/".$matchRuleKey["WeixinRuleKey"]["rule_key"]."/",(String)$postObj->Content,$match)){
			//if(false){
				
			//if($matchRule->){
			return parent::parse($postObj
					,$weixinController->WeixinContentType->findByContentTypeCode($matchRuleEvent["WeixinRuleEvent"]["content_type_code"])
					,$matchRuleEvent["WeixinRuleEvent"]["content_id"]);
			//}
				
		}}

		}
		
		return null;


	}
	
	function __beforeParse($postObject){
		if(isset($postObject)&&!empty($postObject)&&
		   (String)$postObject->MsgType==="event"&&
		   (String)$postObject->Event==="subscribe"){
			FsocketAdapter::post(Configure::read('App.domain_name'), Configure::read('App.sub_dir')."/weixinopen/match/matchUserByAttentionEvent", $postObject);
			//file_put_contents("test5.txt","etste",FILE_APPEND);
		}	
		
	}

}