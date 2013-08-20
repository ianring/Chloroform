/*
 * jQuery chloroform plugin
 * Copyright © 2012 Ian Ring
 * version 0.0.0.1. still lots of work needed to make this truly awesome
*/

/*

form.data contains 
	- options, an object of config options
	- elements , an array of elements

element.data contains:
	- rules,
		an object full of named functions, keyed by a slug name like "length" or "required"
	- arguments,
		an object containing the arguments for those named functions
	- not
		an object containing the boolean "not" operator for those named functions

*/

;(function($) {
	
	var plugin = {};
	
	var methods = {
		
		init : function(options) {
			var self = $(this);
			return this.each(function(idx, elem) {
				
				var form = $(elem);
				
				var defaults = {
					theme: 'blackbubble', 					// default theme, blackbubble ships with chloroform
					lang: 'en', 							// default language - i18n has not been implemented yet
					validateDataAttr: 'data-validate', 		// name of the attribute which stores what validation rules to apply
					scrollToBubble: true,					// if true, then a bubble popup will scroll into view. default: true.
					
					// callback functions
					onBeforeValidateAll:null,
					onAfterValidateAll:null,
					onBeforeValidate:null,
					onAfterValidate:null
					
				};
				
				form.data('options', defaults);
				if(options) {
					form.data('options', $.extend({}, defaults, options));
				}
				
				form.data('elements',[]);
				
				// this should find all textareas, selects, buttons etc too.
				var inputs = form.find(':input[' + form.data('options').validateDataAttr + ']');
				
				inputs.each(function(idx,elem){
					methods.register.apply(form,[$(elem)]);
					methods.readRules.apply(form,[$(elem)]);
				});
				
				// note here that the bubble is controlled by the plugin, not an instance of the plugin. This should be changed
				// so it's a child of "self", not of "plugin".
				if ($('#bubblecontainer').length > 0){
					plugin.bubblecontainer = $('#bubblecontainer');
				} else {
					plugin.bubblecontainer = $("<div id='bubblecontainer' class='bubblecontainer'></div>").appendTo(document.body).addClass('hide');
					plugin.bubblethemer = $("<div id='bubblethemer' class=''></div>").appendTo(plugin.bubblecontainer);
					$('	<span class="bubble" href="#"><span id="bubbledirection" class="down"><span id="bubblecloser" class="closer"></span><span class="point"></span><span class="point2"></span><span id="bubbletext" class="bubbletext">message</span></span></span>').prependTo(plugin.bubblethemer);
					$('#bubblecloser').click(function(){
						methods.hideBubble.apply(self);
					});
				}
				
				// todo: check that this element is a <form> before we assume it'll have a submit() event
				self.submit(function(){ // here we assume that self is the enclosing <form>
					var allvalid = methods.validateAll.apply(self);
					return allvalid;
				});
				
			});
			
		},

		/**
		* register
		* adds an element to the elements collection
		*/
		register: function(element){
			var form = $(this);
			form.data('elements').push(element);
			methods.readRules($(element));
			return true;
		},

		/**
		* unregister
		* removes an element from the elements collection
		*/
		unregister: function(element){
			var form = $(this);
			var newarr = [];
			var arr = form.data('elements');
			// this is a weird way to remove an element from an array
			for(i=0;i<arr.length;i++){
				if ($(arr[i])[0] == $(element)[0]){
					// don't add it.
				} else {
					newarr.push(arr[i]);
				}
			}
			form.data('elements',newarr);
		},
		
		/**
		* setoption
		* sets an option on the form
		*/
		setOption: function(name,value){
			var form = $(this);
			var options = form.data('options');
			options[name] = value;
			form.data('options',options);
			return true;
		},
		
		
		/**
		* readRules
		* given an element, will read its data-validate attribute, parse it, and build 
		* the "rules", "arguments" and "not" data objects that are used for validation.
		*/
		readRules:function(element){
			$element = $(element);
			var form = $($element.closest('form'));
			
			var self = $(this);
			var actionsStr = $.trim( $element.attr( form.data('options').validateDataAttr) );
			is_valid = 0;
			if(!actionsStr){
				return true;
			}
			
			// initialize some of the objects
			if(!$element.data('rules')){
				$element.data('rules',{});
			}
			if(!$element.data('arguments')){
				$element.data('arguments',{});
			}
			if(!$element.data('not')){
				$element.data('not',{});
			}
			
			/*
			an actionsStr might look like these:

			"contains(3,4);regex([a-z]{0-9})"

			this function must be capable of parsing something that complicated
			
			*/
			var actions = $(actionsStr.split(";"));
			actions.each(function(idx,rulestring){
				
				// see if the rulename has parameters
				var ruleparts = rulestring.match(/^(.*?)\((.*?)\)/);
				if (ruleparts && ruleparts.length == 3){
					rulename = ruleparts[1];
					argarray = ruleparts[2].split(",")
				} else {
					rulename = rulestring;
					argarray = [];
				}
				
				if (rulename.substr(0,1) == '!'){
					rulename = rulename.substr(1);
					$element.data('not')[rulename] = true;
				} else {
					$element.data('not')[rulename] = false;
				}
				
				if (rules[rulename]){
					$element.data('rules')[rulename] = rules[rulename];
					$element.data('arguments')[rulename] = argarray;
				} else {
					if ($.isFunction(window[rulename])){
						$element.data('rules')[rulename] = window[rulename];
						$element.data('arguments')[rulename] = argarray;
					} else {
						// if the rule isn't recognized or defined, we treat the entire attribute as a regex (@todo: this is still buggy!)
						rulename = 'regex';
						$element.data('rules')[rulename] = rules[rulename];
						argarray = [];
						$element.data('rules',{'regex':rules['regex']})
						argarray.push(rulestring);
						$element.data('arguments',{'regex':argarray});
						return true;
					}
				}
			});
			return true;
		},

		/**
		* validateAll
		* loops through all the elements in "elements" and performs validation.
		* This is typically the function called on form submission.
		* the loop breaks when it finds the first "false".
		*/
		validateAll: function(){
			var form = $(this);
			if (form.data('options') && $.isFunction(form.data('options').onBeforeValidateAll)){
				r = form.data('options').onBeforeValidateAll(form);
				if (!r){
					return false;
				}
			}
			// runs the validation on all elements. aborts on the first error found.
			var isvalid = false; // this fixes a bug in IE. strange.
			var allValid = true;
			
			for(i=0;i<form.data('elements').length;i++){
				$element = $(form.data('elements')[i]);
				isvalid = methods.validate($element);
				if (isvalid){
				} else {
					allValid = false;
					break;
				}
			}
			if ($.isFunction(form.data('options').onAfterValidateAll)){
				r = form.data('options').onAfterValidateAll(form, allValid);
				if (!r){
					return false;
				}
			}
			
			return allValid;
		},
		
		/**
		* validate
		* executes all the functions in the "rules" using the values in 
		* "arguments" and "not" as arguments 0 and 1
		*/
		validate: function(element){
			$element = $(element);
			var form = $element.closest('form');
			if ($.isFunction(form.data('options').onBeforeValidate)){
				r = form.data('options').onBeforeValidate($element);
				if (!r){
					return false;
				}
			}
			var rules = $element.data('rules');
			// evaluates all the rules, shows a bubble if necessary, and returns true or false
			var thisisvalid = true;
			for(rulename in rules){
				args = [];
				// see if there are any stored arguments for the rule
				var storedargs = $element.data('arguments');
				if (storedargs && storedargs[rulename]!=undefined){
					args = storedargs[rulename]; // an array of arguments
				}
				var not = $element.data('not')[rulename]?true:false;
				// add element and not as the first two arguments, always
				args = $.merge([$element,not],args);
				
				isvalid = rules[rulename].apply(this,args);
				
				if (isvalid.valid){
					// do nothing.
				} else {
					
					lang = form.data('options').lang;
					
					if (typeof isvalid.message == 'string'){
						if ( typeof Chloroform.i18n[lang][rulename] != 'undefined' && typeof Chloroform.i18n[lang][rulename][isvalid.message] != 'undefined'){
							message = Chloroform.i18n[lang][rulename][isvalid.message];
						} else {
							message = isvalid.message;
						}
						
					}
					if (typeof isvalid.message == 'object'){
						pattern = Chloroform.i18n[lang][rulename][isvalid.message[0]];
						args = isvalid.message.slice(1);
						message = pattern.format(args);
					}
					
					methods.errorIndicator($element,message);
					thisisvalid = false;
					break;
				}
			}
			if ($.isFunction(form.data('options').onAfterValidate)){
				r = form.data('options').onAfterValidate($element, thisisvalid);
				if (!r){
					return false;
				}
			}
			
			return thisisvalid;
		},
		
		/**
		* errorIndicator
		* governs the behaviour of error messaging
		*/
		errorIndicator: function(element,message){
			var self = this;
			element.focus();
			element.select();
			
			methods.showBubble(element,message);
			
			// add the keyup handler to make the bubble go away
			element.keypress(function(){
				methods.hideBubble();
			});
			// if this element is a checkbox or radio, then we react to a click
			if (element.is(':checkbox') || element.is(':radio')){
				element.click(function(){
					methods.hideBubble();
				})
			}
			return true;
		},
		
		/**
		* addRule
		* adds a function to the rules collection
		*/
		addRule: function(name,func){
			// todo. STUB!
		},
		
		/**
		* removeRule
		* removes a function to the rules collection
		*/
		removeRule: function(name){
			// todo. STUB!
		},
		
		
		
		/**
		* applyRule
		* adds a rule to an element
		*/
		applyRule: function(element,ruleobj){
			var self = this;
			if (element.data('rules')){
				$.merge(element.data('rules'),ruleobj);
			} else {
				element.data('rules',ruleobj);
			}
		},
		
		/**
		* revokeRule
		* removes a rule from an element
		*/
		revokeRule: function(element,rulename){
			// todo. STUB!
		},
		
		/**
		* showBubble
		* manages the visual positioning of the growler bubble
		*/
		showBubble : function(element,message) {
		
			var form = element.closest('form');
			
			var self = this;
			// shows the error bubble.
			$('#bubbletext').html(message);
			plugin.bubblecontainer.removeClass('hide');
			
			// do we need to change the theme?
			if (!plugin.bubblethemer.hasClass(form.data('options').theme)){
				plugin.bubblethemer.removeClass().addClass(form.data('options').theme);
			}
			
			// get the top of the element
			$targetelem = element;
			if ($(element.data('surrogate-element')).length > 0){
				$targetelem = $(element.data('surrogate-element'));
			}
			var pos = $targetelem.offset();
			pos.width = $targetelem.width();
			pos.height = $targetelem.height();
			
			if (parseInt(pos.top) < 100){
				// pointing up
				$("#bubbledirection").removeClass('down').addClass('up');
				plugin.bubblecontainer.css({
					'position':'absolute',
					'left':pos.left + (pos.width/2),
					'top':pos.top + 46
				});
			} else {
				// pointing down
				
				// get the height of the whole bubble, after the text is added
//				$height = $('#bubbletext').innerHeight() + plugin.bubblecontainer.innerHeight();
				
				$("#bubbledirection").removeClass('up').addClass('down');
				plugin.bubblecontainer.css({
					'position':'absolute',
					'left':pos.left + (pos.width/2),
					'top':pos.top - 5
				});
			}
			
			
			if (form.data('options').scrollToBubble){
				methods.scrollToBubble();
			}
			return true;
		},
		scrollToBubble:function(){
			
			var windowhalfheight = Math.floor($(window).height() / 2);
//			alert(windowhalfheight);
			
			var bubbleoffset = parseInt(plugin.bubblecontainer.offset().top);
			destination = bubbleoffset - windowhalfheight;
			if (destination < 0) {destination = 0;}
			
			$('body,html').animate({scrollTop: destination}, 800);
		},
		hideBubble: function(){
			plugin.bubblecontainer.addClass('hide');
			return true;
		}
		
	};
	
	var rules = {
		'required':function(){
			var elem = arguments[0];
			var not = arguments[1];
			// definition of required:
			// if it's a text box, it must not be empty
			// if it's a checkbox, it must be checked
			// if it's a radio, it must be chosen
			// if it's a select box, something must be selected
			if (elem.is(':radio')){
				if (elem.is(':checked')){
					return not?{'valid':true}:{'valid':false,'message':'1'};
				}
			}
			if (elem.is(':checkbox')){
				if (elem.is(':checked')){
					return not?{'valid':false,'message':'1'}:{'valid':true};
				} else {
					return not?{'valid':true}:{'valid':false,'message':'1'};
				}
			}
			if (elem.is(':text') || elem.is(':password') || elem.is('textarea') || elem.is(':input[type=hidden]')){
				if (elem.val() != ''){
					return not?{'valid':false,'message':'1'}:{'valid':true};
				} else {
					return not?{'valid':true}:{'valid':false,'message':'1'};
				}
			}
			if (elem.is('select')){
				if (elem.val() != ''){
					return not?{'valid':false,'message':'1'}:{'valid':true};
				} else {
					return not?{'valid':true}:{'valid':false,'message':'1'};
				}
			}
			return not?{'valid':false,'message':'1'}:{'valid':true};
		},
		'equals':function(){
			var elem = arguments[0];
			var not = arguments[1];
			if ($(elem).val() != value){
				return not?{'valid':true}:{'valid':false,'message':['1',value]};
			}
			return not?{'valid':false,'message':['1',value]}:{'valid':true};
		},
		'sameas':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val1 = elem.val();
			var val2 = $('#'+arguments[2]).val();
			if (val1 != val2){
				return not?{'valid':true}:{'valid':false,'message':'1'};
			}
			return not?{'valid':false,'message':'2'}:{'valid':true};
		},
		'alpha':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = elem.val().toLowerCase();
			var regex = new RegExp(/^[a-z ._\-]*$/i);
			var valid = regex.test(val);
			if (!valid){
				return not?{'valid':true}:{'valid':false,'message':'1'};
			}
			return not?{'valid':false,'message':'1'}:{'valid':true};
		},
		'numeric':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = elem.val();
			var regex = new RegExp(/^[0-9.\-]*$/);
			var valid = regex.test(val);
			if (!valid){
				return not?{'valid':true}:{'valid':false,'message':'1'};
			}
			return not?{'valid':false,'message':'2'}:{'valid':true};
		},
		'integer':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = elem.val();
			var regex = new RegExp(/^[0-9]*$/);
			var valid = regex.test(val);
			if (!valid){
				return not?{'valid':true}:{'valid':false,'message':'1'};
			}
			return not?{'valid':false,'message':'2'}:{'valid':true};
		},
		'max':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = parseFloat($(elem).val());
			var max = parseFloat(arguments[2]);
			if (val > max){
				return not?{'valid':true}:{'valid':false,'message':['1',max]};
			}
			return not?{'valid':false,'message':['2',max]}:{'valid':true};
		},
		'min':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = parseFloat($(elem).val());
			var min = parseFloat(arguments[2]);
			if (val < min){
				return not?{'valid':true}:{'valid':false,'message':['1',min]};
			}
			return not?{'valid':false,'message':['2',min]}:{'valid':true};
		},
		'between':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var min = 0;
			var max = 0;
			var val = parseFloat($(elem).val());
			if (arguments.length == 3){
				max = parseFloat(arguments[2]);
			}
			if (arguments.length == 4){
				min = parseFloat(arguments[2]);
				max = parseFloat(arguments[3]);
			}
			if (val < min || val > max){
				return not?{'valid':true}:{'valid':false,'message':['1',min,max]};
			}
			return not?{'valid':false,'message':['2',min,max]}:{'valid':true};
		},
		'length':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var min = 0;
			var vallen = $(elem).val().length;
			if (arguments.length == 3){
				// if only one argument is provided, it's interpreted as "min"
				var min = arguments[2];
				if (vallen < min){
					return not?{'valid':true}:{'valid':false,'message':['1',min]};
				}else{
					return not?{'valid':false,'message':['1',min]}:{'valid':true};
				}
			}
			if (arguments.length == 4){
				// if two arguments are provided, they are min and max
				var min = arguments[2];
				var max = arguments[3];
				if (vallen < min || vallen > max){
					if (min == max){
						return not?{'valid':true}:{'valid':false,'message':['2',min]};
					} else {
						return not?{'valid':true}:{'valid':false,'message':['3',min,max]};
					}
				} else {
					if (min == max){
						return not?{'valid':false,'message':['4',min]}:{'valid':true};
					} else {
						return not?{'valid':false,'message':['3',min,max]}:{'valid':true};
					}
				}
			}
			return {'valid':true};
		},
		'choices':function(){
			var elem = arguments[0];
			var not = arguments[1];
			
			// this is a member of a group of elements.
			var name = elem.attr('name');
			
			$chosen=0;
			
			// checkboxes
			if (elem.is("input:checkbox")){
				$others = elem.closest('form').find(':input[name='+name+']');
				if ($others.length > 1){
//					console.log($others);
				}
				for(var j=0;j<$others.length;j++){
					if ( $($others[j]).prop('checked')){
						$chosen++;
					}
				}
			}
			// checkboxes
			if (elem.is("select")){
				$chosen = elem.find(':selected').length;
			}
			
			if (arguments.length == 3){
				// if only one argument is provided, it's interpreted as "min"
				var min = arguments[2];
				if ($chosen < min){
					return {'valid':false,'message':['1',min]};
				}
				return {'valid':true};
			}
			if (arguments.length == 4){
				// if two arguments are provided, they are min and max
				var min = arguments[2];
				var max = arguments[3];
				
				if (min == max){
					if ($chosen < min || $chosen > max){
						return not?{'valid':true}:{'valid':false,'message':['2',min]};
					}
				} else {
					if ($chosen < min){
						return not?{'valid':true}:{'valid':false,'message':['3',min]};
					}
					if ($chosen > max){
						return not?{'valid':true}:{'valid':false,'message':['4',max]};
					}
				}
				return {'valid':true};
			}
			return {'valid':true};
		},
		'contains':function(){
			var elem = arguments[0];
			var not = arguments[1];
			// stub
			return {'valid':true};
		},
		'regex':function(){
			// this is still buggy
			var elem = arguments[0];
			var not = arguments[1];
			var str = arguments[2];
			var val = elem.val();
			var regex = new RegExp(str);
			var valid = regex.test(val);
			if (valid){
				return not?{'valid':false,'message':'1'}:{'valid':true};
			}
			return not?{'valid':true}:{'valid':false,'message':'1'};
		},
		'email':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var regex = new RegExp('[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}');
			var val = elem.val();
			var valid = regex.test(val);
			if (valid){
				return not?{'valid':false,'message':'1'}:{'valid':true};
			}
			return not?{'valid':true}:{'valid':false,'message':'1'};
			// stub!!
			return {'valid':true};
		},
		'url':function(){
			// stub!!
			return {'valid':true};
		},
		'ajax':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var url = arguments[2];
			var form = elem.closest('form');
			var val = elem.val();
			var name = elem.attr('name');
			data = {};
			data[name] = val;
			$.ajax({
				async: false,
				'url': url,
				'type': 'get',
				'data': data,
				'dataType': 'json'
			}).done(function( data ){
				
				if (data.valid){
					console.log('the ajax requests, they have passed');
					form.submit();
				} else {
					element = elem;
					message = data.message;
					form.chloroform('showBubble',element,message);
				}
				
			});
			return false;
		}
	};





	/**
	* MAIN PLUGIN SETUP
	* 
	*/
	$.fn.chloroform = function(method) {
		if(methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if(typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}
		else {
			$.error("Method " +	 method + " does not exist on jQuery.chloroform");
		}
	};

})(jQuery);

if (typeof Chloroform == 'undefined'){
	Chloroform = {};
}
if (typeof Chloroform.i18n == 'undefined'){
	Chloroform.i18n = {};
}
if (typeof Chloroform.i18n.en == 'undefined'){
	Chloroform.i18n.en = {};
}

if (!String.prototype.format){
	String.prototype.format = function() {
		var args = arguments[0];
		
		return this.replace(/{(\d+)}/g,function(match,number){
			return typeof args[number] != 'undefined'
				? args[number]
				: match
			;
		});
	};
}
