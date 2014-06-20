<?php

/**
 * Class/file loader and path management.
 *
 *
 */

App::Import("SourceAdapter.weibooauth.php");

class SinaweibooAdapter extends AdapterHandle {

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
			
		$o = new WeiboOAuth( $wb_akey , $wb_skey  );
			


		$keys = $o->getRequestToken();
			
		$session->write("wb_akey",$wb_akey);
			
		$session->write("wb_skey",$wb_skey);
			
		$session->write("RequestToken",$keys);
			
			
		// echo var_dump($session);

		$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false ,$callback);
			
		return $aurl;
		//echo $html->link($aurl);

	}


	function GetAccessToken($session){

		$wb_akey=$session->read("wb_akey");
			
		$wb_skey=$session->read("wb_skey");
			
		$RequestToken=$session->read("RequestToken");



		$o = new WeiboOAuth( $wb_akey , $wb_skey , $RequestToken['oauth_token'] , $RequestToken['oauth_token_secret']  );



		// echo $_REQUEST['oauth_verifier'] ;

		$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

		echo $last_key;

	}


}