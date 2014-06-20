<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">

    <title>爱车帮帮忙车辆救援服务step2</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../js/html5shiv.js"></script>
      <script src="../js/respond.min.js"></script>
      

    <![endif]-->
    <script src="../js/rescue.js"></script>
    <script language="javascript" src="http://webapi.amap.com/maps?v=1.2&key=2d22f6a6447d8d78afb93e567cad41bd"></script>
  </head>

  <body onload="mapInit();">
    <div class="navbar navbar-static-top wenav" role="navigation">
      <div class="container">
        <span class="nav-logo"><img src="../img/logo.png"></span>
        <a class="navbar-brand nav-brand" href="#">爱车帮帮忙</a>
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div class="steps">
        <img src="../img/step2.png"/>
    </div>
    
    <div class="container">
        <h4>请核对一下信息：</h4>
	    <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="30%">手机号</td>
                    <td><?php
echo $phone_no;
?></td>
                </tr>
                <tr>
                    <td width="30%">您的位置
                        
                        <!--<a class="edit" onclick="#" id="relocate">[重新定位]</a>-->
                    </td>
                    <td><span id="addr"></span><a class="edit" onclick="" id="edit" href="location.mobile" data-toggle="modal" data-target="#myModal">[手动定位]</a><button class="btn btn-primary" id="confirm" style="display:none">确定</button>
                    </td>
                </tr>
                <tr>
                    <td width="30%">应付金额</td>
                    <td>￥50.00</td>
                </tr>
                <tr>
                    <td width="30%">付款方式</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="attention">
            <h4>提示</h4>
            下单后您也可以通过拨打400*****进行人工查询
        </div>

        <button class="btn btn-lg btn-primary btn-block submit-btn" type="submit" id="btn_pay" onClick="window.location='success.html'">确定支付</button>
        <label class="checkbox">
            <input type="checkbox" checked="checked" value="我已同意xx协议"> 我已同意xx协议
        </label>
    </div><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <input type="text" class="form-control" value=""> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
            </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="container">
    	<div class="row">
    		<div class="footer"><p>&copy;2013 爱车帮帮忙. All rights reserved.</p></div>
    	</div>

	</div>

    </div><!--/.container-->



    <script language="javascript">
	

  


</script>
  </body>
</html>
