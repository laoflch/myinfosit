 <?php echo $this->Html->css('login.element') ?>
 
<div class='login_element' >
<form method="post" action='/myinfosit/user/register' > 
<div >




<ul >   
    
<li><span style=''>用户名:</span><input type='text' name=username   /> </li>

<li><span style=''>密&nbsp;&nbsp;&nbsp;码:</span><input name=password type=password  /> </li>
 
<li><span >验证码:</span><input name=valitecode type='text' />&nbsp;&nbsp;
    <img style="width:75px" id="valite_code" src='/myinfosit/img/valite' />
    <a onclick="flush_valite（'valite_code'）" href='' >看不清图片</a>
</li>



</ul>
<div><input type=submit value='新增用户'/></div>
<div><a href='/user/adduser'>注册新用户</a></div>
</div>
</form>
</div>