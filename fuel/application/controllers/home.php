<?php
class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->library('set_meta');
		#enable query strings for this class
		parse_str($_SERVER['QUERY_STRING'],$_GET);
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
	function login()
	{	
		$this->load->helper('cookie');
		$this->load->library('facebook'); 
		$this->set_meta->set_meta_data();
		fuel_set_var('page_id', "1");
		$all_cate = array();

		$this->load->model('core_model');


		//echo "23";


		$fb_data	= $this->core_model->get_fb_data();
		$vars['fb_data'] = $fb_data;

		//print_r($fb_data);

		// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
		
		$vars['all_cate']	= $all_cate;
		$vars['base_url'] = base_url();
		$vars['views'] = 'login';
		$page_init = array('location' => 'login');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
		/*if($this->code_model->is_mobile()){
			$vars['views'] = 'm_login';
			$page_init = array('location' => 'm_login');
			$this->fuel->pages->render('m_login', $vars);
		}else{
			$vars['views'] = 'login';
			$page_init = array('location' => 'login');
			$this->fuel->pages->render('login', $vars);
		}*/
	}
	function google_login()
	{	
		require '/fuel/application/libraries/LightOpenID.php';
		try {
		    # Change 'localhost' to your domain name.
		    $openid = new LightOpenID('localhost');
		    if(!$openid->mode)
		    {
		        if(isset($_GET['login']))
		        {
		            $openid->identity = 'https://www.google.com/accounts/o8/id';
		            header('Location: ' . $openid->authUrl());
		        }
		        echo '<form action="?login" method="post"><button>Login with Google</button></form>';
		    }
		    elseif($openid->mode == 'cancel')
		    {
		        echo 'User has canceled authentication!';
		    }
		    else
		    {
		        echo 'User ' . ($openid->validate() ? $openid->identity . ' has ' : 'has not ') . 'logged in.';
		    }
		}
		catch(ErrorException $e)
		{
		    echo $e->getMessage();
		}
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