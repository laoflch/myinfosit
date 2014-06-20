<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">

    <title>爱车帮帮忙车辆救援服务step1</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-static-top wenav" role="navigation">
      <div class="container">
        <span class="nav-logo"><img src="../img/logo.png"></span>
        <a class="navbar-brand nav-brand" href="#">爱车帮帮忙</a>
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="steps">
        <img src="../img/step1.png"/>
    </div>
    
   <div class="container" >
        <form class="form-phone" onsubmit='' action="confirm.mobile" method="post">
            <h4 class="form-phone-heading">请输入您的手机号</h4>
            <input type="text" name="phone_no" class="form-control" placeholder="手机号...">
            <button class="btn btn-lg btn-primary btn-block submit-btn" id="btn_submit" >下一步</button>
        </form>
    </div><!-- /.container -->

    <div class="container tips">
        <h4>*提示:</h4>
        <p>1、您所支付的50元为此次出险救援的预收定金费用</p>
        <p>2、如需查询救援车辆的派遣进度请点击公众账号首页救援菜单咨询</p>
    </div>

    <div class="container">
    	<div class="row">
    		<div class="footer"><p>&copy;2013 爱车帮帮忙. All rights reserved.</p></div>
    	</div>

	</div>

    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../dist/js/jquery.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <script src="../js/rescue.js"></script>
  </body>
</html>
