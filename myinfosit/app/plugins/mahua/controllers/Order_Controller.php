<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);

class OrderController extends MahuaAppController implements NoModelController
{

	var $helpers = array('Javascript');

	var $components = array('Cookie','Session');

	var $uses = array('mahua.MahuaOrder');

	//var $components = array('RequestHandler');

	

	
	
	function createOrder(){
		if(isset($this->params["form"]['order_info'])
		&&!empty($this->params["form"]['order_info'])){
		$order_info=$this->params["form"]['order_info'];
		        $this->data["phone_no"]=$order_info["phone_no"];
				$this->data["single_price"]=$order_info["single_price"];
				$this->data["count"]=$order_info["count"];
				$this->data["total"]=$order_info["total"];
				$codelist=$this->MahuaOrder->save($this->data);
		
			if($codelist){
				$codelist["MahuaOrder"]["order_id"]="MH001".substr("00000000".$this->MahuaOrder->id,-8);
				$this->set('pass',array("return_code"=>122,"return_message"=>"cookie key has save!","orderInfo"=>$codelist));
					
			}
		}
	}
	
	function payOrder(){
		//App::import('file', "mahua.AlipayConfig",false);
		App::import('file', "mahua.AlipaySubmit",false);
		
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		//合作身份者id，以2088开头的16位纯数字
		$alipay_config['partner']		= '2088801824877184';
		
		//安全检验码，以数字和字母组成的32位字符
		$alipay_config['key']			= 'jmac4o2f3fju3buvqzs7iwzykaua8zni';
		
		
		//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
		
		
		//签名方式 不需修改
		$alipay_config['sign_type']    = strtoupper('MD5');
		
		//字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config['input_charset']= strtolower('utf-8');
		
		//ca证书路径地址，用于curl中ssl校验
		//请保证cacert.pem文件在当前文件夹目录中
		$alipay_config['cacert']    = getcwd().'\\cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'http';
		
		/**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = "http://商户网关地址/create_direct_pay_by_user-PHP-UTF-8/notify_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数        //页面跳转同步通知页面路径
        $return_url = "http://商户网关地址/create_direct_pay_by_user-PHP-UTF-8/return_url.php";
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/        //卖家支付宝帐户
        //$seller_email = $_POST['WIDseller_email'];
        $seller_email = "info@fabula.cc";
        //必填        //商户订单号
        //$out_trade_no = $_POST['WIDout_trade_no'];
        $out_trade_no = "MH0000100000010";
        //商户网站订单系统中唯一订单号，必填        //订单名称
        //$subject = $_POST['WIDsubject'];
        $subject = "支付测试";
        //必填        //付款金额
        //$total_fee = $_POST['WIDtotal_fee'];
        $total_fee = 0.01;
        //必填        //订单描述        $body = $_POST['WIDbody'];
        //商品展示地址
        $show_url = $_POST['WIDshow_url'];
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1


/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => "create_direct_pay_by_user",
		"partner" => trim($alipay_config['partner']),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"seller_email"	=> $seller_email,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"body"	=> $body,
		"show_url"	=> $show_url,
		"anti_phishing_key"	=> $anti_phishing_key,
		"exter_invoke_ip"	=> $exter_invoke_ip,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		$this->set('pass',$html_text);
		
	}
	
	

	
}