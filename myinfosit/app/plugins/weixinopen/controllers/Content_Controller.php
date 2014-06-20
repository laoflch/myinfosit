<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);

class ContentController extends WeixinopenAppController implements NoModelController
{

	var $helpers = array('Javascript');

	

	var $uses = array('weixinopen.WeixinContentType','weixinopen.WeixinRuleContentText','weixinopen.WeixinRuleContentMix','weixinopen.WeixinRuleContentPicture','weixinopen.WeixinRuleContentVideo'
			,'weixinopen.WeixinRuleContentAudio','weixinopen.WeixinRuleContentReplyMix');

	var $components = array('RequestHandler');

	

	//var $components = array('RequestHandler');

	function contentTypeList(){
		$contentTypeList=$this->WeixinContentType->find("all");
		$this->_clearClass($contentTypeList);
		$this->set('pass',array("return_code"=>107,"return_message"=>"get regularList successfully !","contentTypeList"=>$contentTypeList));
		$this->isRenderTemple=false;
			
	}
	
	function contentTextList(){
		/* if(isset($this->params["form"]['account_open_id'])
		&&!empty($this->params["form"]['account_open_id'])){ */
		if(true){
		$contentTextList=$this->WeixinRuleContentText->find("all");
		$this->_clearClass($contentTextList);
		$this->set('pass',array("return_code"=>107,"return_message"=>"get regularList successfully !","contentTextList"=>$contentTextList));
		$this->isRenderTemple=false;
		}
	}
	
	function contentMixList(){
		/* if(isset($this->params["form"]['account_open_id'])
			&&!empty($this->params["form"]['account_open_id'])){ */
		if(true){
			$contentMixList=$this->WeixinRuleContentReplyMix->find("all");
			$this->_clearClass($contentMixList);
			$this->set('pass',array("return_code"=>107,"return_message"=>"get regularList successfully !","contentMixList"=>$contentMixList));
			$this->isRenderTemple=false;
		}
	}
	
	function contentMain(){
		if(isset($this->params["form"]['contentType'])
		&&!empty($this->params["form"]['contentType'])
		/* &&isset($this->params["form"]['account_open_id'])
		&&!empty($this->params["form"]['account_open_id']) */
		){
			
			if($this->params["form"]['contentType']==="text"){
				
				$contentTextList=$this->WeixinRuleContentText->find("all");
				$this->_clearClass($contentTextList); 
				$this->set('pass',array("return_code"=>113,"return_message"=>"get contentTextList successfully !","contentList"=>$contentTextList));
				//$this->isRenderTemple=false;
			}elseif($this->params["form"]['contentType']==="mix"){
				$contentMixList=$this->WeixinRuleContentMix->find("all");
				$this->_clearClass($contentMixList);
				$this->set('pass',array("return_code"=>115,"return_message"=>"get contentMixList successfully !","contentList"=>$contentMixList));
			}elseif($this->params["form"]['contentType']==="picture"){
				$contentPictureList=$this->WeixinRuleContentPicture->find("all");
				$this->_clearClass($contentPictureList);
				$this->set('pass',array("return_code"=>117,"return_message"=>"get contentPictureList successfully !","contentList"=>$contentPictureList));
			}elseif($this->params["form"]['contentType']==="video"){
				$contentVideoList=$this->WeixinRuleContentVideo->find("all");
				$this->_clearClass($contentVideoList);
				$this->set('pass',array("return_code"=>119,"return_message"=>"get contentVideoList successfully !","contentList"=>$contentVideoList));
			}elseif($this->params["form"]['contentType']==="audio"){
				$contentAudioList=$this->WeixinRuleContentAudio->find("all");
				$this->_clearClass($contentAudioList);
				$this->set('pass',array("return_code"=>121,"return_message"=>"get contentAudioList successfully !","contentList"=>$contentAudioList));
			}
			
		}
		
	}
	
	function contentTextSave(){
		if(isset($this->params["form"]['content_id'])
		&&!empty($this->params["form"]['content_id'])
		/* &&isset($this->params["form"]['account_open_id'])
			&&!empty($this->params["form"]['account_open_id']) */
		){
				
			$this->data["content_id"]=$this->params["form"]['content_id'];
			$this->data["content_name"]=$this->params["form"]['content_name'];
			$this->data["text_message"]=$this->params["form"]['text_message'];
			//$this->data["content_id"]=$this->params["form"]['content_id'];
				
				
			$weixinRuleContentText=$this->WeixinRuleContentText->save($this->data);
				
			if($weixinRuleContentText){
					
				$this->set('pass',array("return_code"=>120,"return_message"=>"WeixinRuleContentText successfully !"));
			}else{
				$this->set('pass',array("return_code"=>121,"return_message"=>"WeixinRuleContentText failure !"));
					
			}
			
				
		}elseif(isset($this->params["form"]['content_name'])
		&&!empty($this->params["form"]['content_name'])){
			$this->data["content_name"]=$this->params["form"]['content_name'];
			$this->data["text_message"]=$this->params["form"]['text_message'];
			$weixinRuleContentText=$this->WeixinRuleContentText->save($this->data);
			
			if($weixinRuleContentText){
					
				$this->set('pass',array("return_code"=>122,"return_message"=>"WeixinRuleContentText add successfully !"));
			}else{
				$this->set('pass',array("return_code"=>123,"return_message"=>"WeixinRuleContentText add failure !"));
					
			}
			
		}
		
		$this->isRenderTemple=false;
	
	}
	
	function fetchContentText(){
		if(isset($this->params["form"]['content_id'])
		&&!empty($this->params["form"]['content_id'])
		){
	
			
				$contentTextList=$this->WeixinRuleContentText->find("all",array('conditions' => array('content_id' =>$this->params["form"]['content_id']) ));
				$this->_clearClass($contentTextList);
				$this->set('pass',array("return_code"=>117,"return_message"=>"get contentText successfully !","contentText"=>$contentTextList));
				$this->isRenderTemple=false;
			
	
		}
	
	}
	
	function fetchContentMix(){
		if(isset($this->params["form"]['content_id'])
		&&!empty($this->params["form"]['content_id'])
		){
		
			
			$contentMixList=$this->WeixinRuleContentMix->find("all",array('conditions' => array('content_id' =>$this->params["form"]['content_id']) ));
			$this->_clearClass($contentMixList);
			$this->set('pass',array("return_code"=>124,"return_message"=>"get contentMix successfully !","contentText"=>$contentMixList));
			$this->isRenderTemple=false;
			
		
			}
		
	}
	function fetchContentPicture(){
		if(isset($this->params["form"]['content_id'])
		&&!empty($this->params["form"]['content_id'])
		){
	
				
			$contentPictureList=$this->WeixinRuleContentPicture->find("all",array('conditions' => array('content_id' =>$this->params["form"]['content_id']) ));
			$this->_clearClass($contentPictureList);
			$this->set('pass',array("return_code"=>124,"return_message"=>"get contentMix successfully !","contentText"=>$contentPictureList));
			$this->isRenderTemple=false;
				
	
		}
	
	}

	
}