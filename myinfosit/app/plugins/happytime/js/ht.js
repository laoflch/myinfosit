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
	this.route("pass", {
		path : "/pass"
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

MDEmber.PassRoute = MDEmber.StandRoute.extend({
	setupController : function(controller) {
		
		//MDEmber.passController = controller;
		
		MDEmber.jsonAsync("/happytime/Activity/getPassActiviesList.json",
				"post",
				{"page_info":'{"page":'+controller.currentPage+',"limit":'+controller.pageSize+'}'},
				function(data) {
					if (data["passActiviesList"]) {
						
						//var passActiviesList=new Array
						Ember.ArrayPolyfills.forEach.call(data["passActiviesList"], function(element, index, array) {
							array[index]=MDEmber.Activity.create(element);
							});
						
						/*Ember.ArrayPolyfills.forEach(function(element, index, array){
							array[index]=MDEmber.Activity.create(element);
						},data["passActiviesList"]);*/
						
						controller.set("model",data["passActiviesList"]);
						
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

MDEmber.HappyshareRoute = MDEmber.StandRoute.extend({
	setupController : function(controller) {
		
		//MDEmber.happyshareController = controller;
		
		
		
		MDEmber.jsonAsync("/happytime/Activity/getHappyShareActiviesList.json",
				"post",
				{"page_info":'{"page":'+controller.currentPage+',"limit":'+controller.pageSize+'}'},
				function(data) {
						if (data["happyShareActiviesList"]) {
					
							// callback_with_controller(data["happyShareActiviesList"],controller);
							controller.set("model",data["happyShareActiviesList"]);
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

		
	},
});

MDEmber.Activity = Ember.Object.extend({
	
	isSingle:function() {
	    return this.get('WeixinRuleContentReplyMix_article_count')===1?true:false;
	}.property('WeixinRuleContentReplyMix_article_count')
});


MDEmber.MDArrayController = Ember.ArrayController.extend({});

MDEmber.PassController = MDEmber.MDArrayController.extend({
	isShow:false,
	lastY:0,
	currentPage:1,
	pageSize:1,
	
    moreContent: function(level){
		    var moreContentView =this.get("moreContentView");
		    
		    var _self=this;
		    
			MDEmber.jsonAsync("/happytime/Activity/getPassActiviesList.json",
					"post",
					{"page_info":'{"page":'+(_self.currentPage+1)+',"limit":'+_self.pageSize+'}'},
					function(data) {
							if (data["passActiviesList"]) {
								
								var viewc=Ember.View.create({
							    	templateName: "activity",
							      });
								// callback_with_controller(data["happyShareActiviesList"],controller);
								 viewc.set("controller",data["passActiviesList"]);
								 moreContentView.pushObject(viewc);
								 _self.currentPage++;
								 var passView=_self.get("passView");
								 passView.hookView.hiddenHook.apply(_self,new Array([passView.hookView.elementId,2000]));
						}},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});
		    
		   
		      
		    
		    
		   
    }
    
   
});

MDEmber.DragUpController = MDEmber.MDArrayController.extend({
	isShow:false,
	lastY:0,
	currentPage:1,
	pageSize:1,
});

MDEmber.DragUpView = Ember.View.extend({
	hookView : null,
	click : function(event){
		//alert(event.target.name);
		var ItemNode=$(event.target).closest(".article");
		/*function getItemNode(node){
			
			if(node.className&&node.className!=='null'&&node.className.indexOf("article")>-1){
				return node;
				
			}else{
			    if(typeof(node.className)!="undefined"||node.className!=null){
			    	return false;
			    	
			    }
				return getItemNode(node.parentNode);
			}
			
		}*/
		
		if(typeof(ItemNode)!="undefined"&&ItemNode!=null&&ItemNode.length>0){
			
			window.location.href=ItemNode[0].attributes["url"].value;
			
			
		}else{
			
			return false;
		}
		
		
		
		
	},
	 touchStart:function(event){
	    	this.controller.set("lastY",event.originalEvent.touches[0].pageY);
	    	
	    },
	    touchMove:function(event){
	    	if(!this.controller.isShow){
	    	st = $(window).scrollTop();
	    	function getPageSize(window,document) {
	    	    var xScroll, yScroll;
	    	    if (window.innerHeight && window.scrollMaxY) {
	    	        xScroll = window.innerWidth + window.scrollMaxX;
	    	        yScroll = window.innerHeight + window.scrollMaxY;
	    	    } else {
	    	        if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac    
	    	            xScroll = document.body.scrollWidth;
	    	            yScroll = document.body.scrollHeight;
	    	        } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari    
	    	            xScroll = document.body.offsetWidth;
	    	            yScroll = document.body.offsetHeight;
	    	        }
	    	    }
	    	    var windowWidth, windowHeight;
	    	    if (self.innerHeight) { // all except Explorer    
	    	        if (document.documentElement.clientWidth) {
	    	            windowWidth = document.documentElement.clientWidth;
	    	        } else {
	    	            windowWidth = self.innerWidth;
	    	        }
	    	        windowHeight = self.innerHeight;
	    	    } else {
	    	        if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode    
	    	            windowWidth = document.documentElement.clientWidth;
	    	            windowHeight = document.documentElement.clientHeight;
	    	        } else {
	    	            if (document.body) { // other Explorers    
	    	                windowWidth = document.body.clientWidth;
	    	                windowHeight = document.body.clientHeight;
	    	            }
	    	        }
	    	    }   
	    	    
	    	    // for small pages with total height less then height of the viewport    
	    	    if (yScroll < windowHeight) {
	    	        pageHeight = windowHeight;
	    	    } else {
	    	        pageHeight = yScroll;
	    	    }    
	    	    // for small pages with total width less then width of the viewport    
	    	    if (xScroll < windowWidth) {
	    	        pageWidth = xScroll;
	    	    } else {
	    	        pageWidth = windowWidth;
	    	    }
	    	    arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
	    	    return arrayPageSize;
	    	};
	        arrayPageSize=getPageSize($(window),$(document)[0]);
	        pageHeight =arrayPageSize[1];
	        screenHeight =arrayPageSize[3];
	      // e.preventDefault();
	      if(lastY-event.originalEvent.touches[0].pageY<-1){
	        //alert("pageHeight:"+pageHeight+"st:"+st+"screenHeight:"+screenHeight);
	        /*判断上划*/
	      }
	        if(st+screenHeight+2>=pageHeight&&!this.controller.isShow){
	        	//alert(123);
	        	var lastY=this.controller.get("lastY");
	        	var swipe = lastY-event.originalEvent.touches[0].pageY;
	        	if(swipe > 0&&!this.controller.get("isShow")) {
	         	   this.hookView.showHook.apply(this);	
	               this.controller.send('moreContent', 11);

	        }
	        	
	        }else{
	        	/*判断下划*/
	        	console.warn( "--3--" );
	        	this.controller.set("isShow",false);
	        }

	    	

	           
	    	
	    }
	    }
	
});

MDEmber.MorecontentView=Ember.ContainerView.extend({
	init:function (){
		this._super();
		var controller = this.get("controller");
		if(controller){
			
			controller.set("moreContentView",this);
		}
	},
	
	/*classNames: ['the-container'],
    childViews: ['aView'],
    aView: Ember.View.create({
      template: Ember.Handlebars.compile("A")
    }),*/
});

MDEmber.PassView = Ember.View.extend({
	templateName : "pass_activities",
	hookView : null,
	init:function (){
		this._super();
		var controller = this.container.lookup("controller:pass");
		if(controller){
			
			controller.set("passView",this);
		}
	},
	
	/*llclick : function(event){
		//alert(event.target.name);
		var ItemNode=$(event.target).closest(".article");
		function getItemNode(node){
			
			if(node.className&&node.className!=='null'&&node.className.indexOf("article")>-1){
				return node;
				
			}else{
			    if(typeof(node.className)!="undefined"||node.className!=null){
			    	return false;
			    	
			    }
				return getItemNode(node.parentNode);
			}
			
		}
		
		if(typeof(ItemNode)!="undefined"&&ItemNode!=null&&ItemNode.length>0){
			
			window.location.href=ItemNode[0].attributes["url"].value;
			
			
		}else{
			
			return false;
		}
		
		
		
		
	},*/
    touchStart:function(event){
    	this.controller.set("lastY",event.originalEvent.touches[0].pageY);
    	
    },
    touchMove:function(event){
    	if(!this.controller.isShow){
    		st = $(window).scrollTop();
    	function getPageSize(window,document) {
    		
    	    var xScroll, yScroll;
    	    if (window.innerHeight && window.scrollMaxY) {
    	        xScroll = window.innerWidth + window.scrollMaxX;
    	        yScroll = window.innerHeight + window.scrollMaxY;
    	    } else {
    	        if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac    
    	            xScroll = document.body.scrollWidth;
    	            yScroll = document.body.scrollHeight;
    	        } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari    
    	            xScroll = document.body.offsetWidth;
    	            yScroll = document.body.offsetHeight;
    	        }
    	    }
    	    var windowWidth, windowHeight;
    	    if (self.innerHeight) { // all except Explorer    
    	        if (document.documentElement.clientWidth) {
    	            windowWidth = document.documentElement.clientWidth;
    	        } else {
    	            windowWidth = self.innerWidth;
    	        }
    	        windowHeight = self.innerHeight;
    	    } else {
    	        if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode    
    	            windowWidth = document.documentElement.clientWidth;
    	            windowHeight = document.documentElement.clientHeight;
    	        } else {
    	            if (document.body) { // other Explorers    
    	                windowWidth = document.body.clientWidth;
    	                windowHeight = document.body.clientHeight;
    	            }
    	        }
    	    }   
    	    
    	    // for small pages with total height less then height of the viewport    
    	    if (yScroll < windowHeight) {
    	        pageHeight = windowHeight;
    	    } else {
    	        pageHeight = yScroll;
    	    }    
    	    // for small pages with total width less then width of the viewport    
    	    if (xScroll < windowWidth) {
    	        pageWidth = xScroll;
    	    } else {
    	        pageWidth = windowWidth;
    	    }
    	    arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
    	    return arrayPageSize;
    	};
        arrayPageSize=getPageSize($(window),$(document)[0]);
        pageHeight =arrayPageSize[1];
        screenHeight =arrayPageSize[3];
      
        
        if(st+screenHeight+2>pageHeight){
        	//alert(123);
        	var lastY=this.controller.get("lastY");
        	var swipe = lastY-event.originalEvent.touches[0].pageY;
        	if(swipe > 0&&!this.controller.get("isShow")) {
        	   this.hookView.showHook.apply(this);	
               this.controller.send('moreContent', 11);
              /* hook.delay(2000).slideUp(1000, function () {
                if(settings.reloadPage) {
                       window.location.reload(true);
                   }
              //$("body").animate({"scrollTop": $(document)[0].body.scrollHeight},1);
               });*/
               

        }
        	
        }else{
        	/*判断下划*/
        	console.warn( "--3--" );
        	this.controller.set("isShow",false);
        }

    	

           
    	
    }
    }
});

MDEmber.HappyshareController = MDEmber.DragUpController.extend({
	 moreContent: function(level){
		    var moreContentView =this.get("moreContentView");
		    
		    var _self=this;
		    
			MDEmber.jsonAsync("/happytime/Activity/getHappyShareActiviesList.json",
					"post",
					{"page_info":'{"page":'+(_self.currentPage+1)+',"limit":'+_self.pageSize+'}'},
					function(data) {
							if (data["happyShareActiviesList"]) {
								
								var viewc=Ember.View.create({
							    	templateName: "activity",
							      });
								// callback_with_controller(data["happyShareActiviesList"],controller);
								 viewc.set("controller",data["happyShareActiviesList"]);
								 moreContentView.pushObject(viewc);
								 _self.currentPage++;
								 var passView=_self.get("happyshareView");
								 passView.hookView.hiddenHook.apply(_self,new Array([passView.hookView.elementId,2000]));
						}},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});
		    
		   
		      
		    
		    
		   
 }
	
});

MDEmber.HappyshareView = MDEmber.DragUpView.extend({
	templateName : "pass_activities",
	init:function (){
		this._super();
		var controller = this.container.lookup("controller:happyshare");
		if(controller){
			
			controller.set("happyshareView",this);
		}
	},
	click : function(event){
		//alert(event.target.name);
		var ItemNode=$(event.target).closest(".article");
		/*function getItemNode(node){
			
			if(node.className&&node.className!=='null'&&node.className.indexOf("article")>-1){
				return node;
				
			}else{
			    if(typeof(node.className)!="undefined"||node.className!=null){
			    	return false;
			    	
			    }
				return getItemNode(node.parentNode);
			}
			
		}*/
		
		if(typeof(ItemNode)!="undefined"&&ItemNode!=null&&ItemNode.length>0){
			
			window.location.href=ItemNode[0].attributes["url"].value;
			
			
		}else{
			
			return false;
		}
		
		
		
		
	},
   
});

MDEmber.HookView = Ember.View.extend({
	templateName:'hook',
	classNames : [ 'hook', 'hook-with-text' ],
	init:function(){
		this._super();
		this._parentView.hookView=this;
	},
	hiddenHook:function(args){
		//var hook=$("#"+this.elementId);	
		$("#"+args[0]).slideUp(args[1], function () {
             /*if(settings.reloadPage) {
                    window.location.reload(true);
                }*/
           $("body").animate({"scrollTop": $(document)[0].body.scrollHeight},1);
            });
            
        this.set("isShow",false);
	},
	
	showHook:function(){
		//var hook=$("#"+this.elementId);	
		this.hookView.$().show().css("height","100px");
            
        this.controller.set("isShow",true);
	}
	
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

	   
	      //font =  Handlebars.Utils.escapeExpression(value);
              font = value;
	   
	  }, this);

	  // Add the unique identifier
	  // NOTE: We use all lower-case since Firefox has problems with mixed case in SVG
	  //ret.push('data-bindattr-' + dataId + '="' + dataId + '"');
	  //return new EmberHandlebars.SafeString(ret.join(' '));
	return new Ember.Handlebars.SafeString('<font data-bindattr-' + dataId + '=\"' + dataId + '\">'+font?font.replace(/(.{17}.)/g,"$1<br/>"):""+'</font>');

	
});

Ember.Handlebars.registerHelper('if-gt', function(property, fn) {
	  var context = (fn.contexts && fn.contexts[0]) || this;
	  var properties = property.split("$");
	  var re_property = properties[0];
	  var value = properties[1];
	  var func = function(result) {
		  
		var truthy = result;
		if (typeof truthy === 'boolean') { return truthy; };
		    
	    if (parseInt(result) > (value)) { return true; };
	    
	    return false;

	   /* if (Ember.isArray(result)) {
	      return get(result, 'length') !== 0;
	    } else {
	      return !!result;
	    }*/
	  };

	  return bind.call(context, re_property, fn, true, func, func);
	});


function bind(property, options, preserveContext, shouldDisplay, valueNormalizer, childProperties) {
	  var data = options.data,
	      fn = options.fn,
	      inverse = options.inverse,
	      view = data.view,
	      currentContext = this,
	      normalized, observer, i;

	  normalized = Ember.Handlebars.normalizePath(currentContext, property, data);

	  // Set up observers for observable objects
	  if ('object' === typeof this) {
	    if (data.insideGroup) {
	      observer = function() {
	        Ember.run.once(view, 'rerender');
	      };

	      var template, context, result = handlebarsGet(currentContext, property, options);

	      result = valueNormalizer(result);

	      context = preserveContext ? currentContext : result;
	      if (shouldDisplay(result)) {
	        template = fn;
	      } else if (inverse) {
	        template = inverse;
	      }

	      template(context, { data: options.data });
	    } else {
	      // Create the view that will wrap the output of this template/property
	      // and add it to the nearest view's childViews array.
	      // See the documentation of Ember._HandlebarsBoundView for more.
	      var bindView = view.createChildView(Ember._HandlebarsBoundView, {
	        preserveContext: preserveContext,
	        shouldDisplayFunc: shouldDisplay,
	        valueNormalizerFunc: valueNormalizer,
	        displayTemplate: fn,
	        inverseTemplate: inverse,
	        path: property,
	        pathRoot: currentContext,
	        previousContext: currentContext,
	        isEscaped: !options.hash.unescaped,
	        templateData: options.data
	      });

	      view.appendChild(bindView);

	      observer = function() {
	        Ember.run.scheduleOnce('render', bindView, 'rerenderIfNeeded');
	      };
	    }

	    // Observes the given property on the context and
	    // tells the Ember._HandlebarsBoundView to re-render. If property
	    // is an empty string, we are printing the current context
	    // object ({{this}}) so updating it is not our responsibility.
	    if (normalized.path !== '') {
	      view.registerObserver(normalized.root, normalized.path, observer);
	      if (childProperties) {
	        for (i=0; i<childProperties.length; i++) {
	          view.registerObserver(normalized.root, normalized.path+'.'+childProperties[i], observer);
	        }
	      }
	    }
	  } else {
	    // The object is not observable, so just render it out and
	    // be done with it.
	    data.buffer.push(handlebarsGet(currentContext, property, options));
	  }
	}