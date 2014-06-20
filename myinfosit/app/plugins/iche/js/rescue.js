function showPosition(position)
  {
 var lnglatXY = new AMap.LngLat(position.coords.longitude+0.004411,position.coords.latitude-0.00203);
//var lnglatXY = new AMap.LngLat(position.coords.longitude,position.coords.latitude);
  //var lnglatXY = new AMap.LngLat(121.668,31.2455);
   
    //var lnglatXY = new AMap.LngLat(121.668,31.2455);
 var geocoderOption = {
		 range:300,//范围
		 crossnum:1,//道路交叉口数
		 roadnum:1,//路线记录数
		 poinum:1//POI点数
		 };
		 var geocoder = new AMap.Geocoder(geocoderOption);

		 geocoder.regeocode(lnglatXY,  
		 function(data){  
			 var outStr=data["list"][0]["province"]["name"]+data["list"][0]["city"]["name"]+data["list"][0]["district"]["name"];
			 if(data["list"][0]["crosslist"][0]){
				 outStr+=data["list"][0]["crosslist"][0]["road1"]["name"]+data["list"][0]["crosslist"][0]["road2"]["name"];
			 }
			 if(data["list"][0]["poilist"][0]){
				 outStr+="（";
				if(data["list"][0]["poilist"][0]["address"]){
					outStr+=data["list"][0]["poilist"][0]["address"]+"号";
				}
				outStr+=data["list"][0]["poilist"][0]["name"]+"&#38468;&#36817;";
				outStr+="）";
			 }
		  document.getElementById("addr").innerHTML = outStr;
		 });
 
 
     /*var opt = {
        level: 16, //设置地图缩放级别  
        //center: new AMap.LngLat(e.lnglat.getLng(),e.lnglat.getLat()) //设置地图中心点 
         center: lnglatXY
    }
mapObj = new AMap.Map("result",opt); 

    
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
       
        
        
    });  */

  }

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
}

function geocoder_CallBack(data) {
   
    var address;
    //返回地址描述
    address = data.regeocode.formattedAddress;
    //返回周边道路信息
   
    resultStr = "<b>地址</b>："+ address ;
    document.getElementById("addr").innerHTML = resultStr;
}  	
	
var mapObj,MGeocoder;
function mapInit() {
    
     
     var x=document.getElementById("result");

   if (navigator.geolocation)
    {
var option = {
            enableHighAccuracy : false,
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
    
   	
}