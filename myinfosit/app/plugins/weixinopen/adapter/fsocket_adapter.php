<?php

/**
 * Class/file loader and path management.
 *
 *
 */
class FsocketAdapter extends Object {

	//static public $curl_entity;

	static public function get($host,$subDir,$options){
		if(isset($host)&&!empty($host)){
			$fp=fsockopen($host,80,$errno,$errstr,30);
			if(!$fp){
				echo"$errstr ($errno)
				\n";
			}else{
				$out="GET ".$subDir."?";

				foreach ($options as $key=>$value){
		            $out.=Inflector::underscore($key)."=".rawurlencode($value)."&";			 
				}
				$out.=" HTTP/1.1\r\n";
				$out.="Host: ".$host." \r\n";
				$out.="Connection: Close\r\n\r\n";
//$var = var_export($out,TRUE);
//file_put_contents("test7.txt","test234".$var,FILE_APPEND);
				fwrite($fp,$out);
				/*忽略执行结果*/
				/*  while (!feof($fp)) {
				echo fgets($fp, 128);
				} */
				fclose($fp);
					
			}
		}

	}

	static public function post($host,$subDir,$options){
		if(isset($host)&&!empty($host)){
			$fp=fsockopen($host,80,$errno,$errstr,30);
			if(!$fp){
				echo"$errstr ($errno)
				\n";
			}else{
			$out="POST ".$subDir;
	
					
					$out.=" HTTP/1.1\r\n";
					$out.="Host: ".$host." \r\n";
					$out.="Content-Type: application/x-www-form-urlencoded  \r\n";
					foreach ($options as $key=>$value){
						$content.=Inflector::underscore($key)."=".rawurlencode($value)."&";
					}
					$out.="Content-Length: ".strlen($content)." \r\n";
				    $out.="Connection: Close\r\n\r\n";
				    
				    $out.=$content."  \r\n";
				   /*  foreach ($options as $key=>$value){
				        $out.=Inflector::underscore($key)."=".rawurlencode($value)."&"."  \r\n";
					} */
//$var = var_export($out,TRUE);
					//file_put_contents("test7.txt","test234".$var,FILE_APPEND);
					fwrite($fp,$out);
					/*忽略执行结果*/
					/*while (!feof($fp)) {
					 //echo fgets($fp, 128);
                                        $var = var_export(fgets($fp, 128),TRUE);
					file_put_contents("test7.txt","test234".$var,FILE_APPEND);
					}*/
					fclose($fp);
						
			}
			}
	
			}


}
