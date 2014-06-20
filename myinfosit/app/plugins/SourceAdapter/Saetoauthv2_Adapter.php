<?php

/**
 * Class/file loader and path management.
 *
 *
 */

App::Import("SourceAdapter.saetv2_ex_class.php");



class Saetoauthv2Adapter extends AdapterHandle {

	var $access_toke;

	var $client;

	var $auth;

	/**
	 * Returns an array of filenames of PHP files in the given directory.
	 *So scan for files
	 * @param string $suffix if false, return only directories. if string, match and return files
	 * @return array  List of directories or files in directory
	 * @access private
	 */
	function DoAuth($wb_akey,$wb_skey,$session,$callback) {

		//App::Import("SourceAdapter.weibooauth.php");
			
		//$o = new WeiboOAuth( $wb_akey , $wb_skey  );
		if(!isset($this->auth)){
			$this->auth = new SaeTOAuthV2( $wb_akey , $wb_skey );
		}

		// $keys = $o->getRequestToken();
			
		$session->write("wb_akey",$wb_akey);
			
		$session->write("wb_skey",$wb_skey);
			
		// $session->write("RequestToken",$keys);
			
			
		// echo var_dump($session);
		$code_url = $this->auth->getAuthorizeURL($callback);
		//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false ,$callback);
			
		return $code_url;
		//echo $html->link($aurl);

	}


	function GetAccessToken($session,$callback){

		$wb_akey=$session->read("wb_akey");
			
		$wb_skey=$session->read("wb_skey");
			
		//$RequestToken=$session->read("RequestToken");

		if(!isset($this->auth)){
			$this->auth = new SaeTOAuthV2( $wb_akey , $wb_skey  );
		}
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $callback;
			try {
				$token = $this->auth->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}

		if ($token) {
			$_SESSION['token'] = $token;
			//setcookie( 'weibojs_'.$token->client_id, http_build_qluery($token) );
			//$o = new WeiboOAuth( $wb_akey , $wb_skey , $RequestToken['oauth_token'] , $RequestToken['oauth_token_secret']  );

			//$this->redirect("/Auth/weibolist");
			//return "/pages/Auth/weibolist";
			//return "weibolist";
			$this->access_toke=$token;
		}
		return $this->access_toke;
	}

	function TimeLine($access_token,$uid,$session) {
		$wb_akey=$session->read("wb_akey");
			
		$wb_skey=$session->read("wb_skey");
		if(!isset($this->clinet)&&isset($wb_akey)&&isset($wb_skey)&&isset($access_token)){
			$this->client = new SaeTClientV2( $wb_akey , $wb_skey , $access_token );
		}
			
		return $this->client->home_timeline();

	}

	function GetUserInfo(){
		$wb_akey=$session->read("wb_akey");
			
		$wb_skey=$session->read("wb_skey");
		if(!isset($this->cline)&&isset($wb_akey)&&isset($wb_ske)&&isset($this->access_toke)){
			$this->cline = new SaeTClientV2( $wb_akey , $wb_ske , $this->access_toke);
		}
			
		$uid=$this->client->get_uid();
			
		if(!empty($uid)){
			return $uid;
		}

	}


}