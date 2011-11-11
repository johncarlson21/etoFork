/* Copyright (c) 2008 Kean Loong Tan http://www.gimiti.com/kltan
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * jFlow
 * Version: 1.2 (July 7, 2008)
 * Requires: jQuery 1.2+
 */
 
(function($) {

	$.fn.jFlow = function(options) {
		var opts = $.extend({}, $.fn.jFlow.defaults, options);
		var randNum = Math.floor(Math.random()*11);
		var jFC = opts.controller;
		var jFS =  opts.slideWrapper;
		var jSel = opts.selectedWrapper;
		
		var pos = 'relative';
		
		if(opts.fade){ pos = 'absolute'; }

		var cur = 0;
		var timer;
		var maxi = $(jFC).length;
		
		var curSlide = 0;
		
		// sliding function
		var slide = function (dur, i) {
			//alert(i);
			$(opts.slides).children().css({
				overflow:"hidden"
			});
			$(opts.slides + " iframe").hide().addClass("temp_hide");
			$(opts.slides).animate({
				marginLeft: "-" + (i * $(opts.slides).find(":first-child").width() + "px")
				},
				opts.duration*(dur),
				opts.easing,
				function(){
					$(opts.slides).children().css({
						overflow:"hidden"
					});
					$(".temp_hide").show();
				}
			);
			
		}
		
		
		
		//alert($(opts.slides).children().length);
		
		// fading function
		var fade = function (dur, i, prev){
			
			curSlide = i;
			
			//$('#counter').html('curSlide: ' + curSlide);
			
			if(i > 0){
				n = (i-1);
			}else{
				n = maxi-1;	
			}
			
			if(prev){ n = i+1; }
			
			if(i==maxi){ nx = 0; }else{ nx = i; } //alert(nx);
			$('#counter').html('i=' + i + '; n=' + n + '; nx=' + nx + '; maxi=' + maxi);
			
			$(opts.slides).children().eq(nx).fadeIn(opts.duration);
			$(opts.slides).children().eq(n).fadeOut(opts.duration);
		}
		
		
		$(this).find(jFC).each(function(i){
			$(this).click(function(){
				dotimer();
				if ($(opts.slides).is(":not(:animated)")) {
					$(jFC).removeClass(jSel);
					$(this).addClass(jSel);
					var dur = Math.abs(cur-i);
					//alert(i);
					if(opts.fade){
						if(i<curSlide){
							$(opts.slides).children().eq(curSlide).fadeOut(opts.duration);
							fade(dur,i, true);
						}else{
							fade(dur, i, false);
						}
					}else{
						slide(dur, i, false);
					}
					cur = i;
				}
			});
			
			// make thumbs if necessary
			// get the img src of the slide for this button
			if(opts.thumbs){
				sl = $(opts.slides).children().eq(i).children('img').eq(0);
				thSrc = sl.attr('src');
				thW = sl.attr('width');
				thH = sl.attr('height');
				$(this).css({width:'100px',height:'100px',display:'block',float:'left',overflow:'hidden',marginLeft:'10px',paddingTop:'0px',paddingBottom:'0px',paddingLeft:'0px',paddingRight:'0px'});
				//$(this).css('background-image','url(' + thSrc + ')');
				$(this).html('<img src="' + thSrc + '" width="100" />')
				//alert(thSrc);
			}
			
		});	
		
		$(opts.slides).before('<div id="'+jFS.substring(1, jFS.length)+'"></div>').appendTo(jFS);
		
		$(opts.slides).find("div").each(function(){
			$(this).before('<div class="jFlowSlideContainer"></div>').appendTo($(this).prev());
		});
		
		
		// if fading, we need to hide all the slides first and then show the first
		if(opts.fade){
			for(m=0;m<maxi;m++){
				$(opts.slides).children('div.jFlowSlideContainer').eq(m).hide();
			}
			$(opts.slides).children().eq(0).show();
		}
		
		//initialize the controller
		$(jFC).eq(cur).addClass(jSel);
		
		var resize = function (x){
			$(jFS).css({
				//position:pos,
				position:'relative',
				width: opts.width,
				height: opts.height,
				overflow: "hidden"
			});
			//opts.slides or #mySlides container
			if(opts.fade){
				wd = opts.width;
			}else{
				wd = $(jFS).width()*$(jFC).length+"px";
			}
			
			$(opts.slides).css({
				position:pos,
				width:wd,
				height: $(jFS).height()+"px",
				overflow: "hidden"
			});
			// jFlowSlideContainer
			$(opts.slides).children().css({
				position:pos,
				width: $(jFS).width()+"px",
				height: $(jFS).height()+"px",
				"float":"left",
				overflow:"hidden"
			});
			
			if(opts.fade == false){
				$(opts.slides).css({
					marginLeft: "-" + (cur * $(opts.slides).find(":eq(0)").width() + "px")
				});
			}
		}
		
		// sets initial size
		resize();

		// resets size
		$(window).resize(function(){
			resize();						  
		});
		
		$(opts.prev).click(function(){
			dotimer();
			doprev();
			
		});
		
		$(opts.next).click(function(){
			dotimer();
			donext();
			
		});
		
		var doprev = function (x){
			//if ($(opts.slides).is(":not(:animated)")) {
				var dur = 1;
				if (cur > 0)
					cur--;
				else {
					cur = maxi -1;
					dur = cur;
				}
				$(jFC).removeClass(jSel);
				if(opts.fade){
					
					fade(dur, cur, true);
				}else{
					slide(dur, cur);
				}
				$(jFC).eq(cur).addClass(jSel);
			//}
		}
		
		var donext = function (x){
			/*if ($(opts.slides).is(":not(:animated)")) { */
				var dur = 1;
				if (cur < maxi - 1)
					cur++;
				else {
					cur = 0;
					dur = maxi -1;
				}
				$(jFC).removeClass(jSel);
				if(opts.fade){
					fade(dur, cur, false);
				}else{
					slide(dur, cur);
				}
				$(jFC).eq(cur).addClass(jSel);
			//}
		}
		
		var dotimer = function (x){
			if((opts.auto) == true) {
				if(timer != null) 
					clearInterval(timer);
			    
        		timer = setInterval(function() {
	                	$(opts.next).click();
						}, 6000);
			}
		}

		dotimer();
	};
	
	$.fn.jFlow.defaults = {
		controller: ".jFlowControl", // must be class, use . sign
		slideWrapper : "#jFlowSlide", // must be id, use # sign
		selectedWrapper: "jFlowSelected",  // just pure text, no sign
		auto: false,
		easing: "swing",
		duration: 400,
		width: "100%",
		prev: ".jFlowPrev", // must be class, use . sign
		next: ".jFlowNext", // must be class, use . sign
		fade: false, // activate the fade instead of slide
		thumbs: false // use thumbs
	};
	
})(jQuery);
