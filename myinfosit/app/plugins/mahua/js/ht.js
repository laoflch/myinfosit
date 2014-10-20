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
		path : "/"
	});
	this.route("showactivity", {
		path : "/showactivity"
	});
	this.route("happyshare", {
		path : "/happyshare"
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

MDEmber.ShowactivityRoute = MDEmber.StandRoute.extend({
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
	 actions : {
		    turnItUp: function(level){
		      //Do your thing
		    }
     }
});



MDEmber.Showactivity = Ember.Object.extend({
	
	isSingle:function() {
	    return this.get('WeixinRuleContentReplyMix_article_count')===1?true:false;
	}.property('WeixinRuleContentReplyMix_article_count')
});

MDEmber.ShowactivityController = Ember.ObjectController.extend({
    moreContent: function(thisView){
		    /*var moreContentView =this.get("moreContentView");
		    
		    var _selfView=thisView;
		    var _self=this;    */
			MDEmber.jsonAsync("/mahua/Activity/getHappyShareActiviesList.json",
					"post",
					{},
					function(data) {
							if (data["passActiviesList"]) {
								
							
						}},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});		    
		    
		   
    }
    
   
});


MDEmber.ShowactivityView = Ember.View.extend({
	templateName : "show_activity",
	init:function (){
		
	},
	
	
});

