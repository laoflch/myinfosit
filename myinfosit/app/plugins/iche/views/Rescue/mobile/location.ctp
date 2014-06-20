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
    <script language="javascript" src="http://webapi.amap.com/maps?v=1.2&key=2d22f6a6447d8d78afb93e567cad41bd"></script>
<script language="javascript">
	

  
function showPosition(position)
  {
 var lnglatXY = new AMap.LngLat(position.coords.longitude+0.004411,position.coords.latitude-0.00203);
//var lnglatXY = new AMap.LngLat(position.coords.longitude,position.coords.latitude);
  //var lnglatXY = new AMap.LngLat(121.668,31.2455);
   
    //var lnglatXY = new AMap.LngLat(121.668,31.2455);
   
     var opt = {
        level: 16, //设置地图缩放级别  
        //center: new AMap.LngLat(e.lnglat.getLng(),e.lnglat.getLat()) //设置地图中心点 
         center: lnglatXY
    }
mapObj = new AMap.Map("iCenter",opt); 

    
      var marker = new AMap.Marker({
        map:mapObj,
        icon: new AMap.Icon({
            image: "http://www.amap.com/images/mark.png",
            size:new AMap.Size(58,30),
            imageOffset: new AMap.Pixel(-32, -0)
        }),
        position: lnglatXY,
       offset: new AMap.Pixel(-5,-30)
    }); 
     
      //mapObj.setFitView();
   	
   	 var clickEventListener=AMap.event.addListener(mapObj,'click',function(e){  
        //alert(e.lnglat.getLng());    
         var lnglat = new AMap.LngLat(e.lnglat.getLng(),e.lnglat.getLat());
          //var lnglatXY = new AMap.LngLat(121.668,31.2455);
          //加点
    if (!marker) {
        marker = new AMap.Marker({
            map:mapObj,
            icon: "http://webapi.amap.com/images/0.png",
            position: lnglat,
            offset: new AMap.Pixel(-10, -34)
        });
    } else {
        marker.setPosition(lnglat);
    }
     
      document.getElementById("result").innerHTML = e.lnglat.getLng()+"+"+e.lnglat.getLat();
      
        
    //加载地理编码插件  
    mapObj.plugin(["AMap.Geocoder"], function() {   
    	if(!MGeocoder){       
        MGeocoder = new AMap.Geocoder({   
            radius: 1000,  
            extensions: "all"  
        });  
        //返回地理编码结果   
        
     }   
        AMap.event.addListener(MGeocoder, "complete", geocoder_CallBack);
        //逆地理编码  
        MGeocoder.getAddress(lnglat);   
    });  
       
        
        
    });  

  };

function geoError(event) { 
alert(event.code);
   switch(event.code) 
    {
    case event.PERMISSION_DENIED:
      alert("User denied the request for Geolocation.");
      break;
    case event.POSITION_UNAVAILABLE:
       alert("Location information is unavailable.");
      break;
    case event.TIMEOUT:
      alert("The request to get user location timed out.");
      break;
    case event.UNKNOWN_ERROR:
       alert("An unknown error occurred.");
      break;
    }
};

function geocoder_CallBack(data) {
   
    var address;
    //返回地址描述
    address = data.regeocode.formattedAddress;
    //返回周边道路信息
   
    resultStr = "<b>地址</b>："+ address ;
    document.getElementById("result2").innerHTML = resultStr;
}  	
	
var mapObj,MGeocoder;
function mapInit() {
    
     
     var x=document.getElementById("result");

   if (navigator.geolocation)
    {
var option = {
            enableHighAccuracy : true,
            timeout : 100000,
            maximumAge : 0
        };
    navigator.geolocation.getCurrentPosition(showPosition,geoError,option);

    }
  else{x.innerHTML="Geolocation is not supported by this browser.";};




   	
   		/*var lnglatXY = new AMap.LngLat(121.668,31.2455);
   
     var opt = {
        level: 16, //设置地图缩放级别  
        //center: new AMap.LngLat(e.lnglat.getLng(),e.lnglat.getLat()) //设置地图中心点 
         center: lnglatXY
    }*/
    
   	
};

</script>
  </head>

  <body onload="mapInit();">
    <div class="navbar navbar-static-top wenav" role="navigation">
      <div class="container">
        <span class="nav-logo"><img src="../img/logo.png"></span>
        <a class="navbar-brand nav-brand" href="#">爱车帮帮忙</a>
      </div><!-- /.container -->
    </div><!-- /.navbar -->

    <div id="iCenter" ></div>
    <div class="demo_box"> 
            <input type="button" value="逆地理编码" onclick="geocoder()"/>
        <div id="r_title"><b>查询结果:</b></div>
        <div id="result"> </div>
        <div id="result2"> </div>
    </div>

    
  </body>
</html>
