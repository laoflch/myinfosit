<?php
//App::import('file',"weixinopen.ContentTypeAdapter");
App::import('file',"weixinopen.AbstractContentTypeAdapter");


class ReplyMixContentAdapter extends AbstractContentTypeAdapter implements ContentTypeAdapter
{
	var $model;
	public function out($postObj,$contentId){
		//$this->model=new WeixinRuleContentText();

		//$this->model;
		if(isset($postObj)&&!empty($postObj)&&isset($contentId)&&!empty($contentId)){
			if (!PHP5) {
				$this->model =& ClassRegistry::init(array(
						'class' => "Weinxinopen.WeixinRuleContentReplyMix", 'alias' => "WeixinRuleContentReplyMix", 'id' => null
				));
			} else {
				$this->model = ClassRegistry::init(array(
						'class' => "Weinxinopen.WeixinRuleContentReplyMix", 'alias' => "WeixinRuleContentReplyMix", 'id' => null
				));
			}

			if(isset($this->model)&&!empty($this->model)){
				$contentReplyMix=$this->model->findByContentId($contentId);



			}

			if(isset($contentReplyMix)&&!empty($contentReplyMix)){

				$return_array=array("ToUserName"=>(String)$postObj->FromUserName,
						"FromUserName"=>(String)$postObj->ToUserName,
						"CreateTime"=>time(),
						"MsgType"=>"news",
						"ArticleCount"=>intval($contentReplyMix["WeixinRuleContentReplyMix"]["article_count"]));
				$aticle=array();
				
				if (!PHP5) {
					$this->model =& ClassRegistry::init(array(
							'class' => "Weinxinopen.WeixinRuleContentReplyMixItem", 'alias' => "WeixinRuleContentReplyMixItem", 'id' => null
					));
				} else {
					$this->model = ClassRegistry::init(array(
							'class' => "Weinxinopen.WeixinRuleContentReplyMixItem", 'alias' => "WeixinRuleContentReplyMixItem", 'id' => null
					));
				}
				
				$contentReplyMixItem=$this->model->findAllByContentId($contentId);
				if(isset($contentReplyMixItem)&&!empty($contentReplyMixItem)&&count($contentReplyMixItem)>0){
				
				if(count($contentReplyMixItem)>=1){
					
				for($i=0;$i<$contentReplyMix["WeixinRuleContentReplyMix"]["article_count"];$i++){
					$aticle[$i]=array("item"=>array(
					"Title"=>$contentReplyMixItem[$i]["WeixinRuleContentReplyMixItem"]["title"],
							"Decription"=>$contentReplyMixItem[$i]["WeixinRuleContentReplyMixItem"]["decription"],
							"PicUrl"=>$i===0?$this->maxPic($contentReplyMixItem[$i]["WeixinRuleContentReplyMixItem"]["pic_url"])
							:$this->minPic($contentReplyMixItem[$i]["WeixinRuleContentReplyMixItem"]["pic_url"]),
							"Url"=>$contentReplyMixItem[$i]["WeixinRuleContentReplyMixItem"]["url"],
					));
					
					
				}
				}/* elseif(count($contentReplyMixItem)==1){
					$aticle=array("item"=>array(
							"Title"=>$contentReplyMixItem["WeixinRuleContentReplyMixItem"]["title"],
							"Decription"=>$contentReplyMixItem["WeixinRuleContentReplyMixItem"]["decription"],
							"PicUrl"=>$this->maxPic($contentReplyMixItem["WeixinRuleContentReplyMixItem"]["pic_url"]),
							"Url"=>$contentReplyMixItem["WeixinRuleContentReplyMixItem"]["url"],
					));
				} */
				$return_array["Articles"]=$aticle;

				return $return_array;


			}
			}

		}

		return null;
	}
	
	function maxPic($picUrl){
		
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



}