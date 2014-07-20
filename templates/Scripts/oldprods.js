var jd={};
$(document).ready(function(){
	//定義
	jd.prod=$('div.prod');

	var prodNum=jd.prod.size();
	for(var i=0;i<prodNum;i++){
		jd.prod.eq(i).on('click',function(e){
			window.location.href=$(this).attr('link');
		});
	};


});