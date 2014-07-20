<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Member_manage extends Fuel_base_controller {
	public $view_location = 'member';
	public $nav_selected = 'member/manage';
	
	function __construct()
	{
		parent::__construct();
		$this->_validate_user('member/manage');
		$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');
		$this->load->helper('ajax');
		$this->load->library('pagination');
		$this->load->library('set_page');
	}
	
	function lists($dataStart=0)
	{
		$base_url = base_url();

		$act = $this->input->get_post("act");
		$search_item = $this->input->get_post("search_item");
		$filter = "";

		if($act)
		{
			switch ($act) {
				case 'by_name':
					$filter = " WHERE member_name LIKE '%".$search_item."%'";
					break;
				case 'by_email':
					$filter = " WHERE member_account LIKE '%".$search_item."%'";
					break;	
				default:
					$filter = " WHERE member_name LIKE %'".$search_item."'%";
					break;
			}
		}
		
		$target_url = $base_url.'fuel/member/lists/';

		$total_rows = $this->member_manage_model->get_total_rows($filter);
		$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 20);
		$dataLen = $config['per_page'];
		$this->pagination->initialize($config);

		$member_results = $this->member_manage_model->get_member_list($dataStart, $dataLen, $filter);

		$vars['pagination'] = $this->pagination->create_links();
		$vars['create_url'] = $base_url.'fuel/member/create';
		$vars['edit_url'] = $base_url.'fuel/member/edit/';
		$vars['del_url'] = $base_url.'fuel/member/del/';
		$vars['multi_del_url'] = $base_url.'fuel/member/do_multi_del';
		$vars['member_results'] = $member_results;
		$vars['search_url'] = $base_url.'fuel/member/lists';
		$vars['CI'] = & get_instance();

		$this->_render('_admin/member_lists_view', $vars);

	}

	function create()
	{
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$view_name = "新增會員";
		$base_url = base_url();

		$city_result = $this->product_manage_model->get_code('city', ' AND parent_id=-1 ORDER BY code_key ASC');
		$vars['view_name'] = $view_name;
		$vars['city_result'] = $city_result;
		$vars['submit_url'] = $base_url."fuel/member/do_create";
		$vars['back_url'] = $base_url."fuel/member/lists";
		$vars['CI'] = & get_instance();

		$this->_render('_admin/member_create_view', $vars, 'member');
	}

	function do_create()
	{
		$base_url = base_url();
		
		$member_account = $this->input->get_post("member_account");
		$member_pass = $this->input->get_post("member_pass");
		$member_chk_pass = $this->input->get_post("member_chk_pass");
		$member_name = $this->input->get_post("member_name");
		$member_mobile = $this->input->get_post("member_mobile");
		$member_city = $this->input->get_post("member_city");
		$member_addr = $this->input->get_post("member_addr");
		$vat_num = $this->input->get_post("vat_num");
		$inv_title = $this->input->get_post("inv_title");

		if($member_pass != $member_chk_pass)
		{
			$this->plu_redirect($base_url."fuel/member/lists", 0, "密碼不一致");
			die();
		}

		$success = $this->member_manage_model->do_add_member($member_account, $member_pass, $member_name, $member_mobile, $member_city, $member_addr, $vat_num, $inv_title);

		if($success)
		{
			$this->plu_redirect($base_url."fuel/member/lists", 0, "新增成功");
			die();
		}
	}

	function edit($member_id)
	{
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$view_name = "修改訂單";
		$base_url = base_url();

		$member_results = $this->member_manage_model->get_member_detail($member_id);
		$city_result = $this->product_manage_model->get_code('city', ' AND parent_id=-1 ORDER BY code_key ASC');

		$vars['view_name'] = $view_name;
		$vars['member_results'] = $member_results;
		$vars['city_result'] = $city_result;
		$vars['back_url'] = $base_url."fuel/member/lists";
		$vars['submit_url'] = $base_url."fuel/member/do_edit/".$member_id;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/member_edit_view', $vars, 'member');
	}

	function do_edit($member_id)
	{
		$base_url = base_url();
		
		$member_account = $this->input->get_post("member_account");
		$member_name = $this->input->get_post("member_name");
		$member_mobile = $this->input->get_post("member_mobile");
		$member_city = $this->input->get_post("member_city");
		$member_addr = $this->input->get_post("member_addr");
		$vat_num = $this->input->get_post("vat_num");
		$inv_title = $this->input->get_post("inv_title");

		$member_pass = $this->input->get_post("member_pass");
		$member_chk_pass = $this->input->get_post("member_chk_pass");

		if(!empty($member_pass))
		{
			if($member_pass == $member_chk_pass)
			{
				$success = $this->member_manage_model->update_pwd($member_pass, $member_id);
			}
			else
			{
				$this->plu_redirect($base_url."fuel/member/edit/".$member_id, 0, "密碼不一致");
				die();			
			}			
		}


		$success = $this->member_manage_model->do_edit_member($member_id, $member_account, $member_name, $member_mobile, $member_addr, $vat_num, $inv_title, $member_city);

		if($success)
		{
			$this->plu_redirect($base_url."fuel/member/lists", 0, "修改成功");
			die();
		}
	}

	function do_del($member_id)
	{
		$result = array();

		$success = $this->member_manage_model->do_del_member($member_id);

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

		$member_ids = $this->input->get_post("member_ids");

		if($member_ids)
		{
			$im_member_ids = implode(",", $member_ids);

			$success = $this->member_manage_model->do_multi_del_member($im_member_ids);
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
			$result['status'] = $im_member_ids;
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