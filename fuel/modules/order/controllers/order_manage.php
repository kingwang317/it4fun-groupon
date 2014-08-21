<?php
require_once(FUEL_PATH.'/libraries/Fuel_base_controller.php');

class Order_manage extends Fuel_base_controller {
	public $view_location = 'order';
	public $nav_selected = 'order/manage';
	
	function __construct()
	{
		parent::__construct();
		//$this->config->module_load('codekind', 'codekind');
		$this->_validate_user('order/manage');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$this->load->helper('ajax');
		$this->load->library('pagination');
		$this->load->library('set_page');
		$this->uri->init_get_params();
	}
	
	function lists($pro_id=0, $dataStart=0)
	{
		$base_url = base_url();

		//$pro_id = $this->input->get_post("pro_id");
		$filter = "";
		if($pro_id == 0)
		{
			$target_url = $base_url.'fuel/order/lists/'.$pro_id."/";
		}
		else
		{
			$filter .= " AND product_id=$pro_id";
			$target_url = $base_url.'fuel/order/lists/'.$pro_id;
		}

		$this->uri->init_get_params();	//才能收到$_GET參數
		$search_item = $this->input->get_post("search_item");
		$search_txt = $this->input->get_post("search_txt");

		if($search_item)
		{
			switch($search_item)
			{
				case 'order_id':
					$filter .= " AND order_id ='".$search_txt."'";
					break;
				case 'order_name':
					$filter .= " AND order_name LIKE '%".$search_txt."%'";
					break;
				case 'order_email':
					$filter .= " AND order_email LIKE '%".$search_txt."%'";
				case 'order_date':
					$start_time = $this->input->get_post("start_time")." 00:00:00";
					$end_time = $this->input->get_post("end_time"). " 00:00:00";
					$filter .= " AND mo.order_time > '".$start_time."' AND mo.order_time < '".$end_time."'";
			}
		}
		//制作page
		$total_rows = $this->order_manage_model->get_total_rows($filter);
		$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 20);
		$dataLen = $config['per_page'];
		$this->pagination->initialize($config);

		$order_results = $this->order_manage_model->get_order_list($dataStart, $dataLen, $filter);

		$pro_cate_results = $this->product_manage_model->get_code('product_cate', ' AND parent_id=-1 ORDER BY code_key ASC');

		$vars['pro_cate_results'] = $pro_cate_results;
		$vars['pro_id'] = $pro_id;
		$vars['expor_url'] = $base_url."fuel/order/export/excel";
		$vars['expor_report_url'] = $base_url."fuel/order/export/report";
		$vars['get_prod_url'] = $base_url."fuel/order/get/prod/lists/";
		$vars['pagination'] = $this->pagination->create_links();
		$vars['create_url'] = $base_url.'fuel/order/create';
		$vars['edit_url'] = $base_url.'fuel/order/edit/';
		$vars['del_url'] = $base_url.'fuel/order/del/';
		$vars['multi_del_url'] = $base_url.'fuel/order/do_multi_del';
		$vars['order_results'] = $order_results;
		$vars['order_filter'] = htmlspecialchars($filter, ENT_QUOTES);
		$vars['filter_url'] = $base_url."fuel/order/lists/";
		$vars['date_filter_url'] = $base_url."fuel/order/lists?search_item=order_date";
		$vars['batch_action_url'] = $base_url."fuel/order/batch/action";
		$vars['search_item'] = isset($search_item)?$search_item:"";
		$vars['search_txt'] = isset($search_txt)?$search_txt:"";
		$vars['CI'] = & get_instance();

		$this->_render('_admin/order_lists_view', $vars, 'order');

	}

	function create()
	{
		$view_name = "新增訂單";
		$base_url = base_url();

		$pro_cate_results = $this->product_manage_model->get_code('product_cate', ' AND parent_id=-1 ORDER BY code_key ASC');

		$vars['pro_cate_results'] = $pro_cate_results;
		$vars['view_name'] = $view_name;

		$vars['get_prod_url'] = $base_url."fuel/order/get/prod/lists/";
		$vars['get_plan_url'] = $base_url."fuel/order/get/plan/lists/";
		$vars['submit_url'] = $base_url."fuel/order/do_create";
		$vars['back_url'] = $base_url."fuel/order/lists";
		$vars['CI'] = & get_instance();

		$this->_render('_admin/order_create_view', $vars, 'order');
	}

	function do_create()
	{
		$base_url = base_url();
		
		$pro_id = $this->input->get_post("pro_id");
		$pro_plan = $this->input->get_post("pro_plan");

		$order_name = $this->input->get_post("order_name");
		$order_email = $this->input->get_post("order_email");
		$order_mobile = $this->input->get_post("order_mobile");
		$order_addr = $this->input->get_post("order_addr");
		$order_vat_num = $this->input->get_post("order_vat_num");
		$order_inv_title = $this->input->get_post("order_inv_title");
		$oa_name = $this->input->get_post("oa_name");
		$oa_mobile = $this->input->get_post("oa_mobile");
		$oa_addr = $this->input->get_post("oa_addr");

		$success = $this->order_manage_model->do_add_order($order_name, $order_email, $order_mobile, $order_addr, $order_vat_num, $order_inv_title, $oa_name, $oa_mobile, $oa_addr, $pro_id, $pro_plan);

		if($success)
		{
			redirect($base_url."fuel/order/lists");
		}
	}

	function edit($order_id)
	{
		$view_name = "修改訂單";
		$base_url = base_url();

		$order_results = $this->order_manage_model->get_order_info($order_id);
		$order_dt_results = $this->order_manage_model->get_order_detail($order_id);
		// print_r($order_dt_results);
		// die;
		// $plan_results = $this->order_manage_model->get_plan_by_prod($order_results->pro_id);
		$order_status_results = $this->order_manage_model->get_code('order_status', ' AND parent_id=-1 ORDER BY code_key ASC');
		$ship_status_results = $this->order_manage_model->get_code('ship_status', ' AND parent_id=-1 ORDER BY code_key ASC');
		$ship_time_results = $this->order_manage_model->get_code('ship_time', ' AND parent_id=-1 ORDER BY code_key ASC');
		$inv_status_results = $this->order_manage_model->get_code('inv_status', ' AND parent_id=-1 ORDER BY code_key ASC');

		$vars['view_name'] = $view_name;
		$vars['get_prod_url'] = $base_url."fuel/order/get/prod/lists/";
		$vars['get_plan_url'] = $base_url."fuel/order/get/plan/lists/";
		$vars['order_status_results'] = $order_status_results;
		$vars['ship_status_results'] = $ship_status_results;
		$vars['inv_status_results'] = $inv_status_results;
		$vars['ship_time_results'] = $ship_time_results;
		$vars['order_results'] = $order_results;
		$vars['order_dt_results'] = $order_dt_results;
		// $vars['plan_results'] = $plan_results;
		$vars['back_url'] = $base_url."fuel/order/lists";
		$vars['submit_url'] = $base_url."fuel/order/do_edit/".$order_id;
		$vars['CI'] = & get_instance();

		$this->_render('_admin/order_edit_view', $vars, 'order');
	}

	function do_edit($order_id)
	{
		$base_url = base_url();
		
		$plan_id = $this->input->get_post("plan_id");
		$order_name = $this->input->get_post("order_name");
		$order_email = $this->input->get_post("order_email");
		$order_mobile = $this->input->get_post("order_mobile");
		$order_addr = $this->input->get_post("order_addr");
		$order_vat_num = $this->input->get_post("order_vat_num");
		$order_inv_title = $this->input->get_post("order_inv_title");
		$oa_name = $this->input->get_post("oa_name");
		$oa_mobile = $this->input->get_post("oa_mobile");
		$oa_addr = $this->input->get_post("oa_addr");
		$order_status = $this->input->get_post("order_status");
		$order_ship_status = $this->input->get_post("order_ship_status");
		$order_inv_status = $this->input->get_post("order_inv_status");
		$order_ship_time = $this->input->get_post("order_ship_time");

		$success = $this->order_manage_model->do_edit_order($order_id, $order_name, $order_email, $order_mobile, $order_addr, $order_vat_num, $order_inv_title, $oa_name, $oa_mobile, $oa_addr, $order_status, $order_ship_status, $order_inv_status, $plan_id, $order_ship_time);

		if($success)
		{
			redirect($base_url."fuel/order/lists");
		}
	}

	function do_del($order_id)
	{
		$result = array();

		$success = $this->order_manage_model->do_del_order($order_id);

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

		$order_ids = $this->input->get_post("order_ids");

		if($order_ids)
		{
			$im_order_ids = implode(",", $order_ids);

			$success = $this->order_manage_model->do_multi_del_order($im_order_ids);
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
			$result['status'] = $im_order_ids;
		}


		if(is_ajax())
		{
			echo json_encode($result);
		}
	}

	function get_prod_list($code_key)
	{
		$result = array();

		$product_results = $this->order_manage_model->get_prod_by_code($code_key);


		if(!empty($product_results))
		{
			$result['status'] = 1;
			$result['product_results'] = $product_results;
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

	function get_plan_list($prod_id)
	{
		$result = array();

		$plan_results = $this->order_manage_model->get_plan_by_prod($prod_id);

		if(!empty($plan_results))
		{
			$result['status'] = 1;
			$result['plan_results'] = $plan_results;
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

	function batch_action()
	{
		$act = $this->input->get_post('act');
		$order_ids = $this->input->get_post("order_ids");

		if($act && $order_ids)
		{
			$im_order_ids = implode(",", $order_ids);
			switch ($act) 
			{
				case 'o_checked':
					$success = $this->order_manage_model->update_order_status($im_order_ids, 'order_status_0002');
					break;
				case 'o_un_checked':
					$success = $this->order_manage_model->update_order_status($im_order_ids, 'order_status_0001');
					break;
				case 'o_shiped':
					$success = $this->order_manage_model->update_ship_status($im_order_ids, 'ship_status_0002');
					//$ex_order_id = explode(",", $order_ids);
					$this->send_ship_note($im_order_ids);
					break;
				case 'o_un_shiped':
					$success = $this->order_manage_model->update_ship_status($im_order_ids, 'ship_status_0001');
					break;
				case 'o_inved':
					$success = $this->order_manage_model->update_inv_status($im_order_ids, 'inv_status_0002');
					break;
				case 'o_un_inved':
					$success = $this->order_manage_model->update_inv_status($im_order_ids, 'inv_status_0001');
					break;
			}

			if(isset($success))
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

	public function send_ship_note($order_ids)
	{
		$this->load->module_model(EDM_FOLDER, 'edm_manage_model');

		$edm_id = 3;
		$edm_result = $this->edm_manage_model->get_edm_detail($edm_id);

		$edm_data['subject'] = $edm_result->subject;
		$edm_data['content'] = $edm_result->content;
		$this->edm_manage_model->send_member_email($edm_id, $order_ids, $edm_data);

		return;
	}

	public function export_report()
	{
		$this->load->helper('download');
		$filter = $this->input->get_post('order_filter');
		$filter = htmlspecialchars_decode($filter);

		$query = $this->order_manage_model->get_order_list_excel($filter);

		if(!$query)
        	return false;

        // Starting the PHPExcel library
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
 
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
 
        $objPHPExcel->setActiveSheetIndex(0);
 
        // Field names in the first row
        $fields = $query->list_fields();
        $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }

        // Fetching the table data
        $row = 2;
        $objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()->setFormatCode('0000000000');
        $objPHPExcel->getActiveSheet()->getStyle('E')->getNumberFormat()->setFormatCode('0000000000');
        $objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode('000000000000');
        foreach($query->result() as $data)
        {
            $col = 0;
            foreach ($fields as $field)
            {
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
                $col++;
            }
 
            $row++;
        }
 		
        $objPHPExcel->setActiveSheetIndex(0);
 
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
 
        // Sending headers to force the user to download the file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Orders_'.date('YmdHis').'.xls"');
        header('Cache-Control: max-age=0');
 
        $objWriter->save('php://output');

	}

	public function download_excel() 
	{
			$this->load->helper('download');
			$pro_id = $this->input->get_post("pro_id");
			$filter = $this->input->get_post('order_filter');
			$filter = htmlspecialchars_decode($filter);

			$query = $this->order_manage_model->getExcel($pro_id, $filter);
			if(!$query)
            	return false;
 
	        // Starting the PHPExcel library
	        $this->load->library('PHPExcel');
	        $this->load->library('PHPExcel/IOFactory');
	 
	        $objPHPExcel = new PHPExcel();
	        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
	 
	        $objPHPExcel->setActiveSheetIndex(0);
	 
	        // Field names in the first row
	        $fields = $query->list_fields();
	        $col = 0;
	        foreach ($fields as $field)
	        {
	            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
	            $col++;
	        }

	        // Fetching the table data
	        $row = 2;
	        $objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode('0000000000');
	        foreach($query->result() as $data)
	        {
	            $col = 0;
	            foreach ($fields as $field)
	            {
	                
	                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
	                $col++;
	            }
	 
	            $row++;
	        }
	 		
	        $objPHPExcel->setActiveSheetIndex(0);
	 
	        $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
	 
	        // Sending headers to force the user to download the file
	        header('Content-Type: application/vnd.ms-excel');
	        header('Content-Disposition: attachment;filename="Orders_'.date('YmdHis').'.xls"');
	        header('Cache-Control: max-age=0');
	 
	        $objWriter->save('php://output');
		
	}

}