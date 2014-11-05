MDEmber = Ember.Application.create({
	title : 'activities',
	rootElement : '.bg',
	
	subDir: "/myinfosit",
	
	strToDate: function(dateStr){
		var data = dateStr;  
		var reCat = /(\d{1,4})/gm;   
		var t = data.match(reCat);
		t[1] = t[1] - 1;
		eval('var d = new Date('+t.join(',')+');');
		return d;
	},
	
	transDateFomat:function(date_value){
		
		return date_value.getFullYear()+"年"+date_value.getMonth()+"月"
		       +date_value.getDate()+"日"+"<br> 周"+"日一二三四五六".charAt(date_value.getDay())+" "
               +date_value.getHours()+":"+date_value.getMinutes();
	},
	
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

		
	}else{
		MDEmber.jsonAsync("/mahua/Activity/fetchActivityTimeInfo.json",
				"post",
				{"activity_id":model["activity_id"]},
				function(data) {
					if (data["showTimeInfo"]) {
						
						
					/*	controller.set("model",{activity_id: data["showActivityInfo"]["MahuaActivityBasicInfo_activity_id"]
                        ,single_price: data["showActivityInfo"]["MahuaActivityBasicInfo_single_price"]
                        ,count:data["showActivityInfo"]["MahuaActivityBasicInfo_default_count"]
                        ,total:data["showActivityInfo"]["MahuaActivityBasicInfo_default_count"]*data["showActivityInfo"]["MahuaActivityBasicInfo_single_price"]
						,source_code:"00000001"});*/
						Ember.set(model,"show_time_list",data["showTimeInfo"]);
						
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
		 var count= parseInt(model["count"])+1;
		 
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
		 var time_str=target[0]["attributes"]["value"].nodeValue;
		 Ember.set(model,"show_time",time_str);
		 
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

Ember.Handlebars.registerHelper('time-select',function(options){
	var attrs = options.hash;

	 
	  var view = options.data.view;
	  var font,select ;
	  var ctx = this;

	  // Generate a unique id for this element. This will be added as a
	  // data attribute to the element so it can be looked up when
	  // the bound property changes.
	  var dataId = ++Ember.uuid;

	  

	  var attrKeys = Ember.keys(attrs);
	  var forEach = Ember.ArrayPolyfills.forEach;
	  var url;

	  // For each attribute passed, create an observer and emit the
	  // current value of the property as an attribute.
	forEach.call(attrKeys, function(attr) {
	    var path = attrs[attr],
	        normalized;

	   
	    normalized = Ember.Handlebars.normalizePath(ctx, path, options.data);

	    var value = (path === 'this') ? normalized.root : Ember.Handlebars.get(ctx, path, options),
	        type = Ember.typeOf(value);

	    var observer, invoker;

	    observer = function observer() {
	      var result = Ember.Handlebars.get(ctx, path, options);

	      var elem = view.$("[data-bindattr-" + dataId + "='" + dataId + "']");

	      // If we aren't able to find the element, it means the element
	      // to which we were bound has been removed from the view.
	      // In that case, we can assume the template has been re-rendered
	      // and we need to clean up the observer.
	      if (!elem || elem.length === 0) {
	        Ember.removeObserver(normalized.root, normalized.path, invoker);
	        return;
	      }

	      Ember.View.applyAttributeBindings(elem, attr, result);
	    };

	    // Add an observer to the view for when the property changes.
	    // When the observer fires, find the element using the
	    // unique data id and update the attribute to the new value.
	    // Note: don't add observer when path is 'this' or path
	    // is whole keyword e.g. {{#each x in list}} ... {{bindAttr attr="x"}}
	    if (path !== 'this' && !(normalized.isKeyword && normalized.path === '' )) {
	      view.registerObserver(normalized.root, normalized.path, observer);
	    }

	   if(attr==="content"){
		   font = value;
	   }
	      //font =  Handlebars.Utils.escapeExpression(value);
       if(attr==="select"){
    	   select = value;
       }      
	   
	  }, this);

	  // Add the unique identifier
	  // NOTE: We use all lower-case since Firefox has problems with mixed case in SVG
	  //ret.push('data-bindattr-' + dataId + '="' + dataId + '"');
	  //return new EmberHandlebars.SafeString(ret.join(' '));
	//return new Ember.Handlebars.SafeString('<font data-bindattr-' + dataId + '=\"' + dataId + '\">'+font?font.replace(/(.{17}.)/g,"$1<br/>"):""+'</font>');
	var data_value=MDEmber.strToDate(font);
	var fomart_date_str=MDEmber.transDateFomat(data_value);
	if(parseInt(select)){
	return new Ember.Handlebars.SafeString('<span class="time_cell selected" data-bindattr-' + dataId + '=\"' + dataId + '\" value=\"' + font + '\">'+fomart_date_str+'</span>');
    }else{
    	return new Ember.Handlebars.SafeString('<span class="time_cell" data-bindattr-' + dataId + '=\"' + dataId + '\" value=\"' + font + '\">'+fomart_date_str+'</span>');	
    } 
	
});

Ember.Handlebars.registerHelper('time-format',function(options){
	var attrs = options.hash;

	 
	  var view = options.data.view;
	  var font,select ;
	  var ctx = this;

	  // Generate a unique id for this element. This will be added as a
	  // data attribute to the element so it can be looked up when
	  // the bound property changes.
	  var dataId = ++Ember.uuid;

	  

	  var attrKeys = Ember.keys(attrs);
	  var forEach = Ember.ArrayPolyfills.forEach;
	  var url;

	  // For each attribute passed, create an observer and emit the
	  // current value of the property as an attribute.
	forEach.call(attrKeys, function(attr) {
	    var path = attrs[attr],
	        normalized;

	   
	    normalized = Ember.Handlebars.normalizePath(ctx, path, options.data);

	    var value = (path === 'this') ? normalized.root : Ember.Handlebars.get(ctx, path, options),
	        type = Ember.typeOf(value);

	    var observer, invoker;

	    observer = function observer() {
	      var result = Ember.Handlebars.get(ctx, path, options);

	      var elem = view.$("[data-bindattr-" + dataId + "='" + dataId + "']");

	      // If we aren't able to find the element, it means the element
	      // to which we were bound has been removed from the view.
	      // In that case, we can assume the template has been re-rendered
	      // and we need to clean up the observer.
	      if (!elem || elem.length === 0) {
	        Ember.removeObserver(normalized.root, normalized.path, invoker);
	        return;
	      }

	      Ember.View.applyAttributeBindings(elem, attr, result);
	    };

	    // Add an observer to the view for when the property changes.
	    // When the observer fires, find the element using the
	    // unique data id and update the attribute to the new value.
	    // Note: don't add observer when path is 'this' or path
	    // is whole keyword e.g. {{#each x in list}} ... {{bindAttr attr="x"}}
	    if (path !== 'this' && !(normalized.isKeyword && normalized.path === '' )) {
	      view.registerObserver(normalized.root, normalized.path, observer);
	    }

	   if(attr==="content"){
		   font = value;
	   }
	      //font =  Handlebars.Utils.escapeExpression(value);
       if(attr==="select"){
    	   select = value;
       }      
	   
	  }, this);

	  // Add the unique identifier
	  // NOTE: We use all lower-case since Firefox has problems with mixed case in SVG
	  //ret.push('data-bindattr-' + dataId + '="' + dataId + '"');
	  //return new EmberHandlebars.SafeString(ret.join(' '));
	//return new Ember.Handlebars.SafeString('<font data-bindattr-' + dataId + '=\"' + dataId + '\">'+font?font.replace(/(.{17}.)/g,"$1<br/>"):""+'</font>');
	var data_value=MDEmber.strToDate(font);
	var fomart_date_str=MDEmber.transDateFomat(data_value);
	//if(parseInt(select)){
	return new Ember.Handlebars.SafeString(fomart_date_str);
    //}else{
    	//return new Ember.Handlebars.SafeString('<span class="time_cell" data-bindattr-' + dataId + '=\"' + dataId + '\" value=\"' + font + '\">'+fomart_date_str+'</span>');	
   // } 
	
});