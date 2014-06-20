<?php

/**
 * Class/file loader and path management.
 *
 *
 */

App::Import("QqAdapter.Oauth_class.php");

class Qqoauthv2Adapter extends AdapterHandle {

	/**
	 * Returns an array of filenames of PHP files in the given directory.
	 *
	 * @param string $path Path to scan for files
	 * @param string $suffix if false, return only directories. if string, match and return files
	 * @return array  List of directories or files in directory
	 * @access private
	 */
	function DoAuth($wb_akey,$wb_skey,$session,$callback) {

		//App::Import("SourceAdapter.weibooauth.php");
			
		//$o = new WeiboOAuth( $wb_akey , $wb_skey  );
		$o = new Oauth( $wb_akey , $wb_skey );


		// $keys = $o->getRequestToken();
			
		$session->write("wb_akey",$wb_akey);
			
		$session->write("wb_skey",$wb_skey);
			
		// $session->write("RequestToken",$keys);
			
			
		// echo var_dump($session);
		$code_url = $o->qq_login($callback);
		//$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false ,$callback);
			
		return $code_url;
		//echo $html->link($aurl);

	}


	function GetAccessToken($session,$callback){

		$wb_akey=$session->read("wb_akey");
			
		$wb_skey=$session->read("wb_skey");
			
		//$RequestToken=$session->read("RequestToken");
		echo $wb_akey;
		echo $wb_skey;

		$o = new Oauth( $wb_akey , $wb_skey  );

		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = $callback;
			try {
				$token = $o->qq_callback('code',$keys) ;
			} catch (OAuthException $e) {
			}
		}

		if ($token) {
			$_SESSION['token'] = $token;
			setcookie( 'qqjs_'.$o->client_id, http_build_query($token) );
			//$o = new WeiboOAuth( $wb_akey , $wb_skey , $RequestToken['oauth_token'] , $RequestToken['oauth_token_secret']  );

			//$this->redirect("/Auth/weibolist");
			//return "/pages/Auth/weibolist";
			//return "weibolist";
			echo $token;

		}else{

		}
		// echo $_REQUEST['oauth_verifier'] ;

		// $last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

		// echo $last_key;
	}
}