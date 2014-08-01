<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://raw.github.com/appleboy/jquery.slideShow/master/jquery.slideshow.min.js"></script>
<script>
(function ($) {
    $('.slideShow').slideShow({
        interval: 3,
        start: 'random',
        transition: {
            mode: 'slideshow',
            speed: 800
        }
    });
})(jQuery);
</script>
<style type="text/css" media="screen">
.slideShow {
    width: 900px;
    height: 420px;
    position: relative;
    overflow: hidden;
}
</style>
</head>

<body>
<div class="slideShow">
    <ul class="slides">
        <li class="slide"><img src="900_360_2815b8f28c58175b52b535bf51f3e692.png" width="900" height="360" /></a></li>
        <li class="slide"><img src="900_360_46d09a37f76f27815daafc4b96e46399.png" width="900" height="360" /></a></li>
        <li class="slide"><img src="900_360_cae9a566a9a5e3a42af8b04f2ea299a0.png" width="900" height="360" /></a></li>
        <li class="slide"><img src="900_360_dd9bb48bc247ee3f8358bea788e08ce0.png" width="900" height="360" /></a></li>
        <li class="slide"><img src="900_360_63fa2bcabc296f47758d6aaebf23a530.png" width="900" height="360" /></a></li>
    </ul>
    <ul class="pager">
        <li><a href="javascript:void(0);" class="prev">Previous</a></li>
        <li><a href="javascript:void(0);" class="next">Next</a></li>
    </ul>
</div>
</body>
</html>