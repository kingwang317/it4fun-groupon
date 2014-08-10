<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Product_manage extends Fuel_base_controller {
	public $view_location = 'member';
	public $nav_selected = 'member/manage';
	
	function __construct()
	{
		parent::__construct();
		$this->_validate_user('product/manage');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('upload');
		$this->load->library('pagination');
		$this->load->library('set_page');
	}
	
	function lists($dataStart=0)
	{
		$base_url = base_url();

		$this->uri->init_get_params();	//才能收到$_GET參數
		$search_item = $this->input->get_post("search_item");
		$search_txt = $this->input->get_post("search_txt");
		$filter = "";

		if($search_item)
		{
			switch($search_item)
			{
				case 'pro_name':
					$filter = " WHERE pro_name LIKE '%".$search_txt."%'";
					break;
			}
		}

		$target_url = $base_url.'fuel/product/lists';
		$total_rows = $this->product_manage_model->get_total_rows($filter);
		$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 20);
		$dataLen = $config['per_page'];
		$this->pagination->initialize($config); 

		$product_results = $this->product_manage_model->get_product_list($dataStart, $dataLen, $filter);

		$vars['pagination'] = $this->pagination->create_links();
		$vars['create_url'] = $base_url.'fuel/product/create';
		$vars['edit_url'] = $base_url.'fuel/product/edit/';
		$vars['del_url'] = $base_url.'fuel/product/del/';
		$vars['multi_del_url'] = $base_url.'fuel/product/do_multi_del';
		$vars['product_results'] = $product_results;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/product_lists_view', $vars, 'product');

	}

	function upload_files()
	{
		$base_url = base_url();
		$pro_id = $this->input->get_post("pro_id");

		$files = $_FILES;
		$cpt = count($_FILES['pic']['name']);

		if($pro_id < 0)
		{
			$pro_id = $this->product_manage_model->do_empty_product();
		}
		
		//$pro_id = 0;
		$response = array();

		for($i=0; $i<$cpt; $i++)
		{
			$rand_num= mt_rand();

			$name = "pro_".$pro_id."_".$i."_".$rand_num.substr($files["pic"]["name"][$i], strpos($files["pic"]["name"][$i], "."));

			$_FILES['pic']['name']=  $name;
			$_FILES['pic']['type']= $files['pic']['type'][$i];
			$_FILES['pic']['tmp_name']= $files['pic']['tmp_name'][$i];
			$_FILES['pic']['error']= $files['pic']['error'][$i];
			$_FILES['pic']['size']= $files['pic']['size'][$i];    

			$this->upload->initialize($this->set_upload_options());

			if (!$this->upload->do_upload('pic')) 
			{
			    $error = array('error' => $this->upload->display_errors());
			    $response[$i]['error'] = $error;
			} 
			else 
			{
				$ga_data['ga_path'] = assets_server_path('uploads/products/'.$name);
				$ga_data['ga_url'] = "assets/uploads/products/".$name;
				$ga_data['prog_id'] = "product";
				$ga_data['f_id'] = $pro_id;

				$ga_id = $this->product_manage_model->insert_photo($ga_data);

				$response['imgData'][$i]['name'] = $name;
				$response['imgData'][$i]['ga_id'] = $ga_id;
				$response['imgData'][$i]['ga_url'] = $base_url."assets/uploads/products/".$name;
			}
		}

		$response['pro_id'] = $pro_id;
		$response['post_id'] = $this->input->get_post("pro_id");

		if(is_ajax())
		{
			echo json_encode($response);
		}

	}

	private function set_upload_options()
	{   
		//  upload an image options
		$config = array();
		$config['upload_path'] = assets_server_path('uploads/products/');
		$config['allowed_types'] = 'gif|jpg|png|JPG';
		$config['max_size']      = '30000000';
		$config['overwrite']     = FALSE;

		return $config;
	}

	function del_photos()
	{
		$upload_path = assets_server_path('uploads/products/');
		$ga_ids = $this->input->get_post('ga_ids');

		$result = $this->product_manage_model->get_ga_path($ga_ids);

		$success = $this->product_manage_model->do_del_photos($ga_ids);

		if($success)
		{
			if(isset($result))
			{
				foreach($result as $row)
				{
					if(file_exists($row->ga_path))
					{
						unlink($row->ga_path);
					}
				}
			}
			$response['status'] = 1;
		}
		else
		{
			$response['status'] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function update_photo_data()
	{
		$ga_ids = $this->input->get_post('ga_ids');
		$ga_name = $this->input->get_post('ga_name');
		$ga_desc = $this->input->get_post('ga_desc');
		$ga_w = $this->input->get_post('ga_w');
		$ga_h = $this->input->get_post('ga_h');
		$ga_del_id = $this->input->get_post('ga_del_id');

		$pro_id = $this->input->get_post('pro_id');
		$pro_cover_photo = $this->input->get_post('pro_cover_photo');
		$pro_text_photo = $this->input->get_post('pro_text_photo');
		$pro_ad_photo = $this->input->get_post('pro_ad_photo');

		if(isset($ga_ids))
		{
			$ga_num = count($ga_ids);

			for($i=0; $i<$ga_num; $i++)
			{
				$success = $this->product_manage_model->do_update_photo_data($ga_ids[$i], $ga_name[$i], $ga_desc[$i], $ga_w[$i], $ga_h[$i]);
			}

			if(!empty($pro_cover_photo))
			{
				$this->product_manage_model->do_update_pro_cover_photo($pro_cover_photo, $pro_id);
			}
			else
			{
				$this->product_manage_model->do_update_pro_cover_photo(NULL, $pro_id);
			}
			
			if(!empty($pro_text_photo))
			{
				$this->product_manage_model->do_update_pro_text_photo($pro_text_photo, $pro_id);
			}
			else
			{
				$this->product_manage_model->do_update_pro_text_photo(NULL, $pro_id);
			}

			if(!empty($pro_ad_photo))
			{
				$this->product_manage_model->do_update_pro_ad_photo($pro_ad_photo, $pro_id);
			}
			else
			{
				$this->product_manage_model->do_update_pro_ad_photo(NULL, $pro_id);
			}

			if(!empty($ga_del_id))
			{
				$im_id = implode(",", $ga_del_id);

				$result = $this->product_manage_model->get_ga_path($im_id);

				$success = $this->product_manage_model->do_del_photos($im_id);

				if($success)
				{
					if(isset($result))
					{
						foreach($result as $row)
						{
							if(file_exists($row->ga_path))
							{
								unlink($row->ga_path);
							}
						}
					}
				}
			}

			if($success)
			{
				$response['status'] = 1;
			}
			else
			{
				$response['status'] = -1;
			}
		}
		else
		{
			$response['status'] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function get_photo_data()
	{
		$pro_id = $this->input->get_post("pro_id");

		if(isset($pro_id))
		{
			$ga_data = $this->product_manage_model->get_photo_data_detail($pro_id, "product");

			$response['status'] = 1;
			$response['ga_data'] = $ga_data;
		}
		else
		{
			$response['status'] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function get_photo_data_to_ck($pro_id)
	{
		//$pro_id = $this->input->get_post("pro_id");
		$base_url = base_url();

		$response = array();

		$ga_data = $this->product_manage_model->get_photo_data_detail($pro_id, "product");

		foreach ($ga_data['photo_data'] as $key => $row) {
			$response[$key] = new stdClass();
			$response[$key]->image = $base_url.$row->ga_url;
			$response[$key]->thumb = $base_url.$row->ga_url;
			$response[$key]->folder = "products";
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function create()
	{
		$view_name = "新增產品";
		$base_url = base_url();

		$pro_cate_results = $this->product_manage_model->get_code('product_cate', ' AND parent_id<>-1 ORDER BY code_key ASC');
		$pro_status_results = $this->product_manage_model->get_code('pro_status', ' ORDER BY code_key ASC');

		$vars['view_name'] = $view_name;

		$vars['submit_url'] = $base_url."fuel/product/do_create";
		$vars['upload_path'] = $base_url."fuel/product/upload_files";
		$vars['del_photos_url'] = $base_url."fuel/product/del_photos";
		$vars['get_photo_data_url'] = $base_url."fuel/product/get_photo_data";
		$vars['get_photo_data_to_ck_url'] = $base_url."fuel/product/ckphoto/";
		$vars['update_photo_url'] = $base_url."fuel/product/update_photo_data";
		$vars['upload_swf'] = $base_url."fuel/modules/product/assets/uploadify.swf";
		$vars['img_upload_path'] = $base_url."assets/uploads/products/";
		$vars['plan_detail_url'] = $base_url."fuel/product/plan_detail";
		$vars['add_plan_url'] = $base_url."fuel/product/add_plan";
		$vars['edit_plan_url'] = $base_url."fuel/product/update_plan/";
		$vars['del_plan_url'] = $base_url."fuel/product/del_plan";
		$vars['back_url'] = $base_url."fuel/product/lists";
		$vars['chk_plan_url'] = $base_url."fuel/product/chk_plan/";
		$vars['pro_cate_results'] = $pro_cate_results;
		$vars['pro_status_results'] = $pro_status_results;
		$vars['base_url'] = $base_url;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/product_create_view', $vars, 'product');
	}

	function do_create()
	{
		$base_url = base_url();
		$r_data = array();
		
		$r_data['pro_id'] = $this->input->get_post("pro_id");
		$r_data['pro_name'] = $this->input->get_post("pro_name");
		$r_data['pro_summary'] = $this->input->get_post("pro_summary");
		$r_data['pro_start_sell'] = $this->input->get_post("pro_start_sell");
		$r_data['pro_cate'] = $this->input->get_post("pro_cate");

		$pro_add_time= $this->input->get_post("pro_add_time");
		$pro_add_h = $this->input->get_post("pro_add_h");
		$pro_add_m = $this->input->get_post("pro_add_m");
		$r_data['pro_add_time'] = $pro_add_time." ".$pro_add_h.":".$pro_add_m.":"."00";

		$pro_off_time= $this->input->get_post("pro_off_time");
		$pro_off_h = $this->input->get_post("pro_off_h");
		$pro_off_m = $this->input->get_post("pro_off_m");
		$r_data['pro_off_time'] = $pro_off_time." ".$pro_off_h.":".$pro_off_m.":"."00";

		$r_data['pro_desc'] = htmlspecialchars($this->input->get_post("pro_desc"));
		$r_data['pro_format'] = htmlspecialchars($this->input->get_post("pro_format"));
		$r_data['pro_ship_note'] = htmlspecialchars($this->input->get_post("pro_ship_note"));
		$r_data['pro_original_price'] = $this->input->get_post("pro_original_price");
		$r_data['pro_group_price'] = $this->input->get_post("pro_group_price");
		$r_data['pro_order'] = $this->input->get_post("pro_order");
		$r_data['pro_status'] = $this->input->get_post("pro_status");
		$r_data['pro_status'] = $this->input->get_post("pro_status");
		$r_data['seo_title'] = $this->input->get_post("seo_title");
		$r_data['seo_kw'] = $this->input->get_post("seo_kw");
		$r_data['seo_desc'] = $this->input->get_post("seo_desc");

		$r_data['plan_id'] = $this->input->get_post("plan_id");

		$r_data['pro_pic_s_name'] = $this->input->get_post("pro_pic_s_name");
		$r_data['pro_pic_s_desc'] = $this->input->get_post("pro_pic_s_desc");
		$r_data['pro_pic_s_w'] = $this->input->get_post("pro_pic_s_w");
		$r_data['pro_pic_s_h'] = $this->input->get_post("pro_pic_s_h");

		$r_data['pro_pic_l_name'] = $this->input->get_post("pro_pic_l_name");
		$r_data['pro_pic_l_desc'] = $this->input->get_post("pro_pic_l_desc");
		$r_data['pro_pic_l_w'] = $this->input->get_post("pro_pic_l_w");
		$r_data['pro_pic_l_h'] = $this->input->get_post("pro_pic_l_h");

		//$files = $_FILES;

		if($r_data['pro_id'] > 0  && isset($r_data['pro_id']))
		{
			$success = $this->product_manage_model->do_update_product($r_data);
			/*

			$ga_id = $this->upload_photo('pro_pic_s', $files, $r_data['pro_id']);
			if($ga_id)
			{
				$this->product_manage_model->do_update_photo_data($ga_id, $r_data['pro_pic_s_name'], $r_data['pro_pic_s_desc'], $r_data['pro_pic_s_w'], $r_data['pro_pic_s_h']);
			}
			
			$this->upload_photo('pro_pic_l', $files, $r_data['pro_id']);
			if($ga_id)
			{
				$this->product_manage_model->do_update_photo_data($ga_id, $r_data['pro_pic_l_name'], $r_data['pro_pic_l_desc'], $r_data['pro_pic_l_w'], $r_data['pro_pic_l_h']);
			}
			*/
		}
		else
		{
			$success = $this->product_manage_model->do_add_product($r_data);
			/*
			$ga_id = $this->upload_photo('pro_pic_s', $files, $success);
			if($ga_id)
			{
				$this->product_manage_model->do_update_photo_data($ga_id, $r_data['pro_pic_s_name'], $r_data['pro_pic_s_desc'], $r_data['pro_pic_s_w'], $r_data['pro_pic_s_h']);
			}

			$ga_id = $this->upload_photo('pro_pic_l', $files, $success);
			if($ga_id)
			{
				$this->product_manage_model->do_update_photo_data($ga_id, $r_data['pro_pic_l_name'], $r_data['pro_pic_l_desc'], $r_data['pro_pic_l_w'], $r_data['pro_pic_l_h']);
			}
			*/
		}
		

		if($success)
		{
			$this->plu_redirect($base_url."fuel/product/lists", 0, "新增成功");
			die();
		}
	}

	function upload_photo($upload_name, $files, $pro_id)
	{
		$rand_num= mt_rand();
		$base_url = base_url();

		$name = "pro_".$pro_id."_".$rand_num.substr($files[$upload_name]["name"], strpos($files[$upload_name]["name"], "."));

		$_FILES[$upload_name]['name']=  $name;
		$_FILES[$upload_name]['type']= $files[$upload_name]['type'];
		$_FILES[$upload_name]['tmp_name']= $files[$upload_name]['tmp_name'];
		$_FILES[$upload_name]['error']= $files[$upload_name]['error'];
		$_FILES[$upload_name]['size']= $files[$upload_name]['size'];

		$this->upload->initialize($this->set_upload_options());

		if (!$this->upload->do_upload($upload_name)) 
		{
		    $error = array('error' => $this->upload->display_errors());

		} 
		else 
		{
			$ga_data['ga_path'] = assets_server_path('uploads/products/'.$name);
			$ga_data['ga_url'] = "assets/uploads/products/".$name;
			$ga_data['prog_id'] = "product";
			$ga_data['f_id'] = $pro_id;

			$ga_id = $this->product_manage_model->insert_photo($ga_data);

			return $ga_id;
		}

		return;
	}

	function edit($pro_id)
	{
		$view_name = "修改產品";
		$base_url = base_url();

		$pro_result = $this->product_manage_model->get_pro_detail($pro_id);

		$pro_cate_results = $this->product_manage_model->get_code('product_cate', ' AND parent_id<>-1 ORDER BY code_key ASC');
		$pro_promote_results = $this->product_manage_model->get_code('promot_status', ' AND parent_id<>-1 ORDER BY code_key ASC');
		$pro_status_results = $this->product_manage_model->get_code('pro_status', ' ORDER BY code_key ASC');
		$pro_plan_results = $this->product_manage_model->get_plan_for_pro_id($pro_id);
		$have_photo = $this->product_manage_model->get_pro_photo($pro_id);

		$vars['view_name'] = $view_name;

		$vars['submit_url'] = $base_url."fuel/product/do_edit/".$pro_id;
		$vars['upload_path'] = $base_url."fuel/product/upload_files";
		$vars['del_photos_url'] = $base_url."fuel/product/del_photos";
		$vars['get_photo_data_url'] = $base_url."fuel/product/get_photo_data";
		$vars['get_photo_data_to_ck_url'] = $base_url."fuel/product/ckphoto/".$pro_id;
		$vars['update_photo_url'] = $base_url."fuel/product/update_photo_data";
		$vars['upload_swf'] = $base_url."fuel/modules/product/assets/uploadify.swf";
		$vars['img_upload_path'] = $base_url."assets/uploads/products/";
		$vars['add_plan_url'] = $base_url."fuel/product/add_plan";
		$vars['edit_plan_url'] = $base_url."fuel/product/update_plan/";
		$vars['del_plan_url'] = $base_url."fuel/product/del_plan";
		$vars['plan_detail_url'] = $base_url."fuel/product/plan_detail";
		$vars['back_url'] = $base_url."fuel/product/lists";
		$vars['pro_result'] = $pro_result;
		$vars['pro_cate_results'] = $pro_cate_results;
		$vars['pro_promote_results'] = $pro_promote_results;
		$vars['pro_status_results'] = $pro_status_results;
		$vars['pro_plan_results'] = $pro_plan_results;
		$vars['have_photo'] = $have_photo;
		$vars['pro_id'] = $pro_id;
		$vars['base_url'] = $base_url;

		$vars['CI'] = & get_instance();

		$this->_render('_admin/product_edit_view', $vars, 'product');
	}

	function do_edit($pro_id)
	{
		$base_url = base_url();
		$r_data = array();
		
		$r_data['pro_id'] = $pro_id;
		$r_data['pro_name'] = $this->input->get_post("pro_name");
		$r_data['pro_summary'] = htmlspecialchars($this->input->get_post("pro_summary"));
		$r_data['pro_start_sell'] = $this->input->get_post("pro_start_sell");
		$r_data['pro_cate'] = $this->input->get_post("pro_cate");

		$pro_add_time= $this->input->get_post("pro_add_time");
		$pro_add_h = $this->input->get_post("pro_add_h");
		$pro_add_m = $this->input->get_post("pro_add_m");
		$r_data['pro_add_time'] = $pro_add_time." ".$pro_add_h.":".$pro_add_m.":"."00";

		$pro_off_time= $this->input->get_post("pro_off_time");
		$pro_off_h = $this->input->get_post("pro_off_h");
		$pro_off_m = $this->input->get_post("pro_off_m");
		$r_data['pro_off_time'] = $pro_off_time." ".$pro_off_h.":".$pro_off_m.":"."00";

		$r_data['pro_desc'] = htmlspecialchars($this->input->get_post("pro_desc"));
		$r_data['pro_format'] = htmlspecialchars($this->input->get_post("pro_format"));
		$r_data['pro_ship_note'] = htmlspecialchars($this->input->get_post("pro_ship_note"));
		$r_data['pro_original_price'] = $this->input->get_post("pro_original_price");
		$r_data['pro_group_price'] = $this->input->get_post("pro_group_price");
		$r_data['pro_order'] = $this->input->get_post("pro_order");
		$r_data['pro_status'] = $this->input->get_post("pro_status");
		$r_data['pro_promote'] = $this->input->get_post("pro_promote");
		$r_data['seo_title'] = $this->input->get_post("seo_title");
		$r_data['seo_kw'] = $this->input->get_post("seo_kw");
		$r_data['seo_desc'] = $this->input->get_post("seo_desc");

		$r_data['plan_id'] = $this->input->get_post("plan_id");

		$success = $this->product_manage_model->do_update_product($r_data);

		if($success)
		{
			$this->plu_redirect($base_url."fuel/product/lists", 0, "修改成功");
			die();
		}		
	}

	function add_plan()
	{
		$plan_desc = $this->input->get_post("plan_desc");
		$plan_price = $this->input->get_post("plan_price");
		$plan_num = $this->input->get_post("plan_num");
		$plan_seq = $this->input->get_post("plan_seq");
		$pro_id = $this->input->get_post("pro_id");

		$plan_id = $this->product_manage_model->do_add_plan($plan_desc, $plan_price, $plan_num, $plan_seq, $pro_id);

		if($plan_id)
		{
			$response['status'] = 1;
			$response['plan_id'] = $plan_id;
			$response['msg']	= "新增成功";
		}
		else
		{
			$response['status'] = -1;
			$response['msg']	= "新增失敗";
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function update_plan($plan_id)
	{
		$plan_desc = $this->input->get_post("plan_desc");
		$plan_price = $this->input->get_post("plan_price");
		$plan_num = $this->input->get_post("plan_num");
		$plan_seq = $this->input->get_post("plan_seq");

		$success = $this->product_manage_model->do_update_plan($plan_id, $plan_desc, $plan_price, $plan_num, $plan_seq);

		if($plan_id)
		{
			$response['status'] = 1;
			$response['plan_id'] = $plan_id;
			$response['msg']	= "修改成功";
		}
		else
		{
			$response['status'] = -1;
			$response['msg']	= "修改失敗";
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function plan_detail($plan_id)
	{
		$plan_row = $this->product_manage_model->get_plan_detail($plan_id);


		if(isset($plan_row))
		{
			$response['status'] = 1;
			$response['plan_row'] = $plan_row;
		}
		else
		{
			$response['status'] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}
	}

	function del_plan()
	{
		$plan_id = $this->input->get_post("plan_id");

		if($plan_id)
		{
			$success = $this->product_manage_model->do_del_plan($plan_id);

			if($success)
			{
				$response['status'] = 1;
			}
			else
			{
				$response['status'] = -1;
			}
		}
		else
		{
			$response['status'] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($response);
		}		
	}


	function do_del_pro_photo($photo_id)
	{

	}

	function do_del($pro_id)
	{
		$result = array();

		$success = $this->product_manage_model->do_del_product($pro_id);

		if($success)
		{
			$result['status'] = 1;
		}
		else
		{
			$result['status'] = -1;
		}


		if(is_ajax())
		{
			echo json_encode($result);
		}
	}

	function do_multi_del()
	{
		$result = array();

		$pro_ids = $this->input->get_post("pro_ids");

		if($pro_ids)
		{
			$im_pro_ids = implode(",", $pro_ids);

			$success = $this->product_manage_model->do_multi_del_product($im_pro_ids);
		}
		else
		{
			$success = false;
		}



		if(isset($success))
		{
			$result['status'] = 1;
		}
		else
		{
			$result['status'] = $im_pro_ids;
		}


		if(is_ajax())
		{
			echo json_encode($result);
		}
	}

	function chk_plan($pro_id=0)
	{

		if($pro_id)
		{
			$cnt = $this->product_manage_model->get_pro_plan_cnt($pro_id);

			if($cnt > 0)
			{
				$result['status'] = 1;
			}
			else
			{
				$result['status'] = -1;
			}
		}
		else
		{
			$result['status'] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($result);
		}

	}

	function plu_redirect($url, $delay, $msg)
	{
	    if( isset($msg) )
	    {
	        $this->notify($msg);
	    }

	    echo "<meta http-equiv='Refresh' content='$delay; url=$url'>";
	}

	function notify($msg)
	{
	    $msg = addslashes($msg);
	    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	    echo "<script type='text/javascript'>alert('$msg')</script>\n";
	    echo "<noscript>$msg</noscript>\n";
	    return;
	}

}