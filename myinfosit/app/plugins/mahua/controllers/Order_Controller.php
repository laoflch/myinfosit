<?php

/**
 *
 * @author laoflch
 *
 */
App::import('Controller', "NoModelController",false);
App::import('file', "mahua.AlipayConfig",true);

class OrderController extends MahuaAppController implements NoModelController
{

	var $helpers = array('Javascript');

	var $components = array('Cookie','Session');

	var $uses = array('mahua.MahuaOrder','mahua.MahuaOrderNotify');

	//var $components = array('RequestHandler');

	

	
	
	function createOrder(){
		if(isset($this->params["form"]['order_info'])
		&&!empty($this->params["form"]['order_info'])){
		$order_info=$this->params["form"]['order_info'];
		        $this->data["phone_no"]=$order_info["phone_no"];
				$this->data["single_price"]=$order_info["single_price"];
				$this->data["count"]=$order_info["count"];
				$this->data["total"]=$order_info["total"];
				$this->data["source_code"]=$order_info["source_code"];
				$this->data["showtime"]=$order_info["show_time"];
				$codelist=$this->MahuaOrder->save($this->data);
		
			if($codelist){
				$codelist["MahuaOrder"]["order_id"]="MH001".substr("00000000".$this->MahuaOrder->id,-8);
				
				App::import('file', "mahua.AlipaySubmit",false);
	
		
			/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
	
//返回格式
$format = "xml";
//必填，不需要修改

//返回格式
$v = "2.0";
//必填，不需要修改

//请求号
$req_id = date('Ymdhis');
//必填，须保证每次请求都是唯一

//**req_data详细信息**

//服务器异步通知页面路径
$notify_url = "http://www.micro-data.com.cn/mahua/Order/afterPayNotify.json";
//需http://格式的完整路径，不允许加?id=123这类自定义参数

//页面跳转同步通知页面路径
$call_back_url = "http://www.micro-data.com.cn/mahua/Order/afterPayReturn";
//$call_back_url = "";
//需http://格式的完整路径，不允许加?id=123这类自定义参数

//操作中断返回地址
$merchant_url = "http://127.0.0.1:8800/WS_WAP_PAYWAP-PHP-UTF-8/xxxx.php";
//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

 $seller_email = "info@fabula.cc";
        //必填        //商户订单号
        //$out_trade_no = $_POST['WIDout_trade_no'];
        //$out_trade_no = $this->params["form"]['order_id'];
        $out_trade_no = $codelist["MahuaOrder"]["order_id"];
        //商户网站订单系统中唯一订单号，必填        //订单名称
        //$subject = $_POST['WIDsubject'];
        $subject = "支付测试";
        //必填        //付款金额
        //$total_fee = $_POST['WIDtotal_fee'];
       //$total_fee = $this->params["form"]['total'];
        $total_fee=$codelist["MahuaOrder"]["total"];;
//必填

//请求业务参数详细
$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
//必填

/************************************************************/
$alipay_config_obj=new AlipayConfig();
$alipay_config=$alipay_config_obj->toArray();
//构造要请求的参数数组，无需改动
$para_token = array(
		"service" => "alipay.wap.trade.create.direct",
		"partner" => trim($alipay_config['partner']),
		"sec_id" => trim($alipay_config['sign_type']),
		"format"	=> $format,
		"v"	=> $v,
		"req_id"	=> $req_id,
		"req_data"	=> $req_data,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//$alipay_config=new AlipayConfig();

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestHttp($para_token);

//URLDECODE返回的信息
$html_text = urldecode($html_text);

//解析远程模拟提交后返回的信息
$para_html_text = $alipaySubmit->parseResponse($html_text);

//获取request_token
$request_token = $para_html_text['request_token'];

/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

//业务详细
$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
//必填

//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => "alipay.wap.auth.authAndExecute",
		"partner" => trim($alipay_config['partner']),
		"sec_id" => trim($alipay_config['sign_type']),
		"format"	=> $format,
		"v"	=> $v,
		"req_id"	=> $req_id,
		"req_data"	=> $req_data,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
//$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
				$para = $alipaySubmit->buildRequestPara($parameter);
				
				$this->set('pass',array("return_code"=>122,"return_message"=>"cookie key has save!","orderInfo"=>$codelist,"para"=>$para));
					
			}
		}
	}
	
	function payOrder(){
		/* if(isset($this->params["form"]['order_id'])
		&&!empty($this->params["form"]['order_id'])
		&&isset($this->params["form"]['total'])
		&&!empty($this->params["form"]['total']) */
		if(true)
		/* &&isset($this->params["form"]['single_price'])
		&&!empty($this->params["form"]['single_price']) */
		{
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
		$alipay_config['cacert']    = getcwd().'/../plugins/mahua/alipay/cacert.pem';
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$alipay_config['transport']    = 'https';
		
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
        //$out_trade_no = $this->params["form"]['order_id'];
        $out_trade_no = 12345674589;
        //商户网站订单系统中唯一订单号，必填        //订单名称
        //$subject = $_POST['WIDsubject'];
        $subject = "支付测试";
        //必填        //付款金额
        //$total_fee = $_POST['WIDtotal_fee'];
       // $total_fee = $this->params["form"]['total'];
        $total_fee=0.01;
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
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
       // $html_text = $alipaySubmit->buildRequestForm($parameter,'get','确认');
        
		$this->set('pass',$html_text);
		}else{
			$this->set('pass',"未传入订单号");
		}
		
	}
	
	function payOrderWap(){
		/* if(isset($this->params["form"]['order_id'])
			&&!empty($this->params["form"]['order_id'])
				&&isset($this->params["form"]['total'])
				&&!empty($this->params["form"]['total']) */
		if(true)
			/* &&isset($this->params["form"]['single_price'])
			 &&!empty($this->params["form"]['single_price']) */
		{
			//App::import('file', "mahua.AlipayConfig",false);
			App::import('file', "mahua.AlipaySubmit",false);
	
			  //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
			//合作身份者id，以2088开头的16位纯数字
			/*$alipay_config['partner']		= '2088801824877184';
	
			//安全检验码，以数字和字母组成的32位字符
			$alipay_config['key']			= 'jmac4o2f3fju3buvqzs7iwzykaua8zni';
	
	
			$alipay_config['private_key_path']	= getcwd().'/../plugins/mahua/wapalipay/key/rsa_private_key.pem';

//支付宝公钥（后缀是.pen）文件相对路径
//如果签名方式设置为“0001”时，请设置该参数
$alipay_config['ali_public_key_path']= getcwd().'/../plugins/mahua/wapalipay/key/alipay_public_key.pem';


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//签名方式 不需修改
//$alipay_config['sign_type']    = '0001';
$alipay_config['sign_type']    = 'MD5';
//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= 'utf-8';

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = getcwd().'/../plugins/mahua/wapalipay/cacert.pem';

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';*/
	 
			/**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/ 
//返回格式
$format = "xml";
//必填，不需要修改

//返回格式
$v = "2.0";
//必填，不需要修改

//请求号
$req_id = date('Ymdhis');
//必填，须保证每次请求都是唯一

//**req_data详细信息**

//服务器异步通知页面路径
$notify_url = "http://商户网关地址/WS_WAP_PAYWAP-PHP-UTF-8/notify_url.php";
//需http://格式的完整路径，不允许加?id=123这类自定义参数

//页面跳转同步通知页面路径
$call_back_url = "http://192.168.0.134/myinfosit/mahua/Order/afterPayReturn";
//需http://格式的完整路径，不允许加?id=123这类自定义参数

//操作中断返回地址
$merchant_url = "http://127.0.0.1:8800/WS_WAP_PAYWAP-PHP-UTF-8/xxxx.php";
//用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

 $seller_email = "info@fabula.cc";
        //必填        //商户订单号
        //$out_trade_no = $_POST['WIDout_trade_no'];
        //$out_trade_no = $this->params["form"]['order_id'];
        $out_trade_no = '1234asd5674589';
        //商户网站订单系统中唯一订单号，必填        //订单名称
        //$subject = $_POST['WIDsubject'];
        $subject = "支付测试";
        //必填        //付款金额
        //$total_fee = $_POST['WIDtotal_fee'];
       // $total_fee = $this->params["form"]['total'];
        $total_fee=0.01;
//必填

//请求业务参数详细
$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
//必填

/************************************************************/
$alipay_config_obj=new AlipayConfig();
$alipay_config=$alipay_config_obj->toArray();
//构造要请求的参数数组，无需改动
$para_token = array(
		"service" => "alipay.wap.trade.create.direct",
		"partner" => trim($alipay_config['partner']),
		"sec_id" => trim($alipay_config['sign_type']),
		"format"	=> $format,
		"v"	=> $v,
		"req_id"	=> $req_id,
		"req_data"	=> $req_data,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
//建立请求
//$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestHttp($para_token);

//URLDECODE返回的信息
$html_text = urldecode($html_text);

//解析远程模拟提交后返回的信息
$para_html_text = $alipaySubmit->parseResponse($html_text);

//获取request_token
$request_token = $para_html_text['request_token'];

/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

//业务详细
$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
//必填

//构造要请求的参数数组，无需改动
$parameter = array(
		"service" => "alipay.wap.auth.authAndExecute",
		"partner" => trim($alipay_config['partner']),
		"sec_id" => trim($alipay_config['sign_type']),
		"format"	=> $format,
		"v"	=> $v,
		"req_id"	=> $req_id,
		"req_data"	=> $req_data,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
//echo $html_text;
	
			$this->set('pass',$html_text);
		}else{
			$this->set('pass',"未传入订单号");
		}
	
	}
	
	
	
	function afterPayReturn(){
		App::import('file', "mahua.AlipayNotify",false);
		//App::import('file', "mahua.AlipayConfig",false);
		//App::import('file', "mahua.AlipaySubmit",false);
		
	
		$alipay_config_obj=new AlipayConfig();
		$alipay_config=$alipay_config_obj->toArray();
		
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码
		
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
		
			//商户订单号
			//$out_trade_no = $_GET['out_trade_no'];
			$out_trade_no =$this->params["url"]['out_trade_no'];
			//支付宝交易号
			//$trade_no = $_GET['trade_no'];
			$trade_no =$this->params["url"]['trade_no'];
			//交易状态
			//$result = $_GET['result'];
			$result =$this->params["url"]['result'];
		
			//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
			$codelist=$this->MahuaOrderNotify->find("first",array('conditions' => array('order_id' =>intval(substr(trim($out_trade_no),-8)))));
				
			
			if(!$codelist&&isset($out_trade_no)&&!empty($out_trade_no)){
				$this->data["order_id"]=intval(substr(trim($out_trade_no),-8));
				$this->data["trade_no"]=$trade_no;
				$this->data["result"]=$result;
				$this->MahuaOrderNotify->save($this->data);
			}
		    $codelist=$this->MahuaOrder->find("first",array('conditions' => array('order_id' =>intval(substr(trim($out_trade_no),-8)))));
			//$codelist=$this->MahuaOrder->find("first",array('conditions' => array('order_id' =>19)));
		    $codelist["MahuaOrder"]["result"]="支付成功!";
		    $codelist["MahuaOrder"]["order_id"]="MH001".substr("00000000".$codelist["MahuaOrder"]["order_id"],-8);
		    /* $codelist["MahuaOrder"]["show_time"]=date(Y,$codelist["MahuaOrder"]["showtime"])."年"
		    		                             .date(m,$codelist["MahuaOrder"]["showtime"])."月"
		    		                             .date(d,$codelist["MahuaOrder"]["showtime"])."日"
		    		                             ." 星期".date(w,$codelist["MahuaOrder"]["showtime"])." "
		    		                             .date(H,$codelist["MahuaOrder"]["showtime"])."点"
		    		                             .date(s,$codelist["MahuaOrder"]["showtime"]); */
		    $weekarray=array("日","一","二","三","四","五","六");
		    $codelist["MahuaOrder"]["show_time"]=date("Y年m月d日 ",strtotime($codelist["MahuaOrder"]["showtime"]))
		                                        ."星期".$weekarray[date("w",strtotime($codelist["MahuaOrder"]["showtime"]))]
		                                        .date(" H:i",strtotime($codelist["MahuaOrder"]["showtime"]));
			$this->set('pass',$codelist["MahuaOrder"]);
		
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
			//验证失败
			//如要调试，请看alipay_notify.php页面的verifyReturn函数
			$out_trade_no =$this->params["url"]['out_trade_no'];
			//支付宝交易号
			//$trade_no = $_GET['trade_no'];
			$trade_no =$this->params["url"]['trade_no'];
			//交易状态
			//$result = $_GET['result'];
			$result =$this->params["url"]['result'];
			
			$codelist=$this->MahuaOrder->find("first",array('conditions' => array('order_id' =>intval(substr(trim($out_trade_no),-8)))));
		    $codelist["MahuaOrder"]["result"]="支付失败!";
			$this->set('pass',$codelist["MahuaOrder"]); 
		}
		
	}

	function afterPayNotify(){
	    App::import('file', "mahua.AlipayNotify",false);
		//App::import('file', "mahua.AlipayConfig",false);
		//App::import('file', "mahua.AlipaySubmit",false);
		
		$alipay_config_obj=new AlipayConfig();
$alipay_config=$alipay_config_obj->toArray();
	
	$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) 
//if(true)
{//验证成功

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//解析notify_data
	//注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
	$doc = new DOMDocument();	
	
	if ($alipay_config['sign_type'] == 'MD5') {
		$doc->loadXML($this->params["form"]['notify_data']);
	}
	
	if ($alipay_config['sign_type'] == '0001') {
		$doc->loadXML($alipayNotify->decrypt($this->params["form"]['notify_data']));
	}
	
	if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
	//if(true){

		//商户订单号
		$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
		//支付宝交易号
		$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
		//交易状态
		$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
		
		if($trade_status == 'TRADE_FINISHED'){
		//if(true){
			//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
			//注意：
			//该种交易状态只在两种情况下出现
			//1、开通了普通即时到账，买家付款成功后。
			//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
	
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			$this->data["order_id"]=intval(substr(trim($out_trade_no),-8));
			$this->data["trade_no"]=$trade_no;
			$this->data["trade_status"]=$trade_status;
			$this->data["payment_type"]=$doc->getElementsByTagName( "payment_type" )->item(0)->nodeValue;
			$this->data["subject"]=$doc->getElementsByTagName( "subject" )->item(0)->nodeValue;
			$this->data["buyer_email"]=$doc->getElementsByTagName( "buyer_email" )->item(0)->nodeValue;
			$this->data["gmt_create"]=$doc->getElementsByTagName( "gmt_create" )->item(0)->nodeValue;
			$this->data["notify_type"]=$doc->getElementsByTagName( "notify_type" )->item(0)->nodeValue;
			$this->data["quantity"]=$doc->getElementsByTagName( "quantity" )->item(0)->nodeValue;
			$this->data["notify_time"]=$doc->getElementsByTagName( "notify_time" )->item(0)->nodeValue;
			$this->data["seller_id"]=$doc->getElementsByTagName( "seller_id" )->item(0)->nodeValue;
			$this->data["is_total_fee_adjust"]=$doc->getElementsByTagName( "is_total_fee_adjust" )->item(0)->nodeValue;
			$this->data["gmt_payment"]=$doc->getElementsByTagName( "gmt_payment" )->item(0)->nodeValue;
			$this->data["seller_email"]=$doc->getElementsByTagName( "seller_email" )->item(0)->nodeValue;
			$this->data["gmt_close"]=$doc->getElementsByTagName( "gmt_close" )->item(0)->nodeValue;
			$this->data["price"]=$doc->getElementsByTagName( "price" )->item(0)->nodeValue;
			$this->data["buyer_id"]=$doc->getElementsByTagName( "buyer_id" )->item(0)->nodeValue;
			$this->data["notify_id"]=$doc->getElementsByTagName( "notify_id" )->item(0)->nodeValue;
			$this->data["use_coupon"]=$doc->getElementsByTagName( "use_coupon" )->item(0)->nodeValue;
		//$var = var_export($this->data,TRUE);
		
		//file_put_contents("test6.txt","test;".$var,FILE_APPEND);
			$teturn_str=$this->MahuaOrderNotify->save($this->data);
			
			if($teturn_str){
				$codelist=$this->MahuaOrder->find("first",array('conditions' => array('order_id' =>intval(substr(trim($out_trade_no),-8)))));
		//$codelist=$this->MahuaOrder->find("first",array('conditions' => array('order_id' =>19)));
		
		    if($codelist){
				$phone_no=$codelist["MahuaOrder"]["phone_no"];
				$order_id="MH001".substr("00000000".$codelist["MahuaOrder"]["order_id"],-8);
				$weekarray=array("日","一","二","三","四","五","六");
				$show_time=date("Y年m月d日",strtotime($codelist["MahuaOrder"]["showtime"]))
		                                        ."(星期".$weekarray[date("w",strtotime($codelist["MahuaOrder"]["showtime"]))]
		                                        .date(")H:i",strtotime($codelist["MahuaOrder"]["showtime"]));
				$count=	$codelist["MahuaOrder"]["count"];
				$corp="【微数咨询】";
				$service_phone="18916159788";
				$uid="heasenma";
				$pw=md5("hm123072");
				if($codelist["MahuaOrder"]["rand_num"]==="000001"){
				$rand_num=$codelist["MahuaOrder"]["order_id"].substr("0000".rand(0,9999),-4);
				$codelist["MahuaOrder"]["rand_num"]=$rand_num;
				$teturn_str=$this->MahuaOrder->save($codelist["MahuaOrder"]);
				if(!$teturn_str){
					$this->set('pass',"faild");
					return;
				}
				}else{
					$rand_num=$codelist["MahuaOrder"]["rand_num"];
				}
				
				
				$content="恭喜您！订单".$order_id."已经生效，您所购买的".$show_time."的乌龙山伯爵话剧票共"
						.$count."张已成功出票，验证码:".$rand_num."，请于演出当日提前半小时到演出场地凭验证码换取纸质演出票，客服电话".$service_phone.$corp;
				$content=iconv('UTF-8','GB2312',$content);
				$curl=curl_init();
				curl_setopt($curl, CURLOPT_URL, "http://www.smsadmin.cn/smsmarketing/wwwroot/api/post_send_md5/");
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
				//curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
				
				curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
				curl_setopt($curl, CURLOPT_POSTFIELDS, "uid=".$uid."&pwd=".$pw."&mobile=".$phone_no."&msg=".$content."&dtime="); // Post提交的数据包
				curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
				curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
				//curl_setopt($curl, CURLOPT_REFERER, "https://mp.weixin.qq.com/");
				
				$tmpInfo = curl_exec($curl); // 执行操作
				
				if (curl_errno($curl)) {
					$this->set('pass',"faild");
				}
				
				if (!strcspn($tmpInfo,"0")){
					$this->set('pass',"success");
				}else{
					$this->set('pass',"faild");
				}
				
				
			
				}
			}else{
				$this->set('pass',"faild");//请不要修改或删除
			}
		}
		else if ($trade_status == 'TRADE_SUCCESS') {
			//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
			//注意：
			//该种交易状态只在一种情况下出现——开通了高级即时到账，买家付款成功后。
	
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			
		$this->data["order_id"]=intval(substr(trim($out_trade_no),-8));
			$this->data["trade_no"]=$trade_no;
			$this->data["trade_status"]=$trade_status;
			$this->data["payment_type"]=$doc->getElementsByTagName( "payment_type" )->item(0)->nodeValue;
			$this->data["subject"]=$doc->getElementsByTagName( "subject" )->item(0)->nodeValue;
			$this->data["buyer_email"]=$doc->getElementsByTagName( "buyer_email" )->item(0)->nodeValue;
			$this->data["gmt_create"]=$doc->getElementsByTagName( "gmt_create" )->item(0)->nodeValue;
			$this->data["notify_type"]=$doc->getElementsByTagName( "notify_type" )->item(0)->nodeValue;
			$this->data["quantity"]=$doc->getElementsByTagName( "quantity" )->item(0)->nodeValue;
			$this->data["notify_time"]=$doc->getElementsByTagName( "notify_time" )->item(0)->nodeValue;
			$this->data["seller_id"]=$doc->getElementsByTagName( "seller_id" )->item(0)->nodeValue;
			$this->data["is_total_fee_adjust"]=$doc->getElementsByTagName( "is_total_fee_adjust" )->item(0)->nodeValue;
			$this->data["gmt_payment"]=$doc->getElementsByTagName( "gmt_payment" )->item(0)->nodeValue;
			$this->data["seller_email"]=$doc->getElementsByTagName( "seller_email" )->item(0)->nodeValue;
			$this->data["gmt_close"]=$doc->getElementsByTagName( "gmt_close" )->item(0)->nodeValue;
			$this->data["price"]=$doc->getElementsByTagName( "price" )->item(0)->nodeValue;
			$this->data["buyer_id"]=$doc->getElementsByTagName( "buyer_id" )->item(0)->nodeValue;
			$this->data["notify_id"]=$doc->getElementsByTagName( "notify_id" )->item(0)->nodeValue;
			$this->data["use_coupon"]=$doc->getElementsByTagName( "use_coupon" )->item(0)->nodeValue;
			
			$teturn_str=$this->MahuaOrderNotify->save($this->data);
			
			if($teturn_str){
			$this->set('pass',"success");	
			}else{
				$this->set('pass',"faild");//请不要修改或删除
			}
		}
	}

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
	$this->set('pass',"faild");
	}
	
	
	}
}
