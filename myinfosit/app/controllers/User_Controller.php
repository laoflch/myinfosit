<?php
App::import('Controller', "MdController",false);


class UserController extends MdController
{
	var $uses = array('User','WeixinUserInfo');
	var $helpers = array('Javascript');


	/*json handler 2013-05-19*/
	var $components = array('RequestHandler','Session');



	function view() {
			
	}

	function login(){
		$user=$this->Session->read("CURRENT_USER");
		if(isset($user)&&!empty($user)
		&&(!isset($this->params["form"]['username'])||empty($this->params["form"]['username']))
		//false
		){
			$this->set('pass',array("return_code"=>103,"return_message"=>"login successfully !"));
			//$this->view_outs[] = array("view_name"=>"tool_nav","target"=>"container-1");
			
		}else{

			$user = strip_tags(substr($this->params["form"]['username'],0,32));
			$pw = strip_tags(substr($this->params["form"]['password'],0,32));
			// $email = strip_tags(substr($this->params["form"]['email'],0,32));
			if(strpos($user,"@")){
				preg_match_all("/^(.*)\@.*$/",$user,$out,PREG_PATTERN_ORDER);
				$user=&$this->User->findByemail($user);
			}else{
				$user=&$this->User->findByusername($user);
			}


			$cleanpw = crypt(md5($pw),'$1$'.substr(md5($user["User"]["username"]),1,8).'$');
			

			if($user["User"]["password"]===$cleanpw){
				$this->Session->write("CURRENT_USER",$user);


				//$this->redirect("/User/UserHome");
				$UserInfo=$this->WeixinUserInfo->FindByUid($user["User"]["uid"]);
				if(isset($UserInfo)&&!empty($UserInfo)){
					//$this->Session->write("CURRENT_USER_INFO",$UserInfo);
					//$this->Session->write("CURRENT_CUSTOMER_ID",$UserInfo["user_info"]["WeixinUserInfo"]["default_customer"]);
					$this->set('pass',array("return_code"=>103,"return_message"=>"login successfully !","user_info"=>$UserInfo));
					
				}else{
					$this->set('pass',array("return_code"=>103,"return_message"=>"login successfully !"));
				}
				
				
				
			}else{
				//$this->autoRender=false;
				$this->set('pass',array("return_code"=>104,"return_message"=>"login failure !"));
				
				$this->isRenderTemple=false;
			}
		}

	}
	
	function logout(){
		$user=$this->Session->read("CURRENT_USER");
		if(isset($user)&&!empty($user)
		&&(!isset($this->params["form"]['username'])||empty($this->params["form"]['username']))
		//false
		){
			
			$this->Session->delete("CURRENT_USER");
			$this->set('pass',array("return_code"=>129,"return_message"=>"logout successfully !"));
			//$this->view_outs[] = array("view_name"=>"tool_nav","target"=>"container-1");
				
		}else{
	
			$this->set('pass',array("return_code"=>129,"return_message"=>"logout successfully !"));
		}
		
		$this->isRenderTemple=false;
	
	}

	function hasLogin(){
		$user=$this->Session->read("CURRENT_USER");
		if(isset($user)&&!empty($user)){
			$this->set('pass',array("return_code"=>105,"return_message"=>"has logined !"));
			$this->isRenderTemple=false;
		}else{
			$this->set('pass',array("return_code"=>106,"return_message"=>"has't  logined !"));
			$this->isRenderTemple=false;
		}
	}

	function register(){
		$user = strip_tags(substr($this->params["form"]['username'],0,32));
		$pw = strip_tags(substr($this->params["form"]['password'],0,32));


		if(strpos($user,"@")){
			preg_match_all("/^(.*)\@.*$/",$user,$out,PREG_PATTERN_ORDER);
			$user=$out[1][0];
		}
		$cleanpw = crypt(md5($pw),'$1$'.substr(md5($user),1,8).'$');

		$this->data["username"]=$user;
		$this->data["password"]=$cleanpw;
		$this->data["email"]=$out[0][0];

			
		$check=$this->__checkvalite();
		if($check){
			return $check;
		}
		$this->User->save($this->data);

	}

	function adduser() {
			
	}

	function adduserbyweibo(){


	}

	function __checkvalite(){
		//echo gettype($this->Session->read("valite"));
		if($this->Session->read("valite")!==trim($this->params["form"]['valitecode'])){
			//echo "你好！";
			return $this->render(null,null,'volitecodemiss');
		}

		return 0;
	}

	function index() {
		//App::import('Helper', 'Javascript');
		//$javascript=new JavascriptHelper();
		$this->set('aPosts', $this->User->find("all"));
	}

	function checkunqieuser(){
		$user = strip_tags(substr($this->params["url"]['username'],0,32));
		$user = &$this->User->findByusername($user);
		if($user){
			$this->set('aPosts', array("return_code"=>101,"return_message"=>"user has exist!"));
		}else{
			$this->set('aPosts', array("return_code"=>102,"return_message"=>"user has't exist!"));
		}
	}


}



