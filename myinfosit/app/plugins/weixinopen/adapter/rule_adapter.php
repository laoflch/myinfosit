<?php
interface RuleAdapter
{
	//static private $instance;
	
	//public function instance();
	/* {
		
		if(isset(RuleAdapter::$instance)&&!empty(RuleAdapter::$instance)){
			RuleAdapter::$instance=
			
		}
		
	} */
	
	//public function parse($postObj,$matchRule,$weixinContentTypeModel,$WeixinRuleContentText);
	
	public function parse($postObj,$matchRule,$weixinController);
	

}