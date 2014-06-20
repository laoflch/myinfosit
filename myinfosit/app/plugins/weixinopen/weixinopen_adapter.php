<?php

/**
 * Class/file loader and path management.
 *
 *
 */
class WeixinopenAdapter extends Object {

	static public $curl_entity;

	static public function login_Connect($userid,$passw,$url){
	if(!isset(WeixinopenAdapter::$curl_entity)||empty(WeixinopenAdapter::$curl_entity)){
			$curl=curl_init(); // 启动一个CURL会话

			//$curl=WeixinopenAdapter::$curl_entity;
			//$data ="username=laoflch%40163.com&pwd=".md5("arsenal")."&imgcode=&f=json";
			if(isset($userid)&&!empty($userid)){
				$data ="username=".$userid;

			}
			if(isset($passw)&&!empty($passw)){
				$data .="&pwd=".md5($passw);
			}

			$data .="&imgcode=&f=json";

			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN"); // 要访问的地址
			}

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转

			curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
			curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
			curl_setopt($curl, CURLOPT_HEADER, 1); // 显示返回的Header区域内容
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
			curl_setopt($curl, CURLOPT_REFERER, "https://mp.weixin.qq.com/");

			$tmpInfo = curl_exec($curl); // 执行操作

			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}

			if(preg_match("/(Content-Type:.*)(?=;\s+)/i", $tmpInfo, $matches)){
				list($key,$contenttype)=explode(':',$matches[0]);
				unset($matches);
				if(trim($contenttype)=="application/json"){
					if(preg_match("/(?=\s*)[\{\[][\S\s]*[\}\]](?=\s*)/i", $tmpInfo, $matches)){
						$result_array=json_decode($matches[0]);
						
						unset($matches);
	//				if(isset($result_array->Ret)&& !empty($result_array->Ret)&&isset($result_array->ErrCode)&&$result_array->Ret===302 && $result_array->ErrCode===0){
	if(isset($result_array->base_resp)&& !empty($result_array->base_resp)&&isset($result_array->base_resp->ret)&&$result_array->base_resp->ret===0 &&$result_array->base_resp->err_msg==="ok"){

							preg_match("/token\=([0-9]*)/i", $result_array->redirect_url, $matches);

							$token=$matches[1];
$var = var_export($token,TRUE);
file_put_contents("test37.txt",$var,FILE_APPEND); 
							//unset($matches);
							$cookie_st=WeixinopenAdapter::__handleCookie($tmpInfo);
							$cookie_st=array_merge(array("hasNotifyList=1","hasWarningUser=1","pt2gguin=o0053562664","o_cookie=53562664","pgv_pvi=5120925696","pgv_pvid=5680274286"),$cookie_st);
							curl_setopt($curl,CURLOPT_COOKIE,implode(";",$cookie_st));
							WeixinopenAdapter::$curl_entity=$curl;
							return $token;
						}else{
							echo "登录失败!";
							curl_close($curl);
							return false;
						}
					}
				}

			}
		}

	}

	static public function __handleCookie($tmpInfo){

		if(preg_match_all("/Set-Cookie:\s(.*?\=.*?)(?=;\s+)/i", $tmpInfo, $matches)){
			//curl_setopt($curl,CURLOPT_COOKIE,"hasNotifyList=1; hasWarningUser=1; pt2gguin=o0053562664; o_cookie=53562664; pgv_pvi=5120925696; css_ssid=7911952466; pgv_pvid=5680274286;".implode(";",$matches[1]));
			//var_dump($matches[1]);

		}

		return $matches[1];
	}

	static public function __handleReturnMessage($tmpInfo){
		if(isset($tmpInfo)&&!empty($tmpInfo)){
			//if(preg_match("/(Content-Type:.*)(?=;\s+)/i", $tmpInfo, $matches)){
			//list($key,$contenttype)=explode(':',$matches[0]);
			//unset($matches);
			//if(trim($contenttype)=="application/json"){
			if(preg_match("/(?=\s*)[\{\[][\S\s]*[\}\]](?=\s*)/i", $tmpInfo, $matches)){
				$result_array=json_decode($matches[0]);
				return $result_array;
			}
			//}
			//}
		}
		return false;
	}
	
	
	
	static public function __handleReturnMessageStr($tmpInfo){
		if(isset($tmpInfo)&&!empty($tmpInfo)){
			//if(preg_match("/(Content-Type:.*)(?=;\s+)/i", $tmpInfo, $matches)){
			//list($key,$contenttype)=explode(':',$matches[0]);
			//unset($matches);
			//if(trim($contenttype)=="application/json"){
			if(preg_match("/(?=\s*)[\{\[][\S\s]*[\}\]](?=\s*)/i", $tmpInfo, $matches)){
				//$result_array=json_decode($matches[0]);
				//return $result_array;
				return $matches[0];
			}
			//}
			//}
			}
			return false;
		}
	

	static public function colse_Connect(){
		if(isset(WeixinopenAdapter::$curl_entity)&&!empty(WeixinopenAdapter::$curl_entity)){
			curl_close(WeixinopenAdapter::$curl_entity);
			return true;

		}
		return false;

	}

	static public function send_textmessage($curl,$token,$url,$content="",$fromfakeid,$tofakeid){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/singlesend"); // 要访问的地址
			}

			if(isset($tofakeid)&&!empty($tofakeid)){
				$data ="type=1&content=".$content."&tofakeid=".$tofakeid."&imgcode=&token=". $token."&ajax=1";
				curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				//curl_setopt($curl, CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/singlemsgpage?token=". $token."&imgcode=".$fromfakeid."&random=0.6124528420623392&f=json&ajax=1&t=ajax-response&lang=zh_CN");
				curl_setopt($curl, CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/singlesendpage?t=message/send&action=index&tofakeid=".$tofakeid."&token=". $token."&lang=zh_CN");
				//https://mp.weixin.qq.com/cgi-bin/singlesendpage?tofakeid=678038100&t=message/send&action=index&token=200560394&lang=zh_CN
				$tmpInfo = curl_exec($curl);

				if (curl_errno($curl)) {
					echo'Errno'.curl_error($curl);//捕抓异常
				}

				curl_setopt($curl,CURLOPT_COOKIE,implode(";",WeixinopenAdapter::__handleCookie($tmpInfo)));

				return WeixinopenAdapter::__handleReturnMessage($tmpInfo);
			}

			return fasle;
		}


	}

	static public function create_mixmessage($curl,$token,$url,$fileid,$title,$digest,$content){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/operate_appmsg?token=". $token."&lang=zh_CN&t=ajax-response&sub=create"); // 要访问的地址
			}
			if(isset($fileid)&&!empty($fileid)){
				$data ="error=false&count=1&AppMsgId=&source_url=http%3A%2F%2Fwww.baidu.com&title0=".$title."&digest0=".$digest."&content0=".$content."&fileid0=".$fileid."&token=". $token."&ajax=1";//"//&title0=".$title."&digest0=".$digest."&content0=".$content."&fileid0=".$fileid."&token=". $token."&ajax=1";
				curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

				$tmpInfo = curl_exec($curl); // 执行操作

				//echo 12345;

				if (curl_errno($curl)) {
					echo'Errno'.curl_error($curl);//捕抓异常
				}

				curl_setopt($curl,CURLOPT_COOKIE,implode(";",WeixinopenAdapter::__handleCookie($tmpInfo)));

				return WeixinopenAdapter::__handleReturnMessage($tmpInfo);
			}
		}

		return false;
	}






	static public function upload_image($curl,$token,$url,$imagefile){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/uploadmaterial?cgi=uploadmaterial&type=2&token=". $token."&t=iframe-uploadfile&lang=zh_CN&formId=1"); // 要访问的地址
			}
			curl_setopt($curl,CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/indexpage?token=". $token."&t=wxm-upload&lang=zh_CN&type=2&formId=1");
			$file = array("uploadfile"=>"@".$imagefile.";type=image/jpeg","formId"=>"");
			curl_setopt($curl, CURLOPT_POSTFIELDS, $file);


			$tmpInfo = curl_exec($curl); // 执行操作

			//echo 12345;

			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}

			curl_setopt($curl,CURLOPT_COOKIE,implode(";",WeixinopenAdapter::__handleCookie($tmpInfo)));

			preg_match("/formId,\s*\'([0-9]*)\'\);/i", $tmpInfo, $matches);

			// var_dump($matches);

			$fileid0=$matches[1];

			return $fileid0;


		}
		return false;
	}
 
	static public function fetch_user_list($curl,$token,$url,$pagesize,$pageidx){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
			
			
			if(!isset($pagesize)||empty($pagesize)){
				$pagesize=10;
				
			}
			
			if(!isset($pagesize)||empty($pageidx)){
				$pageidx=0;
			}
			
			if(!isset($url)||empty($url)){
				$url="https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=".$pagesize."&pageidx=".$pageidx."&type=0&token=". $token."&lang=zh_CN";
					
			}
			
			curl_setopt($curl, CURLOPT_URL, $url);
		/* 		
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}elseif(isset($pagesize)&&!empty($pagesize)
					&&isset($pageidx)&&!empty($pageidx)){
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=".$pagesize."&pageidx=".$pageidx."&type=0&token=". $token."&lang=zh_CN");
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=50&pageidx=0&type=0&token=". $token."&lang=zh_CN");
					
			} */
			
			//t=user/index&pagesize=10&pageidx=0&type=0&token=1187117072&lang=zh_CN
			curl_setopt($curl,CURLOPT_REFERER,"https://mp.weixin.qq.com/advanced/advanced?action=index&t=advanced/index&lang=zh_CNtoken=".$token);
			curl_setopt($curl, CURLOPT_POST, 0);

			$tmpInfo = curl_exec($curl); // 执行操作


				
			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}
				


			//\sid\=\"json-friendList\"\stype\=\"json\/text\"\>
		    
			if(preg_match("/\<script\stype\=\"text\/javascript\"\>\s*wx\.cgiData\=\{[\S\s]*?pageIdx\s:\s(.*?)\,[\S\s]*?pageCount\s:\s(.*?)\,[\S\s]*?friendsList\s\:\s\(([\S\s]*?)\)\.contacts[\S\s]*?totalCount\s:\s\'(.*?)\'[\S\s]*?(?=\<\/script\>)/i", $tmpInfo, $matches)){
				$contacts=json_decode($matches[3])->contacts;
				$pagidx=intval($matches[1]);
				$pagecount=intval($matches[2]);
				//$totalcount=intval($matches[4]);
				if($pagidx+1<$pagecount){
					$contacts_inner=WeixinopenAdapter::fetch_user_list($curl, $token, null,$pagesize,$pagidx+1);
					$contacts=array_merge($contacts,$contacts_inner);
					
				}
				return $contacts;
					
			}
		}
			
		return false;

	}

	static public function fetch_user_info($curl,$token,$url,$fakeId){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)&&isset($fakeId)&&!empty($fakeId)){
			curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/getcontactinfo");
			
			$data="token=".$token."&lang=zh_CN&random=0.16230701003273658"."&f=json&ajax=1&t=ajax-getcontactinfo&fakeid=".$fakeId;
			curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
			curl_setopt($curl,CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&token=".$token."&lang=zh_CN");
			
			$tmpInfo = curl_exec($curl); // 执行操作
				
				

			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}
				
			return WeixinopenAdapter::__handleReturnMessage($tmpInfo);
				
		}
			
		return false;

	}
	
    static public function fetch_message_list($curl,$token,$url,$day,$count){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
				
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}elseif(isset($day)&&!empty($day)&&isset($count)&&!empty($count)){
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=".$count."&day=".$day."&token=". $token."&lang=zh_CN");
					
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token=". $token."&lang=zh_CN");
			}

			//curl_setopt($curl,CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/indexpage?t=wxm-index&lang=zh_CN&token=".$token);
			curl_setopt($curl, CURLOPT_POST, 0);

			$tmpInfo = curl_exec($curl); // 执行操作


				
			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}
				


			//\sid\=\"json-friendList\"\stype\=\"json\/text\"\>wx\.cgiData\=\{.*friendsList.*\:\s \.contacts.*
				
			if(preg_match("/\<script\stype\=\"text\/javascript\"\>\s*wx\.cgiData\s\=\s\{[\S\s]*?list\s\:\s\(([\S\s]*?)\)\.msg_item[\S\s]*?(?=\<\/script\>)/i", $tmpInfo, $matches)){
				$list=(json_decode($matches[1]));
				return $list->msg_item;
					
			}
		}
			
		return false;

	}
	
	static public function fetch_friends_by_group($curl,$token,$url,$groupId){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
	
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}elseif(isset($groupId)&&!empty($groupId)){
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid=".$groupId."&token=".$token."&lang=zh_CN");
					
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&groupid=0&token=".$token."&lang=zh_CN");
			}
	
			//curl_setopt($curl,CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/indexpage?t=wxm-index&lang=zh_CN&token=".$token);
			curl_setopt($curl, CURLOPT_POST, 0);
	
			$tmpInfo = curl_exec($curl); // 执行操作
	
	
	
			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}
	
	
	
			//\sid\=\"json-friendList\"\stype\=\"json\/text\"\>wx\.cgiData\=\{.*friendsList.*\:\s \.contacts.*
	
			if(preg_match("/\<script\stype\=\"text\/javascript\"\>\s*wx\.cgiData\=\{[\S\s]*?friendsList\s\:\s\(([\S\s]*?)\)\.contacts[\S\s]*?(?=\<\/script\>)/i", $tmpInfo, $matches)){
				$list=(json_decode($matches[1]));
				return $list->contacts;
					
			}
		}
			
		return false;
	
	}
	
	
	
	static public function update_friend_group($curl,$token,$url,$fakeId,$groupId){
		if(isset($token)&&!empty($token)&&isset($curl)&&!empty($curl)){
	
			if(isset($url)&&!empty($url)){
				curl_setopt($curl, CURLOPT_URL, $url);
			}elseif(isset($groupId)&&!empty($groupId)){
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/modifycontacts");
					
			}else{
				curl_setopt($curl, CURLOPT_URL, "https://mp.weixin.qq.com/cgi-bin/modifycontacts");
			}
	
			curl_setopt($curl,CURLOPT_REFERER,"https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=10&pageidx=0&type=0&token=".$token."&lang=zh_CN");
			
			if(isset($fakeId)&&!empty($fakeId)
			   &&isset($groupId)&&!empty($groupId)){
				$data ="contacttype=".$groupId."&tofakeidlist=".$fakeId."&token=".$token."&lang=zh_CN&random=0.5181587026454508&f=json&ajax=1&action=modifycontacts&t=ajax-putinto-group";
				
			}
			
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	
			$tmpInfo = curl_exec($curl); // 执行操作
	
	
	
			if (curl_errno($curl)) {
				echo'Errno'.curl_error($curl);//捕抓异常
			}
	
	
	
			//\sid\=\"json-friendList\"\stype\=\"json\/text\"\>wx\.cgiData\=\{.*friendsList.*\:\s \.contacts.*
	
			/* if(preg_match("/\<script\stype\=\"text\/javascript\"\>\s*wx\.cgiData\=\{[\S\s]*?friendsList\s\:\s\(([\S\s]*?)\)\.contacts[\S\s]*?(?=\<\/script\>)/i", $tmpInfo, $matches)){
				$list=(json_decode($matches[1]));
				return $list->contacts;
					
			} */
			
			if(preg_match("/(?=\s*)[\{\[][\S\s]*[\}\]](?=\s*)/i", $tmpInfo, $matches)){
						$result_array=json_decode($matches[0]);
				if($result_array->ret==='0'){
					return true;
					
				}
					
			} 
		}
			
		return false;
	
	}


}