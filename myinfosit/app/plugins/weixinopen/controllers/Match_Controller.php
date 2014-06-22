<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);


class MatchController extends WeixinopenAppController implements NoModelController
{

	var $components = array('WeixinHandler');
	
	var $uses = array('weixinopen.WeixinFriend','weixinopen.WeixinOpenAccount','weixinopen.WeixinFriendMap');

	function matchUserByMessage(){
	
		if($this->RequestHandler->isPost()){
			
			if(isset($this->params["form"])&&!empty($this->params["form"])){
				//var_dump($this->params["form"]);
				if(isset($this->params["form"]['create_time'])&&!empty($this->params["form"]['create_time'])&&
				isset($this->params["form"]['from_user_name'])&&!empty($this->params["form"]['from_user_name'])&&
				isset($this->params["form"]['to_user_name'])&&!empty($this->params["form"]['to_user_name'])
				){
					
					$create_time=$this->params["form"]['create_time'];
					$from_user_name=$this->params["form"]['from_user_name'];
					$to_user_name=$this->params["form"]['to_user_name'];
					$openAccount=$this->WeixinOpenAccount->findByOriginalUserId($to_user_name);
					
					
					
					if(isset($openAccount)&&!empty($openAccount)){
						$customer_id=$openAccount["WeixinOpenAccount"]["customer_id"];
						$sit_login_name=$openAccount["WeixinOpenAccount"]["sit_login_name"];
						$password=$openAccount["WeixinOpenAccount"]["password"];
						
						if((!isset($customer_id))||empty($customer_id)){
							return true;
							
						}
					}else{
						return true;
					}
					
					$friendMap=$this->WeixinFriendMap->find('all',
							array('conditions' => array('customer_id' => $customer_id,
									'original_user_id' => $from_user_name)));
					
					
					if(isset($friendMap)&&!empty($friendMap)){
						return true;
					}
					
					
					
					$this->__initConnet($sit_login_name,$password);
					
					if(isset($this->token)&&!empty($this->token)){
						//echo 12345;
						
						
						$end=true;
						do{
						$message_list=$this->WeixinHandler->fetchMessageList($this->token);
						
						foreach ($message_list as $message){
							//var_dump($message);
							//$create_time;
							
							if($message->date_time===intval($create_time)){
								
							$friendMap=array('uuid' => $message->fakeid.$customer_id,
                                                                         'fake_id' => $message->fakeid,
                                                                         'customer_id' => $customer_id,							                 
									 'original_user_id' => $from_user_name
									
							);                         
							
                                                        $returnStr=$this->WeixinFriendMap->save($friendMap);
							if(!$returnStr){
								$end=false;
								break;
									
							}
								
								$end=false;
								break;
								
							
							
							}
						
						}
						sleep(1);
						}while($end);
							
					}
					
					
					//var_dump($message_list);
				
						
				}
			}else{
				return false;
			}
		}elseif($this->RequestHandler->isGet()){
			if(isset($this->params["url"])&&!empty($this->params["url"])){
				//if(true){
				if(isset($this->params["url"]['create_time'])&&!empty($this->params["url"]['create_time'])&&
				   isset($this->params["url"]['from_user_name'])&&!empty($this->params["url"]['from_user_name'])&&
				   isset($this->params["url"]['to_user_name'])&&!empty($this->params["url"]['to_user_name'])){
					
					$create_time=$this->params["url"]['create_time'];
					$from_user_name=$this->params["url"]['from_user_name'];
					$to_user_name=$this->params["url"]['to_user_name'];
					$openAccount=$this->WeixinOpenAccount->findByOriginalUserId($to_user_name);
					
					
					
					if(isset($openAccount)&&!empty($openAccount)){
						$customer_id=$openAccount["WeixinOpenAccount"]["customer_id"];
						$sit_login_name=$openAccount["WeixinOpenAccount"]["sit_login_name"];
						$password=$openAccount["WeixinOpenAccount"]["password"];
						
						if((!isset($customer_id))||empty($customer_id)){
							return true;
							
						}
					}else{
						return true;
					}
					
					$friendMap=$this->WeixinFriendMap->find('all',
							array('conditions' => array('customer_id' => $customer_id,
									'original_user_id' => $from_user_name)));
					/* $var = var_export($friendMap,TRUE);
					file_put_contents("test8.txt","postStr".$var,FILE_APPEND); */
					
					if(isset($friendMap)&&!empty($friendMap)){
						return true;
					}
					
					
					
					$this->__initConnet($sit_login_name,$password);
					
					if(isset($this->token)&&!empty($this->token)){
						//echo 12345;
						
						
						$end=true;
						do{
						$message_list=$this->WeixinHandler->fetchMessageList($this->token);
						
						foreach ($message_list as $message){
							//var_dump($message);
							//$create_time;
							
							if($message->date_time===intval($create_time)){
								
							$friendMap=array('uuid' => $message->fakeid.$customer_id,
                                                                         'fake_id' => $message->fakeid,
                                                                         'customer_id' => $customer_id,							                 
									 'original_user_id' => $from_user_name
									
							);                         
							
                                                        $returnStr=$this->WeixinFriendMap->save($friendMap);
							if(!$returnStr){
								$end=false;
								break;
									
							}
								
								$end=false;
								break;
								
							
							
								
							
							
							}
						
						}
						sleep(1);
						}while($end);
							
					}
					
					
					//var_dump($message_list);
				
						
				}
			}else{
				return false;
			}
		}
	
	}
	
	function matchUserByAttentionEvent(){
		
	if($this->RequestHandler->isPost()){
			if(isset($this->params["form"])&&!empty($this->params["form"])){
				//var_dump($this->params["form"]);
				//if(isset($this->params["form"]['create_time'])&&!empty($this->params["form"]['create_time'])&&
				if(isset($this->params["form"]['from_user_name'])&&!empty($this->params["form"]['from_user_name'])&&
				isset($this->params["form"]['to_user_name'])&&!empty($this->params["form"]['to_user_name'])){
					
					//$create_time=$this->params["form"]['create_time'];
					$from_user_name=$this->params["form"]['from_user_name'];
					$to_user_name=$this->params["form"]['to_user_name'];
					$openAccount=$this->WeixinOpenAccount->findByOriginalUserId($to_user_name);
					
					
					
					if(isset($openAccount)&&!empty($openAccount)){
						$customer_id=$openAccount["WeixinOpenAccount"]["customer_id"];
						$sit_login_name=$openAccount["WeixinOpenAccount"]["sit_login_name"];
						$password=$openAccount["WeixinOpenAccount"]["password"];
						
						if((!isset($customer_id))||empty($customer_id)){
							return true;
							
						}
					}else{
						return true;
					}
					
					$friendMap=$this->WeixinFriendMap->find('all',
							array('conditions' => array('customer_id' => $customer_id,
									'original_user_id' => $from_user_name)));
					
					
					if(isset($friendMap)&&!empty($friendMap)){
						return true;
					}
						
					$this->__initConnet($sit_login_name,$password);
						
					if(isset($this->token)&&!empty($this->token)){
						$end=true;
						do{
						
						$friend_list=$this->WeixinHandler->fetch_friends_ungroup($this->token);
						
						if(count($friend_list)>0){						
							
							
							

                                                        $friendMap=array('uuid' => $friend_list[0]->id.$customer_id,
                                                                         'fake_id' => $friend_list[0]->id,
                                                                         'customer_id' => $customer_id,							                 
									 'original_user_id' => $from_user_name
									
							);                         
							
                                                        $returnStr=$this->WeixinFriendMap->save($friendMap);
							 if(!$returnStr){
								$end=false;
								break;
									
							} 
                                                        /* $var = var_export($returnStr,TRUE);
                                                         file_put_contents("test28.txt",$var,FILE_APPEND); */
							$this->WeixinHandler->change_user_group($this->token,$friendMap["fake_id"],/* "678038100", */"100");
							
								
								$end=false;
								break;								
							
							
						}	

						
						
						sleep(5);
						}while($end);
							
						}
						
			
				}
			}else{
				return false;
			}
			
		}elseif($this->RequestHandler->isGet()){
			if(isset($this->params["url"])&&!empty($this->params["url"])){
				//if(isset($this->params["url"]['create_time'])&&!empty($this->params["url"]['create_time'])&&
				if(isset($this->params["url"]['from_user_name'])&&!empty($this->params["url"]['from_user_name'])&&
				isset($this->params["url"]['to_user_name'])&&!empty($this->params["url"]['to_user_name'])){
					
					//$create_time=$this->params["url"]['create_time'];
					$from_user_name=$this->params["url"]['from_user_name'];
					$to_user_name=$this->params["url"]['to_user_name'];
					$openAccount=$this->WeixinOpenAccount->findByOriginalUserId($to_user_name);
					
					
					
					if(isset($openAccount)&&!empty($openAccount)){
						$customer_id=$openAccount["WeixinOpenAccount"]["customer_id"];
						$sit_login_name=$openAccount["WeixinOpenAccount"]["sit_login_name"];
						$password=$openAccount["WeixinOpenAccount"]["password"];
						
						if((!isset($customer_id))||empty($customer_id)){
							return true;
							
						}
					}else{
						return true;
					}
					
					$friendMap=$this->WeixinFriendMap->find('all',
							array('conditions' => array('customer_id' => $customer_id,
									'original_user_id' => $from_user_name)));
					
					
					if(isset($friendMap)&&!empty($friendMap)){
						return true;
					}
						
					$this->__initConnet($sit_login_name,$password);
						
					if(isset($this->token)&&!empty($this->token)){
						$end=true;
						do{
						
						$friend_list=$this->WeixinHandler->fetch_friends_ungroup($this->token);
						
						if(count($friend_list)>0){						
							
							
							

                                                        $friendMap=array('uuid' => $friend_list[0]->id.$customer_id,
                                                                         'fake_id' => $friend_list[0]->id,
                                                                         'customer_id' => $customer_id,							                 
									 'original_user_id' => $from_user_name
									
							);                         
							
                                                        $returnStr=$this->WeixinFriendMap->save($friendMap);
							if(!$returnStr){
								$end=false;
								break;
									
							}
							
							$this->WeixinHandler->change_user_group($this->token,$friendMap["fake_id"],"100");
								
								$end=false;
								break;								
							
							
						}	

						
									
						
						sleep(1);
						}while($end);
							
						}
			
			
					
				} 
			
		}else{
			
		}
		
	}else{
		
	}
	}
}
