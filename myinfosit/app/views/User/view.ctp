<script type="text/javascript" >
  $.getJSON("http://www.yonganfish.com/myinfosit/user/index.json",function(User){
alert(User[1]["User"]["username"]);
});
</script>