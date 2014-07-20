<?php
class Order_job extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//$this->load->model('product_model');
		$this->load->helper('url');
		$this->load->helper('ajax');
		$this->load->library('email');
	}

	function update_order_num()
	{
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');

		$success = $this->order_manage_model->update_order_tmp_num();

		if($success)
		{
			echo "success";
		}
		else
		{
			log_error("Order Job -- 訂購數量更新失敗");
		}
		return;
	}
	
}