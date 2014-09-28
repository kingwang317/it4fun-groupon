<?php
class Prod extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->helper('url');
		$this->load->library('set_meta');
		$this->load->library('comm');
		$this->load->helper('cookie');
	}

	function do_remove_cart($pro_id)
	{
		$cart = get_cookie("cart",TRUE);

		if (isset($cart) && !empty($cart)) { 
			$cart = stripslashes($cart);
			$cart = json_decode($cart, true);  
		    unset($cart[$pro_id]);
		    $json = json_encode($cart);
			// print_r($json);
			$config = array(
				'name' => "cart",
				'value' => $json,
				'expire' => 0,
				'path' => WEB_PATH
			);
			set_cookie($config); 
		}   
	}

	function do_set_cart_info($pro_id,$plan_id,$num)
	{ 
		$cart = get_cookie("cart",TRUE);

		if (isset($cart) && !empty($cart)) { 
			$cart = stripslashes($cart);
			$cart = json_decode($cart, true); 
		    $cart[$pro_id] = array('pro_id' => $pro_id ,'plan_id' => $plan_id ,'num' => $cart[$pro_id]['num'] + $num); 
		    // $cart = $cart + $num;
		}else{
			$cart[$pro_id] = array('pro_id' => $pro_id ,'plan_id' => $plan_id ,'num' => $num);
			// $cart = $num; 
		}   
		$json = json_encode($cart);
		// print_r($json);
		$config = array(
			'name' => "cart",
			'value' => $json,
			'expire' => 0,
			'path' => WEB_PATH
		);
		set_cookie($config); 
		// $this->comm->plu_redirect(base_url()."product/detail/$pro_id",0,'已加入購物車');
	}

	function cart(){
		$cart = get_cookie("cart",TRUE);
		$pro_cart = null;
		if (isset($cart) && !empty($cart)) { 
			$cart = stripslashes($cart);
			$cart = json_decode($cart, true); 
			$pro_ids = array_keys($cart);
			$pro_ids = implode(",", $pro_ids);
			$pro_cart = $this->product_model->get_cart_pro_list($pro_ids);
		} 
	 

		// print_r($cart);
		// die;
		$vars['cart'] = $cart;
	 	$vars['pro_cart'] = $pro_cart; 
		$vars['views'] = 'cart'; 
		$vars['base_url'] = base_url();
		$vars['login_url'] = base_url()."user/login";
		$page_init = array('location' => 'cart');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}

	function detail($pro_id)
	{
		// $this->do_set_cart_info($pro_id,10);
		// $cart = $this->input->cookie("cart_$pro_id",TRUE);
		// print_r($cart);
		// die;
		$today = date("Y-m-d h:i:s");
		
		$pro_detail_results = $this->product_model->get_pro_detail($pro_id);
		$pro_cate_result = $this->product_model->get_code("product_cate"," AND parent_id <> -1 " );

		$this->product_model->update_click_num($pro_id);

		if($pro_detail_results)
		{

			if ($pro_detail_results->pro_off_time < $today) {
				$this->comm->plu_redirect(base_url(),0,'商品已下架');
				die;
			}

			$pro_photo = $this->product_model->get_pro_photo_url($pro_id, 'product', $pro_detail_results->pro_text_photo);
			$pro_selled_cnt = $this->product_model->get_selled_cnt($pro_id);

			$sell_amt = $pro_detail_results->pro_start_sell + $pro_selled_cnt->cnt;

			if(isset($pro_detail_results))
			{ 
				$pro_top_result = $this->product_model->get_top_list($pro_id);
				$pro_interest_results = $this->product_model->get_pro_list(" AND pro_cate = '$pro_detail_results->pro_cate'"," limit 0,4 ");
				$pro_plan_results = $this->product_model->get_prod_plan($pro_id);
				$vars['pro_detail_results'] = $pro_detail_results;
				$vars['pro_photo_data'] = $pro_photo;
				$vars['pro_top_result'] = $pro_top_result;
				$vars['pro_interest_results'] = $pro_interest_results;
				if (isset($pro_plan_results) && sizeof($pro_plan_results) > 0) {
					$vars['pro_plan_results'] = $pro_plan_results[0];
				}
				
			}
			else
			{
				$vars['pro_results'] = array();
				$vars['pro_interest_results'] = array();
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
			$vars['pro_cate_result'] = $pro_cate_result; 
			// $vars['add_cart_url'] = base_url()."addToCart/$pro_id/"; 
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
		$pro_results = $this->product_model->get_pro_list($code_key,"");
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