<?php

//APP::import("Controller","NoModel");

App::import('Controller', "NoModelController",false);

//require "NoModel_Controller.php";

class DoubanAuthController extends AppController implements NoModelController
{
	var $name = 'Authkey';
	//var $name = 'User';
	//var $helpers = array('Html', 'Form');
	/*function register()
	 {
	if (!empty($this->data))
	{
	if ($this->User->save($this->data))
	{
	$this->Session->setFlash('Your registration information was accepted.');
	}
	}
	}
	function knownusers()
	{
	$this->set('knownusers', $this->User->findAll(null, array('id', 'username', 'first_name','last_name'), 'id DESC'));
	}*/
	function UserAuth() {
		//$this->autoRender=false;
		$this->loadModel($this->modelClass);
		//echo var_dump($this);
		$auth_key=& $this->Authkey->findBySource_name("Douban");
		$auth_key=$auth_key['Authkey'];
			
		App::import("SourceAdapter.AdapterHandle");
			
		//echo var_dump(AdapterHandle);
		// $adapter=& AdapterHandle::GetAdapter("SourceAdapter.SinaweibooAdapter");

		$adapter=& AdapterHandle::GetAdapter("DoubanAdapter.Doubanoauthv2Adapter");
		//headers_sent($file, $line) ;
		//echo $file.$line;
		//echo var_dump($auth_key);
			
			
		//$code_url = $o->getAuthorizeURL( WB_CALLBACK_URL );
		$url=$adapter->DoAuth($auth_key['akey'],$auth_key['skey'],$this->Session,"http://www.yonganfish.com/myinfosit/DoubanAuth/CallBack");
		//headers_sent($file, $line) ;
		//echo $file.$line;
		$this->redirect($url);
		//echo var_dump($auth_key);
	}

	function CallBack() {
		//echo var_dump($_COOKIE);//$_REQUEST['oauth_verifier'];
		// echo var_dump($this->Session);
		//echo var_dump($_SESSION);
		App::import("SourceAdapter.AdapterHandle");
			
		//echo var_dump(AdapterHandle);
		$adapter=& AdapterHandle::GetAdapter("DoubanAdapter.Doubanoauthv2Adapter");
		//headers_sent($file, $line) ;
		//echo $file.$line;
		//echo var_dump($auth_key);
			
		$url=$adapter->GetAccessToken($this->Session,"http://www.yonganfish.com/myinfosit/DoubanAuth/CallBack");
		//$this->redirect($url);
			
		//$this->view=$url;
			
		$this->autoRender=false;
		// App::Import("SourceAdapter.weibolist.php");
			
	}

}