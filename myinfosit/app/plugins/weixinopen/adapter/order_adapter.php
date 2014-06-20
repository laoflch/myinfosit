<?php
interface OrderAdapter
{
	//static private $instance;

	//public function instance();
	/* {

	if(isset(RuleAdapter::$instance)&&!empty(RuleAdapter::$instance)){
	RuleAdapter::$instance=
		
	}

	} */

	public function order($postObj,$contentId);


}