<?php
class Edm_job extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//$this->load->model('product_model');
		$this->load->helper('url');
		$this->load->helper('ajax');
		$this->load->library('email');
	}

	function edm_send_job()
	{
		$this->load->module_model(EDM_FOLDER, 'edm_manage_model');
		$email_result = $this->edm_manage_model->scan_job();

		if(isset($email_result))
		{
			foreach($email_result as $key=>$row)
			{
				
				if($key == 0)
				{
					$this->edm_manage_model->update_send_date($row->edm_id);
				}

				$this->email->from('service@taste-it.com.tw', '【Taste it】海鮮團購網站');
				$this->email->to($row->target); 

				$this->email->subject($row->subject);
				$this->email->message(htmlspecialchars_decode($row->content));
				
				if($this->email->send())
				{
					$this->edm_manage_model->update_send_status($row->edm_log_id);
				}
				else
				{
					echo $this->email->print_debugger();
				}

				
			}
		}

		return;
	}
	
}