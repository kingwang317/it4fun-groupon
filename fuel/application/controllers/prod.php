<?php
class Prod extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url');
		$this->load->library('set_meta');
	}

	function detail($pro_id)
	{
		$today = date("Y-m-d h:i:s");

		$pro_detail_results = $this->product_model->get_pro_detail($pro_id);

		$this->product_model->update_click_num($pro_id);

		if($pro_detail_results)
		{
			$pro_photo = $this->product_model->get_pro_photo_url($pro_id, 'product', $pro_detail_results->pro_text_photo);
			$pro_selled_cnt = $this->product_model->get_selled_cnt($pro_id);
			$pro_top_result = $this->product_model->get_top_list($pro_id);

			$sell_amt = $pro_detail_results->pro_start_sell + $pro_selled_cnt->cnt;

			if(isset($pro_detail_results))
			{
				$vars['pro_detail_results'] = $pro_detail_results;
				$vars['pro_photo_data'] = $pro_photo;
				$vars['pro_top_result'] = $pro_top_result;
			}
			else
			{
				$vars['pro_results'] = array();
			}
			
			$this->set_meta->set_meta_data($pro_id, "mod_product");

			if($pro_detail_results->pro_off_time < $today)
			{
				$vars['pro_offed'] = true;
			}
			else
			{
				$vars['pro_offed'] = false;
			}

			$vars['system_time'] = date('Y/m/d h:y:s');

			$vars['prod_detail_url'] = base_url()."prod/detail/";
			$vars['pro_selled_cnt'] = isset($sell_amt)?$sell_amt:0;
			// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
			$vars['views'] = 'product_detail';
			$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
			$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
			$vars['go_cart_url'] = base_url()."prod/plan/".$pro_id;
			$vars['base_url'] = base_url();
			$page_init = array('location' => 'product_detail');
			$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
			$this->fuel_page->add_variables($vars);
			$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
		}
		else
		{
			show_404();
			die();
		}
	}

	function pro_list($code_key='pro_cate_0002')
	{	
		$pro_results = $this->product_model->get_pro_list($code_key);
		$ad_results = $this->product_model->get_ad_data();
		
		if(isset($pro_results))
		{
			$vars['pro_results'] = $pro_results;
		}
		else
		{
			$vars['pro_results'] = array();
		}
		$vars['ad_results'] = $ad_results;
		$vars['system_time'] = date('Y/m/d h:y:s');
		$vars['code_key'] = $code_key;


		// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
		$vars['views'] = 'product';
		$vars['prod_detail_url'] = base_url()."prod/detail/";
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$vars['base_url'] = base_url();
		$page_init = array('location' => 'product');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}

	function plan($pro_id="")
	{
		$this->load->module_library(FUEL_FOLDER, 'fuel_auth');

		if(!empty($pro_id))
		{
			$is_existed = $this->product_model->prod_is_existed($pro_id);

			if($is_existed === 1)
			{
				$today = date("Y-m-d H:i:s");
				$pro_detail_results = $this->product_model->get_pro_detail($pro_id);

				if($pro_detail_results->pro_off_time < $today)
				{
					show_404();
					die();
				}
				else
				{
					$plan_results = $this->product_model->get_prod_plan($pro_id);
					$pro_cover_photo = $this->product_model->get_pro_cover($pro_id, 'product', $pro_detail_results->pro_cover_photo);

					$vars['pro_detail_results'] = $pro_detail_results;
					$vars['plan_results'] = $plan_results;
					$vars['pro_cover_photo'] = $pro_cover_photo;
					// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
					$vars['views'] = 'cart';
					$vars['forgot_url'] = base_url()."forgot_pwd";
					$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
					$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
					$vars['login_url'] = base_url()."user/login";
					$vars['payment_url'] = base_url()."payment";
					$vars['is_logined'] = $this->fuel_auth->front_is_logined();
					$page_init = array('location' => 'cart');
					$vars['base_url'] = base_url();
					$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
					$this->fuel_page->add_variables($vars);
					$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
				}
					
			}
			else
			{
				show_404();
				die();
			}
			
		}
		else
		{
			show_404();
			die();
		}

	}

	function oldprods()
	{
		$old_prod_result = $this->product_model->get_old_prod();

		$vars['base_url'] = base_url();
		$vars['old_prod_result'] = $old_prod_result;

		$vars['prod_detail_url'] = base_url()."prod/detail/";
		$vars['views'] = 'oldprods';
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$page_init = array('location' => 'oldprods');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR	
	}
	
}