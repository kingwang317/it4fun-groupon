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

function htmlspecialchars_decode (string, quote_style) {
  // From: http://phpjs.org/functions
  // +   original by: Mirek Slugen
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Mateusz "loonquawl" Zalega
  // +      input by: ReverseSyntax
  // +      input by: Slawomir Kaniecki
  // +      input by: Scott Cariss
  // +      input by: Francois
  // +   bugfixed by: Onno Marsman
  // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Ratheous
  // +      input by: Mailfaker (http://www.weedem.fr/)
  // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
  // +    bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *     example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
  // *     returns 1: '<p>this -> &quot;</p>'
  // *     example 2: htmlspecialchars_decode("&amp;quot;");
  // *     returns 2: '&quot;'
  var optTemp = 0,
    i = 0,
    noquotes = false;
  if (typeof quote_style === 'undefined') {
    quote_style = 2;
  }
  string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
  var OPTS = {
    'ENT_NOQUOTES': 0,
    'ENT_HTML_QUOTE_SINGLE': 1,
    'ENT_HTML_QUOTE_DOUBLE': 2,
    'ENT_COMPAT': 2,
    'ENT_QUOTES': 3,
    'ENT_IGNORE': 4
  };
  if (quote_style === 0) {
    noquotes = true;
  }
  if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
    quote_style = [].concat(quote_style);
    for (i = 0; i < quote_style.length; i++) {
      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
      if (OPTS[quote_style[i]] === 0) {
        noquotes = true;
      } else if (OPTS[quote_style[i]]) {
        optTemp = optTemp | OPTS[quote_style[i]];
      }
    }
    quote_style = optTemp;
  }
  if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
    string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
    // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
  }
  if (!noquotes) {
    string = string.replace(/&quot;/g, '"');
  }
  // Put this in last place to avoid escape being double-decoded
  string = string.replace(/&amp;/g, '&');

  return string;
}

$(document).ready(function(){
	jd.prod=$("div#prod");
	jd.info2=$("div#prod div.prod_1 div.info_2");
	//倒數
	var endDate=new Date(jd.info2.attr('endDate'));
	var startDate=new Date(jd.info2.attr('startDate')); 
	timer_go(jd.info2,startDate,endDate);

	//
	// 預設顯示第一個 Tab
	var _showTab = 0;
	var $defaultLi = $('ul.tabs li').eq(_showTab).addClass('active');
	$($defaultLi.find('a').attr('href')).siblings().hide();

	$('ul.tabs li').click(function() {
		var $this = $(this),
			_clickTab = $this.find('a').attr('href');

		$this.addClass('active').siblings('.active').removeClass('active');
		
		$(_clickTab).stop(false, true).fadeIn().siblings().hide();

		return false;
	}).find('a').focus(function(){
		this.blur();
	});

	//產品倒數
	var prod_num=$("div.subprod").length;
	for(var i=0;i<prod_num;i++){
		var endDate=new Date($("div.subprod").eq(i).attr('endDate'));
		var startDate=new Date($("div.subprod").eq(i).attr('startDate'));
		timer_go($("div.subprod").eq(i),startDate,endDate);
	};	

	//產品
	$("div.subprod").on('mouseenter',function(event){
		event.stopPropagation();
		var _black=$(this).find('div.black');
		_black.animate({top: '0px'},'fast', function() { });
	});
	$("div.subprod").on('mouseleave',function(event){
		event.stopPropagation();
		var _black=$(this).find('div.black');
		_black.animate({top: '-53px'},'fast', function() { });
	});
	$("div.subprod").on('click',function(event){
		event.stopPropagation();
		var _link=$(this).attr('link');
		window.location.href=_link;
	});

});