<?php
App::import('Controller', "MdController",false);
/**
 *
 * @author laoflch
 *
*/

class WeixinopenAppController extends MdController {
	
	var $helpers = array('Javascript');
	
	var $components = array('RequestHandler');
	
	var $uses = array('WeixinOpenAccount');

	function _clearClass(&$data){
		//if(is_array($classNames)&&isset($classNames[0])&&!empty($classNames[0])){
		for ($i=0;$i<count($data);$i++){

			//foreach ($classNames as $className){
			foreach ($data[$i] as $className=>$classvalue){
				foreach ($classvalue as $key=>$value){
					if(!isset($temp_entity)){
						$temp_entity=array();
					}
					$temp_entity[$className."_".$key]=$value;
				}
				//$temp_entity[$className][$key]=$value;
			}
			//}
			unset($data[$i]);
			$data[$i]=$temp_entity;
			unset($temp_entity);
		}

		/* }elseif(is_string($classNames)){
			foreach ($data as $key=> $entity){
		$data[$key]=$entity[$classNames];
			
		}
			
		} */

	}
	
	function __initConnet(){
		
		if(!isset($this->token)&&empty($this->token)){
			switch (func_num_args()){
			case 1 :
			
				
				$this->token=$this->WeixinHandler->loginOpenWeixin("laoflch@163.com","liverpool");
			
			    break;
			case 2 :
				$sit_login_name = func_get_arg(0);
				$password = func_get_arg(1);
				$this->token=$this->WeixinHandler->loginOpenWeixin($sit_login_name,$password);
			    break;
			default :
				$this->token=$this->WeixinHandler->loginOpenWeixin("laoflch@163.com","liverpool");
			}
		}
	
	}
	
	

}