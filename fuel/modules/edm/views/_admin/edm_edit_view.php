<?php echo js($this->config->item('edm_javascript'), 'edm')?>
<?php echo js($this->config->item('ck_js'), 'edm')?>
<?php echo css($this->config->item('edm_css'), 'edm')?>
<script type="text/javascript">
	var $j = jQuery.noConflict(true);

	$j(document).ready(function($) {
		//$j("body").addClass('yui-skin-sam');
       var config =
            {
                height: 380,
                width: 850,
                linkShowAdvancedTab: false,
                scayt_autoStartup: false,
                enterMode: Number(2),
                toolbar_Full: [
                				[ 'Styles', 'Format', 'Font', 'FontSize', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
                				['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList'],
                                ['Link', 'Unlink'], ['Undo', 'Redo', '-', 'SelectAll'], [ 'TextColor', 'BGColor' ],['Checkbox', 'Radio', 'Image' ], ['Source']
                              ]

            };
		$( 'textarea#edm_desc' ).ckeditor(config);

		$j( "#edm_send_time" ).datepicker();
		$j( "#edm_send_time" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

		$j("#sendbtn").click(function(){
			$j("#addForm").attr("action", $(this).attr('data-href'));

			$j("#addForm").submit();
		});

		$j("#addbtn").click(function(){
			$j("#addForm").attr("action", $(this).attr('data-href'));

			$j("#addForm").submit();
		});

	});
</script>
<div id="main_content">
	<div class="row" style="margin:10px 10px">
	    <div class="col-md-2 sheader"><h2 style="font-size:28px">電子報管理</h2></div>
	    <div class="col-md-10 sheader">
	    </div>
	</div>

<div class="row" style="margin:10px 10px">
	<div class="col-md-12">
		<ul class="breadcrumb">
		  <li><a href="<?php echo $back_url?>">電子報管理</a></li>
		  <li class="active"><?php echo $view_name?></li>
		</ul>
	</div>
</div>
<div class="row" style="margin:10px 10px" id="fileupload">
	<div class="col-md-12">
		<form method="post" action="" enctype="multipart/form-data" id="addForm">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td colspan="2"><?php echo $view_name?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>主旨</td>
						<td>
							<div class="col-xs-5">
								<input type="text" class="form-control input-sm" name="subject" id="subject" value="<?php echo $edm_result->subject?>"/>
							</div>
						</td>
					</tr>
					<tr>
						<td>電子報內容</td>
						<td>
							<textarea name="edm_desc" id="edm_desc"><?php echo htmlspecialchars_decode($edm_result->content)?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right">
							<button class="btn btn-small btn-primary" type="submit" id="sendbtn" data-href="<?php echo $send_url;?>">送出信件</button>
							<button class="btn btn-small btn-primary" type="submit" id="addbtn" data-href="<?php echo $submit_url.$edm_result->edm_id;?>">儲存信件</button>
							<button class="btn btn-small btn-warning" type="button" onclick="aHover('<?php echo $back_url?>')">取消</button>
						</td>
					</tr>
				</tobdy>
			</table>
		</form>
	</div>
</div>

<script>
	function aHover(url)
	{
		location.href = url;
	}
</script>