<?php
//App::import('file',"weixinopen.OrderAdapter");
App::import('file',"weixinopen.AbstractContentTypeAdapter");


class OrderContentAdapter extends AbstractContentTypeAdapter implements ContentTypeAdapter
{
	var $model;
	public function out($postObj,$contentId){
		//$this->model=new WeixinRuleContentText();

		//$this->model;
		if(isset($postObj)&&!empty($postObj)&&isset($contentId)&&!empty($contentId)){
			if (!PHP5) {
				$this->model =& ClassRegistry::init(array(
						'class' => "Weinxinopen.WeixinRuleContentOrder", 'alias' => "WeixinRuleContentOrder", 'id' => null
				));
			} else {
				$this->model = ClassRegistry::init(array(
						'class' => "Weinxinopen.WeixinRuleContentOrder", 'alias' => "WeixinRuleContentOrder", 'id' => null
				));
			}

			if(isset($this->model)&&!empty($this->model)){
				$ruleContentOrder=$this->model->findByContentId($contentId);



			}

			if(isset($ruleContentOrder)&&!empty($ruleContentOrder)){
			
			$pluginName=$ruleContentOrder["WeixinRuleContentOrder"]["plugin"];
			$adapterName=$ruleContentOrder["WeixinRuleContentOrder"]["adapter_name"];
			App::import('file',$pluginName.".".$adapterName);
			$adapter=new $adapterName();
			$returnStr=$adapter->order($postObj,$contentId);
			
			if($returnStr){

				$return_array=array("ToUserName"=>(String)$postObj->FromUserName,
						"FromUserName"=>(String)$postObj->ToUserName,
						"CreateTime"=>time(),
						"MsgType"=>"text",
						"Content"=>$returnStr,
						"FuncFlag"=>0);

				return $return_array;


			}
			 }

		}

		return null;
	}
	
/* 	function maxPic($picUrl){
		
		if(isset($picUrl)&&!empty($picUrl)){
			return  preg_replace('/\.(png|jpg)/','_max.$1',$picUrl);
		}
		return null;
	}
	
	function minPic($picUrl){
	
		if(isset($picUrl)&&!empty($picUrl)){
			return  preg_replace('/\.(png|jpg)/','_min.$1',$picUrl);
		}
		return null;
	}

 */

}