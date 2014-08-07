<?php echo css($this->config->item('news_css'), 'news')?> 
<div id="main_content">
	<div class="row" style="margin:10px 10px">
	    <div class="span2 sheader"><h1>最新消息</h1></div>
	    <div class="span11 sheader">
	    </div>
	</div>

<div class="row" style="margin:10px 10px">
	<div class="span12">
		<ul class="breadcrumb">
		  <li><a href="<?php echo $module_uri;?>">最新消息</a></li>
		  <li class="active"><?php echo $view_name?></li>
		</ul>
	</div>
</div>
<div class="row" style="margin:10px 10px">
	<div class="span12">
		<form method="post" action="<?php echo $submit_url?>" enctype="multipart/form-data" id="addForm">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td colspan="2"><?php echo $view_name?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>日期</td>
						<td>
							<div class="col-xs-5">
								<?php 
	                             $date = explode(" ", $news->date); 
	                             $date2 = $date[0]; 

	                            ?>
								<input type="text" class="form-control date input-sm" name="date" value="<?php echo $date2 ?>" required /> 
							</div>
						</td>
					</tr>
					<tr>
						<td>標題</td>
						<td>
							<div class="col-xs-5">
								<input id="title" name="title" type="text" class="form-control input-sm" value="<?php echo $news->title ?>" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>內容</td>
						<td>
							<div class="col-xs-5">
								<textarea name="content" class="form-control" rows="5"><?php echo $news->content; ?></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td>圖片</td>
						<td>
							<div class="col-xs-5">
								<input type="file" class="form-control" name="img" value=""> 
								<img src="<?php echo site_url()."assets/".$news->img; ?>" />
								<input type="hidden" value="<?php echo $news->img; ?>" name="exist_img" />	
							</div>
						</td>
					</tr> 
					<tr>
						<td colspan="2" style="text-align:right"> 
							<button type="submit" class="btn btn-info">更新</button>
							<button type="button" class="btn btn-danger" onClick="aHover('<?php echo $module_uri?>')">取消</button>
						</td>
					</tr>
				</tobdy>
			</table>
		</form>
	</div>
</div>
 

<?php echo js($this->config->item('news_javascript'), 'news')?> 
<script type="text/javascript">
	var $j = jQuery.noConflict(true);  

	$j(document).ready(function($) {
		  
		$('.date').datepicker({dateFormat: 'yy-mm-dd'});  
	});

	function aHover(url)
	{
		location.href = url;
	}
	 
</script>
  
