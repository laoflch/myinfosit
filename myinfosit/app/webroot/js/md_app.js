MDEmber = Ember.Application.create({
	title : 'micro-data',
	rootElement : '#main_content',
	//openAccountId :"678038100",
	//customerId : 1,
	customerTimeStamp : 0,
	
	subDir: "/myinfosit",
	
	floatLayer : Ember.Object.create({
		rootElement : "float_layer",
		currentView : null,
		appendInto : function(view){
			if(view instanceof Ember.View){
				view.appendTo($("#"+this.rootElement));
				this.currentView=view;
			}
			
		},
		hide : function(){
			if(this.rootElement){
				//$("#"+this.rootElement+">*:gt(0)").empty();
			$("#"+this.rootElement).hide();
			}
			
		},
		destroyAndHide : function(){
			if(this.currentView instanceof Ember.View){
				this.currentView.remove();
				this.hide();
			}
			
		},
        show : function(){
        	if(this.rootElement){
        	$("#"+this.rootElement).show();
        	}
			
		}
		
	}),
	
	dynamicsEval : function(code) {
		if (!!(window.attachEvent && !window.opera)) {
			// ie
			execScript(code);
		} else {
			// not ie
			window.eval(code);
		}
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
	}
	,
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
	
	/*
	 * function handleTemple
	 * 处理模版，将模版加入document的最后
	 *
	 * 
	 * */
	handleTemple : function(temple) {
		// temple=$.parseHTML(temple);
		$("html").append(temple);
		Ember.Handlebars.bootstrap(Ember.$(document));

	},
	
	/*
	 * function getLoginState
	 * 获取当前用户登陆状态
	 *
	 * 
	 * */
	getLoginState : function() {

		MDEmber.jsonSync('/user/hasLogin.json',
				'get',
				null,
				function(data) {
			if (data["return_code"] == 105) {
				MDEmber.hasLogin = true;
			} else {
				MDEmber.hasLogin = false;
			}},function() {
				// view("异常！");
				alert("获取json数据错误！");
			}
		);
	},
	
	/*
	 * var hasLogin
	 * 标识用户是否登陆
	 * 
	 * 
	 * */
	hasLogin : undefined,

	viewSet : {},
    
	/*
	 * function getContentTypes
	 * 获取内容类型
	 * 
	 * 
	 * */
	getContentTypes : function() {
	
		MDEmber.jsonSync("/weixinopen/content/contentTypeList.json",
				"get",
				null,function(data) {			
			    	MDEmber.contentTypeList = data["contentTypeList"];
			    },
			    function() {
			    	alert("获取json数据错误！");
			    });
		
		return MDEmber.contentTypeList;

	},
	
	/*
	 * function getContentList
	 * 获取内容列表 
	 * content_type_code：内容类型代码
	 * account_open_id：开放账号ID
	 * 
	 * */
	getContentList : function(content_type_code, account_open_id) {
		var contentList;
		if (content_type_code === "01") {
			
			MDEmber.jsonSync("/weixinopen/content/contentTextList.json",
					"get",
					null,
					function(data) {
						// alert(typeof data["return_code"]);
				    	contentList = data["contentTextList"];

					},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});
		}
		
		if (content_type_code === "02") {
			
			
			
			MDEmber.jsonSync("/weixinopen/content/contentMixList.json",
					"get",
					null,
					function(data) {
				    	contentList = data["contentMixList"];

					},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});
		}
		return contentList;

	},
	
	/*
	 * function getRuleAdapterList
	 * 获取规则适配器列表 
	 * content_type_code：内容类型代码
	 * account_open_id：开放账号ID
	 * 
	 * */
	
	getRuleAdapterList : function(content_type_code, account_open_id) {

		var ruleAdapterList;
		
		MDEmber.jsonSync("/weixinopen/regular/ruleAdapterList.json",
				"get",
				null,
				function(data) {
			    	ruleAdapterList = data["ruleAdapterList"];

				},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

		return ruleAdapterList;

	},
	
	/*
	 * function logout
	 * 用户登出 
	 * e：js事件
	 * 
	 * */
	
	logout:function(e){
		var event=e || window.event;
		if(event.target.name==="logout"){
			
			MDEmber.jsonSync("/user/logout.json",
					"get",
					null,
					function(data) {
						if (data["return_code"] == 129) {
							var currentRoute = MDEmber.Router.router.currentHandlerInfos[1].handler;
											//currentRoute
							MDEmber.hasLogin = false;
											// MDEmber.userController.isSignin=false;

							location.href = MDEmber.subDir+"?"+Math.round(Math.random()*10000);
				}

					},
					function() {
						// view("异常！");
						alert("获取json数据错误！");
					});
	}

	},
	
	/*
	 * function setCustomerId()
	 * 更改当前用户客户公众帐号
	 *
	 * 
	 * */
	
	setCustomerId:function(customer_id){
		if(customer_id){
			MDEmber.customerId=customer_id;
			MDEmber.setCookie(MDEmber.currentUser+"_customer_id",customer_id,5);
		}
		
	},
	
	/*
	 * function getCustomerId()
	 * 获取当前用户客户公众帐号
	 *
	 * 
	 * */
	getCustomerId:function(){
		if(MDEmber.customerId){
			return MDEmber.customerId;
			
		}else{
			var customerId=MDEmber.getCookie(MDEmber.currentUser+"_customer_id");
			if(customerId){
				return customerId;
			}
		}
		return null;
	},
	
	setCookie:function(c_name,value,expiredays){
		var exdate = new Date();
		exdate.setDate(exdate.getDate() + expiredays)
		document.cookie = c_name
						+ "="
						+ escape(value)
						+ ((expiredays == null) ? "" : ";expires="
								+ exdate.toGMTString());
	},
	
	getCookie:function(c_name){
		if (document.cookie.length>0)
		  {
		  c_start=document.cookie.indexOf(c_name + "=");
		  if (c_start!=-1)
		    { 
		    c_start=c_start + c_name.length+1 ;
		    c_end=document.cookie.indexOf(";",c_start);
		    if (c_end==-1) c_end=document.cookie.length
		    return unescape(document.cookie.substring(c_start,c_end));
		    } 
		  }
		return "";
	}

});

/* model */

MDEmber.Store = Ember.Map.create();

MDEmber.Router.map(function() {
	this.route("index", {
		path : "/"
	});
	this.route("friend", {
		path : "/friend"
	});
	this.route("regular", {
		path : "/regular"
	});
	this.route("content", {
		path : "/content"
	});
	this.route("message", {
		path : "/message"
	});
});

/*
 * route
 */
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

MDEmber.IndexRoute = MDEmber.StandRoute.extend({
	init : function() {
		if (!MDEmber.hasLogin) {
			// false){
			this.childView = [ {
				name : 'signin',
				into : 'index',
				outlet : 'container-1'
			} ];
		} else {
			if (Ember.TEMPLATES['tool-template'] === undefined) {
				
				MDEmber.jsonSync("/user/login.json",
						"post",
						{view_outs : [ "tool", "selectMenu" ]
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								MDEmber.handleTemple(temple);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			}
			this.childView = [ {
				name : 'tool',
				into : 'index',
				outlet : 'container-1'
			} ];
		}
		;

	},
	renderTemplate : function() {

		this.render();

		this.renderChildView();
	},
	setupController : function(controller) {

		MDEmber.indexController = controller;
	},
});

MDEmber.RegularRoute = MDEmber.StandRoute.extend({
	init : function() {

	},
	renderTemplate : function() {

		this.render();
		
		this.renderChildView();
	},
	setupController : function(controller) {
		
		MDEmber.regularController = controller;

		if (!MDEmber.hasLogin) {
			this.childView = [ {
				name : 'signin',
				into : 'index',
				outlet : 'container-1'
			} ];
		} else {
			if (Ember.TEMPLATES['tool-template'] === undefined) {
				
				MDEmber.jsonSync("/weixinopen/regular/regularList.json",
						"post",
						{
							"view_outs" : [ "tool", "selectMenu", "regularlist" ],
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);
								MDEmber.regularController.set("model",
										data["regularList"]);
								MDEmber.regularController.controllerUpdateTime=(new Date()).valueOf();
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			} else {
				
				MDEmber.jsonSync("/weixinopen/regular/regularList.json",
						"post",
						{
							"view_outs" : [ "regularlist" ],
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.regularController.set("model",
										data["regularList"]);
								MDEmber.regularController.controllerUpdateTime=(new Date()).valueOf();
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});

			}
			this.childView = [ {
				name : 'tool',
				into : 'regular',
				outlet : 'container-1'
			}, {
				name : 'regularlist',
				into : 'regular',
				outlet : 'container-2'
			} ];
		}
		;
	},
	freshController : function(){
        if(MDEmber.regularController.controllerUpdateTime<MDEmber.customerTimeStamp){
		MDEmber.jsonSync("/weixinopen/regular/regularList.json",
				"post",
				{
					"customer_id" : MDEmber.getCustomerId(),
				},
				function(data) {
					if (data["regularList"]) {						

						MDEmber.regularController.set("model",
								data["regularList"]);
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});
        }
	}
});

MDEmber.FriendRoute = MDEmber.StandRoute.extend({
	init : function() {

	},
	renderTemplate : function() {

		this.render();
		
		this.renderChildView();
	},
	setupController : function(controller) {
		
		MDEmber.friendController = controller;

		if (!MDEmber.hasLogin) {
			this.childView = [ {
				name : 'signin',
				into : 'index',
				outlet : 'container-1'
			} ];
		} else {
			if (Ember.TEMPLATES['tool-template'] === undefined) {
				
				MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
						"post",
						{
							"view_outs" : [ "tool", "selectMenu", "friendlist", "friendnav","friendcurrentlist"],
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);
								
								MDEmber.friendController.set("model",
										data["friend_info_list"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			} else {
				
				MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
						"post",
						{
							"view_outs" : [ "friendlist","friendnav","friendcurrentlist"],
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.friendController.set("model",
										data["friend_info_list"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			}
			this.childView = [{
				name : 'tool',
				into : 'friend',
				outlet : 'container-1'
			}, {
				name : 'friendnav',
				into : 'friend',
				outlet : 'container-right'
			}, {
				name : 'friendlist',
				into : 'friend',
				outlet : 'container-left'
			} ];
		}
		;
	},
	freshController : function(){
        if(MDEmber.friendController.controllerUpdateTime<MDEmber.customerTimeStamp){
		MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
				"post",
				{
					"customer_id" : MDEmber.getCustomerId(),
				},
				function(data) {
					if (data["friend_info_list"]) {						

						MDEmber.friendController.set("model",
								data["friend_info_list"]);
					}},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});
        }
	}
});

MDEmber.ContentRoute = MDEmber.StandRoute.extend({
	init : function() {

	},
	defaultContentType : "text",
	renderTemplate : function() {

		this.render();

		this.renderChildView();
	},
	setupController : function(controller) {

		MDEmber.contentController = controller;

		if (!MDEmber.hasLogin) {
			this.childView = [ {
				name : 'signin',
				into : 'index',
				outlet : 'container-1'
			} ];
		} else {
			if (Ember.TEMPLATES['tool-template'] === undefined) {
			   MDEmber.jsonSync("/weixinopen/content/contentMain.json",
						"post",
						 {
							"view_outs" : [ "tool", "selectMenu", "contentnav",
									"contentlist", "contenttextlist" ],
							"contentType" : "text"
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.contentController.set("contentList",
										data["contentList"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
				
			} else {
			
				MDEmber.jsonSync("/weixinopen/content/contentMain.json",
						"post",
						{
							"view_outs" : [
									"content" + this.defaultContentType + "list",
									"contentnav", "contentlist" ],
							"contentType" : this.defaultContentType
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.contentController.set("contentList",
										data["contentList"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			}
			this.childView = [{
				name : 'tool',
				into : 'content',
				outlet : 'container-1'
			}, {
				name : 'contentnav',
				into : 'content',
				outlet : 'container-right'
			}, {
				name : 'contentlist',
				into : 'content',
				outlet : 'container-left'
			} ];
		}
		;
	},
});

MDEmber.MessageRoute = MDEmber.StandRoute.extend({
	init : function() {

	},
	defaultMessageType : "sample",
	renderTemplate : function() {

		this.render();

		this.renderChildView();
	},	
	setupController : function(controller) {

		MDEmber.messageController = controller;
		
		controller.set("isShowPage",true);

		if (!MDEmber.hasLogin) {
			this.childView = [ {
				name : 'signin',
				into : 'index',
				outlet : 'container-1'
			} ];
		} else {
			if (Ember.TEMPLATES['tool-template'] === undefined) {
			   MDEmber.jsonSync("/weixinopen/message/messageMain.json",
						"post",
						 {
							"view_outs" : [ "tool", "selectMenu","pagetool","messagenav",
									"messagelist", "messagesamplelist" ],
							"messageType" : "sample",
							"customer_id" : MDEmber.getCustomerId()
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.messageController.set("messageList",
										data["messageList"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
				
			} else {
			
				MDEmber.jsonSync("/weixinopen/message/messageMain.json",
						"post",
						{
							"view_outs" : [
									"message" + this.defaultMessageType + "list",
									"messagenav", "messagelist","pagetool" ],
							"messageType" : this.defaultMessageType,
							"customer_id" : MDEmber.getCustomerId()
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.messageController.set("messageList",
										data["messageList"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			}
			this.childView = [{
				name : 'tool',
				into : 'message',
				outlet : 'container-1'
			}, {
				name : 'messagenav',
				into : 'message',
				outlet : 'container-right'
			}, {
				name : 'messagelist',
				into : 'message',
				outlet : 'container-left'
			} ];
		}
		;
	},
});

/**
 * Controller
 */

MDEmber.MDArrayController = Ember.ArrayController.extend({
	controllerUpdateTime : 0,
});

MDEmber.MDPageController = MDEmber.MDArrayController.extend({
	isShowPage : false,
	currentPage : 0,
	pageSize : 20,
	pageCount :0
});

MDEmber.FriendController = MDEmber.MDArrayController.extend({
	currentOperationType : "current",
	friend_list : null,
	// content:Ember.Object.create(),
	isShowCurrent : function() {
		return (this.get("currentOperationType") === "current");
	}.property('currentOperationType'),
	isShowGroup : function() {
		return (this.get("currentOperationType") === "group");
	}.property('currentOperationType'),
	isShowLabel : function() {
		return (this.get("currentOperationType") === "label");
	}.property('currentOperationType'),
	isShowSynchronize : function() {
		//alert(this.get("currentOperationType"));
		return (this.get("currentOperationType") === "synchronize");
	}.property('currentOperationType'),
	isShowHistroy : function() {
		return (this.get("currentOperationType") === "histroy");
	}.property('currentOperationType'),
});


MDEmber.RegularController = MDEmber.MDArrayController.extend({});


MDEmber.IndexController = MDEmber.MDArrayController.extend({});

MDEmber.RegularContentController = Ember.ObjectController.extend({
	isKeyAdapter : function() {
		return (this.currentRuleAdapter === "01");/* RuleKeyAdapter */
	}.property('currentRuleAdapter'),
	isEventAdapter
	: function() {
		return (this.currentRuleAdapter === "02");/* EventKeyAdapter */
	}.property('currentRuleAdapter'),
	isTextContent : function() {
		return (this.currentContentType === "01");/* RuleKeyAdapter */
	}.property('currentContentType'),
	isReplyMixContent : function(){
		return (this.currentContentType === "02");/* RuleKeyAdapter */
	}.property('currentContentType'),
});


// MDEmber.NavController =Ember.ArrayController.extend({});

MDEmber.UserController = Ember.Controller.extend({
	// greeting : greeting.get("greeting")
	//  
	hasLogin :undefined,
	isSignin :function() {
		if(MDEmber.hasLogin===undefined){
			
			MDEmber.getLoginState();
			
		}
		return  MDEmber.hasLogin;
	}.property('hasLogin'),

});

//MDEmber.SelectMenuController = Ember.ArrayController.extend({});

MDEmber.userController = MDEmber.UserController.create({});

MDEmber.ContentController = Ember.ObjectController.extend({
	currentContentType : "text",
	contentList : null,
	// content:Ember.Object.create(),
	isShowText : function() {
		return (this.get("currentContentType") === "text");
	}.property('currentContentType'),
	isShowMix : function() {
		return (this.get("currentContentType") === "mix");
	}.property('currentContentType'),
	isShowPicture : function() {
		return (this.get("currentContentType") === "picture");
	}.property('currentContentType'),
	isShowVideo : function() {
		return (this.get("currentContentType") === "video");
	}.property('currentContentType'),
	isShowAudio : function() {
		return (this.get("currentContentType") === "audio");
	}.property('currentContentType'),

});


MDEmber.MessageController = MDEmber.MDPageController.extend({
	currentMessageType : "sample",
	messageList : null,
	// content:Ember.Object.create(),
	isShowSample : function() {
		return (this.get("currentMessageType") === "sample");
	}.property('currentMessageType'),
	isShowMix : function() {
		return (this.get("currentContentType") === "mix");
	}.property('currentContentType'),
	isShowPicture : function() {
		return (this.get("currentContentType") === "picture");
	}.property('currentContentType'),
	isShowVideo : function() {
		return (this.get("currentContentType") === "video");
	}.property('currentContentType'),
	isShowAudio : function() {
		return (this.get("currentContentType") === "audio");
	}.property('currentContentType'),

});

/**
 * View
 */

MDEmber.SinginView = Ember.View.extend({
	classNames : [ 'jumbotron' ],
	templateName : 'signin',
	controller : MDEmber.userController,
});

MDEmber.NavView = Ember.View.extend({
	classNames : [ 'container', 'clearfix' ],
	templateName : 'sign_nav',
	// controller : MDEmber.NavController,
	controller : MDEmber.userController,
	click : function(event){
		alert(event.target.name);
		return false;
	}
});

MDEmber.SelectMenu = Ember.View
		.extend({
			classNames : [ 'select-menu-modal-holder', 'js-menu-content' ],
			templateName : 'selectMenu-template',
			// changed: 0,
			controller : Ember
					.computed(
							function(key) {
								if (MDEmber.selectMenuController === undefined) {
									var parentView = Ember.get(this,
											'_parentView');
									// MDEmber.selectMenuController=MDEmber.selectMenuController
									// = MDEmber.SelectMenuController.create();
									MDEmber.selectMenuController = Ember.get(
											parentView, 'controller');
									/*$
											.get(
													MDEmber.subDir+"/weixinopen/openaccount/openaccountList.json",
													{},
													function(data) {
														MDEmber.selectMenuController
																.set(
																		"openaccount_list",
																		data["openaccount_list"]);

													})*/
									MDEmber.jsonAsync("/weixinopen/openaccount/openaccountList.json",
											"post",
											{
												customer_id:MDEmber.getCustomerId()
											},
											function(data) {
												MDEmber.selectMenuController.set("openaccount_list",data["openaccount_list"]);
												MDEmber.selectMenuController.set("currentCustomerName",data["openaccount_list"][parseInt(MDEmber.getCustomerId())-1]["WeixinOpenAccount_customer_name"]);
											},
											function() {
												// view("异常！");
												alert("获取json数据错误！");
											});
								}
								return MDEmber.selectMenuController;
							}).property('_parentView'),
			click:function(event){
				//alert(1234560);
				var elementId = this.get("elementId");
				 if($("#" + elementId + " .select-menu-list")
							.find(event.target).length > 0){
					 if(event.target.attributes["customer_id"].value){
						//MDEmber.customerId = event.target.attributes["customer_id"].value;
						 MDEmber.setCustomerId(event.target.attributes["customer_id"].value);
						 MDEmber.selectMenuController.set("currentCustomerName",event.target.innerText);
						MDEmber.customerTimeStamp=(new Date()).valueOf();
						var currentRoute = MDEmber.Router.router.currentHandlerInfos[1].handler;
						if (currentRoute.freshController instanceof Function) {
							currentRoute.freshController();
						};						
						currentRoute.renderTemplate();
					 };
					 
				 }
				 if (event) {
				        event.stopPropagation();
				 } else{
				        window.event.cancelBubble = true;
				       
				      };
			},

		});

MDEmber.InputView = Ember.View.extend({
	tagName : 'input',
	classNames : [ 'textfield' ],
	attributeBindings : [ 'autofocus', 'placeholder', 'type', 'name',
			'data-autocheck-url' ],
	autofocus : 'autofocus',
	type : 'text'

});

MDEmber.FormView = Ember.View
		.extend({
			tagName : "form",
			classNames : [ 'home-signup' ],
			attributeBindings : [ 'autocomplete', 'method', 'action',
					'accept-charset' ],
			submit : function(event) {
				// will be invoked whenever the user triggers
				// the browser's `submit` method
				var username = $("[name='username']").val();

				if (username !== undefined) {
					// data+="username: \""+username+", \"";

				}
				;
				var password = $("[name='password']").val();
				if (password !== undefined) {
					// data+="password: \""+password+", \"";

				}
				;
				var valitecode = $("[name='valitecode']").val();
				if (valitecode !== undefined) {
					// data+="valitecode: \""+valitecode+"\" }";

				}
				;

				// alert(data);

				// this.renderView(username,password,valitecode);

					MDEmber.jsonAsync("/user/login.json",
						"post",
						{
							username : username,
							password : password,
							valitecode : valitecode,
							view_outs : [ "tool", "selectMenu" ],
						},
						function(data) {
							if (MDEmber.viewSet[data["view_name"]] === undefined) {
								var temple = data["ember_temple"];
						        MDEmber.handleTemple(temple);
								var currentRoute = MDEmber.Router.router.currentHandlerInfos[1].handler;
								var view = currentRoute.router._activeViews['index'];
								view[0].remove();

								currentRoute.childView = [ {
									name : 'tool',
									into : 'index',
									outlet : 'container-1'
								} ];

								currentRoute.renderTemplate();
								MDEmber.hasLogin=true;
								//MDEmber.navView.render();
								MDEmber.userController.set("hasLogin",true);
								//MDEmber.customerId=data["user_info"]["WeixinUserInfo"]["default_customer"];
								MDEmber.setCustomerId(data["user_info"]["WeixinUserInfo"]["default_customer"]);
								MDEmber.customerTimeStamp=(new Date()).valueOf();

							} else {
								MDEmber.viewSet[data["view_name"]]
										.appendTo("#container-1");

							}
					},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});

				return false;
			},

		});


MDEmber.FriendSynFormView = Ember.View
.extend({
	tagName : "form",
	classNames : [ 'home-signup' ],
	attributeBindings : [ 'autocomplete', 'method', 'action',
			'accept-charset' ],
	submit : function(event) {
		
		
			MDEmber.jsonAsync("/weixinopen/Friend/friendSynchronize.json",
				"post",
				{
				    synchronize : 1,
				    customer_id : MDEmber.getCustomerId(),
					
				},
				function(data) {
					alert("同步完成!");
				},
				function() {
					// view("异常！");
					alert("获取json数据错误！");
				});

		return false;
	},

});

MDEmber.ToolController = Ember.ArrayController.extend({
	currentCustomerName : "",
	
}); 

MDEmber.ToolView = Ember.View
		.extend({
			templateName : "tool-template",
			click : function(event) {
				// alert(event.target);
				var elementId = this.get("elementId");
				if ($("#" + elementId + " .account-switcher")
						.find(event.target).length > 0) {
					if (!$("#" + elementId + " .account-switcher").hasClass(
							"active")
							|| !$(
									"#"
											+ elementId
											+ " .account-switcher .select-menu-button")
									.hasClass("selected")) {
						$("#" + elementId + " .account-switcher").addClass(
								"active");
						$(
								"#"
										+ elementId
										+ " .account-switcher .select-menu-button")
								.addClass("selected");

						$("body").bind("click", _hideSelectMenu);

						return false;
					} else {
						_hideSelectMenu();
					}

				}

				function _hideSelectMenu(event) {
					$("#" + elementId + " .account-switcher").removeClass(
							"active");
					$(
							"#" + elementId
									+ " .account-switcher .select-menu-button")
							.removeClass("selected");
					$("body").unbind("click", _hideSelectMenu);
				}

			},

		});

MDEmber.PageToolView = Ember.View
.extend({
	templateName : "pagetool-template",
	click : function(event) {
		
	},

});

MDEmber.IndexView = Ember.View.extend({
	templateName : "logon_home",

	navView : MDEmber.NavView,
});

MDEmber.FriendView = Ember.View.extend({
	templateName : "friend",

});

/*MDEmber.FriendlistView = Ember.View.extend({
	templateName : "friendlist-template",
});*/

MDEmber.FriendlistView = Ember.View.
extend({
	templateName : "friendlist-template",
	
});

MDEmber.FriendnavView = Ember.View
.extend({
	templateName : "friendnav-template",
	click : function(event) {
		// alert(event.target.name);
		if (event.target.name === "current") {
            
			MDEmber.friendController
			.set("currentOperationType", "current");


			if (Ember.TEMPLATES['friendcurrentlist-template'] === undefined) {
				
				MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
						"post",
						{
							"view_outs" : [ "friendcurrentlist"],
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.friendController.set("model",
										data["friend_info_list"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			
			} else {
				
				MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
						"post",
						{
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							/*if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);
*/
								MDEmber.friendController.set("model",
										data["friend_info_list"]);
							/*}*/},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			}
			;
		}
		;

		if (event.target.name === "mix") {

			MDEmber.contentController.set("currentContentType", "mix");

			if (Ember.TEMPLATES['contentmixlist-template'] === undefined) {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"view_outs" : [ "content"
										+ MDEmber.contentController
												.get("currentContentType")
										+ "list" ],
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.contentController.set(
											"contentList",
											data["contentList"]);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.contentController.set(
										"contentList",
										data["contentList"]);
								/* } */

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		if (event.target.name === "picture") {

			MDEmber.contentController.set("currentContentType",
					"picture");

			if (Ember.TEMPLATES['contentpicturelist-template'] === undefined) {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"view_outs" : [ "content"
										+ MDEmber.contentController
												.get("currentContentType")
										+ "list" ],
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.contentController.set(
											"contentList",
											data["contentList"]);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.contentController.set(
										"contentList",
										data["contentList"]);
								/* } */

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		if (event.target.name === "synchronize") {

			MDEmber.friendController
					.set("currentOperationType", "synchronize");

			if (Ember.TEMPLATES['friendsynchronizelist-template'] === undefined) {
				$.ajax({
							url : MDEmber.subDir+"/weixinopen/Friend/friendSynchronize.json",// 跳转到
							// action
							async : false,
							data :{},
							data : {
								"view_outs" : [ "friend"
										+ MDEmber.friendController
												.get("currentOperationType")
										+ "list" ],
								"operationType" : MDEmber.friendController
										.get("currentOperationType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.friendController.set("model",null);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$.ajax({
							url : MDEmber.subDir+"/weixinopen/Friend/friendSynchronize.json",// 跳转到
							// action
							async : false,
							data : {
								"operationType" : MDEmber.friendController
								.get("currentOperationType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.friendController.set("model",null);

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		if (event.target.name === "audio") {

			MDEmber.contentController
					.set("currentContentType", "audio");

			if (Ember.TEMPLATES['contentaudiolist-template'] === undefined) {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"view_outs" : [ "content"
										+ MDEmber.contentController
												.get("currentContentType")
										+ "list" ],
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.contentController.set(
											"contentList",
											data["contentList"]);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.contentController.set(
										"contentList",
										data["contentList"]);
								/* } */G

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		return false;
	}

});

MDEmber.FriendCurrentListView = Ember.View
.extend({
	templateName : "friendcurrentlist-template",
	click : function(event) {
		if (event.target.className.indexOf("send_message") >= 0) {

			var target_content = $(event.target).parent();
			var fake_id = target_content.attr("fake_id");
			if (!fake_id) {
				return null;
			}
			
			MDEmber.friendController.set("fake_id",fake_id);

			if (Ember.TEMPLATES['sendmessage-template'] === undefined) {
				$.ajax({
					url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
					// action
					async : false,
					data : {
						view_outs : [ "sendmessage" ]
					},
					type : 'post',
					cache : false,
					dataType : 'json',
					success : function(data) {
						// alert(typeof data["return_code"]);
						if (data["ember_temple"]) {
							var temple = data["ember_temple"];
							// alert(temple);

							// temple=jQuery(temple);
							MDEmber.handleTemple(temple);
						}

					},
					error : function() {
						// view("异常！");
						alert("获取json数据错误！");
					}
				});
			};


			MDEmber.sendMessageByFriendView = MDEmber.SendMessageByFriendView
					.create({
						templateName : 'sendmessage-template',
						classNames : [ 'auth-form' ],
						submit : function(event) {
							
							/*var model = MDEmber.friendController
									.get("model");*/
							
							$.post(
											MDEmber.subDir+"/weixinopen/Message/sendTextMessageByUI.json",
											{
												fake_id : MDEmber.friendController.get("fake_id"),
												msg_type : "text",
												content : this.xhEditor
														.getSource(),
											}, function(data) {

											});

							return false;

						},
					});			

		} else {
			if (event.target.className.indexOf("create_content_text") >= 0) {
				// alert(12345);
				if (Ember.TEMPLATES['contenttext-template'] === undefined) {
					$.ajax({
						url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
						// action
						async : false,
						data : {
							view_outs : [ "contenttext" ]
						},
						type : 'post',
						cache : false,
						dataType : 'json',
						success : function(data) {
							// alert(typeof data["return_code"]);
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);
							}

						},
						error : function() {
							// view("异常！");
							alert("获取json数据错误！");
						}
					});
				}
				;
				MDEmber.contentTextView = MDEmber.ContentTextView
						.create({
							templateName : 'contenttext-template',
							classNames : [ 'auth-form' ],
							xhEditor : null,
							submit : function(event) {
								var model = MDEmber.contentController
										.get("model");

								$
										.post(
												MDEmber.subDir+"/weixinopen/content/contentTextSave.json",
												{

													content_name : model
															.get("WeixinRuleContentText_content_name"),
													text_message : this.xhEditor
															.getSource(),

												}, function(data) {

													alert(data);

												});
								return false;
							},
						});

				if (!MDEmber.contentController) {
					MDEmber.contentController = MDEmber.ContentController
							.create({
								/*
								 * ruleAdapterList : MDEmber
								 * .getRuleAdapterList(),
								 * contentTypeList : MDEmber
								 * .getContentTypes(),
								 * currentRuleAdapter : "01",
								 * currentContentType : "01",
								 */
								content : Ember.Object.create(),
							/*
							 * contentList : MDEmber
							 * .getContentList("01"),
							 */
							});

				} else {
					MDEmber.contentController.set("model", Ember.Object
							.create());
				}

			}
		}

		MDEmber.sendMessageByFriendView.set("controller",
				MDEmber.friendController);

		/*MDEmber.sendMessageByFriendView.appendTo("#float_layer");

		$("#back_ground").addClass("alpha60 bg");
		//$("#floater").addClass("floater");
		//$("#float_layer").addClass("content");
*/
		$("#float_layer .header_title").html("<h1>发送消息</h1>");
		/*MDEmber.regularContentView
		.appendTo("#float_layer");*/
		MDEmber.floatLayer.appendInto(MDEmber.sendMessageByFriendView);
		MDEmber.floatLayer.show();
	},

});

MDEmber.FriendSynchronizeListView = Ember.View
.extend({
	templateName : "friendsynchronizelist-template",
});

MDEmber.SendMessageByFriendView = Ember.View.extend({
	// templateName : "contenttextlist-template",
	tagName : 'form',
	xhEditor : null,
	didInsertElement : function() {
		if (!this.xhEditor) {
			this.xhEditor = $("#elm1").xheditor({
				tools : 'mini'
			});
		}
	},
});


MDEmber.RegularView = Ember.View.extend({
	templateName : "regular",

});

MDEmber.ContentView = Ember.View.extend({
	templateName : "content",

});

MDEmber.MessageView = Ember.View.extend({
	templateName : "message",

});

MDEmber.RegularContentView = Ember.View.extend({
	tagName : 'form',

});

MDEmber.RegularlistView = Ember.View
		.extend({
			templateName : "regularlist-template",
			click : function(event) {
				// alert($(event.target).parent());
				if (event.target.className.indexOf("edit_rule") >= 0) {
					// alert(12345);
					if (Ember.TEMPLATES['regulartext-template'] === undefined) {
						
						MDEmber.jsonSync("/template/loadTemplate.json",
								"post",
								{
									view_outs : [ "regulartext" ]
								},
								function(data) {
									if (data["ember_temple"]) {
										var temple = data["ember_temple"];
										// alert(temple);

										// temple=jQuery(temple);
										MDEmber.handleTemple(temple);
									}},
								function() {
									// view("异常！");
									alert("获取json数据错误！");
								});
					}
					;

					var target_rule = $(event.target).parent();
					var rule_id = target_rule.attr("rule_id");
					var rule_adapter_element=target_rule.children(
					"div[name='rule_adapter_name']");
					var rule_adapter_name = rule_adapter_element.text();
					var rule_adapter_id = rule_adapter_element.attr("rule_adapter_id");
					
					if (MDEmber.regularContentView === undefined) {

					MDEmber.regularContentView = MDEmber.RegularContentView
							.create({
								templateName : 'regulartext-template',
								classNames : [ 'auth-form' ],
								change : function(event) {

									if (MDEmber.regularContentController) {
										if (event.target.name === "rule_adapter_id") {
																						
											MDEmber.regularContentController
													.set(
															"currentRuleAdapter",
															event.target.value);
										}
										if (event.target.name === "content_type_code") {
											MDEmber.regularContentController
											.set(
													"contentList",
													MDEmber
													.getContentList(event.target.value));
											
											MDEmber.regularContentController
													.set(
															"currentContentType",
															event.target.value);
										}
										// isKeyAdapter
										// MDEmber.regularContentView.rerender();
									}

									return false;

								},

								submit : function(event) {
									var rule_id = $("input[name='rule_id']")
											.val();
									var rule_key = $("input[name='rule_key']")
											.val();
									var content_type_code = $(
											"select[name='content_type_code']")
											.val();
									var content_id = $(
											"select[name='content_id']").val();

									
									MDEmber.jsonAsync("/weixinopen/regular/saveRuleKey.json",
											"post",
											{
												rule_id : rule_id,
												rule_key : rule_key,
												content_type_code : content_type_code,
												content_id : content_id,
											},
											function(data) {},
											function() {
												// view("异常！");
												alert("获取json数据错误！");
											});

									return false;

								},

							});
					};

										
					MDEmber.jsonAsync("/weixinopen/regular/fetchRuleContent.json",
							"post",
							{
								rule_id : Number(rule_id),
								rule_adapter_name : rule_adapter_name
							},
							function(data) {
								if (data["rule_content"]) {
									if (MDEmber.regularContentController === undefined) {
										MDEmber.regularContentController = MDEmber.regularContentView
												.get("controller");
									}

									if (!MDEmber.regularContentController) {
										MDEmber.regularContentController = MDEmber.RegularContentController
												.create({
													//model : data["rule_content"],
													currentRuleAdapter :rule_adapter_id,
													contentTypeList : MDEmber
															.getContentTypes(),
													content : Ember.Object
															.create(),
													contentList : MDEmber
															.getContentList(data["rule_content"][0]["WeixinRuleDetail_content_type_code"]),
															currentContentType : data["rule_content"][0]["WeixinRuleDetail_content_type_code"]
												});
										
										//MDEmber.regularContentController.set("currentContentType",data["rule_content"][0]["WeixinRuleKey_content_type_code"]);
										
										MDEmber.regularContentView
												.set(
														"controller",
														MDEmber.regularContentController);

									}else{
										MDEmber.regularContentController.set("currentRuleAdapter",rule_adapter_id);
										MDEmber.regularContentController.set("contentList",MDEmber
										.getContentList(data["rule_content"][0]["WeixinRuleDetail_content_type_code"]));
										MDEmber.regularContentController.set("currentContentType",data["rule_content"][0]["WeixinRuleDetail_content_type_code"]);
										
									}

									MDEmber.regularContentController.set(
											"model",
											data["rule_content"][0]);
									// MDEmber.regularContentController.set("model2","asfasdfads");

									
									$("#float_layer .header_title").html("<h1>配置规则-关键字匹配</h1>");
									/*MDEmber.regularContentView
									.appendTo("#float_layer");*/
									MDEmber.floatLayer.appendInto(MDEmber.regularContentView);
									MDEmber.floatLayer.show();
								}

							},
							function() {
								// view("异常！");
								alert("获取json数据错误！");
							});


					// MDEmber.regularContentView=MDEmber.RegularContentView.create({templateName
					// : "regulartext-template"});

				} else {
					if (event.target.className.indexOf("create_rule") >= 0) {
						if (Ember.TEMPLATES['regularrule-template'] === undefined) {
							/*$.ajax({
								url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
								// action
								async : false,
								data : {
									view_outs : [ "regularrule" ]
								},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									if (data["ember_temple"]) {
										var temple = data["ember_temple"];
										// alert(temple);

										// temple=jQuery(temple);
										MDEmber.handleTemple(temple);
									}

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});*/
							MDEmber.jsonSync("/template/loadTemplate.json",
									"post",
									{
										view_outs : [ "regularrule" ]
									},
									function(data) {
										if (data["ember_temple"]) {
											var temple = data["ember_temple"];
											// alert(temple);

											// temple=jQuery(temple);
											MDEmber.handleTemple(temple);
										}

									},
									function() {
										// view("异常！");
										alert("获取json数据错误！");
									});
						}
						;
						MDEmber.regularContentView = MDEmber.RegularContentView
								.create({
									templateName : 'regularrule-template',
									classNames : [ 'auth-form' ],
									change : function(event) {

										if (MDEmber.regularContentController) {
											if (event.target.name === "rule_adapter_id") {
																							
												MDEmber.regularContentController
														.set(
																"currentRuleAdapter",
																event.target.value);
											}
											if (event.target.name === "content_type_code") {
												MDEmber.regularContentController
												.set(
														"contentList",
														MDEmber
														.getContentList(event.target.value));
												
												MDEmber.regularContentController
														.set(
																"currentContentType",
																event.target.value);
											}
											// isKeyAdapter
											// MDEmber.regularContentView.rerender();
										}

										return false;

									},
									submit : function(event) {
										var content = MDEmber.regularContentController
												.get("content");

									/*	$
												.post(
														MDEmber.subDir+"/weixinopen/regular/saveRule.json",
														{
															rule_name : content
																	.get("rule_name"),
															rule_adapter_id : content
																	.get("rule_adapter_id"),
															rule_key : content
																	.get("rule_key"),
															content_type_code : content
																	.get("content_type_code"),
															content_id : content
																	.get("content_id"),
														}, function(data) {

															// alert(data);

														});*/
										/*$.ajax({url : MDEmber.subDir+"/weixinopen/regular/saveRule.json",
											async : true,
											data : {
																	rule_name : content
																				.get("rule_name"),
																	customer_id:MDEmber.customerId,
																	rule_type:content.get("rule_adapter_id"),
																				rule_group_id:1,
																		rule_adapter_id : content
																				.get("rule_adapter_id"),
																		rule_key : content
																				.get("rule_key"),
																		content_type_code : content
																				.get("content_type_code"),
																		content_id : content
																				.get("content_id"),
																},
											type : 'post',
											cache : false,
											dataType : 'json',
											timeout : 10000,
											success : function(data) {
			                                                                },
											error : function() {
												// view("异常！");
												alert("获取json数据错误！");
											}
										});*/
										
										MDEmber.jsonAsync("/weixinopen/regular/saveRule.json",
												"post",
												{
													rule_name : content
																.get("rule_name"),
													customer_id:MDEmber.getCustomerId(),
													rule_type:content.get("rule_adapter_id"),
																rule_group_id:1,
														rule_adapter_id : content
																.get("rule_adapter_id"),
														rule_key : content
																.get("rule_key"),
														content_type_code : content
																.get("content_type_code"),
														content_id : content
																.get("content_id"),
												},
												function(data) {},
												function() {
													// view("异常！");
													alert("获取json数据错误！");
												});
										return false;
									},
								});

						if (!MDEmber.regularContentController) {
							MDEmber.regularContentController = MDEmber.RegularContentController
									.create({
										ruleAdapterList : MDEmber
												.getRuleAdapterList(),
										contentTypeList : MDEmber
												.getContentTypes(),
										currentRuleAdapter : "01",
										currentContentType : "01",
										content : Ember.Object.create(),
										contentList : MDEmber
												.getContentList("01"),
									});

						}

						MDEmber.regularContentView.set("controller",
								MDEmber.regularContentController);

						//MDEmber.regularContentView.appendTo("#float_layer");
						$("#float_layer .header_title").html("<h1>新建规则</h1>");
						/*MDEmber.regularContentView
						.appendTo("#float_layer");*/
						MDEmber.floatLayer.appendInto(MDEmber.regularContentView);
						MDEmber.floatLayer.show();

					}
				}
				;

				//$("#back_ground").addClass("alpha60 bg");
				//$("#floater").addClass("floater");
				//$("#float_layer").addClass("content");

			}
		});

MDEmber.RuleAdapterListView = Ember.Select.extend({

});

MDEmber.KeyAdapterView = Ember.TextArea.extend({

})

MDEmber.EasyComboBox = Ember.View.extend({
	//template:Ember.Handlebars.compile("laofldh"),
	
	
	template:Ember.Handlebars.compile("<input class=\"easyui-combobox\" data-options=\"data:[{name:'test',vaule:'value'}],valueField:'value',textField:'name'\"></input>"),
	tagName:"div",
	
});

MDEmber.ContentnavView = Ember.View
		.extend({
			templateName : "contentnav-template",
			click : function(event) {
				// alert(event.target.name);
				if (event.target.name === "text") {

					MDEmber.contentController.set("currentContentType", "text");

					if (Ember.TEMPLATES['contenttextlist-template'] === undefined) {
						
						MDEmber.jsonSync("/template/loadTemplate.json",
								"get",
								{
									view_outs : [ "contenttextlist" ]
								},
								function(data) {
									if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);
								}
									},
								function() {
									// view("异常！");
									alert("获取json数据错误！");
								});
					} else {
						
						MDEmber.jsonSync("/weixinopen/content/contentMain.json",
								"post",
								{
									"view_outs" : [
											"content"
													+ MDEmber.contentController
															.get("currentContentType")
													+ "list", "contentnav",
											"contentlist" ],
									"contentType" : MDEmber.contentController
											.get("currentContentType")
								},
								function(data) {
									if (data["ember_temple"]) {
										var temple = data["ember_temple"];
										// alert(temple);

										// temple=jQuery(temple);
										MDEmber.handleTemple(temple);

										MDEmber.contentController.set(
												"contentList",
												data["contentList"]);
																	}
									},
								function() {
									// view("异常！");
									alert("获取json数据错误！");
								});	
					}
					;
				}
				;

				if (event.target.name === "mix") {

					MDEmber.contentController.set("currentContentType", "mix");

					if (Ember.TEMPLATES['contentmixlist-template'] === undefined) {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"view_outs" : [ "content"
												+ MDEmber.contentController
														.get("currentContentType")
												+ "list" ],
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										if (data["ember_temple"]) {
											var temple = data["ember_temple"];

											MDEmber.handleTemple(temple);

											MDEmber.contentController.set(
													"contentList",
													data["contentList"]);
										}

									},
									error : function() {
										alert("获取json数据错误！");
									}
								});
					} else {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										// alert(typeof data["return_code"]);
										/*
										 * if (data["ember_temple"]) { var
										 * temple = data["ember_temple"]; //
										 * alert(temple);
										 *  // temple=jQuery(temple);
										 * MDEmber.handleTemple(temple);
										 */

										MDEmber.contentController.set(
												"contentList",
												data["contentList"]);
										/* } */

									},
									error : function() {
										// view("异常！");
										alert("获取json数据错误！");
									}
								});

					}
					;
				}
				;

				if (event.target.name === "picture") {

					MDEmber.contentController.set("currentContentType",
							"picture");

					if (Ember.TEMPLATES['contentpicturelist-template'] === undefined) {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"view_outs" : [ "content"
												+ MDEmber.contentController
														.get("currentContentType")
												+ "list" ],
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										if (data["ember_temple"]) {
											var temple = data["ember_temple"];

											MDEmber.handleTemple(temple);

											MDEmber.contentController.set(
													"contentList",
													data["contentList"]);
										}

									},
									error : function() {
										alert("获取json数据错误！");
									}
								});
					} else {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										// alert(typeof data["return_code"]);
										/*
										 * if (data["ember_temple"]) { var
										 * temple = data["ember_temple"]; //
										 * alert(temple);
										 *  // temple=jQuery(temple);
										 * MDEmber.handleTemple(temple);
										 */

										MDEmber.contentController.set(
												"contentList",
												data["contentList"]);
										/* } */

									},
									error : function() {
										// view("异常！");
										alert("获取json数据错误！");
									}
								});

					}
					;
				}
				;

				if (event.target.name === "video") {

					MDEmber.contentController
							.set("currentContentType", "video");

					if (Ember.TEMPLATES['contentvideolist-template'] === undefined) {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"view_outs" : [ "content"
												+ MDEmber.contentController
														.get("currentContentType")
												+ "list" ],
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										if (data["ember_temple"]) {
											var temple = data["ember_temple"];

											MDEmber.handleTemple(temple);

											MDEmber.contentController.set(
													"contentList",
													data["contentList"]);
										}

									},
									error : function() {
										alert("获取json数据错误！");
									}
								});
					} else {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										// alert(typeof data["return_code"]);
										/*
										 * if (data["ember_temple"]) { var
										 * temple = data["ember_temple"]; //
										 * alert(temple);
										 *  // temple=jQuery(temple);
										 * MDEmber.handleTemple(temple);
										 */

										MDEmber.contentController.set(
												"contentList",
												data["contentList"]);
										/* } */

									},
									error : function() {
										// view("异常！");
										alert("获取json数据错误！");
									}
								});

					}
					;
				}
				;

				if (event.target.name === "audio") {

					MDEmber.contentController
							.set("currentContentType", "audio");

					if (Ember.TEMPLATES['contentaudiolist-template'] === undefined) {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"view_outs" : [ "content"
												+ MDEmber.contentController
														.get("currentContentType")
												+ "list" ],
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										if (data["ember_temple"]) {
											var temple = data["ember_temple"];

											MDEmber.handleTemple(temple);

											MDEmber.contentController.set(
													"contentList",
													data["contentList"]);
										}

									},
									error : function() {
										alert("获取json数据错误！");
									}
								});
					} else {
						$
								.ajax({
									url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
									// action
									async : false,
									data : {
										"contentType" : MDEmber.contentController
												.get("currentContentType")
									},
									type : 'post',
									cache : false,
									dataType : 'json',
									success : function(data) {
										// alert(typeof data["return_code"]);
										/*
										 * if (data["ember_temple"]) { var
										 * temple = data["ember_temple"]; //
										 * alert(temple);
										 *  // temple=jQuery(temple);
										 * MDEmber.handleTemple(temple);
										 */

										MDEmber.contentController.set(
												"contentList",
												data["contentList"]);
										/* } */G

									},
									error : function() {
										// view("异常！");
										alert("获取json数据错误！");
									}
								});

					}
					;
				}
				;

				return false;
			}

		});

MDEmber.ContentlistView = Ember.View.extend({
	templateName : "contentlist-template",
});

MDEmber.ContentTextListView = Ember.View
		.extend({
			templateName : "contenttextlist-template",
			click : function(event) {
				if (event.target.className.indexOf("edit_content_text") >= 0) {

					var target_content = $(event.target).parent();
					var content_id = target_content.attr("content_id");
					if (!content_id) {
						return null;
					}

					if (Ember.TEMPLATES['contenttext-template'] === undefined) {
						$.ajax({
							url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
							// action
							async : false,
							data : {
								view_outs : [ "contenttext" ]
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];
									// alert(temple);

									// temple=jQuery(temple);
									MDEmber.handleTemple(temple);
								}

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});
					}
					;

					// var
					// rule_adapter_name=target_rule.children("div[name='rule_adapter_name']").text();

					MDEmber.contentTextView = MDEmber.ContentTextView
							.create({
								templateName : 'contenttext-template',
								classNames : [ 'auth-form' ],
								submit : function(event) {
									// alert(this.xhEditor.getSource());

									// var xhContent=this.xhEditor.getSource();
									/*
									 * var
									 * rule_id=$("input[name='rule_id']").val();
									 * var
									 * rule_key=$("input[name='rule_key']").val();
									 * var
									 * 
									 * var
									 * content_id=$("select[name='content_id']").val();
									 */
									var model = MDEmber.contentController
											.get("model");
									// var
									// content_name=$("input[name='content_name']").val();
									$
											.post(
													MDEmber.subDir+"/weixinopen/content/contentTextSave.json",
													{
														content_id : model.WeixinRuleContentText_content_id,
														content_name : model.WeixinRuleContentText_content_name,
														text_message : this.xhEditor
																.getSource(),
													}, function(data) {

													});

									return false;

								},
							});

					$
							.ajax({
								url : MDEmber.subDir+"/weixinopen/content/fetchContentText.json",// 跳转到
								// action
								async : true,
								data : {
									content_id : Number(content_id)
								},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									// if(data["rule_content"]){
									if (MDEmber.contentController === undefined) {
										MDEmber.contentController = MDEmber.contentTextView
												.get("controller");
									}

									if (!MDEmber.contentController) {
										MDEmber.contentController = MDEmber.ContentController
												.create({
													model : data["contentText"][0],

												});

									} else {
										MDEmber.contentController.set("model",
												data["contentText"][0]);
									}
									/*
									 * MDEmber.contentTextView.set("controller",MDEmber.contentController);
									 * 
									 * MDEmber.regularContentController.set("model",data["rule_content"][0]);
									 * //MDEmber.regularContentController.set("model2","asfasdfads");
									 * 
									 * MDEmber.contentTextView
									 * .appendTo("#float_layer"); // }
									 */
								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});

					// MDEmber.regularContentView=MDEmber.RegularContentView.create({templateName
					// : "regulartext-template"});

				} else {
					if (event.target.className.indexOf("create_content_text") >= 0) {
						// alert(12345);
						if (Ember.TEMPLATES['contenttext-template'] === undefined) {
							$.ajax({
								url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
								// action
								async : false,
								data : {
									view_outs : [ "contenttext" ]
								},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									if (data["ember_temple"]) {
										var temple = data["ember_temple"];
										// alert(temple);

										// temple=jQuery(temple);
										MDEmber.handleTemple(temple);
									}

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});
						}
						;
						MDEmber.contentTextView = MDEmber.ContentTextView
								.create({
									templateName : 'contenttext-template',
									classNames : [ 'auth-form' ],
									xhEditor : null,
									submit : function(event) {
										var model = MDEmber.contentController
												.get("model");

										$
												.post(
														MDEmber.subDir+"/weixinopen/content/contentTextSave.json",
														{

															content_name : model
																	.get("WeixinRuleContentText_content_name"),
															text_message : this.xhEditor
																	.getSource(),

														}, function(data) {

															alert(data);

														});
										return false;
									},
								});

						if (!MDEmber.contentController) {
							MDEmber.contentController = MDEmber.ContentController
									.create({
										/*
										 * ruleAdapterList : MDEmber
										 * .getRuleAdapterList(),
										 * contentTypeList : MDEmber
										 * .getContentTypes(),
										 * currentRuleAdapter : "01",
										 * currentContentType : "01",
										 */
										content : Ember.Object.create(),
									/*
									 * contentList : MDEmber
									 * .getContentList("01"),
									 */
									});

						} else {
							MDEmber.contentController.set("model", Ember.Object
									.create());
						}

					}
				}

				MDEmber.contentTextView.set("controller",
						MDEmber.contentController);

				$("#float_layer .header_title").html("<h1>文本消息</h1>");
				/*MDEmber.regularContentView
				.appendTo("#float_layer");*/
				MDEmber.floatLayer.appendInto(MDEmber.contentTextView);
				MDEmber.floatLayer.show();

			},

		});

MDEmber.ContentTextView = Ember.View.extend({
	// templateName : "contenttextlist-template",
	tagName : 'form',
	xhEditor : null,
	didInsertElement : function() {
		if (!this.xhEditor) {
			this.xhEditor = $("#elm1").xheditor({
				tools : 'mini'
			});
		}
	},
});

MDEmber.ContentMixListView = Ember.View
		.extend({
			templateName : "contentmixlist-template",
			click : function(event) {
				if (event.target.className.indexOf("edit_content_mix") >= 0) {
					// alert(12345);

					var target_content = $(event.target).parent();
					var content_id = target_content.attr("content_id");
					if (!content_id) {
						return null;
					}

					if (Ember.TEMPLATES['contentmixlist-template'] === undefined) {
						$.ajax({
							url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
							// action
							async : false,
							data : {
								view_outs : [ "contentmix" ]
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];
									// alert(temple);

									// temple=jQuery(temple);
									MDEmber.handleTemple(temple);
								}

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});
					}
					;

					// var
					// rule_adapter_name=target_rule.children("div[name='rule_adapter_name']").text();

					MDEmber.contentMixtView = MDEmber.ContentMixView
							.create({
								templateName : 'contenttext-template',
								classNames : [ 'auth-form' ],
								submit : function(event) {

									var model = MDEmber.contentController
											.get("model");
									// var
									// content_name=$("input[name='content_name']").val();
									$
											.post(
													MDEmber.subDir+"/weixinopen/content/contentMixSave.json",
													{
														content_id : model.WeixinRuleContentMix_content_id,
														content_name : model.WeixinRuleContentMix_content_name,
														text_message : this.xhEditor
																.getSource(),
													}, function(data) {

													});

									return false;

								},

							});

					$
							.ajax({
								url : MDEmber.subDir+"/weixinopen/content/fetchContentMix.json",// 跳转到
								// action
								async : true,
								data : {/* rule_id:Number(rule_id),rule_adapter_name:rule_adapter_name */},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									// if(data["rule_content"]){
									if (MDEmber.contentController === undefined) {
										MDEmber.contentController = MDEmber.contentMixView
												.get("controller");
									}

									if (!MDEmber.contentController) {
										MDEmber.contentController = MDEmber.ContentController
												.create({
													model : data["contentText"][0],

												});

									} else {
										MDEmber.contentController.set("model",
												data["contentText"][0]);
									}

									MDEmber.contentMixView
											.appendTo("#float_layer");
									// }

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});
				} else {
					if (event.target.className.indexOf("create_content_mix") >= 0) {
						// alert(12345);
						if (Ember.TEMPLATES['contenttext-template'] === undefined) {
							$.ajax({
								url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
								// action
								async : false,
								data : {
									view_outs : [ "contentmix" ]
								},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									if (data["ember_temple"]) {
										var temple = data["ember_temple"];
										// alert(temple);

										// temple=jQuery(temple);
										MDEmber.handleTemple(temple);
									}

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});
						}
						;
						MDEmber.contentMixView = MDEmber.ContentMixView
								.create({
									templateName : 'contentmix-template',
									classNames : [ 'auth-form' ],
									xhEditor : null,
									submit : function(event) {
										var model = MDEmber.contentController
												.get("model");

										$.post(
														MDEmber.subDir+"/weixinopen/content/contentMixSave.json",
														{

															content_name : model
																	.get("WeixinRuleContentMix_content_name"),
															text_message : this.xhEditor
																	.getSource(),

														}, function(data) {

															alert(data);

														});
										return false;
									},
								});

						if (!MDEmber.contentController) {
							MDEmber.contentController = MDEmber.ContentController
									.create({
										/*
										 * ruleAdapterList : MDEmber
										 * .getRuleAdapterList(),
										 * contentTypeList : MDEmber
										 * .getContentTypes(),
										 * currentRuleAdapter : "01",
										 * currentContentType : "01",
										 */
										content : Ember.Object.create(),
									/*
									 * contentList : MDEmber
									 * .getContentList("01"),
									 */
									});

						} else {
							MDEmber.contentController.set("model", Ember.Object
									.create());
						}

					}
				}

				// MDEmber.regularContentView=MDEmber.RegularContentView.create({templateName
				// : "regulartext-template"});
				MDEmber.contentMixView.set("controller",
						MDEmber.contentController);

				$("#float_layer .header_title").html("<h1>图文消息</h1>");
				/*MDEmber.regularContentView
				.appendTo("#float_layer");*/
				MDEmber.floatLayer.appendInto(MDEmber.contentMixView);
				MDEmber.floatLayer.show();
				//$("#floater").addClass("floater");
				//$("#float_layer").addClass("content");

			}

		});

MDEmber.ContentMixView = Ember.View.extend({
	// templateName : "contenttextlist-template",
	tagName : 'form',
	xhEditor : null,
	didInsertElement : function() {
		if (!this.xhEditor) {
			this.xhEditor = $("#elm1").xheditor({
				tools : 'full'
			});
		}
	},
});

MDEmber.ContentVideoListView = Ember.View
		.extend({
			templateName : "contentvideolist-template",
			click : function(event) {
				if (event.target.className.indexOf("edit_content_text") >= 0) {
					// alert(12345);

					var target_content = $(event.target).parent();
					var content_id = target_content.attr("content_id");
					if (!content_id) {
						return null;
					}

					if (Ember.TEMPLATES['contentvideolist-template'] === undefined) {
						$.ajax({
							url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
							// action
							async : false,
							data : {
								view_outs : [ "contentvideo" ]
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];
									// alert(temple);

									// temple=jQuery(temple);
									MDEmber.handleTemple(temple);
								}

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});
					}
					;

					// var
					// rule_adapter_name=target_rule.children("div[name='rule_adapter_name']").text();

					MDEmber.contentVideoView = MDEmber.ContentVideoView
							.create({
								templateName : 'contentvideo-template',
								classNames : [ 'auth-form' ],
								submit : function(event) {
									/*
									 * var
									 * rule_id=$("input[name='rule_id']").val();
									 * var
									 * rule_key=$("input[name='rule_key']").val();
									 * var
									 * content_type_code=$("select[name='content_type_code']").val();
									 * var
									 * content_id=$("select[name='content_id']").val();
									 * 
									 * $.post(MDEmber.subDir+"/weixinopen/regular/saveRuleKey.json", {
									 * rule_id : rule_id, rule_key : rule_key,
									 * content_type_code : content_type_code,
									 * content_id : content_id, },
									 * function(data){ });
									 * 
									 * return false;
									 */

								},

							});

					$
							.ajax({
								url : MDEmber.subDir+"/weixinopen/regular/fetchRuleContent.json",// 跳转到
								// action
								async : true,
								data : {/* rule_id:Number(rule_id),rule_adapter_name:rule_adapter_name */},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									// if(data["rule_content"]){
									if (MDEmber.contentController === undefined) {
										MDEmber.contentController = MDEmber.contentVideoView
												.get("controller");
									}

									/*
									 * if(!MDEmber.contentController){
									 * MDEmber.contentController=MDEmber.ContentController.create({
									 * model:data["content_"],
									 * contentTypeList:MDEmber.getContentTypes(),
									 * content:Ember.Object.create(),
									 * contentList:MDEmber.getContentList(data["rule_content"][0]["WeixinRuleKey_content_type_code"])
									 * });
									 * MDEmber.regularContentView.set("controller",MDEmber.regularContentController); }
									 * 
									 * MDEmber.regularContentController.set("model",data["rule_content"][0]);
									 * //MDEmber.regularContentController.set("model2","asfasdfads");
									 */
									MDEmber.contentVideoView
											.appendTo("#float_layer");
									// }

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});

					// MDEmber.regularContentView=MDEmber.RegularContentView.create({templateName
					// : "regulartext-template"});

					$("#float_layer .header_title").html("<h1>视频消息</h1>");
					/*MDEmber.regularContentView
					.appendTo("#float_layer");*/
					MDEmber.floatLayer.appendInto(MDEmber.contentTextView);
					MDEmber.floatLayer.show();

				}
			}
		});

MDEmber.ContentVideoView = Ember.View.extend({
// templateName : "contenttextlist-template",
});

MDEmber.ContentAudioListView = Ember.View
		.extend({
			templateName : "contentaudiolist-template",
			click : function(event) {
				if (event.target.className.indexOf("edit_content_text") >= 0) {
					// alert(12345);

					var target_content = $(event.target).parent();
					var content_id = target_content.attr("content_id");
					if (!content_id) {
						return null;
					}

					if (Ember.TEMPLATES['contentaudiolist-template'] === undefined) {
						$.ajax({
							url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
							// action
							async : false,
							data : {
								view_outs : [ "contentaudio" ]
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];
									// alert(temple);

									// temple=jQuery(temple);
									MDEmber.handleTemple(temple);
								}

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});
					}
					;

					// var
					// rule_adapter_name=target_rule.children("div[name='rule_adapter_name']").text();

					MDEmber.contentVideoView = MDEmber.ContentVideoView
							.create({
								templateName : 'contentaudio-template',
								classNames : [ 'auth-form' ],
								submit : function(event) {
									/*
									 * var
									 * rule_id=$("input[name='rule_id']").val();
									 * var
									 * rule_key=$("input[name='rule_key']").val();
									 * var
									 * content_type_code=$("select[name='content_type_code']").val();
									 * var
									 * content_id=$("select[name='content_id']").val();
									 * 
									 * $.post(MDEmber.subDir+"/weixinopen/regular/saveRuleKey.json", {
									 * rule_id : rule_id, rule_key : rule_key,
									 * content_type_code : content_type_code,
									 * content_id : content_id, },
									 * function(data){ });
									 * 
									 * return false;
									 */

								},

							});

					$
							.ajax({
								url : MDEmber.subDir+"/weixinopen/regular/fetchRuleContent.json",// 跳转到
								// action
								async : true,
								data : {/* rule_id:Number(rule_id),rule_adapter_name:rule_adapter_name */},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									// if(data["rule_content"]){
									if (MDEmber.contentController === undefined) {
										MDEmber.contentController = MDEmber.contentAudioView
												.get("controller");
									}

									/*
									 * if(!MDEmber.contentController){
									 * MDEmber.contentController=MDEmber.ContentController.create({
									 * model:data["content_"],
									 * contentTypeList:MDEmber.getContentTypes(),
									 * content:Ember.Object.create(),
									 * contentList:MDEmber.getContentList(data["rule_content"][0]["WeixinRuleKey_content_type_code"])
									 * });
									 * MDEmber.regularContentView.set("controller",MDEmber.regularContentController); }
									 * 
									 * MDEmber.regularContentController.set("model",data["rule_content"][0]);
									 * //MDEmber.regularContentController.set("model2","asfasdfads");
									 */
									MDEmber.contentAudioView
											.appendTo("#float_layer");
									// }

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});

					$("#float_layer .header_title").html("<h1>音频消息</h1>");
					/*MDEmber.regularContentView
					.appendTo("#float_layer");*/
					MDEmber.floatLayer.appendInto(MDEmber.contentTextView);
					MDEmber.floatLayer.show();

				}
			}
		});

MDEmber.ContentAudioView = Ember.View.extend({
// templateName : "contenttextlist-template",
});

MDEmber.ContentPictureListView = Ember.View
		.extend({
			templateName : "contentpicturelist-template",
			click : function(event) {
				if (event.target.className.indexOf("edit_content_text") >= 0) {
					// alert(12345);

					var target_content = $(event.target).parent();
					var content_id = target_content.attr("content_id");
					if (!content_id) {
						return null;
					}

					if (Ember.TEMPLATES['contentpicturelist-template'] === undefined) {
						$.ajax({
							url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
																			// action
							async : false,
							data : {
								view_outs : [ "contentpicture" ]
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];
									// alert(temple);

									// temple=jQuery(temple);
									MDEmber.handleTemple(temple);
								}

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});
					}
					;

					// var
					// rule_adapter_name=target_rule.children("div[name='rule_adapter_name']").text();

					MDEmber.contentVideoView = MDEmber.ContentVideoView
							.create({
								templateName : 'contentpicture-template',
								classNames : [ 'auth-form' ],
								submit : function(event) {
									/*
									 * var
									 * rule_id=$("input[name='rule_id']").val();
									 * var
									 * rule_key=$("input[name='rule_key']").val();
									 * var
									 * content_type_code=$("select[name='content_type_code']").val();
									 * var
									 * content_id=$("select[name='content_id']").val();
									 * 
									 * $.post(MDEmber.subDir+"/weixinopen/regular/saveRuleKey.json", {
									 * rule_id : rule_id, rule_key : rule_key,
									 * content_type_code : content_type_code,
									 * content_id : content_id, },
									 * function(data){ });
									 * 
									 * return false;
									 */

								},

							});

					$
							.ajax({
								url : MDEmber.subDir+"/weixinopen/regular/fetchRuleContent.json",// 跳转到
																							// action
								async : true,
								data : {/* rule_id:Number(rule_id),rule_adapter_name:rule_adapter_name */},
								type : 'post',
								cache : false,
								dataType : 'json',
								success : function(data) {
									// alert(typeof data["return_code"]);
									// if(data["rule_content"]){
									if (MDEmber.contentController === undefined) {
										MDEmber.contentController = MDEmber.contentAudioView
												.get("controller");
									}

									/*
									 * if(!MDEmber.contentController){
									 * MDEmber.contentController=MDEmber.ContentController.create({
									 * model:data["content_"],
									 * contentTypeList:MDEmber.getContentTypes(),
									 * content:Ember.Object.create(),
									 * contentList:MDEmber.getContentList(data["rule_content"][0]["WeixinRuleKey_content_type_code"])
									 * });
									 * MDEmber.regularContentView.set("controller",MDEmber.regularContentController);
									 *  }
									 * 
									 * MDEmber.regularContentController.set("model",data["rule_content"][0]);
									 * //MDEmber.regularContentController.set("model2","asfasdfads");
									 */
									MDEmber.contentAudioView
											.appendTo("#float_layer");
									// }

								},
								error : function() {
									// view("异常！");
									alert("获取json数据错误！");
								}
							});

					$("#float_layer .header_title").html("<h1>图片消息</h1>");
					/*MDEmber.regularContentView
					.appendTo("#float_layer");*/
					MDEmber.floatLayer.appendInto(MDEmber.contentTextView);
					MDEmber.floatLayer.show();

				}
			}
		});

MDEmber.ContentPictureView = Ember.View.extend({
// templateName : "contenttextlist-template",
});

MDEmber.MessagenavView = Ember.View
.extend({
	templateName : "messagenav-template",
	click : function(event) {
		// alert(event.target.name);
		if (event.target.name === "current") {
            
			MDEmber.friendController
			.set("currentOperationType", "current");


			if (Ember.TEMPLATES['friendcurrentlist-template'] === undefined) {
				
				MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
						"post",
						{
							"view_outs" : [ "friendcurrentlist"],
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);

								MDEmber.friendController.set("model",
										data["friend_info_list"]);
							}},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			
			} else {
				
				MDEmber.jsonSync("/weixinopen/friend/friendInfo.json",
						"post",
						{
							"customer_id" : MDEmber.getCustomerId(),
						},
						function(data) {
							/*if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);
*/
								MDEmber.friendController.set("model",
										data["friend_info_list"]);
							/*}*/},
						function() {
							// view("异常！");
							alert("获取json数据错误！");
						});
			}
			;
		}
		;

		if (event.target.name === "mix") {

			MDEmber.contentController.set("currentContentType", "mix");

			if (Ember.TEMPLATES['contentmixlist-template'] === undefined) {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"view_outs" : [ "content"
										+ MDEmber.contentController
												.get("currentContentType")
										+ "list" ],
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.contentController.set(
											"contentList",
											data["contentList"]);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.contentController.set(
										"contentList",
										data["contentList"]);
								/* } */

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		if (event.target.name === "picture") {

			MDEmber.contentController.set("currentContentType",
					"picture");

			if (Ember.TEMPLATES['contentpicturelist-template'] === undefined) {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"view_outs" : [ "content"
										+ MDEmber.contentController
												.get("currentContentType")
										+ "list" ],
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.contentController.set(
											"contentList",
											data["contentList"]);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.contentController.set(
										"contentList",
										data["contentList"]);
								/* } */

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		if (event.target.name === "synchronize") {

			MDEmber.friendController
					.set("currentOperationType", "synchronize");

			if (Ember.TEMPLATES['friendsynchronizelist-template'] === undefined) {
				$.ajax({
							url : MDEmber.subDir+"/weixinopen/Friend/friendSynchronize.json",// 跳转到
							// action
							async : false,
							data :{},
							data : {
								"view_outs" : [ "friend"
										+ MDEmber.friendController
												.get("currentOperationType")
										+ "list" ],
								"operationType" : MDEmber.friendController
										.get("currentOperationType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.friendController.set("model",null);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$.ajax({
							url : MDEmber.subDir+"/weixinopen/Friend/friendSynchronize.json",// 跳转到
							// action
							async : false,
							data : {
								"operationType" : MDEmber.friendController
								.get("currentOperationType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.friendController.set("model",null);

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		if (event.target.name === "audio") {

			MDEmber.contentController
					.set("currentContentType", "audio");

			if (Ember.TEMPLATES['contentaudiolist-template'] === undefined) {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"view_outs" : [ "content"
										+ MDEmber.contentController
												.get("currentContentType")
										+ "list" ],
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								if (data["ember_temple"]) {
									var temple = data["ember_temple"];

									MDEmber.handleTemple(temple);

									MDEmber.contentController.set(
											"contentList",
											data["contentList"]);
								}

							},
							error : function() {
								alert("获取json数据错误！");
							}
						});
			} else {
				$
						.ajax({
							url : MDEmber.subDir+"/weixinopen/content/contentMain.json",// 跳转到
							// action
							async : false,
							data : {
								"contentType" : MDEmber.contentController
										.get("currentContentType")
							},
							type : 'post',
							cache : false,
							dataType : 'json',
							success : function(data) {
								// alert(typeof data["return_code"]);
								/*
								 * if (data["ember_temple"]) { var
								 * temple = data["ember_temple"]; //
								 * alert(temple);
								 *  // temple=jQuery(temple);
								 * MDEmber.handleTemple(temple);
								 */

								MDEmber.contentController.set(
										"contentList",
										data["contentList"]);
								/* } */G

							},
							error : function() {
								// view("异常！");
								alert("获取json数据错误！");
							}
						});

			}
			;
		}
		;

		return false;
	}

});

MDEmber.MessagelistView = Ember.View.extend({
	templateName : "messagelist-template",
});

MDEmber.MessageSampleListView = Ember.View
.extend({
	templateName : "messagesamplelist-template",
	click : function(event) {
		if (event.target.className.indexOf("edit_content_text") >= 0) {

			var target_content = $(event.target).parent();
			var content_id = target_content.attr("content_id");
			if (!content_id) {
				return null;
			}

			if (Ember.TEMPLATES['contenttext-template'] === undefined) {
				$.ajax({
					url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
					// action
					async : false,
					data : {
						view_outs : [ "contenttext" ]
					},
					type : 'post',
					cache : false,
					dataType : 'json',
					success : function(data) {
						// alert(typeof data["return_code"]);
						if (data["ember_temple"]) {
							var temple = data["ember_temple"];
							// alert(temple);

							// temple=jQuery(temple);
							MDEmber.handleTemple(temple);
						}

					},
					error : function() {
						// view("异常！");
						alert("获取json数据错误！");
					}
				});
			}
			;

			// var
			// rule_adapter_name=target_rule.children("div[name='rule_adapter_name']").text();

			MDEmber.contentTextView = MDEmber.ContentTextView
					.create({
						templateName : 'contenttext-template',
						classNames : [ 'auth-form' ],
						submit : function(event) {
							// alert(this.xhEditor.getSource());

							// var xhContent=this.xhEditor.getSource();
							/*
							 * var
							 * rule_id=$("input[name='rule_id']").val();
							 * var
							 * rule_key=$("input[name='rule_key']").val();
							 * var
							 * 
							 * var
							 * content_id=$("select[name='content_id']").val();
							 */
							var model = MDEmber.contentController
									.get("model");
							// var
							// content_name=$("input[name='content_name']").val();
							$
									.post(
											MDEmber.subDir+"/weixinopen/content/contentTextSave.json",
											{
												content_id : model.WeixinRuleContentText_content_id,
												content_name : model.WeixinRuleContentText_content_name,
												text_message : this.xhEditor
														.getSource(),
											}, function(data) {

											});

							return false;

						},
					});

			$
					.ajax({
						url : MDEmber.subDir+"/weixinopen/content/fetchContentText.json",// 跳转到
						// action
						async : true,
						data : {
							content_id : Number(content_id)
						},
						type : 'post',
						cache : false,
						dataType : 'json',
						success : function(data) {
							// alert(typeof data["return_code"]);
							// if(data["rule_content"]){
							if (MDEmber.contentController === undefined) {
								MDEmber.contentController = MDEmber.contentTextView
										.get("controller");
							}

							if (!MDEmber.contentController) {
								MDEmber.contentController = MDEmber.ContentController
										.create({
											model : data["contentText"][0],

										});

							} else {
								MDEmber.contentController.set("model",
										data["contentText"][0]);
							}
							/*
							 * MDEmber.contentTextView.set("controller",MDEmber.contentController);
							 * 
							 * MDEmber.regularContentController.set("model",data["rule_content"][0]);
							 * //MDEmber.regularContentController.set("model2","asfasdfads");
							 * 
							 * MDEmber.contentTextView
							 * .appendTo("#float_layer"); // }
							 */
						},
						error : function() {
							// view("异常！");
							alert("获取json数据错误！");
						}
					});

			// MDEmber.regularContentView=MDEmber.RegularContentView.create({templateName
			// : "regulartext-template"});

		} else {
			if (event.target.className.indexOf("create_content_text") >= 0) {
				// alert(12345);
				if (Ember.TEMPLATES['contenttext-template'] === undefined) {
					$.ajax({
						url : MDEmber.subDir+"/template/loadTemplate.json",// 跳转到
						// action
						async : false,
						data : {
							view_outs : [ "contenttext" ]
						},
						type : 'post',
						cache : false,
						dataType : 'json',
						success : function(data) {
							// alert(typeof data["return_code"]);
							if (data["ember_temple"]) {
								var temple = data["ember_temple"];
								// alert(temple);

								// temple=jQuery(temple);
								MDEmber.handleTemple(temple);
							}

						},
						error : function() {
							// view("异常！");
							alert("获取json数据错误！");
						}
					});
				}
				;
				MDEmber.contentTextView = MDEmber.ContentTextView
						.create({
							templateName : 'contenttext-template',
							classNames : [ 'auth-form' ],
							xhEditor : null,
							submit : function(event) {
								var model = MDEmber.contentController
										.get("model");

								$
										.post(
												MDEmber.subDir+"/weixinopen/content/contentTextSave.json",
												{

													content_name : model
															.get("WeixinRuleContentText_content_name"),
													text_message : this.xhEditor
															.getSource(),

												}, function(data) {

													alert(data);

												});
								return false;
							},
						});

				if (!MDEmber.contentController) {
					MDEmber.contentController = MDEmber.ContentController
							.create({
								/*
								 * ruleAdapterList : MDEmber
								 * .getRuleAdapterList(),
								 * contentTypeList : MDEmber
								 * .getContentTypes(),
								 * currentRuleAdapter : "01",
								 * currentContentType : "01",
								 */
								content : Ember.Object.create(),
							/*
							 * contentList : MDEmber
							 * .getContentList("01"),
							 */
							});

				} else {
					MDEmber.contentController.set("model", Ember.Object
							.create());
				}

			}
		}

		MDEmber.contentTextView.set("controller",
				MDEmber.contentController);

		$("#float_layer .header_title").html("<h1>文本消息</h1>");
		/*MDEmber.regularContentView
		.appendTo("#float_layer");*/
		MDEmber.floatLayer.appendInto(MDEmber.contentTextView);
		MDEmber.floatLayer.show();

	},

});


Ember.onLoad('application', function() {

	MDEmber.navView=MDEmber.NavView.create();
	MDEmber.navView.appendTo('.header');
	MDEmber.getLoginState();
	//alert(MDEmber.hasLogin);
	if (MDEmber.hasLogin) {
		//if (false) {
		//$.post(MDEmber.subDir+"/user/login.json", null, function(data) {
		//var temple = data["ember_temple"];
		//alert(temple);

		//temple=jQuery(temple);
		//MDEmber.handleTemple(temple);

		//Ember.Handlebars.bootstrap(temple);
		//Ember.$('script[type="text/x-handlebars"], script[type="text/x-raw-handlebars"]',temple).each(function() {alert(this);});
		//alert(Ember.TEMPLATES["tool_nav"]);

		//}, "json");

	} else {
		//MDEmber.singinView.appendTo('#container-1');
	}

});
