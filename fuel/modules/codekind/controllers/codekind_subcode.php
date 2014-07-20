<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Codekind_subcode extends Fuel_base_controller {
	public $view_location = 'condekind';
	public $nav_selected = 'condekind/subcodelists';
	
	function __construct()
	{
		parent::__construct();
		//$this->config->module_load('codekind', 'codekind');
		$this->_validate_user('codekind/subcode');
		$this->load->module_model(CODEKIND_FOLDER, 'codekind_subcode_model');
		$this->load->helper('ajax');
	}
	
	function lists($code_id)
	{
		//$this->js_controller_params['method'] = 'add_edit';
		$view_name = "代碼列表";
		$base_url = base_url();

		$code_results = $this->codekind_subcode_model->get_subcode($code_id);

		$parent_id = $this->codekind_subcode_model->is_top($code_id);
		
		$vars['code_results'] = $code_results;
		$vars['code_id'] = $code_id;
		$vars['view_name'] = $view_name;

		if($parent_id == -1)
		{
			$vars['back_url'] = $base_url.'fuel/codekind/codelists';
		}
		else
		{
			$vars['back_url'] = $base_url.'fuel/codekind/sub/codelists/'.$parent_id;
		}
		$vars['create_url'] = $base_url.'fuel/codekind/sub/codelists/create/'.$code_id;
		$vars['edit_url'] = $base_url.'fuel/codekind/sub/codelists/edit/';
		$vars['del_url'] = $base_url.'fuel/codekind/sub/codelists/delete/';
		$vars['CI'] = & get_instance();

		$this->_render('_admin/codekind_subcode_view', $vars, 'codekind');

	}

	function create($code_id)
	{
		$view_name = "新增代碼";
		$base_url = base_url();

		$code_result = $this->codekind_subcode_model->get_subcode_detail($code_id);
		$parent_name = $code_result->code_name;

		$codekind_results = $this->codekind_subcode_model->get_codekind();

		$vars['view_name'] = $view_name;
		
		$vars['codekind_results'] = $codekind_results;
		$vars['parent_name'] = $parent_name;
		$vars['parent_id'] = $code_id;
		$vars['codekind_key'] = $code_result->codekind_key;
		$vars['submit_url'] = $base_url."fuel/codekind/sub/codelists/do_create";
		$vars['CI'] = & get_instance();

		$this->_render('_admin/codekind_subcode_create_view', $vars, 'codekind');
	}

	function do_create()
	{
		$base_url = base_url();
		
		$codekind_key = $this->input->get_post('codekind_key');
		$code_name = $this->input->get_post('code_name');
		$code_key = $this->input->get_post('code_key');
		$code_value1 = $this->input->get_post('code_value1');
		$code_value2 = $this->input->get_post('code_value2');
		$code_value3 = $this->input->get_post('code_value3');
		$parent_id = $this->input->get_post('parent_id');

		$success = $this->codekind_subcode_model->do_create_code($codekind_key, $code_name, $code_key, $code_value1, $code_value2, $code_value3, $parent_id);

		if($success)
		{
			redirect($base_url."fuel/codekind/codelists");
		}
	}

	function edit($code_id)
	{
		$view_name = "修改代碼";
		$base_url = base_url();

		$code_results = $this->codekind_subcode_model->get_subcode_detail($code_id);
		$codekind_results = $this->codekind_subcode_model->get_codekind();

		$vars['view_name'] = $view_name;
		$vars['row'] = $code_results;
		$vars['codekind_results'] = $codekind_results;
		$vars['back_url'] = $base_url."fuel/codekind/sub/codelists/".$code_results->parent_id;
		$vars['submit_url'] = $base_url."fuel/codekind/sub/codelists/do_edit/".$code_id;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/codekind_subcode_edit_view', $vars, 'codekind');
	}

	function do_edit($code_id)
	{
		$base_url = base_url();
		
		$codekind_key = $this->input->get_post('codekind_key');
		$code_name = $this->input->get_post('code_name');
		$code_key = $this->input->get_post('code_key');
		$code_value1 = $this->input->get_post('code_value1');
		$code_value2 = $this->input->get_post('code_value2');
		$code_value3 = $this->input->get_post('code_value3');

		$success = $this->codekind_subcode_model->do_edit_code($code_id, $codekind_key, $code_name, $code_key, $code_value1, $code_value2, $code_value3);

		if($success)
		{
			redirect($base_url."fuel/codekind/codelists");
		}
	}

	function delete($code_id)
	{
		$result = array();

		$success = $this->codekind_subcode_model->do_del_code($code_id);
		//$success = 1;
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

}