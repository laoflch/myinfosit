<?php
App::import('file', "weixinopen.RuleAdapter");


class RuleKeyAdapter extends RuleAdapter
{
	static public $instance;

	public function instance(){

		if(isset(RuleAdapter::$instance)&&!empty(RuleAdapter::$instance)){
			RuleAdapter::$instance=new RuleKeyAdapter();

		}

	}



	public function parse($postObj,$matchRule){
		if(greg_match("/".$matchRule."/",$postObj,$match)){
			
			echo $postObj[];
			
		}
		
		
	}
}