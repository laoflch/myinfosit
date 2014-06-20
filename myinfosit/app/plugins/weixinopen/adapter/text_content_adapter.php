<?php
//App::import('file',"weixinopen.ContentTypeAdapter");
App::import('file',"weixinopen.AbstractContentTypeAdapter");


class TextContentAdapter extends AbstractContentTypeAdapter implements ContentTypeAdapter
{
	var $model;
	public function out($postObj,$contentId){
		//$this->model=new WeixinRuleContentText();

		//$this->model;
		if(isset($postObj)&&!empty($postObj)&&isset($contentId)&&!empty($contentId)){
			if (!PHP5) {
				$this->model =& ClassRegistry::init(array(
						'class' => "Weinxinopen.WeixinRuleContentText", 'alias' => "WeixinRuleContentText", 'id' => null
				));
			} else {
				$this->model = ClassRegistry::init(array(
						'class' => "Weinxinopen.WeixinRuleContentText", 'alias' => "WeixinRuleContentText", 'id' => null
				));
			}

			if(isset($this->model)&&!empty($this->model)){
				$contentText=$this->model->findByContentId($contentId);



			}

			if(isset($contentText)&&!empty($contentText)){

				$return_array=array("ToUserName"=>(String)$postObj->FromUserName,
						"FromUserName"=>(String)$postObj->ToUserName,
						"CreateTime"=>time(),
						"MsgType"=>"text",
						"Content"=>$contentText["WeixinRuleContentText"]["text_message"],
						"FuncFlag"=>0);

				return $return_array;


			}


		}

		return null;
	}



}