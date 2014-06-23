<?php
App::import('Controller', "MdController",false);
/**
 *
 * @author laoflch
 *
*/

class HappytimeAppController extends MdController {
	
	var $helpers = array('Javascript');
	
	var $components = array('RequestHandler');
	
	//var $uses = array('WeixinOpenAccount');

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
	

	}
	
	

}