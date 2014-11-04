
<div class="" id="myModal" style="border:1px solid #000" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
        <div class="modal-content">
<form id='alipaysubmit' name='alipaysubmit' action='http://wappaygw.alipay.com/service/rest.htm?
 _input_charset=utf-8' method='get'>
<!--form id='alipaysubmit' name='alipaysubmit' action='https://mapi.alipay.com/gateway.do?_input_charset=utf-8' method='get' target="_blank"-->
        
          <div class="modal-body">
            <div class="alert alert-warning">订单号:<?php echo $pass["order_id"].$pass["result"]?></div>
            <h4>订单信息</h4>
            <div class="confirm_info">
                <span> 单价：</span><span class="confirm_name"><?php echo $pass["single_price"]?></span>
                <span> 演出地点：</span><span class="confirm_name">上戏剧院（华山路630号）</span>
                <span> 总价：</span><span class="confirm_name"><?php echo $pass["total_price "]?>元 (参与本次活动票价)</span>
                <span> 张数：</span><span class="confirm_name"><?php echo $pass["count"]?></span>
                <span> 验证码接收手机：</span><span class="confirm_name"><?php echo $pass["phone_no"]?></span>
            </div>  
          </div>
         
</form>
        </div>
      </div>
    </div>

