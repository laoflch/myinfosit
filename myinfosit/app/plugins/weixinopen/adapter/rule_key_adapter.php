<?php
App::import('file',"weixinopen.RuleAdapter");
App::import('file',"weixinopen.AbstractRuleAdapter");


class RuleKeyAdapter extends AbstractRuleAdapter implements RuleAdapter
{
	public static  $instance;



	public static  function instance(){

			
		if(!isset(self::$instance)||empty(self::$instance)){
			self::$instance=new RuleKeyAdapter();

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
			$matchRuleKey=$weixinController->WeixinRuleKey->findByRuleId($matchRule["rule_id"]);
		}
		
		
		if(isset($matchRuleKey)&&!empty($matchRuleKey)){
		if(preg_match("/".$matchRuleKey["WeixinRuleKey"]["rule_key"]."/",(String)$postObj->Content,$match)){
			//if(false){
				
			//if($matchRule->){
			return parent::parse($postObj
					,$weixinController->WeixinContentType->findByContentTypeCode($matchRuleKey["WeixinRuleKey"]["content_type_code"])
					,$matchRuleKey["WeixinRuleKey"]["content_id"]);
			//}
				
		}

		}


	}

}