<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('file', "weixinopen.RuleKeyAdapter",false);
App::import('file', "weixinopen.FsocketAdapter",false);



class WeixinController extends WeixinopenAppController implements NoModelController
{

	var $helpers = array('Javascript');

	var $uses = array('weixinopen.WeixinOriginalUser'
			,'weixinopen.WeixinRule','weixinopen.WeixinRuleAdapter','weixinopen.WeixinRuleKey','weixinopen.WeixinRuleEvent'
			,'weixinopen.WeixinContentType','weixinopen.WeixinRuleContentText');

	var $components = array('WeixinHandler');

	function handle(){
		if($this->RequestHandler->isPost()) {
			//get post data, May be due to the different environments
			$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
/*$var = var_export($postStr,TRUE);
file_put_contents("test8.txt","postStr".$var,FILE_APPEND); */
			/* $postStr="
					<xml>
					<ToUserName><![CDATA[gh_b1cef7dbee50]]></ToUserName>
					<FromUserName><![CDATA[gh_b1cef7dbee50]]></FromUserName>
					<CreateTime>1348831860</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[你好 ! ]]></Content>
					<MsgId>1234567890123456</MsgId>
					</xml>
					"; */
 /* $postStr="<xml><ToUserName><![CDATA[gh_b1cef7dbee50]]></ToUserName>
<FromUserName><![CDATA[o2ibWjoi-ribPxlZLHuYck1LZiw0]]></FromUserName>
<CreateTime>1387076331</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[诗歌来到美术馆 预约]]></Content>
<MsgId>5932774192960243012</MsgId>
</xml>";  */
/* $postStr="<xml><ToUserName><![CDATA[gh_b1cef7dbee50]]></ToUserName>
<FromUserName><![CDATA[o2ibWjoi-ribPxlZLHuYck1LZiw0]]></FromUserName>
<CreateTime>1381501655</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[subscribe]]></Event>
<EventKey><![CDATA[]]></EventKey>
</xml>"; */ 
			//extract post data
			if (!empty($postStr)){
				 
				if($postObject=$this->__beforeResponse($postStr)){
					$returnStr=$this->__filterRequestContent($postObject);
					if($returnStr){
						echo $returnStr;
						$this->__afterResponse($returnStr);
					}
				    
				}
				
				
				$this->autoRender=false;
				
			}else {
				echo "";
				exit;
			}
		}elseif($this->RequestHandler->isGet()){

			$tmpArr = array("myinfosit", $this->params["url"]["timestamp"], $this->params["url"]["nonce"]);
			sort($tmpArr);
			$tmpStr = implode( $tmpArr );
			$tmpStr = sha1( $tmpStr );

			if( $tmpStr == $this->params["url"]["signature"] ){
					
				//$this->set('echostr', $this->params["url"]["echostr"]);

				echo $this->params["url"]["echostr"];
				$this->autoRender=false;
				//$this->render('/User/home');
					
			}
		}
		return;
			
	}

	function saveOriginalUser(){
		$this->WeixinOriginalUser->save(array("original_user_name"=>"gh_b1cef7dbee50"));

	}

	function __getMatchRuleGroup($originaUserName){
		return $this->WeixinOriginalUser->findByOriginalUserName($originaUserName);



	}

	function __getMatchRule($originalUserId,$msgType){
		$returnArray=array();
		if(isset($originalUserId)&&!empty($originalUserId)
		&&isset($msgType)&&!empty($msgType)){
			$ruleGroups=$this->__getMatchRuleGroup($originalUserId);
			foreach ($ruleGroups as $ruleGroup){
				$returnArray[]=$this->__getMatchRuleByGroup($ruleGroup["rule_group_id"],$msgType);
					
			}
		}
		return $returnArray;

	}

	function __getMatchRuleByGroup($ruleGroupID,$msgType){
		//return $this->WeixinRule->findAllByRuleGroupId($ruleGroupID);
		return $this->WeixinRule->find('all', array(
				'joins' => array(
						array(
								'table' => 'weixin_receive_msg_types',
								'alias' => 'MsgType',
								'type' => 'INNER',
								'conditions' => array(
										'MsgType.msg_type_code = WeixinRule.receive_msg_type'
								)
						)
				),
				'conditions' => array('WeixinRule.rule_group_id' => $ruleGroupID,
				'WeixinRule.rule_order_no >'=>0,
				'MsgType.msg_type_name'=>$msgType),
				'order' => 'WeixinRule.rule_order_no ASC'
		));
	}

	function __filterRequestContent($postObj){
		if(isset($postObj)&&!empty($postObj)){
			$matchRules=$this->__getMatchRule((string)$postObj->ToUserName,(string)$postObj->MsgType);
			
			/* $var = var_export($matchRules,TRUE);
				file_put_contents("test4.txt",$var,FILE_APPEND); */
			foreach ($matchRules as $matchRuleGrgoup){
				foreach ($matchRuleGrgoup as $matchRule){				
				
				$ruleAdapters=$this->__fetchRuleAdapters();
				if($ruleAdapters[$matchRule["WeixinRule"]["rule_adapter_id"]]){
				App::import('file','weixinopen.'.$ruleAdapters[$matchRule["WeixinRule"]["rule_adapter_id"]],false);
			}else{
				continue;
			}
				//$reflectMethod=new ReflectionMethod($ruleAdapters[$matchRule["WeixinRule"]["rule_adapter_id"]],"instance");
				//$ruleAdapter=$reflectMethod.invoke(null);
				$r = new ReflectionClass($ruleAdapters[$matchRule["WeixinRule"]["rule_adapter_id"]]);
				$ruleadapter=$r->getMethod('instance')->invoke(null);
				//$r=RuleKeyAdapter::instance();
				//$ruleAdapter=$matchRule->ruleAdapterId->instance();
				
				//$matchRuleKey=$this->WeixinRuleKey->findByRuleId($matchRule["WeixinRule"]["rule_id"]);				
				
				
				//$contentType=$this->WeixinContentType-find("all");
				//$return_str=$ruleadapter->parse($postObj,$matchRule["WeixinRule"],$this->WeixinContentType,$this->WeixinRuleContentText);
				$return_str=$ruleadapter->parse($postObj,$matchRule["WeixinRule"],$this);
			 
					
				if($return_str){
					return $return_str;
				}
			}
					
			}
			
			
		}
		
		return null;

	}

	function __fetchRuleAdapters(){
		if(!isset($this->__ruleAdapters)||empty($this->__ruleAdapters)){
			//$this->__ruleAdapters=
			$ruleObjs=$this->WeixinRuleAdapter->find("all");
				
				
			foreach ($ruleObjs as $key){
					
				
					$this->__ruleAdapters[$key["WeixinRuleAdapter"]["rule_adapter_id"]]=$key["WeixinRuleAdapter"]["rule_adapter_name"];
				
			}
				
		}
		return $this->__ruleAdapters;

	}

	function sendMessage(){

		$this->__initConnet();

		$return_obj=$this->WeixinHandler->sendMessage($this->token,"hello worlds",null,"678038100");

		if($return_obj->msg==="ok"){

			echo 1234;

		}

		$this->set('pass',array("return_code"=>107,"return_message"=>"send message successfully !"));
		//$this->view_outs[] = array("view_name"=>"tool_nav","target"=>"#container-2");
		//return $this->render(null,null,'index');
		$this->isRenderTemple=false;

			
	}

	function createMixMessage(){

		$this->__initConnet();
		//$token=$this->WeixinHandler->loginOpenWeixin("laoflch@163.com","arsenal");

		$fileid=$this->WeixinHandler->uploadImage($this->token,"/home/laoflch/test.jpg");

		$this->WeixinHandler->createMixMessage($this->token,$fileid,"hello world!","hello world!","http://www.baidu.com");

		$this->set('pass',array("return_code"=>108,"return_message"=>"upload image successfully !"));
		//$this->view_outs[] = array("view_name"=>"tool_nav","target"=>"#container-2");
		//return $this->render(null,null,'index');
		$this->isRenderTemple=false;




	}

	

	function fetchAllUserInfo(){
		$this->__initConnet();
		if(isset($this->token)&&!empty($this->token)){
			$users=$this->WeixinHandler->fetch_all_user_info($this->token);
			$user_friends=array();
			$user_friend_infos=array();
				
				

			foreach ($users as $user){
				$vars=get_object_vars($user);
				//$user;
				//$data=array();
				$data=array();
				foreach (array_keys($vars) as $key){
						
					if(!is_object($vars[$key])&&!is_array($vars[$key])){
						$data=array_merge($data,array($this->__tranVarName($key)=>$vars[$key]));
						/* array("fake_id"=>$user->fakeId,
						 "open_fake_id"=>"678038100",
								"nick_name"=>$user->nickName,
								"remark_name"=>isset($user->remakName)&&!empty($user->remakName)?$user->remakName:"",
								"group_id"=>$user->groupId); */
					}
						
				}
				if(isset($data)&&!empty($data)){
					$data["open_account_id"]="678038100";
					$user_friends[]=$data;
				}

				$vars=get_object_vars($user->info);
				$data=array();
				foreach (array_keys($vars) as $key){
						
					if(!is_object($vars[$key])&&!is_array($vars[$key])){
						$data=array_merge($data,array($this->__tranVarName($key)=>$vars[$key]));
						/* array("fake_id"=>$user->fakeId,
						 "open_fake_id"=>"678038100",
								"nick_name"=>$user->nickName,
								"remark_name"=>isset($user->remakName)&&!empty($user->remakName)?$user->remakName:"",
								"group_id"=>$user->groupId); */
					}
						
				}

				if(isset($data)&&!empty($data)){
					$data["open_account_id"]="678038100";
					$user_friends_infos[]=$data;
				}
				/* $user_friend_info[]=array("fake_id"=>$user->info->fakeId,
				 "user_name"=>$user->info->userName,
						"nick_name"=>$user->info->nickName,
						"remark_name"=>isset($user->info->remakName)&&!empty($user->info->remakName)?$user->info->remakName:"",
						"user_name"=>$user->info->userName,
						"nick_name"=>$user->info->nickName,
						"group_id"=>$user->info->groupId);
				*/
			}

			$this->WeixinFriend->deleteAll(array("open_fake_id"=>"678038100"),false,false);
			$this->WeixinFriend->saveAll($user_friends);
			$this->WeixinFriendInfo->deleteAll(array("open_fake_id"=>"678038100"),false,false);
			$this->WeixinFriendInfo->saveAll($user_friends_infos);

			//$this->WeixinFriendInfo-deleteAll();

		}
			
	}

	function __tranVarName($var_name){
		if(isset($var_name)&&!empty($var_name)){
			return  strtolower(preg_replace("/([a-z])([A-Z])/e","$1.'_'.$2",lcfirst($var_name)));

		}
			
	}

	function addOpenAccount(){
		$open_account_id = strip_tags($this->params["form"]['username']);
		$pw = strip_tags($this->params["form"]['password']);

		//$this->WeixinOpenAccount->save(array("open_account_id"=>$open_account_id,"password"=>$pw));
		$this->WeixinOpenAccount->save(array("open_account_id"=>'$open_account_id',"password"=>'$pw'));



	}
	
	function matchUserId(){
		
		
	}
	
	/* *
	 * 记录信息 
	 * 
	 */
	function __recordMessage($postObj){
		switch ($postObj->MsgType){
			case "text":
				FsocketAdapter::get(Configure::read('App.domain_name'), Configure::read('App.sub_dir')."/weixinopen/message/addTextMessage", $postObj);
                               // file_put_contents("test5.txt","etste",FILE_APPEND);
				break;
			default:
				FsocketAdapter::get(Configure::read('App.domain_name'), Configure::read('App.sub_dir')."/weixinopen/message/addTextMessage", $postObj);
                               //file_put_contents("test5.txt","etsets",FILE_APPEND);
				break;
		}

	}
	
	function __beforeResponse($postStr){
		if(isset($postStr)&&!empty($postStr)){
			$postObject=simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->__recordMessage($postObject);
			
			$this->__matchUserByMessage($postObject);
			return $postObject;
		}
		
		return false;
	}
	
	function __afterResponse($returnStr){
		if(isset($returnStr)&&!empty($returnStr)){
			$returnObject=simplexml_load_string($returnStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->__recordMessage($returnObject);
			
			return true;
		}

		return false;
		
	}
	
	function __matchUserByMessage($postObject){
		
		if(isset($postObject)&&!empty($postObject)){
				
			FsocketAdapter::post(Configure::read('App.domain_name'), Configure::read('App.sub_dir')."/weixinopen/match/matchUserByMessage", $postObject);
		}
	}
	
	function __matchUserByAttentionEvent($postObject){
		/* switch ($postObj->MsgType){
		 case "text": */
		if(isset($postObject)&&!empty($postObject)){
			FsocketAdapter::post(Configure::read('App.domain_name'), Configure::read('App.sub_dir')."/weixinopen/match/matchUserByAttentionEvent", $postObject);
			//file_put_contents("test5.txt","etste",FILE_APPEND);
		}	/* 	break;
		default:
		FsocketAdapter::post(Configure::read('App.domain_name'), Configure::write('App.sub_dir')."/weixinopen/message/addTextMessage", $postObj);
		//file_put_contents("test5.txt","etsets",FILE_APPEND);
		break;
		}*/
	
	
	
	
	}
	


}	////
