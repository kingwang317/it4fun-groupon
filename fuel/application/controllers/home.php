<?php
class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->library('set_meta');
	}

	function home() 
	{
		parent::Controller();	
	}

    private function url_checker(){
    	
		$this_host = $_SERVER['HTTP_HOST']; 

    	if(substr($this_host,0, 4)!='www.')
    	{
    		$p = $_SERVER['REQUEST_URI'];

			if(strlen($p)>1)
			{
				$p = substr($p, 1, strlen($p)-1);
			}else
			{
				$p='';
			}
    		
    		//redirect("http://taste-it.com.tw"); 
    	}
    	else
    	{
    		redirect("http://taste-it.com.tw"); 
    	}

    }
	
	function index()
	{	
		$this->url_checker();
		$this->set_meta->set_meta_data();
		$pro_results = $this->product_model->get_pro_list(" AND (pro_promote='new' or pro_promote='hot') ","" );
		$ad_results = $this->product_model->get_ad_data();
		$pro_cate_result = $this->product_model->get_code("product_cate"," AND parent_id <> -1 " );


		// print_r($pro_results);
		// die;
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
		// $vars['code_key'] = $code_key;

		// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
		$vars['views'] = 'product';
		$vars['prod_detail_url'] = base_url()."prod/detail/";
		$vars['pro_cate_result'] = $pro_cate_result; 
		$vars['base_url'] = base_url();
		$page_init = array('location' => 'product');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}

	function category($pro_cate)
	{	
		$this->url_checker();
		$this->set_meta->set_meta_data();
		$pro_results = $this->product_model->get_pro_list(" AND pro_cate='$pro_cate' ","" );
		$ad_results = $this->product_model->get_ad_data();
		$pro_cate_result = $this->product_model->get_code("product_cate"," AND parent_id <> -1 " );


		// print_r($pro_results);
		// die;
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
		$vars['pro_cate'] = $pro_cate;

		// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
		$vars['views'] = 'category';
		$vars['prod_detail_url'] = base_url()."prod/detail/";
		$vars['pro_cate_result'] = $pro_cate_result; 
		$vars['base_url'] = base_url();
		$page_init = array('location' => 'category');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}
	
}