<?php


App::import('Controller', "NoModelController",false);
App::import('Models',"SinaUsersKey",false);

/**
 *
 * @author laoflch
 *
*/

class AuthController extends AppController implements NoModelController
{
	var $name = 'Authkey';


	var $adapter;

	function UserAuth() {
			
		$this->loadModel($this->modelClass);
		$auth_key=& $this->Authkey->findBySource_name("sina_weiboo");
		$auth_key=$auth_key['Authkey'];

		if(!isset($this->adapter)){
			App::import("SourceAdapter.AdapterHandle");
			$this->adapter=& AdapterHandle::GetAdapter("SourceAdapter.Saetoauthv2Adapter");
		}
			
		$url=$this->adapter->DoAuth($auth_key['akey'],$auth_key['skey'],$this->Session,"http://www.yonganfish.com/myinfosit/Auth/CallBack");
			

		$this->redirect($url);
			
	}

	/**
	 *
	 * @author laoflch
	 */
	function CallBack() {

		//获取新浪处理适配器
		if(!isset($this->adapter)){
			App::import("SourceAdapter.AdapterHandle");
			$this->adapter=& AdapterHandle::GetAdapter("SourceAdapter.Saetoauthv2Adapter");
		}
			
		//获取 sina Accesstoken
		$uid=$this->adapter->GetAccessToken($this->Session,"http://www.yonganfish.com/myinfosit/Auth/CallBack");
			
			
		//将新浪登陆用户转为本地用户
		//装载 User 模型
		$this->loadModel("User");
			
		$this->data["username"]=$uid["uid"];
		$this->data["password"]="";
		$this->data["source"]="sina_weibo";
			
		$this->User->save($this->data);
			
		$user=$this->User->findByusername($uid["uid"]);
			
			
		//装载 Sinauserskey 模型
		$this->loadModel("Sinauserskey");
		$uid["source_uid"]=$uid["uid"];
		$uid["uid"]=$user["User"]["uid"];
			
		//保存 sina 登陆用户的 Sinauserskey
		$this->Sinauserskey->save($uid);
			
		//写入 Sessionl
		$this->Session->write("uid",$uid["uid"]);
		$this->Session->write("access_token",$uid["access_token"]);
		$this->Session->write("access_token_expires_in",$uid["expires_in"]);
			
		$this->redirect("/Auth/FetchTimeLine");
			
	}


	/**
	 *
	 *
	 *@author laoflch
	 */

	function FetchTimeLine() {

		$uid=$this->Session->read("uid");
		$access_token=$this->Session->read("access_token");

		if(isset($uid)&&!empty($uid)&&isset($access_token)&&!empty($access_token)){

			if(!isset($this->adapter)){
				App::import("SourceAdapter.AdapterHandle");
				$this->adapter=& AdapterHandle::GetAdapter("SourceAdapter.Saetoauthv2Adapter");
			}

			$message_list=$this->adapter->TimeLine($access_token,$uid,$this->Session);

			if(isset($message_list['statuses'])&&!empty($message_list['statuses'])){
					
				$this->set('message_list', $message_list['statuses']);
				$this->set('message_type', "sina_weibo");
					
				$this->render('/User/home');
			}

		}
		return ;

			
	}

}