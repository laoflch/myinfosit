<?php
interface ContentTypeAdapter
{
	//static private $instance;

	//public function instance();
	/* {

	if(isset(RuleAdapter::$instance)&&!empty(RuleAdapter::$instance)){
	RuleAdapter::$instance=
		
	}

	} */

	public function out($postObj,$content_id);


}