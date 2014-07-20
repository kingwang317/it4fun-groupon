var jd={};

//倒數計時器
function timer_go(prod,startDate,endDate){
	if(startDate==undefined){
		var startDate =new Date();
	}else{
		var startDate =startDate;
	}

	var spantime = (endDate - startDate);
	prod.everyTime('1ds', function(i) {
		spantime=spantime-100;
	     var d = Math.floor(spantime / (24 * 3600 * 1000));
	     var h = Math.floor((spantime % (24*3600 * 1000))/(3600 * 1000));
	     var m = Math.floor((spantime % (3600 * 1000))/(60 * 1000));
	     var s = Math.floor(spantime%(60*1000)/1000);
	     var ms = (Math.floor(spantime%(60*1000))%1000)/10;
	     if(spantime>0){
	     	prod.find("span.day").text(d);
	        prod.find("span.hour").text(h);
	        prod.find("span.min").text(m);
	        prod.find("span.sec").text(s);
	        prod.find("span.msec").text(ms);
	     }else{ // 避免倒數變成負的
	        prod.find("span.hour").text(0);
	        prod.find("span.min").text(0);
	        prod.find("span.sec").text(0);
	        prod.find("span.msec").text(0);
	     }
	 });
};


$(document).ready(function(){
	//定義
	jd.prod=$('div.prod');
	jd.menu1=$('#navigation div.menu1');
	jd.menu2=$('#navigation div.menu2');
	jd.bannerInfobox=$('div#bannerbox div.infobox');
	jd.bannerbtnbox=$('div.bannerbtnbox');
	jd.bannerUl=$('div.bannerphotobox ul');
	//初始	


	//banner
	var bannerIdx=0;
	var bannerInfobox_num=jd.bannerInfobox.length;
	jd.bannerbtnbox.children('ul').css({'width':bannerInfobox_num*34});
	for(var i=0;i<bannerInfobox_num;i++){
		jd.bannerbtnbox.children('ul').append("<li bid='"+i+"'></li>");
	};
	jd.bannerbtnbox.find('ul li').eq(0).css({'background':'#c62900'});
	jd.bannerbtnbox.find('ul li').on('click',function(e){
		jd.bannerbtnbox.find('ul li').css({'background':'#cd6f57'});
		$(this).css({'background':'#c62900'});
		var idx=$(this).index();
		var _w=$('div.bannerphotobox ul li').eq(idx).width();
		//console.log(_w);
		jd.bannerUl.animate({
			left:  -(idx*_w) },
			'normal', function() {
			/* stuff to do after animation is complete */
		});
		bannerIdx=idx;
	});

	//banner自動輪播
	var int=self.setInterval(function(){
		if(bannerIdx==jd.bannerInfobox.length-1){
			bannerIdx=0;
		}else{
			bannerIdx++;
		};
		jd.bannerbtnbox.find('ul li').eq(bannerIdx).click();		
	},4000);


	//banner倒數
	for(var i=0;i<bannerInfobox_num;i++){
		var endDate=new Date(jd.bannerInfobox.eq(i).attr('endDate'));
		var startDate=new Date(jd.bannerInfobox.eq(i).attr('startDate'));
		timer_go(jd.bannerInfobox.eq(i),startDate,endDate);
	};

	//產品倒數
	var prod_num=jd.prod.length;
	for(var i=0;i<prod_num;i++){
		var endDate=new Date(jd.prod.eq(i).attr('endDate'));
		var startDate=new Date(jd.prod.eq(i).attr('startDate'));
		timer_go(jd.prod.eq(i),startDate,endDate);
	};	

	//產品
	jd.prod.on('mouseenter',function(event){
		event.stopPropagation();
		var _black=$(this).find('div.black');
		_black.animate({top: '0px'},'fast', function() { });
	});
	jd.prod.on('mouseleave',function(event){
		event.stopPropagation();
		var _black=$(this).find('div.black');
		_black.animate({top: '-53px'},'fast', function() { });
	});
	jd.prod.on('click',function(event){
		event.stopPropagation();
		var _link=$(this).attr('link');
		window.open(_link, "_newtab");
	});

});
