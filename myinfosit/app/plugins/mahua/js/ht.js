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
		path : "/showactivity"
	});
	this.resource("orderticket", {
		path : "/orderticket/:params"
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
		MDEmber.jsonSync("/mahua/Activity/showActivityInfo.json",
				"post",
				{"source_code":params.source_code},
				function(data) {
					if (data["showActivityInfo"]) {
						
						
						return_str=data["showActivityInfo"];
						
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

       return return_str;
	  },	
	setupController : function(controller) {
		
		
		MDEmber.jsonAsync("/mahua/Activity/showActivityInfo.json",
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
		this.transitionToRoute("orderticket",{single_price: 280,count:1,total:280});
		
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
	   
	    //alert(params.single_price+"1");
	  },
	  serialize: function(params) {
		    // this will make the URL `/posts/foo-post`
		  //alert(this.controller);
		 //return {"single_price2":single_price,"single_price":single_price,};
		  return {"params":params,};
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
     }
});


MDEmber.OrderticketView = Ember.View.extend({
	templateName : "order_ticket",
	click: function() {
		if($(event.target).hasClass("time_cell")){
			//this.controller.send('nextAction', this);
			
			$("span[class='time_cell selected']").removeClass("selected");
			$(event.target).addClass("selected");
		};
		if($(event.target).hasClass("count_picker")){
			//this.controller.send('nextAction', this);
			
			if($(event.target)[0].id=="plus"){
				this.controller.send('plus', this);
				
			}else{
				this.controller.send('minus', this);
			}
		};
	}
	
});

