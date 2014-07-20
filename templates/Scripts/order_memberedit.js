$(document).ready(function(){
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

});