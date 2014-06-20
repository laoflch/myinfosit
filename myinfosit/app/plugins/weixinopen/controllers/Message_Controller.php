<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);



class MessageController extends WeixinopenAppController implements NoModelController
{
    var $uses = array('weixinopen.WeixinMessageText','weixinopen.WeixinMessage','weixinopen.WeixinOpenAccount');
    
    
    
    var $components = array('RequestHandler','WeixinHandler');
    
	function view() {
			
	}

	function logMessage(){
		$Messages=$this->WeixinMessageText->find("all");

	}
	
	function addTextMessage(){
		
		if($this->RequestHandler->isPost()){
		if(isset($this->params["form"])&&!empty($this->params["form"])&&$this->params["form"]['msg_type']=="text"){
			/* if(isset($this->params["form"]['msg_type'])
					&&!empty($this->params["form"]['msg_type'])){ */
		//$this->WeixinMessageText->create();
		
		//if(isset($this->data["WeixinMessageText"])){
			//$this->data["WeixinMessageText"]["msg_type"]=$this->params["form"]['msg_type'];
			//$this->data["msg_type"]="text";
			$this->data["msg_type"]=$this->params["form"]['msg_type'];
			$this->data["to_user_name"]=$this->params["form"]['to_user_name'];
			$this->data["from_user_name"]=$this->params["form"]['from_user_name'];
			$this->data["create_time"]=$this->params["form"]['create_time'];
			$this->data["from_msg_id"]=$this->params["form"]['from_msg_id'];
			$this->data['WeixinMessageTextOnly']['content'] = $this->params["form"]['content'];
		//}
		}else{
			return;
		}
		}elseif($this->RequestHandler->isGet()){
			
			$var = var_export($this->params,TRUE);

		file_put_contents("test6.txt","test;".$var,FILE_APPEND);
			
		if(isset($this->params["url"])&&!empty($this->params["url"])&&$this->params["url"]['msg_type']=="text"){
			$this->data["msg_type"]="text";
			$this->data["to_user_name"]=$this->params["url"]['to_user_name'];
			$this->data["from_user_name"]=$this->params["url"]['from_user_name'];
			$this->data["create_time"]=intval($this->params["url"]['create_time']);
			if(isset($this->params["url"]['msg_id'])&&!empty($this->params["url"]['msg_id'])){
//$var = var_export($this->params["url"]['msg_id']."+++++".floatval($this->params["url"]['msg_id']),TRUE);

		//file_put_contents("test6.txt","test2;".$var,FILE_APPEND);
			$this->data["from_msg_id"]=floatval($this->params["url"]['msg_id']);
			}
			$this->data['WeixinMessageTextOnly']['content'] = rawurldecode($this->params["url"]['content']);
			
		}else{
			return ;
		}
		}		
		
		$content = $this->WeixinMessageText->save( $this->data );
		
		if( !empty( $content )&&(isset($this->params["form"]['content'])||isset($this->params["url"]['content'])) ) {
			$this->data['WeixinMessageTextOnly']['message_id'] = $this->WeixinMessageText->id;
			
			$this->WeixinMessageText->WeixinMessageTextOnly->save( $this->data );
			
		}
		
	}	
	
	function sendTextMessageByUI(){
		if($this->RequestHandler->isPost()){
			if(isset($this->params["form"])&&!empty($this->params["form"])&&$this->params["form"]['msg_type']==="text"){
				if(isset($this->params["form"]['fake_id'])&&!empty($this->params["form"]['fake_id'])
				&&isset($this->params["form"]['content'])&&!empty($this->params["form"]['content'])){ 
				 	$this->__initConnet();
					
					if(isset($this->token)&&!empty($this->token)){
						$return_str=$this->WeixinHandler->sendMessageByUI($this->token,$this->params["form"]['content'],null,$this->params["form"]['fake_id']);
						//$return_str=$this->WeixinHandler->sendMessageByUI($this->token,"nihao",null,"678038100");
						
						
						   $this->set('pass',array("return_code"=>107,"return_message"=>"get friend info successfully !","return_str"=>$return_str));
						
						
					}
					
			}
			}else{
				return;
		}}
		 
	
	}
	
	function getMessagesSampleByCustomerId(){
		if($this->RequestHandler->isPost()){
			if(isset($this->params["form"])&&!empty($this->params["form"])&&$this->params["form"]['msg_type']==="text"){
				if(isset($this->params["form"]['customer_id'])&&!empty($this->params["form"]['customer_id'])
				/* &&isset($this->params["form"]['content'])&&!empty($this->params["form"]['content']) */){ 
				$open_account=$this->WeixinOpenAccount->find("first",array('conditions' => array('customer_id' =>$this->params["form"]['customer_id']) ));
				$messages_sample=$this->WeixinMessage->find("all",array('conditions' => array(
						'or'=>array(
								'to_user_name' =>$open_account[0]["WeixinOpenAccount"]["original_user_id"]))));
				$this->_clearClass($messages_sample);
				if($messages_sample){
				$this->set('pass',array("return_code"=>107,"return_message"=>"get message successfully !","message_list"=>$messages_sample));
				}
						
				} 
			}else{
				return;
			}}
			
	}
	
	function messageMain(){
		if(isset($this->params["form"]['messageType'])
		&&!empty($this->params["form"]['messageType'])
		&&isset($this->params["form"]['customer_id'])
		&&!empty($this->params["form"]['customer_id'])
		/* &&isset($this->params["form"]['account_open_id'])
		 &&!empty($this->params["form"]['account_open_id']) */
		){
			/* $this->params["form"]['messageType']==="sample" */
			if($this->params["form"]['messageType']==="sample"){
		
			
				if(isset($this->params["form"]['customer_id'])&&!empty($this->params["form"]['customer_id'])){
				/* &&isset($this->params["form"]['content'])&&!empty($this->params["form"]['content']) ){*/
				$open_account=$this->WeixinOpenAccount->find("first",array('conditions' => array('customer_id' =>1/* $this->params["form"]['customer_id'] */)));
				$messages_sample=$this->WeixinMessage->find("all",array('conditions' => array(
						'or'=>array(
								'to_user_name' =>$open_account["WeixinOpenAccount"]["original_user_id"],
								'from_user_name' =>$open_account[0]["WeixinOpenAccount"]["original_user_id"]
						))));
				$this->_clearClass($messages_sample);
				if($messages_sample){
				$this->set('pass',array("return_code"=>107,"return_message"=>"get message successfully !","messageList"=>$messages_sample));
				}
						
				} 
			
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
	
	
}
