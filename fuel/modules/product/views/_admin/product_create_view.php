<?php echo js($this->config->item('product_javascript'), 'product')?>
<?php echo js($this->config->item('ck_js'), 'product')?>
<?php echo css($this->config->item('product_css'), 'product')?>
<script type="text/javascript">
	var $j = jQuery.noConflict(true);
	var uploadPath = '<?php echo $upload_path;?>';
	var imgData = 0;
	var current_pro_id = -1;

	$j(document).ready(function($) {
		//$j("body").addClass('yui-skin-sam');
		$j("#pro_original_price").numeric();
		$j("#pro_group_price").numeric();
		$j("#pro_start_sell").numeric();
		$j("#pro_order").numeric();
		$j("input[name='plan_price']").numeric();
		$j("input[name='plan_num']").numeric();
		$j("input[name='plan_seq']").numeric();
		//$("#addForm").validate();

		$("#addForm").validate({
			rules: {
				pro_name: "required",
				pro_summary: "required",
			},
			messages: {
				pro_name: "請輸入產品名稱",
				pro_summary: "請輸入產品摘要",
				pro_original_price: "請輸入產品原價",
				pro_group_price: "請輸入產品團購價",
				pro_order: "請輸入產品順序",
				pro_start_sell: "請輸產品起始數量",
				pro_add_time: "請選擇產品上架日期",
				pro_off_time: "請選擇產品下架日期"
			}
		});
		
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
                                ['Link', 'Unlink'], ['Undo', 'Redo', '-', 'SelectAll'], [ 'TextColor', 'BGColor' ],['Checkbox', 'Radio', 'Image', 'flash' ], ['Source']
                              ]

            };
		$( 'textarea#pro_desc' ).ckeditor(config);
		$( 'textarea#pro_format' ).ckeditor(config);
		$( 'textarea#pro_ship_note' ).ckeditor(config);
		img_template = doT.template($j('#img_template').text());
		edit_photo_template = doT.template($j('#edit_photo_template').text());
		plan_template = doT.template($j('#plan_template').text());
		//$j('.file-inputs').bootstrapFileInput();

		$j(document).on('change', 'input[name="pic[]"]', function() {
		        var input = $(this),
		            numFiles = input.get(0).files ? input.get(0).files.length : 1,
		            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		        input.trigger('fileselect', [numFiles, label]);
		});


		$j( "#pro_add_time" ).datepicker({
			onClose:function(selectedDate){
				$j("#pro_off_time").datepicker("option", "minDate", selectedDate);
			}
		});
		$j( "#pro_off_time" ).datepicker({
			onClose:function(selectedDate){
				$j("#pro_add_time").datepicker("option", "maxDate", selectedDate);
			}
		});

		$j( "#pro_add_time" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		$j( "#pro_off_time" ).datepicker( "option", "dateFormat", "yy-mm-dd" );


		$j(document).on("click", "input[name='ga_url[]']", function(){
			$j(this).select(); //選取圖片連結文字
		});

		$j(".btn-file :file").on('change',function(){
			var str = "";
			$j("#inlineUpload").find(".span2").remove();

			var numFiles = this.files.length;
			$j.colorbox({
				inline:true,
				width:"60%", 
				height:"100%",
				overlayClose: false,
				closeButton: false,
				href: "#inlineUpload"
			});

        	uploadImg();

        	$j(".editPhoto").show();
        	$j("#uploadpic").val('');
		});

		$j("#cancel_upload").click(function(){
			var gaids = $(this).attr('gaids');
			var ga_num = gaids.split(",").length;
			var del_url = '<?php echo $del_photos_url;?>';
			var post_data = {'ga_ids': gaids}

			$j.colorbox.close();
			$j.post(del_url, post_data, function(data, textStatus, xhr) {
				var parse_data = $j.parseJSON(data);

				if(parse_data.status == 1)
				{
					imgData = imgData - ga_num;
					if(imgData == 0)
					{
						$j(".editPhoto").hide();
					}
				}
				console.log(parse_data);
			});
		});

		$j("#upload_ok").click(function(){
			var gaids = $j(this).attr('gaids');
			var pro_id = $j(this).attr('proid');
			var ga_name = $j("#inlineUpload input[name='ga_name[]']").map(function(){return $j(this).val();}).get();
			var ga_desc = $j("#inlineUpload input[name='ga_desc[]']").map(function(){return $j(this).val();}).get();
			var ga_w = $j("#inlineUpload input[name='ga_w[]']").map(function(){return $j(this).val();}).get();
			var ga_h = $j("#inlineUpload input[name='ga_h[]']").map(function(){return $j(this).val();}).get();
			var pro_cover_photo = $j("#inlineUpload .radio_cover[checked='checked']").find('span').attr('gaid');
			var pro_text_photo = $j("#inlineUpload .radio_text[checked='checked']").find('span').attr('gaid');
			var pro_ad_photo = $j("#inlineUpload .radio_ad[checked='checked']").find('span').attr('gaid');
			var ga_ids = gaids.split(",");
			var post_data = 
				{
					'ga_name': ga_name, 
					'ga_desc': ga_desc, 
					'ga_w': ga_w, 
					'ga_h': ga_h, 
					'ga_ids': ga_ids,
					'pro_cover_photo': pro_cover_photo,
					'pro_text_photo': pro_text_photo,
					'pro_ad_photo': pro_ad_photo,
					'pro_id': pro_id
				};
			var update_photo_url = '<?php echo $update_photo_url;?>';

			$.post(update_photo_url, post_data, function(data, textStatus, xhr) {
				console.log(data);
				var parse_data = $j.parseJSON(data);

				console.log(post_data);

				if(parse_data.status == 1)
				{
				    var editor = CKEDITOR.instances["pro_desc"];
				    var editor_1 = CKEDITOR.instances["pro_format"];
				    var editor_2 = CKEDITOR.instances["pro_ship_note"];
    				if (editor) { editor.destroy(true); }
    				if (editor_1) { editor_1.destroy(true); }
    				if (editor_2) { editor_2.destroy(true); }

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

					config.extraPlugins = "imagebrowser";
					config.imageBrowser_listUrl = "<?php echo $get_photo_data_to_ck_url?>" + pro_id;
					$( 'textarea#pro_desc' ).ckeditor(config);
					$( 'textarea#pro_format' ).ckeditor(config);
					$( 'textarea#pro_ship_note' ).ckeditor(config);
					//CKEDITOR.replace("pro_desc",{"extraPlugins":"imagebrowser", "imageBrowser_listUrl": "<?php echo $get_photo_data_to_ck_url?>"});
					$j.colorbox.close();
				}
			});
		});

		$j("#edit_ok").click(function(){
			var gaids = $j(this).attr('gaids');
			var pro_id = $j(this).attr('proid');
			var ga_name = $j("#editPhotoArea input[name='ga_name[]']").map(function(){return $j(this).val();}).get();
			var ga_desc = $j("#editPhotoArea input[name='ga_desc[]']").map(function(){return $j(this).val();}).get();
			var ga_w = $j("#editPhotoArea input[name='ga_w[]']").map(function(){return $j(this).val();}).get();
			var ga_h = $j("#editPhotoArea input[name='ga_h[]']").map(function(){return $j(this).val();}).get();
			var ga_del_id = $j("#editPhotoArea input[name='ga_del_id[]']:checked").map(function(){return $j(this).val();}).get();
			var ga_ids = gaids.split(",");
			var pro_cover_photo = $j("#editPhotoArea .radio_cover[checked='checked']").find('span').attr('gaid');
			var pro_text_photo = $j("#editPhotoArea .radio_text[checked='checked']").find('span').attr('gaid');
			var pro_ad_photo = $j("#editPhotoArea .radio_ad[checked='checked']").find('span').attr('gaid');

			var post_data = 
				{
					'ga_name': ga_name, 
					'ga_desc': ga_desc, 
					'ga_w': ga_w, 
					'ga_h': ga_h, 
					'ga_ids': ga_ids, 
					'ga_del_id': ga_del_id,
					'pro_cover_photo': pro_cover_photo,
					'pro_text_photo': pro_text_photo,
					'pro_ad_photo': pro_ad_photo,
					'pro_id': pro_id
				};
			var update_photo_url = '<?php echo $update_photo_url;?>';

			$.post(update_photo_url, post_data, function(data, textStatus, xhr) {
				console.log(data);
				var parse_data = $j.parseJSON(data);

				console.log(post_data);

				if(parse_data.status == 1)
				{
					$j.colorbox.close();
				}
			});
		});

		$j("#cancel_edit").click(function(){
			$j.colorbox.close();
		});

		$j(".editPhoto").click(function() {
			var pro_id = $(this).attr('proid');
			var post_data = {'pro_id': pro_id};
			var url = '<?php echo $get_photo_data_url?>';
			$j("#editPhotoArea").find(".span2").remove();
			$j.colorbox({
				inline:true,
				width:"60%", 
				height:"100%",
				closeButton: false,
				href: "#editPhotoArea"
			});

			$.post(url, post_data, function(data, textStatus, xhr) {
				var parse_data = $j.parseJSON(data);
	        	var data_num = parse_data.ga_data.photo_data.length;
	        	var gaids = "";
	        	console.log(parse_data);
	        	for(var i=0; i<data_num; i++)
	        	{
		        	if(i == 0)
		        	{
		        		gaids = parse_data.ga_data.photo_data[i].ga_id;
		        	}
		        	else
		        	{
		        		gaids += "," + parse_data.ga_data.photo_data[i].ga_id;
		        	}
		        			
	        	}
				$j("#edit_ok").attr('gaids', gaids);
				$j("#edit_ok").attr('proid', pro_id);

				$j(".rowimgs").children().remove();
				$j('#editPhotoArea').find(".rowimgs").append(edit_photo_template(parse_data));

				$j("#editPhotoArea .radio_cover[checked='checked']").css('background-color', '#39b3d7');
				$j("#editPhotoArea .radio_cover[checked='checked']").css('color', '#fff');
				$j("#editPhotoArea .radio_text[checked='checked']").css('background-color', '#5cb85c');
				$j("#editPhotoArea .radio_text[checked='checked']").css('color', '#fff');
				$j("#editPhotoArea .radio_ad[checked='checked']").css('background-color', '#da4f49');
				$j("#editPhotoArea .radio_ad[checked='checked']").css('color', '#fff');
				
			});
		});

		$j("#addPlanButton").click(function() {
			$j("#add_plan").text("新增");
			$j("#add_plan").attr("url", "<?php echo $add_plan_url?>");
			$j("textarea[name='plan_desc']").text("方案一");
			$j("input[name='plan_price']").val("");
			$j("input[name='plan_num']").val("");
			$j("input[name='plan_seq']").val("");
			$j.colorbox({
				inline:true,
				width:"60%", 
				height:"60%",
				closeButton: false,
				href: "#choosePlanArea"
			});
		});

		$j(document).on("click", ".add_plan", function() {
			var planDetailUrl = '<?php echo $plan_detail_url;?>/' + $(this).attr('planid');
			var plan_id = $(this).attr('planid');
			$j("#add_plan").text("修改");
			$j.colorbox({
				inline:true,
				width:"60%", 
				height:"60%",
				closeButton: false,
				href: "#choosePlanArea"
			});

			$j.ajax({
				url: planDetailUrl,
				type: 'GET',
				dataType: 'json',
				success: function(data){
					$j("textarea[name='plan_desc']").text(data.plan_row.plan_desc);
					$j("input[name='plan_price']").val(data.plan_row.plan_price);
					$j("input[name='plan_num']").val(data.plan_row.plan_num);
					$j("input[name='plan_seq']").val(data.plan_row.plan_seq);

					$j("#add_plan").attr("url", "<?php echo $edit_plan_url?>" + plan_id);
				}
			});
			
		});

		$j(document).on("click", ".updatePlanButton", function() {
			var planDetailUrl = '<?php echo $plan_detail_url;?>/' + $(this).attr('planid');
			var plan_id = $(this).attr('planid');
			$j("#add_plan").text("修改");
			$j.colorbox({
				inline:true,
				width:"60%", 
				height:"60%",
				closeButton: false,
				href: "#choosePlanArea"
			});

			$j.ajax({
				url: planDetailUrl,
				type: 'GET',
				dataType: 'json',
				success: function(data){
					$j("textarea[name='plan_desc']").text(data.plan_row.plan_desc);
					$j("input[name='plan_price']").val(data.plan_row.plan_price);
					$j("input[name='plan_num']").val(data.plan_row.plan_num);
					$j("input[name='plan_seq']").val(data.plan_row.plan_seq);

					$j("#add_plan").attr("url", "<?php echo $edit_plan_url?>" + plan_id);
				}
			});
			
		});

		$j("#add_plan").click(function() {
			var plan_desc = $("textarea[name='plan_desc']").val();
			var plan_price = $("input[name='plan_price']").val();
			var plan_num = $("input[name='plan_num']").val();
			var plan_seq = $("input[name='plan_seq']").val();
			var url = $(this).attr("url");

			var tpl_data = {};

			tpl_data = {'plan_seq': plan_seq, 'plan_desc': plan_desc, 'plan_price': plan_price, 'plan_num': plan_num};

			$.post(url, tpl_data, function(data, textStatus, xhr) {
				console.log(data);
				var parse_data = $.parseJSON(data);

				if(parse_data.status == 1)
				{
					$j('.blockText').find('span').html(parse_data.msg);
					tpl_data.plan_id = parse_data.plan_id;
					if(parse_data.msg != "修改成功")
					{
						$j('.planDisplayArea').append(plan_template(tpl_data));
					}
					else
					{
						$(".updatePlanButton[planid='"+parse_data.plan_id+"']").text(tpl_data.plan_desc);
					}
					
					
					$j('.blockText').fadeIn(500).delay(200).fadeOut(500);					
				}
				else
				{
					$j('.blockText').find('span').html(parse_data.msg);
					$j('.blockText').fadeIn(500).delay(200).fadeOut(500);						
				}
			});
			

		});

		$j("#cancel_plan").click(function(event) {
			$j.colorbox.close();
		});

		$(".planDisplayArea").on('click', 'button.del_plan', function(){
			var plan_id = $(this).attr('planid');
			var url = '<?php echo $del_plan_url?>';
			var post_data = {'plan_id': plan_id};

			$.post(url, post_data, function(data, textStatus, xhr) {
				console.log(data);
				var parse_data = $.parseJSON(data);

				if(parse_data.status == 1)
				{
					$("#plan_"+plan_id).remove();
				}
			});
		});

		$j(document).on("click", ".radio_cover", function(){
			
			if($j(this).attr("checked") == "checked")
			{
				$j(this).css('background-color', '#fff');
				$j(this).css('color', '#000');
				$j(this).removeAttr("checked", "checked");
			}
			else
			{
				$j(this).css('background-color', '#39b3d7');
				$j(this).css('color', '#fff');
				$j(this).attr("checked", "checked");		
			}

			$j(this).parents().siblings().find(".radio_cover").css('background-color', '#fff');
			$j(this).parents().siblings().find(".radio_cover").css('color', '#000');
			$j(this).parents().siblings().find(".radio_cover").removeAttr('checked');
		});

		$j(document).on("click", ".radio_text", function(){

			if($j(this).attr("checked") == "checked")
			{
				$j(this).css('background-color', '#fff');
				$j(this).css('color', '#000');
				$j(this).removeAttr("checked", "checked");
			}
			else
			{
				$j(this).css('background-color', '#5cb85c');
				$j(this).css('color', '#fff');
				$j(this).attr("checked", "checked");			
			}

			$j(this).parents().siblings().find(".radio_text").css('background-color', '#fff');
			$j(this).parents().siblings().find(".radio_text").css('color', '#000');
			$j(this).parents().siblings().find(".radio_text").removeAttr('checked');
		});

		$j(document).on("click", ".radio_ad", function(){

			if($j(this).attr("checked") == "checked")
			{
				$j(this).css('background-color', '#fff');
				$j(this).css('color', '#000');
				$j(this).removeAttr("checked", "checked");
			}
			else
			{
				$j(this).css('background-color', '#da4f49');
				$j(this).css('color', '#fff');
				$j(this).attr("checked", "checked");			
			}

			$j(this).parents().siblings().find(".radio_ad").css('background-color', '#fff');
			$j(this).parents().siblings().find(".radio_ad").css('color', '#000');
			$j(this).parents().siblings().find(".radio_ad").removeAttr('checked');
		});

	});

	function chk_form()
	{
		if($j(".planDisplayArea").find("p").size() == 0)
		{
			alert("請建立方案");
			return;
		}
		else
		{
			$j("#addForm").submit();
		}

		return true;
	}

	function uploadImg()
	{
	    var formData = new FormData($j("#addForm")[0]);
    	var upload_path = '<?php echo $upload_path;?>';

	    $j.ajax({
	        url: upload_path,  //Server script to process data
	        type: 'POST',
	        // Form data
	        data: formData,
	        //Options to tell jQuery not to process data or worry about content-type.
	        cache: false,
	        contentType: false,
	        processData: false,
	        xhr: function() {  // Custom XMLHttpRequest
	        	$("progress").fadeIn(100);
	            var myXhr = $.ajaxSettings.xhr();
	            if(myXhr.upload){ // Check if upload property exists
	                myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
	            }
	            return myXhr;
	        },
	        //Ajax events
	        success: function(data)
	        {
	        	console.log(data);
	        	var parse_data = $j.parseJSON(data);
	        	var data_num = parse_data.imgData.length;
	        	var str = "";
	        	var gaids = "";
	        	
	        	for(var i=0; i<data_num; i++)
	        	{
		        	if(i == 0)
		        	{
		        		gaids = parse_data.imgData[i].ga_id;
		        	}
		        	else
		        	{
		        		gaids += "," + parse_data.imgData[i].ga_id;
		        	}			
	        	}

	        	$j("#cancel_upload").attr('gaids', gaids);
	        	$j("#upload_ok").attr('gaids', gaids);
	        	$j("#upload_ok").attr('proid', parse_data.pro_id);
	        	$j("#edit_ok").attr('proid', parse_data.pro_id);
	        	
	        	//$j(str).appendTo("#inlineUpload .rowimgs");

	        	$(".rowimgs").children().remove();
	        	$j('#inlineUpload').find(".rowimgs").append(img_template(parse_data));
	        	
	        	imgData = data_num;
	        	$j("#pro_id").val(parse_data.pro_id);
	        	$j(".editPhoto").attr('proid', parse_data.pro_id);
	        }
	    });
	}

	function progressHandlingFunction(e){
	    if(e.lengthComputable){
	        //$('.progress-bar').attr({value:e.loaded,max:e.total});
	        var c = 0;
	        c = Math.round((e.loaded / e.total) * 100);
	        $('.progress-bar').attr({'aria-valuenow':e.loaded,'aria-valuemax':e.total});

	        $('.progress-bar').css({
	        	'width': c+"%"
	        });

	        $('span.sr-only span').text(c);
	    }
	}
</script>
<div id="main_content">
	<div class="row" style="margin:10px 10px">
	    <div class="col-md-2 sheader"><h2>產品管理</h2></div>
	    <div class="col-md-10 sheader">
	    </div>
	</div>

<div class="row" style="margin:10px 10px">
	<div class="col-md-12">
		<ul class="breadcrumb">
		  <li><a href="<?php echo $back_url;?>">產品管理</a></li>
		  <li class="active"><?php echo $view_name?></li>
		</ul>
	</div>
</div>
<div class="row" style="margin:10px 10px" id="fileupload">
	<div class="col-md-12">
		<form method="post" action="<?php echo $submit_url?>" enctype="multipart/form-data" id="addForm">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td colspan="2"><?php echo $view_name?></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>*產品名稱</td>
						<td>
							<div class="col-xs-5">
								<input type="text" class="form-control input-sm" name="pro_name" id="pro_name" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>*產品摘要</td>
						<td>
							<div class="col-xs-6">
								<textarea name="pro_summary" class="form-control" rows="5" required></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td>*產品類別</td>
						<td>
							<div class="col-xs-3">
								<select name="pro_cate" class="form-control input-sm">
									<?php 
										foreach($pro_cate_results as $cate_row)
										{
									?>
										<option value="<?php echo $cate_row->id?>"><?php echo $cate_row->code_name?></option>
									<?php 
										}
									?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>*產品方案</td>
						<td class="planDisplayArea">
							<button class="btn btn-sm btn-default" id="addPlanButton" type="button">新增方案</button>
			        		<script id='plan_template'  type="text/x-dot-template">
					        	<p style="margin: 0 0 0 0;" id="plan_{{= it.plan_id}}">
					        		<button class="btn btn-link updatePlanButton" planid="{{= it.plan_id}}" type="button">{{= it.plan_desc}}</button>
					        		&nbsp;&nbsp;
					        		<button class="btn btn-link del_plan" planid="{{= it.plan_id}}" type="button" style="color:red">刪除</button>
					        		<input type="hidden" name="plan_id[]" value="{{= it.plan_id}}" />
				        		</p>
				        	</script>			
						</td>
					</tr>
					<tr>
						<td>*產品原價</td>
						<td>
							<div class="col-xs-5">
								<input type="text" class="form-control input-sm" name="pro_original_price" id="pro_original_price" value="" required/>
							</div>
						</td>
					</tr>
					<tr style="display:none">
						<td >*產品團購價</td>
						<td>
							<div class="col-xs-5">
							<input type="text" class="form-control input-sm" name="pro_group_price" id="pro_group_price" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>*產品排序</td>
						<td>
							<div class="col-xs-5">
							<input type="text" class="form-control input-sm" name="pro_order" id="pro_order" value="99" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>*產品起始已購數量</td>
						<td>
							<div class="col-xs-5">
							<input type="text" class="form-control input-sm" name="pro_start_sell" id="pro_start_sell" value="" required/>
							</div>
						</td>
					</tr>
					<tr>
						<td>*產品圖片</td>
						<td id="pic_name">
							<span class="btn btn-default btn-file btn-sm">
							    新增相片 <input type="file" name="pic[]" id="uploadpic" multiple>
							</span>
						    <button class="btn btn-sm btn-default editPhoto" style="margin-bottom: 5px; display:none" type="button" proid="">編輯相片</button>
							<input name="pro_id" id="pro_id" value="-1" type="hidden"/>
						</td>
					</tr>
					<tr>
						<td>產品上架時間</td>
						<td>
							<div class="col-xs-2">
								<input type="text" class="form-control input-sm" name="pro_add_time" id="pro_add_time" readonly='true'/>&nbsp;&nbsp;
							</div>
							<div class="col-xs-2">
								<select name="pro_add_h" class="form-control input-sm">
									<option vale="00">00</option>
									<option vale="01">01</option>
									<option vale="02">02</option>
									<option vale="03">03</option>
									<option vale="04">04</option>
									<option vale="05">05</option>
									<option vale="06">06</option>
									<option vale="07">07</option>
									<option vale="08">08</option>
									<option vale="09">09</option>
									<option vale="10">10</option>
									<option vale="11">11</option>
									<option vale="12">12</option>
									<option vale="13">13</option>
									<option vale="14">14</option>
									<option vale="15">15</option>
									<option vale="16">16</option>
									<option vale="17">17</option>
									<option vale="18">18</option>
									<option vale="19">19</option>
									<option vale="20">20</option>
									<option vale="21">21</option>
									<option vale="22">22</option>
									<option vale="23">23</option>
								</select>
							</div>
							<div class="col-xs-1"><h5>時</h5></div>
							<div class="col-xs-2">
							<select name="pro_add_m" class="form-control input-sm">
								<option vale="00">00</option>
								<option vale="10">10</option>
								<option vale="20">20</option>
								<option vale="30">30</option>
								<option vale="40">40</option>
								<option vale="50">50</option>
							</select>
							</div>
							<div class="col-xs-1"><h5>分</h5></div>
						</td>
					</tr>
					<tr>
						<td>產品下架時間</td>
						<td>
							<div class="col-xs-2">
								<input type="text" class="form-control input-sm" name="pro_off_time" id="pro_off_time" readonly='true'/>&nbsp;&nbsp;
							</div>
							<div class="col-xs-2">
								<select name="pro_off_h" class="form-control input-sm">
									<option vale="00">00</option>
									<option vale="01">01</option>
									<option vale="02">02</option>
									<option vale="03">03</option>
									<option vale="04">04</option>
									<option vale="05">05</option>
									<option vale="06">06</option>
									<option vale="07">07</option>
									<option vale="08">08</option>
									<option vale="09">09</option>
									<option vale="10">10</option>
									<option vale="11">11</option>
									<option vale="12">12</option>
									<option vale="13">13</option>
									<option vale="14">14</option>
									<option vale="15">15</option>
									<option vale="16">16</option>
									<option vale="17">17</option>
									<option vale="18">18</option>
									<option vale="19">19</option>
									<option vale="20">20</option>
									<option vale="21">21</option>
									<option vale="22">22</option>
									<option vale="23">23</option>
								</select>
							</div>
							<div class="col-xs-1"><h5>時</h5></div>
							<div class="col-xs-2">
							<select name="pro_off_m" class="form-control input-sm">
								<option vale="00">00</option>
								<option vale="10">10</option>
								<option vale="20">20</option>
								<option vale="30">30</option>
								<option vale="40">40</option>
								<option vale="50">50</option>
							</select>
							</div>
							<div class="col-xs-1"><h5>分</h5></div>
						</td>
					</tr>
					<tr>
						<td>持續上架</td>
						<td> 
							<input type="checkbox" name="always_available"
								<?php if ($row->always_available == 1): ?>
									checked
								<?php endif ?>
							 />						 
						</td>
					</tr>
					<tr>
						<td>產品說明</td>
						<td>
							<textarea name="pro_desc" id="pro_desc"></textarea>
						</td>
					</tr>
					<tr style="display:none">
						<td>產品規格</td>
						<td>
							<textarea name="pro_format" id="pro_format"></textarea>
						</td>
					</tr>
					<tr style="display:none">
						<td>產品運輸說明</td>
						<td>
							<textarea name="pro_ship_note" id="pro_ship_note"></textarea>
						</td>
					</tr>
					<tr>
						<td>產品狀態</td>
						<td>
							<div class="col-xs-2">
							<select name="pro_status" class="form-control input-sm">
								<?php 
									foreach($pro_status_results as $status_row)
									{
								?>
										<option value="<?php echo $status_row->code_key?>"><?php echo $status_row->code_name?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>促銷狀態</td>
						<td>
							<div class="col-xs-3">
							<select name="pro_promote" class="form-control input-sm">
								<option value="">無</option>
								<?php 
									foreach($pro_promote_results as $promote_row)
									{
								?>
										<option value="<?php echo $promote_row->code_key?>"><?php echo $promote_row->code_name?></option>
								<?php
									}
								?>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">SEO Setting</td>
					</tr>
					<tr>
						<td>Seo Title</td>
						<td>
							<div class="col-xs-6">
							<textarea name="seo_title" id="seo_title" class="form-control" rows="3"></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td>Seo Keyword</td>
						<td>
							<div class="col-xs-6">
								<textarea name="seo_kw" id="seo_kw" class="form-control" rows="3"></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td>Seo Description</td>
						<td>
							<div class="col-xs-6">
								<textarea name="seo_desc" id="seo_desc" class="form-control" rows="3"></textarea>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:right">
							<button class="btn btn-small btn-primary" type="submit" id="addbtn">新增</button>
							<button class="btn btn-small btn-warning" type="button" onclick="aHover('<?php echo $back_url?>')">取消</button>
						</td>
					</tr>
				</tobdy>
			</table>
		</form>
	</div>
</div>
<div style='display:none'>
	<div id="inlineUpload">
		<div class="row" style="margin: 0 0 0 20px">
			上傳進度：
			<div class="progress">
			  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
			    <span class="sr-only"><span>0</span>% Complete</span>
			  </div>
			</div>
		</div>
		<div class="row" style="margin: 0 0 0 10px">
			<table class='table table-bordered'>
				<tr>
					<th>圖片像素說明</th>
					<td>
						首頁橫幅：width:1000px, height:297px<br />
						產品封面：width:240px, height:194px<br />
						產品內頁：width:640px, height:480px<br />
					</td>
				</tr>
			</table>
		</div>
		<div class="row rowimgs" style="margin: 0 0 0 10px">
		<script id='img_template'  type="text/x-dot-template">
			{{for(var i=0, l=it.imgData.length; i<l; i++){ }}
	        	<div class='col-md-4'>
		        	<table class='imgtable table-bordered'>
			        	<tr><td><div style='width:182px; height: 150px; background-image: url("<?php echo $img_upload_path?>{{= it.imgData[i].name}}");background-size:cover;background-position:50% 50%;'></div></td></tr>
			        	<tr><td><input type='text' name='ga_name[]' placeholder='圖片標題' class='form-control input-sm tdinput'></td></tr>
			        	<tr><td><input type='text' name='ga_desc[]' placeholder='圖片敘述' class='form-control input-sm tdinput'></td></tr>
			        	<!--<tr><td><input type='text' name='ga_w[]' placeholder='圖片Width' class='form-control input-sm tdinput'></td></tr>
			        	<tr><td><input type='text' name='ga_h[]' placeholder='圖片height' class='form-control input-sm tdinput'></td></tr>-->
			        	<tr class='radio_cover'>
			        		<td>
			        			<!--
				        		<label class="radio">
				        			<input type='radio' name='pro_cover_photo' value='{{= it.imgData[i].ga_id}}'>封面
				        		</label>
				        		-->
				        		<span gaid='{{= it.imgData[i].ga_id}}'>封面</span>
			        		</td>
			        	</tr>
			        	<tr class='radio_text'>
			        		<td>
			        			<!--
				        		<label class="radio">
				        			<input type='radio' name='pro_text_photo'  value='{{= it.imgData[i].ga_id}}'>內文
				        		</label>
				        		-->
				        		<span gaid='{{= it.imgData[i].ga_id}}'>內文</span>
			        		</td>
			        	</tr>
			        	<tr class='radio_ad'>
			        		<td>
				        		<span gaid='{{= it.imgData[i].ga_id}}'>廣告橫幅</span>
			        		</td>
			        	</tr>
			        	<tr>
			        		<td>
			        			
			        				<input type='text' name='ga_url[]' class='form-control input-sm tdinput' value='<?php echo $base_url;?>{{= it.imgData[i].ga_url}}'>
			        			
			        		</td>
			        	</tr>
		        	</table>
	        	</div>
			{{ } }}
		</script>
		</div>
		<div class="row imgsbuttons" style="text-align:center; margin: 20px 0 0 0;">
			<button class="btn btn-sm btn-default" id="upload_ok" gaids="" proid="">確定</button>
			<button class="btn btn-sm btn-default" id="cancel_upload" gaids="">取消</button>
		</div>
	</div>
</div>
<div style='display:none'>
	<div id="editPhotoArea">
		<h3>編輯相簿</h3>
		<div class="row rowimgs" style="margin: 0 0 0 10px">
			<div class="row" style="margin: 0 0 0 10px">
				<table class='table table-bordered'>
					<tr>
						<th>圖片像素說明</th>
						<td>
							首頁橫幅：width:1000px, height:297px<br />
							產品封面：width:240px, height:194px<br />
							產品內頁：width:640px, height:480px<br />
						</td>
					</tr>
				</table>
			</div>
			<script id='edit_photo_template'  type="text/x-dot-template">
				{{for(var i=0, l=it.ga_data.photo_data.length; i<l; i++){ }}
		        	<div class='col-md-4'>
			        	<table class='imgtable table-bordered' ga_id='{{= it.ga_data.photo_data[i].ga_id}}'>
				        	<tr><td><div style='width:182px; height: 182px; background-image: url("<?php echo $base_url?>{{= it.ga_data.photo_data[i].ga_url}}");background-size:cover;background-position:50% 50%;'></div></td></tr>
				        	<tr><td><input type='text' name='ga_name[]' placeholder='圖片標題' class='form-control input-sm tdinput' value='{{= it.ga_data.photo_data[i].ga_name}}'></td></tr>
				        	<tr><td><input type='text' name='ga_desc[]' placeholder='圖片敘述' class='form-control input-sm tdinput' value='{{= it.ga_data.photo_data[i].ga_desc}}'></td></tr>
				        	<!--<tr><td><input type='text' name='ga_w[]' placeholder='圖片Width' class='form-control input-sm tdinput' value='{{= it.ga_data.photo_data[i].ga_w}}'></td></tr>
				        	<tr><td><input type='text' name='ga_h[]' placeholder='圖片height' class='form-control input-sm tdinput' value='{{= it.ga_data.photo_data[i].ga_h}}'></td></tr>-->
				        	<tr class='radio_cover' {{? it.ga_data.photo_data[i].ga_id === it.ga_data.cover_text.pro_cover_photo}} checked='checked' {{?}}>
				        		<td>
					        		<span gaid='{{= it.ga_data.photo_data[i].ga_id}}'>封面</span>
				        		</td>
				        	</tr>
				        	<tr class='radio_text' {{? it.ga_data.photo_data[i].ga_id === it.ga_data.cover_text.pro_text_photo}} checked='checked' {{?}}>
				        		<td>
					        		<span gaid='{{= it.ga_data.photo_data[i].ga_id}}'>內文</span>
				        		</td>
				        	</tr>
				        	<tr class='radio_ad' {{? it.ga_data.photo_data[i].ga_id === it.ga_data.cover_text.pro_ad_photo}} checked='checked' {{?}}>
				        		<td>
					        		<span gaid='{{= it.ga_data.photo_data[i].ga_id}}'>廣告橫幅</span>
				        		</td>
				        	</tr>
				        	<tr>
				        		<td>
				        			<input type='text' name='ga_url[]' class='form-control input-sm tdinput' value='<?php echo $base_url;?>{{= it.ga_data.photo_data[i].ga_url}}'>
				        		</td>
				        	</tr>
				        	<tr>
				        		<td>
									<label class="checkbox">
										<input type='checkbox' name='ga_del_id[]' value='{{= it.ga_data.photo_data[i].ga_id}}'> 刪除
									</label>
				        		</td>
				        	</tr>
			        	</table>
		        	</div>
				{{ } }}
			</script>
		</div>
		<div class="row imgsbuttons" style="text-align:center; margin: 20px 0 0 0;">
			<button class="btn btn-small btn-default" id="edit_ok" gaids="" proid="">確定</button>
			<button class="btn btn-small btn-default" id="cancel_edit" gaids="">取消</button>
		</div>
	</div>
</div>
<div style='display:none'>
	<div id="choosePlanArea">
		<h1>新增</h1>
		<div class="row blockText" style="display:none; margin: 0 0 10px 10px; width:150px; height: 30px; background-color: rgba(0, 0, 0, .8); color:#fff; text-align:center; line-height: 28px;">
			<span>新增成功</span>
		</div>
		<div class="row" style="margin: 0 0 0 10px">
			<table class="table table-bordered">
				<tr style="display:none">
					<td>方案描述</td>
					<td>
						<div class="col-xs-10">
						<textarea name="plan_desc" id="paln_desc" class="form-control" rows="3">方案一</textarea>
						</div>
					</td>
				</tr>
				<tr>
					<td>價錢</td>
					<td>
						<div class="col-xs-3">
							<input type="text" name="plan_price" class="form-control input-sm" value="">
						</td>
				</tr>
				<tr>
					<td>數量</td>
					<td>
						<div class="col-xs-3">
							<input type="text" name="plan_num" class="form-control input-sm" value="">
						</div>
					</td>
				</tr>
				<tr style="display:none">
					<td>方案順序</td>
					<td>
						<div class="col-xs-3">
							<input type="text" name="plan_seq" class="form-control input-sm" value="99">
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="row" style="text-align:center; margin: 20px 0 0 0;">
			<button class="btn btn-small btn-default" id="add_plan" gaids="" url="<?php echo $add_plan_url?>">新增</button>
			<button class="btn btn-small btn-default" id="cancel_plan" gaids="">取消</button>
		</div>
	</div>
</div>
<script>
	function aHover(url)
	{
		location.href = url;
	}
</script>