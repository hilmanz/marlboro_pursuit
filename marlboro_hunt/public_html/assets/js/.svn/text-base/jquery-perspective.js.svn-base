/**
 * Perspective - jQuery plugin
 * Version 1.1 - Last-edit : 08.12.2010
 * Author VIUU - France
 * http://viuu.fr
 * Copyright (c) 2010.
 */


// Welcome to Perspective plugin :
jQuery.fn.perspective = function(options){
	
	
	
	
	// All Defaults variables
	var defaults = {
		scrollingSpeed : 5000,
		slidingSpeed : 800,
		depth : 20,
		rotation : 'autoManual',
		flightPoint : 'left',
		timerPers : 'show',
		playPers : 'show',
		hoverGap : 20,
		maxDarkening : 0.5,
		switchOrder : true
	};
	
	// Switch defaults variables with options
	var opts = jQuery.extend(defaults, options);
	// Options variables
	var flightPoint = opts.flightPoint;
	var depth = opts.depth;
	var rotation = opts.rotation;
	var timerPers = opts.timerPers;
	var hoverGap = opts.hoverGap;
	var maxDarkening = opts.maxDarkening;
	var switchOrder = opts.switchOrder;
	// moving status
	var moving = false;
	// Main objects
	var thatPers = $(this);
	var persDiap = thatPers.children('div');
	// Number of images to slide
	var nbrDiap = persDiap.length;
	var nbrDiapPers = nbrDiap - 1;
	// Container dimensions
	var widthPers = thatPers.width();
	var heightPers = thatPers.height();
	// Pause and timer object
	var persPause = $('.pause_rota');
	var persPlay = $('.play_rota');
	var persTimer = $('.timer_rota');
	// Position of pause and timer
	var posPause = $('.pause_rota').position();
	var posTimer = persTimer.position();
	// Images container dimensions
	var widthDiap = persDiap.width();
	var heightDiap = persDiap.height();
	// Images dimensions
	var diapImg = $('img', persDiap);
	var widthImg = diapImg.width();
	var heightImg = diapImg.height();
	// Gap between Images and their containers
	var gapWidthDiapImg = widthDiap - widthImg;
	var gapHeightDiapImg = heightDiap - heightImg;
	// Gap between main container and contents
	var gapPersHoriz = widthPers - widthDiap;
	var gapDiapHoriz = gapPersHoriz / nbrDiapPers;
	var gapPersVertic = heightPers - heightDiap;
	var gapDiapVertic = gapPersVertic / nbrDiapPers;
	// Security if option 'depth' is too high
	var depthMax = heightPers / nbrDiap;
	if (depthMax < depth) {
		depth = depthMax;
	}
	
	if ((flightPoint == 'left')||(flightPoint == 'right')) {
		var rapportGap = heightDiap / depth;
		var otherDepth = widthDiap / rapportGap;
		if (hoverGap > gapDiapHoriz) {
			hoverGap = gapDiapHoriz;
		}
	}
	
	if ((flightPoint == 'top')||(flightPoint == 'bottom')) {
		var rapportGap = widthDiap / depth;
		var otherDepth = heightDiap / rapportGap;
		if (hoverGap > gapDiapVertic) {
			hoverGap = gapDiapVertic;
		}
	}
	
	// Initialization begin
	if (switchOrder == true) {
		for (j = 0; j < nbrDiap; j++) {
			var thatDiv = $('>div:eq('+ j +')', thatPers);
			if (j != 0) {
				thatPers.prepend(thatDiv);
			}
		}
	}
	
	for (i = 0; i < nbrDiap; i++) {
		
		var thatDiv = $('>div:eq('+ i +')', thatPers);
		var backgroundImg = '<em class="darkPers"></em>';
		
		if ((flightPoint == 'left')||(flightPoint == 'right')) {
			var heightDiapPers = heightDiap + (nbrDiapPers - i) * -depth;
			var posTopDiap = (nbrDiapPers - i)*depth/2;
			var widthDiapPers = widthDiap + (nbrDiapPers - i) * -otherDepth;
		}
		else if ((flightPoint == 'top') || (flightPoint == 'bottom')) {
			var posLeftDiap = (nbrDiapPers - i)*depth/2;
			var heightDiapPers = heightDiap + (nbrDiapPers - i) * -otherDepth;
			var widthDiapPers = widthDiap + (nbrDiapPers - i) * -depth;
		}
		if (flightPoint == 'left') {
			var posLeftDiap = i * gapDiapHoriz;
		}
		else if (flightPoint == 'top') {
			var posTopDiap = i * gapDiapVertic;
		}
		else if (flightPoint == 'right') {
			var posLeftDiap = (nbrDiapPers - i) * gapDiapHoriz + otherDepth * (nbrDiapPers - i);
		}
		else if (flightPoint == 'bottom') {
			var posTopDiap = (nbrDiapPers - i) * gapDiapVertic + otherDepth * (nbrDiapPers - i);
		}
		
		var widthImgPers = widthDiapPers - gapWidthDiapImg;
		var heightImgPers = heightDiapPers - gapHeightDiapImg;
		
		thatDiv.css({
			left : posLeftDiap,
			height : heightDiapPers,
			top : posTopDiap,
			width : widthDiapPers
		}).attr('tabindex', nbrDiapPers - i);
		
		thatDiv.append(backgroundImg);
		
		$('img, .darkPers', thatDiv).css({
			height : heightImgPers,
			width : widthImgPers
		});
		
		var thatdarkPers = $('.darkPers', thatDiv);
		thatdarkPers.css({
			opacity : maxDarkening - i  * (maxDarkening / nbrDiapPers)
		});
	}
	
	// Show last <p> description
	$('div:last p', thatPers).show();
	
	// Functions begin
	// Reposition after mouseover
	jQuery.fn.rePosDiap = function(rePosOnly){
		persDiap.each(function(){
			var thatPersDiap = $(this);
			var divIndexOther = thatPersDiap.attr('tabindex');
			var divPosTop = thatPersDiap.position().top;
			var divPosLeft = thatPersDiap.position().left;
			
			if ((flightPoint == 'left')||(flightPoint == 'right')) {
				var rePosTop = divPosTop;
			}
			if ((flightPoint == 'top')||(flightPoint == 'bottom')) {
				var rePosLeft = divPosLeft;
			}
			if (flightPoint == 'left') {
				var rePosLeft = (nbrDiapPers - divIndexOther)*gapDiapHoriz;
			}
			if (flightPoint == 'top') {
				var rePosTop = (nbrDiapPers - divIndexOther)*gapDiapVertic;
			}
			if (flightPoint == 'right') {
				var rePosLeft = divIndexOther * gapDiapHoriz + otherDepth * divIndexOther;
			}
			if (flightPoint == 'bottom') {
				var rePosTop = divIndexOther * gapDiapVertic + otherDepth * divIndexOther;
			}
			if (rePosOnly == true) {
				thatPersDiap.animate({left : rePosLeft, top : rePosTop},300, function(){
					thatPers.removeClass('persDisorder');
				});
			}
			else {
				thatPersDiap.stop(true, false).animate({left : rePosLeft, top : rePosTop},300);
			}
		});
	};
	
	
	jQuery.fn.prePersRota = function(cibleRank){
		if (thatPers.hasClass('persDisorder')) {
			var rePosOnly = false;
			persDiap.rePosDiap(rePosOnly);
			
			setTimeout(function(){
				thatPers.removeClass('persDisorder');
				thatPers.persRota(cibleRank);
			},320);
		}
		else {
			thatPers.persRota(cibleRank);
		}
	};
	
	// Rotation Function
	jQuery.fn.persRota = function(cibleRank){
		if (rotation != 'manual') {
			clearInterval(autoRota);
			if (timerPers != 'no') {
				persTimer.hide();
				clearInterval(timerRota);
			}
		}
			
		var currentCount = -1;
		
		persDiap.each(function(){
			currentCount++;
			
			var currentDiap = $('div:eq(' + currentCount + ')', thatPers);
			var diapSpeed = opts.slidingSpeed / cibleRank;
			var lastDiapSpeed = opts.slidingSpeed / 2 / cibleRank;
			
			if ((flightPoint == 'left')||(flightPoint == 'right')) {
				var posDiap = currentDiap.position().left;
			}
			if ((flightPoint == 'top')||(flightPoint == 'bottom')) {
				var posDiap = currentDiap.position().top;
			}
			// Every div in the back
			if (currentCount < nbrDiapPers) {
				if ((flightPoint == 'left')||(flightPoint == 'right')) {
					var heightDiapPers = '+=' + depth;
					var posTopDiap = '-=' + depth/2;
					var widthDiapPers = '+=' + otherDepth;
				}
				if ((flightPoint == 'top') || (flightPoint == 'bottom')) {
					var posLeftDiap = '-=' + depth/2;
					var heightDiapPers = '+=' + otherDepth;
					var widthDiapPers = '+=' + depth;
				}
				if (flightPoint == 'left') {
					var posLeftDiap = posDiap + gapDiapHoriz;
				}
				if (flightPoint == 'top') {
					var posTopDiap = posDiap + gapDiapVertic;
				}
				if (flightPoint == 'right') {
					var posLeftDiap = posDiap - gapDiapHoriz - otherDepth;
				}
				if (flightPoint == 'bottom') {
					var posTopDiap = posDiap - gapDiapVertic - otherDepth;
				}

				currentDiap.animate({left : posLeftDiap, height : heightDiapPers, top : posTopDiap, width : widthDiapPers}, diapSpeed).attr('tabindex', nbrDiapPers - currentCount - 1);
				$('.darkPers', currentDiap).animate({opacity : maxDarkening - (currentCount + 1)  * (maxDarkening / nbrDiapPers), height : heightDiapPers, width : widthDiapPers}, diapSpeed);
				$('img', currentDiap).animate({height : heightDiapPers, width : widthDiapPers}, diapSpeed);
				if (currentCount == nbrDiapPers - 1) {
					$('p', currentDiap).delay(diapSpeed).fadeIn(lastDiapSpeed);
				}
			}
			// Front div that slides in the background
			else {
				var currentDiapImg = $('img', currentDiap);
				var currentDarkPers = $('.darkPers', currentDiap);
				
				$('p', currentDiap).fadeOut(lastDiapSpeed / 2);
				
				if ((flightPoint == 'left') || (flightPoint == 'right')) {
					var posLeftDiap = '+=' + gapDiapHoriz;
					var heightDiapPers = heightDiap + nbrDiapPers * -depth;
					var posTopDiap = nbrDiapPers*(depth/2);
					var widthDiapPers = widthDiap + nbrDiapPers * -otherDepth;
					var fadeTop = 0;
					var preTopDiap = posTopDiap;
					var preLeftDiap = gapDiapHoriz;
					var topBack = posTopDiap;
				}
				if ((flightPoint == 'top') || (flightPoint == 'bottom')) {
					var posLeftDiap = nbrDiapPers*(depth/2);
					var heightDiapPers = heightDiap + nbrDiapPers * -otherDepth;
					var posTopDiap = '+=' + gapDiapVertic;
					var widthDiapPers = widthDiap + nbrDiapPers * -depth;
					var fadeLeft = 0;
					var preTopDiap = gapDiapVertic;
					var preLeftDiap = posLeftDiap;
					var leftBack = posLeftDiap;
					
				}
				if (flightPoint == 'left') {
					var fadeLeft = widthDiap;
					var leftBack = 0;
				}
				if (flightPoint == 'top') {
					var fadeTop = heightDiap;
					var topBack = 0;
				}
				if (flightPoint == 'right') {
					var fadeLeft = -widthDiap;
					var leftBack = nbrDiapPers * gapDiapHoriz + nbrDiapPers * otherDepth;
				}
				if (flightPoint == 'bottom') {
					var fadeTop = -heightDiap;
					var topBack = nbrDiapPers * gapDiapVertic + nbrDiapPers * otherDepth;
				}
				
				var heightImgPers = heightDiapPers - gapHeightDiapImg;
				var widthImgPers = widthDiapPers - gapWidthDiapImg;
				
				currentDiapImg.animate({opacity : 0}, lastDiapSpeed);
				currentDiap.animate({left : fadeLeft, top : fadeTop}, lastDiapSpeed, function(){
					currentDarkPers.css({opacity : maxDarkening});
					currentDiapImg.css({opacity : 1});
					$('img, .darkPers', currentDiap).css({height : heightImgPers, width : widthImgPers});
					
					currentDiap.prependTo(thatPers).css({left : preLeftDiap, top : preTopDiap, height : heightDiapPers, width : widthDiapPers})
					.attr('tabindex', nbrDiapPers)
					.animate({left: leftBack, top : topBack}, lastDiapSpeed, function(){
						cibleRank--;
						if (cibleRank > 0) {
							thatPers.prePersRota(cibleRank);
						}
						else {
							moving = false;
							if (! thatPers.hasClass('enPause')) {
								if (rotation != 'manual') {
									thatPers.autoRotaPers();
									if (timerPers != 'no') {
										persTimer.find('span').css({top: 0});
										persTimer.show().timerRotaPers();
									}
									if (timerPers == 'hide') {
										persTimer.hide();
									}
								}
							}
						}
					});
				});
			}
		});
	};
	
	// Timer
	jQuery.fn.timerRotaPers = function(){
		timerRota=setInterval(function(){
			$('span', persTimer).animate({top : '-=24'},0);
		},opts.scrollingSpeed/24);
	};
	
	
	// Autorotation function
	jQuery.fn.autoRotaPers = function(){
		autoRota=setInterval(function(){
			if (moving == false) {
				var cibleRank = 1;
				moving = true;
				thatPers.prePersRota(cibleRank);
			}
		},opts.scrollingSpeed);
	};
	
	// Interaction begin
	// Manual click rotation
	if (rotation != 'auto') {
		persDiap.click(function(){
			var cibleRank = $(this).attr('tabindex');
			if ((moving == false) && (cibleRank != 0)) {
				moving = true;
				if (rotation != 'manual') {
					clearInterval(autoRota);
				}
				thatPers.prePersRota(cibleRank);
			}
		});
	}
	// Launch rotation and timer animation
	if (rotation != 'manual') {
		thatPers.autoRotaPers();
		if (timerPers != 'no') {
			persTimer.timerRotaPers();
		}
	}
	// Hide controller
	if (opts.playPers == 'hide') {
		persPause.hide();
		thatPers.hover(function(){
			$(persPause, persPlay).stop(true, true).fadeIn(200);
		}, function(){
			$(persPause, persPlay).fadeOut(200);
		});
	}
	// Hide timer
	if (timerPers == 'hide') {
		persTimer.hide().addClass('timerHidden');
		thatPers.hover(function(){
			persTimer.stop(true, true).fadeIn(200);
		}, function(){
			persTimer.fadeOut(200);
		});
	}
	// Allows pause function
	if (rotation != 'manual') {
		persPause.live('click', function(){
			var thatPause = $(this);
			if (timerPers != 'no') {
				persTimer.find('span').css({top : 34});
				clearInterval(timerRota);
				thatPause.css({left : posPause.left, top : posPause.top}).animate({left : posPause.left + (posTimer.left - posPause.left), top : posPause.top + (posTimer.top - posPause.top)},200).attr('class', 'play_rota');
			}
			else {
				thatPause.attr('class', 'play_rota');
			}
			clearInterval(autoRota);
			thatPers.addClass('enPause');
		});
	}
	// Rotate again after pause
	persPlay.live('click', function(){
		persTimer.find('span').css({top : 0});
		if (timerPers != 'no') {
			persTimer.show().timerRotaPers();
		}
		thatPers.autoRotaPers();
		$(this).animate({left : posPause.left, top : posPause.top},200).attr('class', 'pause_rota');
		thatPers.removeClass('enPause');
	});
	// Hover interaction
	persDiap.hover(function(){
		var hoverDiap = $(this);
		
		if (moving == false) {
			var divIndex = hoverDiap.attr('tabindex');
			
			if (divIndex != 0) {
				thatPers.addClass('persDisorder');
				$('.darkPers', hoverDiap).animate({opacity : 0},200);
					
				persDiap.each(function(){
					var relativeDiap = $(this);
					
					var divIndexOther = relativeDiap.attr('tabindex');
					var posIndexFirst = $('div[tabindex="0"]', thatPers).position();
					var posIndexLast = $('div[tabindex="' + nbrDiapPers + '"]', thatPers).position();
					var hoverPrevPos = (nbrDiapPers - divIndexOther)*hoverGap;
					var hoverNextPos = divIndexOther*hoverGap + divIndexOther * otherDepth;
					var divPosTop = relativeDiap.position().top;
					var divPosLeft = relativeDiap.position().left;
					
					if ((flightPoint == 'left')||(flightPoint == 'right')) {
						var hoverPrevTop = divPosTop;
						var hoverNextTop = divPosTop;
					}
					if ((flightPoint == 'top')||(flightPoint == 'bottom')) {
						var hoverPrevLeft = divPosLeft;
						var hoverNextLeft = divPosLeft;
					}
					if (flightPoint == 'left') {
						var hoverPrevLeft = hoverPrevPos;
						var hoverNextLeft = posIndexFirst.left - divIndexOther * hoverGap;
					}
					if (flightPoint == 'top') {
						var hoverPrevTop = hoverPrevPos;
						var hoverNextTop = posIndexFirst.top - divIndexOther * hoverGap;
					}
					if (flightPoint == 'right') {
						var hoverPrevLeft = posIndexLast.left - (nbrDiapPers - divIndexOther) * otherDepth - (nbrDiapPers - divIndexOther)*hoverGap;
						var hoverNextLeft = hoverNextPos;
					}
					if (flightPoint == 'bottom') {
						var hoverPrevTop = posIndexLast.top - (nbrDiapPers - divIndexOther) * otherDepth - (nbrDiapPers - divIndexOther)*hoverGap;
						var hoverNextTop = hoverNextPos;
					}

					if (divIndexOther >= divIndex) {
						relativeDiap.stop(true, false).animate({left : hoverPrevLeft, top : hoverPrevTop},300);
					}
					if (divIndexOther < divIndex) {
						relativeDiap.stop(true, false).animate({left: hoverNextLeft, top : hoverNextTop}, 300);
					}
				});
			}
		}
		
	},function(){
		var hoverDiap = $(this);
		
		if ((moving == false)&&(thatPers.hasClass('persDisorder'))) {
			var divIndex = hoverDiap.attr('tabindex');
			$('.darkPers', hoverDiap).stop(true, true).animate({opacity : divIndex  * (maxDarkening / nbrDiapPers)},200);
			
			var rePosOnly = true;
			persDiap.rePosDiap(rePosOnly);
		}
	});
	

};