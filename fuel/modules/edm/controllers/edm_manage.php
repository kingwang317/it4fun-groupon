<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Edm_manage extends Fuel_base_controller {
	public $view_location = 'edm';
	public $nav_selected = 'edm/manage';
	
	function __construct()
	{
		parent::__construct();
		$this->_validate_user('edm/manage');
		$this->load->module_model(EDM_FOLDER, 'edm_manage_model');
		$this->load->helper(array('form', 'url'));
		$this->load->library('upload');
		$this->load->library('pagination');
		$this->load->library('email');
		$this->load->library('set_page');
	}
	
	function lists($dataStart=0)
	{
		$base_url = base_url();

		$target_url = $base_url.'fuel/edm/lists';
		$total_rows = $this->edm_manage_model->get_total_rows();
		$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 20);
		$dataLen = $config['per_page'];
		$this->pagination->initialize($config); 

		$edm_results = $this->edm_manage_model->get_edm_list($dataStart, $dataLen);

		$vars['pagination'] = $this->pagination->create_links();
		$vars['create_url'] = $base_url.'fuel/edm/create';
		$vars['edit_url'] = $base_url.'fuel/edm/edit/';
		$vars['log_url'] = $base_url.'fuel/edm/loglists/';
		$vars['del_url'] = $base_url.'fuel/edm/del/';
		$vars['send_url'] = $base_url.'fuel/edm/send/';
		$vars['multi_del_url'] = $base_url.'fuel/edm/do_multi_del';
		$vars['edm_results'] = $edm_results;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/edm_lists_view', $vars, 'edm');

	}

	function create()
	{
		$view_name = "新增電子報";
		$base_url = base_url();

		$vars['view_name'] = $view_name;

		$vars['submit_url'] = $base_url."fuel/edm/do_create";
		$vars['send_url']	= $base_url."fuel/edm/do_send";
		$vars['back_url'] = $base_url."fuel/edm/lists";
		$vars['base_url'] = $base_url;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/edm_create_view', $vars, 'edm');
	}

	function do_create()
	{
		$base_url = base_url();

		$post_data['subject'] = $this->input->get_post("subject");
		$post_data['content'] = htmlspecialchars($this->input->get_post("edm_desc"));
		/*
		$send_time = $this->input->get_post("edm_send_time");
		$send_h = $this->input->get_post("edm_send_h");
		$send_m = $this->input->get_post("edm_send_m");
		*/

		//$post_data['send_date'] = $send_time." ".$send_h.":".$send_m;

		$success = $this->edm_manage_model->do_add_edm($post_data);

		if($success)
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增成功");
			die();
		}
		else
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增失敗");
			die();		
		}
	}

	function do_send()
	{
		$base_url = base_url();

		$post_data['subject'] = $this->input->get_post("subject");
		$post_data['content'] = htmlspecialchars($this->input->get_post("edm_desc"));
		/*
		$send_time = $this->input->get_post("edm_send_time");
		$send_h = $this->input->get_post("edm_send_h");
		$send_m = $this->input->get_post("edm_send_m");
		*/

		//$post_data['send_date'] = $send_time." ".$send_h.":".$send_m;

		$success = $this->edm_manage_model->do_add_edm_and_send($post_data);

		if($success)
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增成功，信件開始寄送");
			die();
		}
		else
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增失敗");
			die();		
		}
	}

	function do_send_by_id($edm_id)
	{
		$base_url = base_url();
		$edm_data = array();
		if($edm_id)
		{

			$edm_result = $this->edm_manage_model->get_edm_detail($edm_id);
			$edm_data['subject'] = $edm_result->subject;
			$edm_data['content'] = $edm_result->content;

			$success = $this->edm_manage_model->add_log($edm_data, $edm_id);

			if($success)
			{
				$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增成功，信件開始寄送");
				die();
			}
			else
			{
				$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增失敗");
				die();		
			}			
		}


	}

	function edit($edm_id)
	{
		$view_name = "修改電子報";
		$base_url = base_url();

		$edm_result = $this->edm_manage_model->get_edm_detail($edm_id);

		$vars['view_name'] = $view_name;
		$vars['edm_result'] = $edm_result;
		$vars['submit_url'] = $base_url."fuel/edm/do_edit/";
		$vars['send_url']	= $base_url."fuel/edm/do_send";
		$vars['back_url'] = $base_url."fuel/edm/lists";
		$vars['base_url'] = $base_url;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/edm_edit_view', $vars, 'edm');
	}

	function do_edit($edm_id)
	{
		$base_url = base_url();

		$post_data['subject'] = $this->input->get_post("subject");
		$post_data['content'] = htmlspecialchars($this->input->get_post("edm_desc"));


		$success = $this->edm_manage_model->do_update_edm($post_data, $edm_id);

		if($success)
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "修改成功");
			die();
		}
		else
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "新增失敗");
			die();		
		}
	}

	function do_multi_del()
	{
		$result = array();

		$edm_ids = $this->input->get_post("edm_ids");

		if($edm_ids)
		{
			$im_edm_ids = implode(",", $edm_ids);

			$success = $this->edm_manage_model->do_multi_del_edm($im_edm_ids);
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
			$result['status'] = $im_edm_ids;
		}


		if(is_ajax())
		{
			echo json_encode($result);
		}
	}

	function edm_log_list($edm_id, $dataStart=0)
	{
		$view_name = "發送紀錄";
		$base_url = base_url();
		if($edm_id)
		{
			$target_url = $base_url.'fuel/edm/loglist';
			$total_rows = $this->edm_manage_model->get_total_rows();
			$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 20);
			$dataLen = $dataStart + $config['per_page'];
			$this->pagination->initialize($config);

			$edm_log_result = $this->edm_manage_model->get_send_record_list($edm_id, $dataStart, $dataLen);

			$vars['view_name'] = $view_name;
			$vars['pagination'] = $this->pagination->create_links();
			$vars['edm_log_result'] = $edm_log_result;
			$vars['submit_url'] = $base_url."fuel/edm/do_edit/";
			$vars['send_url']	= $base_url."fuel/edm/do_send";
			$vars['back_url'] = $base_url."fuel/edm/lists";
			$vars['base_url'] = $base_url;
			$vars['CI'] = & get_instance();

			$this->_render('_admin/edm_log_view', $vars, 'edm');
		}
		else
		{
			$this->plu_redirect($base_url."fuel/edm/lists", 0, "缺少參數");

			die();
		}
	}

	function do_del($edm_id)
	{
		$result = array();

		$success = $this->edm_manage_model->do_del_edm($edm_id);

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