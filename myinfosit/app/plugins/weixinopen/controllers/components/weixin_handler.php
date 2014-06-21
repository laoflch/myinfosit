<?php
/**
 * Request object for handling alternative HTTP requests
 *
 * Alternative HTTP requests can come from wireless units like mobile phones, palmtop computers,
 * and the like.  These units have no use for Ajax requests, and this Component can tell how Cake
 * should respond to the different needs of a handheld computer and a desktop machine.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller.components
 * @since         CakePHP(tm) v 0.10.4.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import("file","weixinopen.WeixinopenAdapter");

/**
 * Request object for handling HTTP requests
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller.components
 * @link http://book.cakephp.org/view/1291/Request-Handling
 *
 */
class WeixinHandlerComponent extends Object {

	function loginOpenWeixin($userid, $passw){
		$token=WeixinopenAdapter::login_Connect($userid, $passw, null);
		if(isset($token)&&!empty($token)){
			return $token;
				
		}

	}

	function sendMessage($token,$content,$fromfakeid,$tofakeid){
		if(isset($token)&&!empty($token)){
			return WeixinopenAdapter::send_textmessage(WeixinopenAdapter::$curl_entity, $token, null,$content,$fromfakeid,$tofakeid);
		}
	}
	
	function sendMessageByUI($token,$content,$fromfakeid,$tofakeid){
		if(isset($token)&&!empty($token)){
			return WeixinopenAdapter::send_textmessage(WeixinopenAdapter::$curl_entity, $token, null,$content,$fromfakeid,$tofakeid);
		}
	}
	
	function uploadImage($token,$imagefile){
		if(isset($token)&&!empty($token)){
			return WeixinopenAdapter::upload_image(WeixinopenAdapter::$curl_entity, $token, null, $imagefile);
		}
		
	}
	
	function createMixMessage($token,$fileid,$title,$digest,$content){
		if(isset($token)&&!empty($token)&&isset($fileid)&&!empty($fileid)){
			return WeixinopenAdapter::create_mixmessage(WeixinopenAdapter::$curl_entity, $token, null,$fileid,$title,$digest,$content);
		}
		
	}
	
	function fetch_all_user_info($token){
		if(isset($token)&&!empty($token)){
			$contacts=WeixinopenAdapter::fetch_user_list(WeixinopenAdapter::$curl_entity, $token, null,null,null);
			if(is_array($contacts)){
				foreach ($contacts as $contact){
					if(isset($contact->id)&&!empty($contact->id)){
					$contact->info=WeixinopenAdapter::fetch_user_info(WeixinopenAdapter::$curl_entity, $token, null,$contact->id)->contact_info;
					}
				}
				
			}elseif (is_object($contacts)){
				if(isset($contacts->id)&&!empty($contacts->id)){
				$contacts->info=WeixinopenAdapter::fetch_user_info(WeixinopenAdapter::$curl_entity, $token, null,$contacts->fakeId)->contact_info;
				}
				
			}
			
			return $contacts;
			
		}
		
		return false;
		
	
	}
	
	function fetch_friends_ungroup($token){
		if(isset($token)&&!empty($token)){
		    $user_list=WeixinopenAdapter::fetch_friends_by_group(WeixinopenAdapter::$curl_entity, $token, null,"0");
			if(is_array($user_list)){
				
			  return $user_list;
			}
				
		}
	
		return false;
	
	
	}
	
	function change_user_group($token,$fakeId,$groupId){
		if(isset($token)&&!empty($token)&&
		   isset($fakeId)&&!empty($fakeId)&&
		   isset($groupId)&&!empty($groupId)){
			if(WeixinopenAdapter::update_friend_group(WeixinopenAdapter::$curl_entity,$token,null,$fakeId,$groupId)){
				return true;
			}
			
		}		
		return false;		
	}
	
	function fetchMessageList($token){
		if(isset($token)&&!empty($token)){
			$message_list=WeixinopenAdapter::fetch_message_list(WeixinopenAdapter::$curl_entity, $token, null);
			if(is_array($message_list)){
				
			  return $message_list;
			}
				
		}
		
		return false;
	
	}
	
	function getAccessToken($controller,$customer_id){
		if(isset($customer_id)&&!empty($customer_id)
		 &&isset($controller)&&!empty($controller)){
			if($controller->WeixinAccessToken){
				
				$access_token=$controller->WeixinAccessToken->find("first",
						array('conditions' => array('WeixinAccessToken.customer_id' => $customer_id)));//=>$this->params["form"]['open_account_id']);
			    
				if($access_token&&isset($access_token)&&!empty($access_token)){
					if($access_token["WeixinAccessToken"]["update_time"]+$access_token["WeixinAccessToken"]["expires_in"]<time()){
						$access_token_update=$this->updateAccessToken($access_token["WeixinAccessToken"]["app_id"],$access_token["WeixinAccessToken"]["app_secret"]);
						if($access_token_update&&isset($access_token_update)&&!empty($access_token_update)){
							$access_token_update["customer_id"]=$access_token["WeixinAccessToken"]["customer_id"];
							$access_token_update["update_time"]=time();
							$access_token_update=$controller->WeixinAccessToken->save($access_token_update);
							return $access_token_update["WeixinAccessToken"]["access_token"];
						}
					}else{
						return $access_token["WeixinAccessToken"]["access_token"];
					}
				}
			}
			
		}
		
		return false;
	}
	
	function updateAccessToken($app_id,$app_secret){
		if(isset($app_id)&&!empty($app_id)&&
		isset($app_secret)&&!empty($app_secret)){
			$access_token=WeixinopenAdapter::fetch_access_token($app_id,$app_secret);
			if(is_array($access_token)){
					
				return $access_token;
			}elseif(is_object($access_token)){

				return (array)$access_token;
			}

		}
	}
	
	function updateSelfMenu($access_token,$menu_str){
		if(isset($access_token)&&!empty($access_token)&&
		isset($menu_str)&&!empty($menu_str)){
			$return_obj=WeixinopenAdapter::update_self_menu($access_token,$menu_str);
			if($return_obj&&isset($return_obj)&&!empty($return_obj)&&$return_obj->errmsg==="ok"){
			
				return true;
			}
		}
		
		
	}

}
	
	

