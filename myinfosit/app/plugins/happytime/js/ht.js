MDEmber = Ember.Application.create({
	title : 'happy_time_pass_activities',
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

MDEmber.Router.map(function() {
	this.route("pass", {
		path : "/"
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

MDEmber.PassRoute = MDEmber.StandRoute.extend({
	setupController : function(controller) {
		
		MDEmber.passController = controller;
		
		MDEmber.jsonAsync("/happytime/Activity/getPassActiviesList.json",
				"post",
				{
				},
				function(data) {
					if (data["passActiviesList"]) {
						MDEmber.passController.set("model",data["passActiviesList"]);
						
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

		
	},
});

MDEmber.MDArrayController = Ember.ArrayController.extend({});

MDEmber.PassController = MDEmber.MDArrayController.extend({});

MDEmber.PassView = Ember.View.extend({
	templateName : "pass_activities",
});

Ember.Handlebars.registerHelper('backgroup-image',function(options){
	var attrs = options.hash;

	 
	  var view = options.data.view;
	  var ret = [];
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

	   
	      url =  Handlebars.Utils.escapeExpression(value);
	   
	  }, this);

	  // Add the unique identifier
	  // NOTE: We use all lower-case since Firefox has problems with mixed case in SVG
	  //ret.push('data-bindattr-' + dataId + '="' + dataId + '"');
	  //return new EmberHandlebars.SafeString(ret.join(' '));
	return new Ember.Handlebars.SafeString('style=\"background-image:url('+url+')\" data-bindattr-' + dataId + '=\"' + dataId + '\"');
	
});


Ember.Handlebars.registerHelper('text-content',function(options){
	var attrs = options.hash;

	 
	  var view = options.data.view;
	  var ret = [];
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

	   
	      content =  Handlebars.Utils.escapeExpression(value);
	   
	  }, this);

	  // Add the unique identifier
	  // NOTE: We use all lower-case since Firefox has problems with mixed case in SVG
	  //ret.push('data-bindattr-' + dataId + '="' + dataId + '"');
	  //return new EmberHandlebars.SafeString(ret.join(' '));
	return new Ember.Handlebars.SafeString('<font data-bindattr-' + dataId + '=\"' + dataId + '\">'+content+'</div>');
	
});
/*Ember.Handlebars.registerHelper('int-if', function(context, options) {
	  Ember.assert("You must pass exactly one argument to the if helper", arguments.length === 2);
	  Ember.assert("You must pass a block to the if helper", options.fn && options.fn !== Handlebars.VM.noop);

	  return Ember.Handlebars.helpers.int-boundif.call(options.contexts[0], context, options);
	});

Ember.Handlebars.registerHelper('int-boundif',function(property, fn) {
	  var context = (fn.contexts && fn.contexts[0]) || this;
	  var func = function(result) {
	    //var truthy = result && get(result, 'isTruthy');
	    if (!isNaN(result)) { 
	    	return false; 
	    }else{
	    	if(result==1){
	    		return true;
	    		
	    	
	    }
	  };

	  return bind.call(context, property, fn, true, func, func, ['isTruthy', 'length']);
});*/
