<?php
//session_start();

//include_once( 'config.php' );
//include_once( 'saetv2.ex.class.php' );

App::Import("SourceAdapter.saetv2_ex_class.php");

$wb_akey= $_SESSION['wb_akey'];

$wb_skey= $_SESSION['wb_skey'];

$c = new SaeTClientV2( $wb_akey , $wb_ske , $_SESSION['token']['access_token'] );
$ms  = $c->home_timeline(); // done
//var_dump($ms);
$uid_get = $c->get_uid();
//echo 12345;
//var_dump($uid_get);
$uid = $uid_get['uid'];
$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
//var_dump($user_message);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新浪微博V2接口演示程序-Powered by Sina App Engine</title>
</head>

<body>
	<?php echo $user_message['screen_name']?>
	,您好！
	<h2 align="left">发送新微博</h2>
	<form action="">
		<input type="text" name="text" style="width: 300px" /> <input
			type="submit" />
	</form>
	<?php
	if( isset($_REQUEST['text']) ) {
	$ret = $c->update( $_REQUEST['text'] );	//发送微博
	if ( isset($ret['error_code']) && $ret['error_code'] > 0 ) {
		echo "<p>发送失败，错误：{$ret['error_code']}:{$ret['error']}</p>";
	} else {
		echo "<p>发送成功</p>";
	}
}
?>

	<?php if( is_array( $ms['statuses'] ) ): ?>
	<?php foreach( $ms['statuses'] as $item ): ?>
	<div style="padding: 10px; margin: 5px; border: 1px solid #ccc">
		<?php echo $item['text'];
	if ($item['bmiddle_pic']!==''){?>
		<image src='<?php echo $item['bmiddle_pic'];?>'></image>
		<?php } ?>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>

</body>
</html>
