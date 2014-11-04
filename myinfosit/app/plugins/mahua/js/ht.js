MDEmber = Ember.Application.create({
	title : 'activities',
	rootElement : '.bg',
	
	subDir: "/myinfosit",
	
	/*
	 * function isFunction
	 * 判断是否Function对象  
	 *
	 * 
	 * */
	isFunction: function(fn){
		return !!fn && !fn.nodeName && fn.constructor != String &&
		fn.constructor != RegExp && fn.constructor != Array &&
		/function/i.test( fn + "" );
	},
	/*
	 * function jsonAsync
	 * 处理异步Json请求和响应
	 * 
	 * url: 请求的url地址
	 * type： http方法 get或post
	 * success: 请求响应成功的处理
	 * error：请求响应失败的处理
	 * 
	 * */
	jsonAsync: function(url,type,data,success,error){
		if(MDEmber.isFunction(success)&&MDEmber.isFunction(error)){
		$.ajax({
			url : MDEmber.subDir+url,// 跳转到 action
			async : true,
			data : data,
			type : type,
			cache : false,
			timeout : 10000,
			dataType : 'json',
			success : success,
			error : error
		});
		}
	},
	
	/*
	 * function jsonAsync
	 * 处理同步Json请求和响应
	 * 
	 * url: 请求的url地址
	 * type： http方法 get或post
	 * success: 请求响应成功的处理
	 * error：请求响应失败的处理
	 * 
	 * */
	jsonSync: function(url,type,data,success,error){
		if(MDEmber.isFunction(success)&&MDEmber.isFunction(error)){
		$.ajax({
			url : MDEmber.subDir+url,// 跳转到 action
			async : false,
			data : data,
			type : type,
			cache : false,
			timeout : 10000,
			dataType : 'json',
			success : success,
			error : error
		});
		}
	},
	
});

MDEmber.Router.map(function(){
	this.route("showactivity", {
		path : "/:source_code"
	});
	this.route("showactivity", {
		path : "/:source_code/:activity_id"
	});
	this.route("showactivity", {
		path : "/showactivity"
	});
	this.route("showactivity", {
		path : "/"
	});
	this.resource("orderticket", {
		path : "/orderticket/:activity_id"
		//path : "/orderticket"
	});
	this.resource("confirmorder", {
		path : "/confirmorder/:single_price/:count/:total"
		//path : "/orderticket"
	});
	
});

MDEmber.StandRoute = Ember.Route.extend({
	childView : [],
	renderChildView : function() {
		if (this.childView && this.childView.length > 0) {
			for ( var i = 0; i < this.childView.length; i++) {
				if (this.childView[i].name && this.childView[i].name !== '') {
					this.render(this.childView[i].name, this.childView[i]);
				}
			}

		}

	}

});

MDEmber.ShowactivityRoute = Ember.Route.extend({
	model: function(params) {
		   
	    //alert(params.source_code+"1");
		var return_str;
		if(params.source_code){
		MDEmber.jsonSync("/mahua/Activity/showActivityInfo.json",
				"post",
				{"source_code":params.source_code,"activity_id":params.activity_id},
				function(data) {
					if (data["showActivityInfo"]) {
						
						
						return_str=data["showActivityInfo"];
						
					}
					if(data["showActivityInfo"]){
						
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});
		}
       return {"source_code":params.source_code,"activity_id":params.activity_id};
	   //return return_str;
	},	
	setupController : function(controller) {
		
		var model=controller.get("model");
		//alert(123);
		var source_code=model["source_code"];
		MDEmber.jsonAsync("/mahua/Activity/fetchActivityInfo.json",
				"post",
				{"activity_id":model["activity_id"]},
				function(data) {
					if (data["showActivityInfo"]) {
						
						data["showActivityInfo"]["source_code"]=source_code;
						controller.set("model",data["showActivityInfo"]);
						
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

		
	},
});



MDEmber.Showactivity = Ember.Object.extend({
	
	isSingle:function() {
	    return this.get('WeixinRuleContentReplyMix_article_count')===1?true:false;
	}.property('WeixinRuleContentReplyMix_article_count')
});

MDEmber.MDArrayController = Ember.ArrayController.extend({});

MDEmber.ShowactivityController = Ember.Controller.extend({
	nextAction:function(thisView){
		//this.transitionToRoute("orderticket",280);
		var model=this.get("model");
		this.transitionToRoute("orderticket",{activity_id: model["MahuaActivityBasicInfo_activity_id"]
			                                 ,single_price: model["MahuaActivityBasicInfo_single_price"]
		                                     ,count:model["MahuaActivityBasicInfo_default_count"]
		                                     ,total:model["MahuaActivityBasicInfo_default_count"]*model["MahuaActivityBasicInfo_single_price"]
		                                     ,source_code:model["source_code"]});
		
	}
   
});


MDEmber.ShowactivityView = Ember.View.extend({
	templateName : "show_activity",
	click:function (){
		if(event.target.id=="submit"){
			this.controller.send('nextAction', this);
		};
		
	},
	
	
});

MDEmber.OrderticketRoute = Ember.Route.extend({
	model: function(params) {
	   
		return {"activity_id":params.activity_id};
	  },
	  serialize: function(params) {
		    // this will make the URL `/posts/foo-post`
		 // alert(2);
		 //return {"single_price2":single_price,"single_price":single_price,};
		  return {"activity_id":params["activity_id"]};
		  },
	setupController : function(controller) {
		

		
		var model=controller.get("model");
		//alert(123);
		if(!model["single_price"]){
		MDEmber.jsonAsync("/mahua/Activity/fetchBasicInfo.json",
				"post",
				{"activity_id":model["activity_id"]},
				function(data) {
					if (data["showActivityInfo"]) {
						
						
						controller.set("model",{activity_id: data["showActivityInfo"]["MahuaActivityBasicInfo_activity_id"]
                        ,single_price: data["showActivityInfo"]["MahuaActivityBasicInfo_single_price"]
                        ,count:data["showActivityInfo"]["MahuaActivityBasicInfo_default_count"]
                        ,total:data["showActivityInfo"]["MahuaActivityBasicInfo_default_count"]*data["showActivityInfo"]["MahuaActivityBasicInfo_single_price"]
						,source_code:"00000001"});
						
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

		
	}
}
});



MDEmber.Orderticket = Ember.Object.extend({
	
	isSingle:function() {
	    return this.get('WeixinRuleContentReplyMix_article_count')===1?true:false;
	}.property('WeixinRuleContentReplyMix_article_count')
});



MDEmber.OrderticketController = Ember.Controller.extend({
	 plus:function(thisView){
		 var model=this.get("model")
		 //var count=model.count;
		 var count= model["count"]+1;
		 
		 Ember.set(model,"count",count);
		 Ember.set(model,"total",count*model["single_price"]);
		 this.set("model",model);
		 
		 if(count>1){
			 $("#minus").removeClass("disable");
			 $("#minus").addClass("enable");
		 }
	 },
     minus:function(thisView){
    	 var model=this.get("model")
		 //var count=model.count;
		 var count= model["count"]-1;
		 if(count>=1){
		 Ember.set(model,"count",count);
		 Ember.set(model,"total",count*model["single_price"]);
		 this.set("model",model);
		 
		
         }
		 if(count==1){
			 $("#minus").removeClass("enable");
			 $("#minus").addClass("disable");
		 }
     },
	 confirmOrder:function(){
		 var model=this.get("model");
		 var phone_no=$("#phone_no")[0].value;
		 Ember.set(model,"phone_no",phone_no);
		 var thisController=this;
		 MDEmber.jsonAsync("/mahua/Order/createOrder.json",
					"post",
					{"order_info":this.get("model")},
					function(data) {
						if (data["orderInfo"]) {
							
							
							 //var model=thisController.get("model");
							 thisController.transitionToRoute("confirmorder",{"order_info":data["orderInfo"]["MahuaOrder"],"para":data["para"]});
							
						}},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});
		
	 },
	 timeCellPick:function(target){
		 var model=this.get("model");
		 var time_str=target[0].innerText;
		 Ember.set(model,"showtime",time_str);
		 
	 }
});


MDEmber.OrderticketView = Ember.View.extend({
	templateName : "order_ticket",
	click: function() {
		if($(event.target).hasClass("time_cell")){
			//this.controller.send('nextAction', this);
			
			$("span[class='time_cell selected']").removeClass("selected");
			$(event.target).addClass("selected");
			this.controller.send('timeCellPick', $(event.target));
		};
		if($(event.target).hasClass("count_picker")){
			//this.controller.send('nextAction', this);
			
			if($(event.target)[0].id=="plus"){
				this.controller.send('plus', this);
				
			}else{
				this.controller.send('minus', this);
			}
		};
		if($(event.target).hasClass("btn-primary")){
			
			
			this.controller.send('confirmOrder', this);
			
			
		};
	}
	
});

MDEmber.ConfirmorderRoute = Ember.Route.extend({
	model: function(params) {
	   
		//alert(1);
	  },
	  serialize: function(params) {
		  //alert(2);
		    // this will make the URL `/posts/foo-post`
		 //this.controller.set("model",params);
		 //return {"single_price":params["single_price"],"count":params["count"],"total":params["total"]};
		  //return {"params":params,};
		  return params["order_info"];
		  },
	setupController : function(controller) {
		
		//alert(controller);
		/*MDEmber.jsonAsync("/mahua/Activity/showActivityInfo.json",
				"post",
				{},
				function(data) {
					if (data["showActivityInfo"]) {
						
						
						controller.set("model",data["showActivityInfo"]);
						
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});
*/
		
	},
});



MDEmber.Confirmorder = Ember.Object.extend({
	
	isSingle:function() {
	    return this.get('WeixinRuleContentReplyMix_article_count')===1?true:false;
	}.property('WeixinRuleContentReplyMix_article_count')
});



MDEmber.ConfirmorderController = Ember.Controller.extend({
	
});


MDEmber.ConfirmorderView = Ember.View.extend({
	templateName : "confirm_order",
	
});