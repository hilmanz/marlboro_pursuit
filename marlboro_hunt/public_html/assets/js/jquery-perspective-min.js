jQuery.fn.perspective=function(aa){var ba={scrollingSpeed:5000,slidingSpeed:800,depth:20,rotation:'autoManual',flightPoint:'left',timerPers:'show',playPers:'show',hoverGap:20,maxDarkening:0.5,switchOrder:true};var ca=jQuery.extend(ba,aa);var da=ca.flightPoint;var ea=ca.depth;var fa=ca.rotation;var ga=ca.timerPers;var ha=ca.hoverGap;var ia=ca.maxDarkening;var ja=ca.switchOrder;var ka=false;var la=$(this);var ma=la.children('div');var na=ma.length;var oa=na-1;var pa=la.width();var qa=la.height();var ra=$('.pause_rota');var sa=$('.play_rota');var ta=$('.timer_rota');var ua=$('.pause_rota').position();var va=ta.position();var wa=ma.width();var xa=ma.height();var ya=$('img',ma);var za=ya.width();var Aa=ya.height();var Ba=wa-za;var Ca=xa-Aa;var Da=pa-wa;var Ea=Da/oa;var Fa=qa-xa;var Ga=Fa/oa;var Ha=qa/na;if(Ha<ea){ea=Ha;}
if((da=='left')||(da=='right')){var Ia=xa/ea;var Ja=wa/Ia;if(ha>Ea){ha=Ea;}}
if((da=='top')||(da=='bottom')){var Ia=wa/ea;var Ja=xa/Ia;if(ha>Ga){ha=Ga;}}
if(ja==true){for(j=0;j<na;j++){var Ka=$('>div:eq('+j+')',la);if(j!=0){la.prepend(Ka);}}}
for(i=0;i<na;i++){var Ka=$('>div:eq('+i+')',la);var La='<em class="darkPers"></em>';if((da=='left')||(da=='right')){var Ma=xa+(oa-i)*-ea;var Na=(oa-i)*ea/2;var Oa=wa+(oa-i)*-Ja;}
else if((da=='top')||(da=='bottom')){var Pa=(oa-i)*ea/2;var Ma=xa+(oa-i)*-Ja;var Oa=wa+(oa-i)*-ea;}
if(da=='left'){var Pa=i*Ea;}
else if(da=='top'){var Na=i*Ga;}
else if(da=='right'){var Pa=(oa-i)*Ea+Ja*(oa-i);}
else if(da=='bottom'){var Na=(oa-i)*Ga+Ja*(oa-i);}
var Qa=Oa-Ba;var Ra=Ma-Ca;Ka.css({left:Pa,height:Ma,top:Na,width:Oa}).attr('tabindex',oa-i);Ka.append(La);$('img, .darkPers',Ka).css({height:Ra,width:Qa});var Sa=$('.darkPers',Ka);Sa.css({opacity:ia-i*(ia/oa)});}$('div:last p',la).show();jQuery.fn.rePosDiap=function(Ta){ma.each(function(){var Ua=$(this);var Va=Ua.attr('tabindex');var Wa=Ua.position().top;var Xa=Ua.position().left;if((da=='left')||(da=='right')){var Ya=Wa;}
if((da=='top')||(da=='bottom')){var Za=Xa;}
if(da=='left'){var Za=(oa-Va)*Ea;}
if(da=='top'){var Ya=(oa-Va)*Ga;}
if(da=='right'){var Za=Va*Ea+Ja*Va;}
if(da=='bottom'){var Ya=Va*Ga+Ja*Va;}
if(Ta==true){Ua.animate({left:Za,top:Ya},300,function(){la.removeClass('persDisorder');});}
else{Ua.stop(true,false).animate({left:Za,top:Ya},300);}});};jQuery.fn.prePersRota=function($a){if(la.hasClass('persDisorder')){var ab=false;ma.rePosDiap(ab);setTimeout(function(){la.removeClass('persDisorder');la.persRota($a);},320);}
else{la.persRota($a);}};jQuery.fn.persRota=function(bb){if(fa!='manual'){clearInterval(autoRota);if(ga!='no'){ta.hide();clearInterval(timerRota);}}
var cb=-1;ma.each(function(){cb++;var db=$('div:eq('+cb+')',la);var eb=ca.slidingSpeed/bb;var fb=ca.slidingSpeed / 2 / bb;if((da=='left')||(da=='right')){var gb=db.position().left;}
if((da=='top')||(da=='bottom')){var gb=db.position().top;}
if(cb<oa){if((da=='left')||(da=='right')){var Ma='+='+ea;var Na='-='+ea/2;var Oa='+='+Ja;}
if((da=='top')||(da=='bottom')){var Pa='-='+ea/2;var Ma='+='+Ja;var Oa='+='+ea;}
if(da=='left'){var Pa=gb+Ea;}
if(da=='top'){var Na=gb+Ga;}
if(da=='right'){var Pa=gb-Ea-Ja;}
if(da=='bottom'){var Na=gb-Ga-Ja;}
db.animate({left:Pa,height:Ma,top:Na,width:Oa},eb).attr('tabindex',oa-cb-1);$('.darkPers',db).animate({opacity:ia-(cb+1)*(ia/oa),height:Ma,width:Oa},eb);$('img',db).animate({height:Ma,width:Oa},eb);if(cb==oa-1){$('p',db).delay(eb).fadeIn(fb);}}
else{var hb=$('img',db);var ib=$('.darkPers',db);$('p',db).fadeOut(fb/2);if((da=='left')||(da=='right')){var Pa='+='+Ea;var Ma=xa+oa*-ea;var Na=oa*(ea/2);var Oa=wa+oa*-Ja;var jb=0;var kb=Na;var lb=Ea;var mb=Na;}
if((da=='top')||(da=='bottom')){var Pa=oa*(ea/2);var Ma=xa+oa*-Ja;var Na='+='+Ga;var Oa=wa+oa*-ea;var nb=0;var kb=Ga;var lb=Pa;var ob=Pa;}
if(da=='left'){var nb=wa;var ob=0;}
if(da=='top'){var jb=xa;var mb=0;}
if(da=='right'){var nb=-wa;var ob=oa*Ea+oa*Ja;}
if(da=='bottom'){var jb=-xa;var mb=oa*Ga+oa*Ja;}
var Ra=Ma-Ca;var Qa=Oa-Ba;hb.animate({opacity:0},fb);db.animate({left:nb,top:jb},fb,function(){ib.css({opacity:ia});hb.css({opacity:1});$('img, .darkPers',db).css({height:Ra,width:Qa});db.prependTo(la).css({left:lb,top:kb,height:Ma,width:Oa}).attr('tabindex',oa).animate({left:ob,top:mb},fb,function(){bb--;if(bb>0){la.prePersRota(bb);}
else{ka=false;if(!la.hasClass('enPause')){if(fa!='manual'){la.autoRotaPers();if(ga!='no'){ta.find('span').css({top:0});ta.show().timerRotaPers();}
if(ga=='hide'){ta.hide();}}}}});});}});};jQuery.fn.timerRotaPers=function(){timerRota=setInterval(function(){$('span',ta).animate({top:'-=24'},0);},ca.scrollingSpeed/24);};jQuery.fn.autoRotaPers=function(){autoRota=setInterval(function(){if(ka==false){var pb=1;ka=true;la.prePersRota(pb);}},ca.scrollingSpeed);};if(fa!='auto'){ma.click(function(){var qb=$(this).attr('tabindex');if((ka==false)&&(qb!=0)){ka=true;if(fa!='manual'){clearInterval(autoRota);}
la.prePersRota(qb);}});}
if(fa!='manual'){la.autoRotaPers();if(ga!='no'){ta.timerRotaPers();}}
if(ca.playPers=='hide'){ra.hide();la.hover(function(){$(ra,sa).stop(true,true).fadeIn(200);},function(){$(ra,sa).fadeOut(200);});}
if(ga=='hide'){ta.hide().addClass('timerHidden');la.hover(function(){ta.stop(true,true).fadeIn(200);},function(){ta.fadeOut(200);});}
if(fa!='manual'){ra.live('click',function(){var rb=$(this);if(ga!='no'){ta.find('span').css({top:34});clearInterval(timerRota);rb.css({left:ua.left,top:ua.top}).animate({left:ua.left+(va.left-ua.left),top:ua.top+(va.top-ua.top)},200).attr('class','play_rota');}
else{rb.attr('class','play_rota');}
clearInterval(autoRota);la.addClass('enPause');});}
sa.live('click',function(){ta.find('span').css({top:0});if(ga!='no'){ta.show().timerRotaPers();}
la.autoRotaPers();$(this).animate({left:ua.left,top:ua.top},200).attr('class','pause_rota');la.removeClass('enPause');});ma.hover(function(){var sb=$(this);if(ka==false){var tb=sb.attr('tabindex');if(tb!=0){la.addClass('persDisorder');$('.darkPers',sb).animate({opacity:0},200);ma.each(function(){var ub=$(this);var vb=ub.attr('tabindex');var wb=$('div[tabindex="0"]',la).position();var xb=$('div[tabindex="'+oa+'"]',la).position();var yb=(oa-vb)*ha;var zb=vb*ha+vb*Ja;var Ab=ub.position().top;var Bb=ub.position().left;if((da=='left')||(da=='right')){var Cb=Ab;var Db=Ab;}
if((da=='top')||(da=='bottom')){var Eb=Bb;var Fb=Bb;}
if(da=='left'){var Eb=yb;var Fb=wb.left-vb*ha;}
if(da=='top'){var Cb=yb;var Db=wb.top-vb*ha;}
if(da=='right'){var Eb=xb.left-(oa-vb)*Ja-(oa-vb)*ha;var Fb=zb;}
if(da=='bottom'){var Cb=xb.top-(oa-vb)*Ja-(oa-vb)*ha;var Db=zb;}
if(vb>=tb){ub.stop(true,false).animate({left:Eb,top:Cb},300);}
if(vb<tb){ub.stop(true,false).animate({left:Fb,top:Db},300);}});}}},function(){var Gb=$(this);if((ka==false)&&(la.hasClass('persDisorder'))){var Hb=Gb.attr('tabindex');$('.darkPers',Gb).stop(true,true).animate({opacity:Hb*(ia/oa)},200);var Ib=true;ma.rePosDiap(Ib);}});};