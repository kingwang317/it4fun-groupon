<?php
class Orders extends CI_Controller {
	insert into mod_code (`codekind_key`,`code_name`,`code_key`,`code_value1`,`parent_id`,`modi_time`)
	SELECT 'COUNTRY',`COL 1` ,`COL 2`, `COL 2`,-1,NOW()  
	FROM  `TABLE 27`  
	function __construct()
	{
		parent::__construct();
		$this->load->model('member_model');
		$this->load->library('pagination');
		$this->load->library('set_page');
	}

	function index($dataStart=0)
	{	 
		$this->load->module_library(FUEL_FOLDER, 'fuel_auth');
		$base_url = base_url();
		//bowen 先寫死 $member_id = 5;
		$user_data = $this->fuel_auth->valid_user();
		$member_id = isset($user_data['member_id'])?$user_data['member_id']:"";

		// if($member_id == "")
		// {
		// 	$this->comm->plu_redirect(site_url(), 0, "");
		// }

		$target_url = $base_url.'orders/';
		$total_rows = $this->member_model->get_order_total_rows($member_id);
		$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 10);
		$dataLen = $config['per_page'];
		$this->pagination->initialize($config); 
		$order_result = $this->member_model->order_info($dataStart, $dataLen, $member_id); 

		$vars['pagination'] = $this->pagination->create_links(); 
		$vars['order_result'] = $order_result; 
		$vars['views'] = 'orders'; 
		$vars['base_url'] = base_url();
		$page_init = array('location' => 'orders');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}
 
 
	
}