/*
 * jQuery chloroform plugin
 * Copyright © 2012 Ian Ring
 * version 0.0.0.1. still lots of work needed to make this truly awesome
*/

;(function($) {
	
	var plugin = {};
	
	var methods = {
		
		init : function(options) {
			var self = $(this);
			return this.each(function() {
				
				plugin.form = $(this);
				
				var settings = {
					lang: 'en', 						// default language - i18n has not been implemented yet
					validateDataAttr: 'data-validate', 	// name of the attribute which stores what validation rules to apply
					scrollToBubble: true,				// if true, then a bubble popup will scroll into view.
					
					// callback functions
					onBeforeValidateAll:null,
					onAfterValidateAll:null,
					onBeforeValidate:null,
					onAfterValidate:null
					
				};
				if(options) {
					$.extend(settings, options);
				}
				
				plugin.options = settings;
				
				self.data('elements',[]);
				
				var inputs = plugin.form.find(':input[' + settings.validateDataAttr + ']');
				
				inputs.each(function(idx,elem){
					methods.register.apply(self,[$(elem)]);
					methods.readrules.apply(self,[$(elem)]);
				});
				
				// note here that the bubble is controlled by the plugin, not an instance of the plugin. This should be changed
				// so it's a child of "self", not of "plugin".
				if ($('#bubblecontainer').length > 0){
					plugin.bubblecontainer = $('#bubblecontainer');
				} else {
					plugin.bubblecontainer = $("<div id='bubblecontainer' class='bubblecontainer'></div>").appendTo(document.body).addClass('hide');
					$('	<span class="bubble" href="#"><span id="bubbledirection" class="down"><span id="bubblecloser" class="closer"></span><span class="point"></span><span class="point2"></span><span id="bubbletext" class="bubbletext">message</span></span></span>').prependTo(plugin.bubblecontainer);
					$('#bubblecloser').click(function(){
						methods.hidebubble.apply(self);
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
			var self = $(this);
			self.data('elements').push(element);
			return true;
		},

		/**
		* unregister
		* removes an element from the elements collection
		*/
		unregister: function(element){
			var self = $(this);
			var newarr = [];
			var arr = self.data('elements');
			// this is a weird way to remove an element from an array
			for(i=0;i<arr.length;i++){
				if ($(arr[i])[0] == $(element)[0]){
					// don't add it.
				} else {
					newarr.push(arr[i]);
				}
			}
			self.data('elements',newarr);
		},

		/**
		* readrules
		* given an element, will read its data-validate attribute, parse it, and build 
		* the "rules", "arguments" and "not" data objects that are used for validation
		*/
		readrules:function(element){
			$element = $(element);
			var self = $(this);
			var actionsStr = $.trim($(element).attr(plugin.options.validateDataAttr).replace(new RegExp('\\s*\\' + plugin.options.validatorsDelimiter + '\\s*', 'g'), plugin.options.validatorsDelimiter));
			is_valid = 0;
			if(!actionsStr){
				return true;
			}
			if(!$element.data('rules')){
				$element.data('rules',{});
			}
			if(!$element.data('arguments')){
				$element.data('arguments',{});
			}
			if(!$element.data('not')){
				$element.data('not',{});
			}
			var actions = $(actionsStr.split(","));
			actions.each(function(idx,rulestring){
				
				// see if the rulename has parameters
				var ruleparts = rulestring.match(/^(.*?)\[(.*?)\]/);
				if (ruleparts && ruleparts.length == 3){
					rulename = ruleparts[1];
					argarray = ruleparts[2].split(":")
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
						// if the rule isn't recognized or defined, we treat the entire attribute as a regex (buggy!)
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
			var self = $(this);
			if ($.isFunction(plugin.options.onBeforeValidateAll)){
				r = plugin.options.onBeforeValidateAll();
				if (!r){
					return false;
				}
			}
			// runs the validation on all elements. aborts on the first error found.
			var isvalid = false; // this fixes a bug in IE. strange.
			var allValid = true;
			for(i=0;i<self.data('elements').length;i++){
				element = self.data('elements')[i];
				isvalid = methods.validate($(element));
				if (isvalid){
				} else {
					allValid = false;
					break;
				}
			}
			if ($.isFunction(plugin.options.onAfterValidateAll)){
				r = plugin.options.onAfterValidateAll(allValid);
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
			var self = $(this);
			if ($.isFunction(plugin.options.onBeforeValidate)){
				r = plugin.options.onBeforeValidate();
				if (!r){
					return false;
				}
			}
			var rules = element.data('rules');
			// evaluates all the rules, shows a bubble if necessary, and returns true or false
			var thisisvalid = true;
			for(rulename in rules){
				args = [];
				// see if there are any stored arguments for the rule
				var storedargs = element.data('arguments');
				if (storedargs && storedargs[rulename]!=undefined){
					args = storedargs[rulename]; // an array of arguments
				}
				var not = element.data('not')[rulename]?true:false;
				// add element and not as the first two arguments, always
				args = $.merge([element,not],args);
				
				isvalid = rules[rulename].apply(this,args);
				
				if (isvalid.valid){
				} else {
					methods.errorindicator(element,isvalid.message);
					thisisvalid = false;
					break;
				}
			}
			if ($.isFunction(plugin.options.onAfterValidate)){
				r = plugin.options.onAfterValidate(thisisvalid);
				if (!r){
					return false;
				}
			}
			
			return thisisvalid;
		},
		
		/**
		* errorindicator
		* governs the behaviour of error messaging
		*/
		errorindicator: function(element,message){
			var self = this;
			element.focus();
			element.select();
			
			methods.showbubble(element,message);
			
			// add the keyup handler to make the bubble go away
			element.keypress(function(){
				methods.hidebubble();
			});
			return true;
		},
		
		/**
		* addrule
		* adds a function to the rules collection
		*/
		addrule: function(name,func){
			// todo. STUB!
		},
		
		/**
		* removerule
		* removes a function to the rules collection
		*/
		removerule: function(name){
			// todo. STUB!
		},
		
		
		
		/**
		* applyrule
		* adds a rule to an element
		*/
		applyrule: function(element,ruleobj){
			var self = this;
			if (element.data('rules')){
				$.merge(element.data('rules'),ruleobj);
			} else {
				element.data('rules',ruleobj);
			}
		},
		
		/**
		* revokerule
		* removes a rule from an element
		*/
		revokerule: function(element,rulename){
			// todo. STUB!
		},
		
		/**
		* ischildtype
		* returns true if the element is a group of radio or checkboxes
		*/
		ischildtype: function(element){
			// todo. STUB!
		},
		
		/**
		* showbubble
		* manages the visual positioning of the growler bubble
		*/
		showbubble : function(element,message) {
			var self = this;
			// shows the error bubble.
			if (plugin.options.scrollToError){
				// TODO: scroll to the element
			}
			$('#bubbletext').html(message);
			plugin.bubblecontainer.removeClass('hide');
			
			var pos = element.offset();
			
			if (parseInt(pos.top) < 100){
				// pointing up
				$("#bubbledirection").removeClass('down').addClass('up');
				plugin.bubblecontainer.css({
					'position':'absolute',
					'left':pos.left,
					'top':pos.top + 46
				});
			} else {
				// pointing down
				$("#bubbledirection").removeClass('up').addClass('down');
				plugin.bubblecontainer.css({
					'position':'absolute',
					'left':pos.left,
					'top':pos.top - plugin.bubblecontainer.outerHeight() - 20
				});
			}
			if (plugin.options.scrollToBubble){
				methods.scrolltobubble();
			}
			return true;
		},
		scrolltobubble:function(){
			// needs work.
			
			// check the top and bottom of the bubble and the element it's pointing to
			// if either of them are above the frame, scroll to the min() of the two tops
			//if either of them are below the frame, scroll to the max() of the two bottoms
			
//			var bubbleoffset = plugin.bubblecontainer.offset();
//			destination = bubbleoffset.top - 18;
//			if (destination < 0) {destination = 0;}
//			$(document).scrollTop(destination);
		},
		hidebubble: function(){
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
					return not?{'valid':true}:{'valid':false,'message':'this field is required'};
				}
			}
			if (elem.is(':checkbox')){
				if (elem.is(':checked')){
					return not?{'valid':false,'message':'this field is required'}:{'valid':true};
				} else {
					return not?{'valid':true}:{'valid':false,'message':'this field is required'};
				}
			}
			if (elem.is(':text') || elem.is(':password') || elem.is('textarea')){
				if (elem.val() != ''){
					return not?{'valid':false,'message':'this field is required'}:{'valid':true};
				} else {
					return not?{'valid':true}:{'valid':false,'message':'this field is required'};
				}
			}
			if (elem.is('select')){
				if (elem.val() != ''){
					return not?{'valid':false,'message':'this field is required'}:{'valid':true};
				} else {
					return not?{'valid':true}:{'valid':false,'message':'this field is required'};
				}
			}
			return not?{'valid':false,'message':'this field is required'}:{'valid':true};
		},
		'equals':function(){
			var elem = arguments[0];
			var not = arguments[1];
			if ($(elem).val() != value){
				return not?{'valid':true}:{'valid':false,'message':'this field is not '+value};
			}
			return not?{'valid':false,'message':'this field is not '+value}:{'valid':true};
		},
		'sameas':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val1 = elem.val();
			var val2 = $('#'+arguments[2]).val();
			if (val1 != val2){
				return not?{'valid':true}:{'valid':false,'message':'values are not the same'};
			}
			return not?{'valid':false,'message':'values must not be the same'}:{'valid':true};
		},
		'alpha':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = elem.val();
			var regex = new RegExp(/^[a-z ._\-]*$/i);
			var valid = regex.test(val);
			if (!valid){
				return not?{'valid':true}:{'valid':false,'message':'this field is not alphabetic'};
			}
			return not?{'valid':false,'message':'this field is not alphabetic'}:{'valid':true};
		},
		'numeric':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = elem.val();
			var regex = new RegExp(/^[0-9.\-]*$/);
			var valid = regex.test(val);
			if (!valid){
				return not?{'valid':true}:{'valid':false,'message':'this field is not numeric'};
			}
			return not?{'valid':false,'message':'this field must not be numeric'}:{'valid':true};
		},
		'integer':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = elem.val();
			var regex = new RegExp(/^[0-9]*$/);
			var valid = regex.test(val);
			if (!valid){
				return not?{'valid':true}:{'valid':false,'message':'this field is not an integer'};
			}
			return not?{'valid':false,'message':'this field must NOT be an integer'}:{'valid':true};
		},
		'max':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = parseFloat($(elem).val());
			var max = parseFloat(arguments[2]);
			if (val > max){
				return not?{'valid':true}:{'valid':false,'message':'this must be less than '+max};
			}
			return not?{'valid':false,'message':'this must NOT be less than '+max}:{'valid':true};
		},
		'min':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var val = parseFloat($(elem).val());
			var min = parseFloat(arguments[2]);
			if (val < min){
				return not?{'valid':true}:{'valid':false,'message':'this must be at least '+min};
			}
			return not?{'valid':false,'message':'this must be at least '+min}:{'valid':true};
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
				return not?{'valid':true}:{'valid':false,'message':'this must be between '+min+' and '+max};
			}
			return not?{'valid':false,'message':'this must be between '+min+' and '+max}:{'valid':true};
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
					return not?{'valid':true}:{'valid':false,'message':'this must be at least '+min+' characters long'};
				}else{
					return not?{'valid':false,'message':'this must be at least '+min+' characters long'}:{'valid':true};
				}
			}
			if (arguments.length == 4){
				// if two arguments are provided, they are min and max
				var min = arguments[2];
				var max = arguments[3];
				if (vallen < min || vallen > max){
					if (min == max){
						return not?{'valid':true}:{'valid':false,'message':'length must be '+min};
					} else {
						return not?{'valid':true}:{'valid':false,'message':'length must be between '+min+' and '+max};
					}
				} else {
					if (min == max){
						return not?{'valid':false,'message':'length must not be '+min}:{'valid':true};
					} else {
						return not?{'valid':false,'message':'length must be between '+min+' and '+max}:{'valid':true};
					}
				}
			}
			return {'valid':true};
		},
		'choices':function(){
			var elem = arguments[0];
			var not = arguments[1];
			// stub
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
				return not?{'valid':false,'message':'this field is not valid'}:{'valid':true};
			}
			return not?{'valid':true}:{'valid':false,'message':'this field is not valid'};
		},
		'email':function(){
			var elem = arguments[0];
			var not = arguments[1];
			var regex = new RegExp('[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}');
			var val = elem.val();
			var valid = regex.test(val);
			if (valid){
				return not?{'valid':false,'message':'please enter a valid email address'}:{'valid':true};
			}
			return not?{'valid':true}:{'valid':false,'message':'please enter a valid email address'};
			// stub!!
			return {'valid':true};
		},
		'url':function(){
			// stub!!
			return {'valid':true};
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
