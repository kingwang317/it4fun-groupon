<?php
class Member_about extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('member_model');
		$this->load->helper('url');
		$this->load->helper('ajax');
		$this->load->library('comm');
		// $this->load->library('pagination');
		// $this->load->library('set_page');
		$this->load->module_library(FUEL_FOLDER, 'fuel_auth');
	}

	function do_login()
	{
		$result = array();

		$member_account = $this->input->get_post("member_account");
		$password = $this->input->get_post("password");
		$success = $this->fuel_auth->front_login($member_account, $password);
		if($success)
		{
		// echo("1");
			$session_key = $this->fuel_auth->get_session_namespace();
			$user_data = $this->session->userdata($session_key);
			$config = array(
				'name' => $this->fuel_auth->get_fuel_trigger_cookie_name(), 
				'value' => serialize(array('id' => $this->fuel_auth->user_data('id'), 'language' => $this->fuel_auth->user_data('language'))),
				'expire' => 0,
				'path' => WEB_PATH
			);
			set_cookie($config);
			
			if($this->fuel_auth->front_is_logined())
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
		// echo("2");
			$result['status'] = -1;
		}


		if(is_ajax())
		{
			echo json_encode($result);
		}

		die();
	}

	function do_fb_login()
	{
		$result = array();

		$member_account = "";
		$password = "";

    	$this->load->helper('cookie');
		$this->load->model('core_model');

		$data = $this->core_model->get_fb_data();


		if(isset($data['user_profile'])){

		
			$member_account = $data['user_profile']['id'];
			$password = $data['user_profile']['id'];
			$name = "";
			$fb_email = "";
			if(isset($data['user_profile']['name'])){
				$name = $data['user_profile']['name'];
			}
			if(isset($data['user_profile']['email'])){
				$fb_email = $data['user_profile']['email'];
			}

			//$this->comm->plu_redirect(site_url(), 0, "FACEBOOK登入成功");
			//die();
		}else{
			$this->comm->plu_redirect(site_url(), 0, "FACEBOOK登入失敗");
			die();
		}





		$success = $this->fuel_auth->front_login($member_account, $password);

		if(!$success){
		$member_id = $this->member_manage_model->do_add_member($fb_email, $password, $name, "", "", "", "", "",$member_account);
			if($member_id)
			{
				$success = $this->fuel_auth->front_login($fb_email, $password);
				$session_key = $this->fuel_auth->get_session_namespace();
				$user_data = $this->session->userdata($session_key);
				$config = array(
					'name' => $this->fuel_auth->get_fuel_trigger_cookie_name(), 
					'value' => serialize(array('id' => $this->fuel_auth->user_data('id'), 'language' => $this->fuel_auth->user_data('language'))),
					'expire' => 0,
					'path' => WEB_PATH
				);
				set_cookie($config);
			}
		}

		$this->comm->plu_redirect(site_url("payment"), 0, "FACEBOOK登入成功");
		die();
	}

	function do_logout()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
		$this->load->helper('cookie');
		$this->fuel_auth->logout();
		$config = array(
			'name' => $this->fuel_auth->get_fuel_trigger_cookie_name(),
			'path' => WEB_PATH
		);
		delete_cookie($config);

		redirect(site_url(), 'refresh');
	}

	function is_logined()
	{
		$result = array();

		if($this->fuel_auth->front_is_logined())
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

		die();
	}

	function add()
	{
		$this->load->module_library(FUEL_FOLDER, 'fuel_auth');
		$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');

		// $plan_id = $this->input->get_post("pro_plan");
		$user_data = $this->fuel_auth->valid_user();
		$member_id = isset($user_data['member_id'])?$user_data['member_id']:$user_data['user_name'];
		$city_result = $this->product_manage_model->get_code('city', ' AND parent_id=-1 ORDER BY code_key ASC');
		$ship_time_result = $this->product_manage_model->get_code('ship_time', ' AND parent_id=-1 ORDER BY code_key ASC');
		
		$member_result = $this->member_manage_model->get_member_detail_row($member_id);
		// $this->order_manage_model->update_plan_tmp_num($plan_id);

		$vars['get_payment_url'] = base_url()."payment/create";
		$vars['city_result'] = $city_result;
		$vars['ship_time_result'] = $ship_time_result;
		// $vars['plan_id'] = $plan_id;
		$vars['views'] = 'user';
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$vars['member_result'] = isset($member_result)?$member_result:array();
		$vars['is_logined'] = $this->fuel_auth->front_is_logined();
		$page_init = array('location' => 'user');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR	

		return;
	}

	function edit($dataStart=0)
	{
		$this->load->library('pagination');
		$this->load->module_library(PRODUCT_FOLDER, 'my_page');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');
		$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$user_data = $this->fuel_auth->valid_user();
		$member_id = isset($user_data['member_id'])?$user_data['member_id']:"";
		

		if($member_id)
		{
			
		$member_result = $this->member_manage_model->get_member_detail_row($member_id);
		$filter = " AND member_id=$member_id";
		$target_url = base_url().'ordercheck';
		$total_rows = $this->order_manage_model->get_total_rows($filter);
		$config = $this->my_page->set_config($target_url, $total_rows, $dataStart, 20);
		$dataLen = $dataStart + $config['per_page'];
		$this->pagination->initialize($config);
		$order_result = $this->order_manage_model->get_member_order($dataStart, $dataLen, $filter);
		$city_result = $this->product_manage_model->get_code('city', ' AND parent_id=-1 ORDER BY code_key ASC');
		$vars['is_logined'] = 1;
		$vars['order_result'] = $order_result;
		$vars['member_result'] = $member_result;
		$vars['city_result'] = $city_result;
		}
		else
		{
			$vars['is_logined'] = -1;
		}
		$vars['login_url'] = base_url()."user/login";
		$vars['update_url'] = base_url()."update_my_data/".$member_id;
		$vars['forgot_url'] = base_url()."forgot_pwd";
		$vars['views'] = 'user_edit';
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$vars['pagination'] = $this->pagination->create_links();
		$page_init = array('location' => 'user_edit');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}


	function order_check($dataStart=0)
	{
		$this->load->library('pagination');
		$this->load->module_library(PRODUCT_FOLDER, 'my_page');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');
		$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$user_data = $this->fuel_auth->valid_user();
		$member_id = isset($user_data['member_id'])?$user_data['member_id']:"";

		if($member_id)
		{
			
			$member_result = $this->member_manage_model->get_member_detail_row($member_id);
			$filter = " AND member_id=$member_id";
			$target_url = base_url().'ordercheck';
			$total_rows = $this->order_manage_model->get_total_rows($filter);
			$config = $this->my_page->set_config($target_url, $total_rows, $dataStart, 20);
			$dataLen = $dataStart + $config['per_page'];
			$this->pagination->initialize($config);
			$order_result = $this->order_manage_model->get_member_order($dataStart, $dataLen, $filter);
			$city_result = $this->product_manage_model->get_code('city', ' AND parent_id=-1 ORDER BY code_key ASC');
			$vars['is_logined'] = 1;
			$vars['order_result'] = $order_result;
			$vars['member_result'] = $member_result;
			$vars['city_result'] = $city_result;
		}
		else
		{
			$vars['is_logined'] = -1;
		}
		$vars['login_url'] = base_url()."user/login";
		$vars['update_url'] = base_url()."update_my_data/".$member_id;
		$vars['forgot_url'] = base_url()."forgot_pwd";
		$vars['views'] = 'order_memberedit';
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$vars['pagination'] = $this->pagination->create_links();
		$page_init = array('location' => 'order_memberedit');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}

	function do_update_member($member_id)
	{
		if(is_ajax())
		{
			$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');

			$member_account = $this->input->get_post("member_account");
			$member_name = $this->input->get_post("member_name");
			$member_city = $this->input->get_post("member_city");
			$member_mobile = $this->input->get_post("member_mobile");
			$member_addr = $this->input->get_post("member_addr");
			$vat_num = $this->input->get_post("vat_number");
			$inv_title = $this->input->get_post("invoice_title");

			$member_pass = $this->input->get_post("member_pass");
			$member_chk_pass = $this->input->get_post("member_chk_pass");

			$success = $this->member_manage_model->do_edit_member($member_id, $member_account, $member_name, $member_mobile, $member_addr, $vat_num, $inv_title, $member_city);

			if($success)
			{
				$result['status'] = 1;
			}
			else
			{
				$result['status'] = -1;
			}

			if($member_pass && $member_chk_pass)
			{
				if($member_pass == $member_chk_pass)
				{
					$success = $this->member_manage_model->update_pwd($member_pass, $member_id);
				}
				else
				{
					$result['status'] = -1;
					$result['msg'] = "密碼不一致";
				}
				
			}


			echo json_encode($result);
		}
		else
		{
			redirect(site_url(), 'refresh');
		}

		die();
	}

	function forget_pass()
	{	  
		$vars['views'] = 'f_pass';   
		$vars['forgot_url'] = base_url()."forgot_pwd";
		$page_init = array('location' => 'f_pass');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}

	function send_new_pwd()
	{
		$this->load->library('email');
		$email = $this->input->get_post("cemail");

		if(!empty($email) && is_ajax())
		{
			$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');
			$chk_email = $this->member_manage_model->chk_email($email);
			if($chk_email === TRUE)
			{
				$new_pwd = $this->generatorPassword();
				$this->member_manage_model->update_pwd_by_email($new_pwd, $email);
				
				$msg = "您好，您的密碼是：".$new_pwd."<br /><br />請回到我們<a href='".base_url()."ordercheck' target='_blank'>網站</a>會員中心更改您的密碼。";


				$this->email->from('service@taste-it.com.tw', '【Taste it】海鮮團購網站');
				$this->email->to($email); 

				$this->email->subject("【Taste it】海鮮團購網站會員密碼通知信");
				$this->email->message($msg);
				
				if($this->email->send())
				{
					$result['status'] = 1;
					$result['msg'] = "密碼已寄出，請到email收信";
				}
				else
				{
					$result['status'] = -1;
					$result['msg'] = "信件未寄出，請恰系統管理員";
				}
			}
			else
			{
				$result['status'] = -1;
				$result['msg'] = "此email不存在，請輸入正確的email";
			}
		}
		else
		{
			$result["status"] = -1;
		}

		if(is_ajax())
		{
			echo json_encode($result);

			die();
		}
		else
		{
			show_404();
		}
	}

	function generatorPassword()
	{
	    $password_len = 7;
	    $password = '';

	    // remove o,0,1,l
	    $word = 'abcdefghijkmnpqrstuvwxyz!@#$%^&*()-=ABCDEFGHIJKLMNPQRSTUVWXYZ<>;{}[]23456789';
	    $len = strlen($word);

	    for ($i = 0; $i < $password_len; $i++) {
	        $password .= $word[rand() % $len];
	    }

	    return $password;
	}

	function chk_login()
	{
		$is_logined = $this->fuel_auth->front_is_logined();

		$result = array();

		$result['status'] = 1;
		$result['is_logined'] = $is_logined;

		if(is_ajax())
		{
			echo json_encode($result);
			die();
		}
		else
		{
			show_404();
		}
	}
	
}