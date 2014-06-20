<?php
App::import('file',"weixinopen.ContentTypeAdapter");

abstract class AbstractRuleAdapter
{

	public function parse($postObj,$contentType,$content_id){
		if(method_exists($this,"__beforeParse")){
			$this->__beforeParse($postObj);
		
		}
		if(isset($contentType)&&!empty($contentType)){
			
			$contentTypeName=$contentType["WeixinContentType"]["content_type_name"];
			$contentTypeClassName=ucfirst($contentTypeName."ContentAdapter");
			App::import('file',"weixinopen.".$contentTypeClassName);
			$contentType=new $contentTypeClassName();
			
			if($contentType instanceof ContentTypeAdapter){
				$result_array=$contentType->out($postObj,$content_id);
				$return_str= "<xml>".$this->__a2xml($result_array)."</xml>";
			}
			if(isset($return_str)&&!empty($return_str)){			
			    return $return_str;	
			}		
			

		}

		return "<xml></xml>";
	}

	private function __a2xml($array) {
		//$xml="<xml>";
		foreach ($array as $key=>$value) {
			if(is_string($key)){
			if(is_array($value)) {
				$xml.="<$key>".$this->__a2xml($value)."</$key>";
			}elseif (is_string($value)){
				$xml.="<$key><![CDATA[".$value."]]></$key>";
			}elseif (is_numeric($value)){
				$xml.="<$key>".$value."</$key>";
			}elseif (is_object($value)){
				$xml.="<$key><![CDATA[".$value->toString()."]]></$key>";
			}
			}elseif (is_numeric($key)){
				if(is_array($value)) {
					$xml.=$this->__a2xml($value);
				}
			}
				
		}
		return $xml/* ."</xml>" */;
	}



}