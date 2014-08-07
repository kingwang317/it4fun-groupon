<?php
class News_front extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('news_front_model'); 
		$this->load->library('pagination');
		$this->load->library('set_page');
	}

	function news_front() 
	{
		parent::Controller();	
	}

	function index($dataStart=0)
	{	
		$base_url = base_url();
		$target_url = $base_url.'news/';
		$filter = "";
		$total_rows = $this->news_front_model->get_total_rows($filter);
		$config = $this->set_page->set_config($target_url, $total_rows, $dataStart, 10);
		$dataLen = $config['per_page'];
		$this->pagination->initialize($config);

		$news_results = $this->news_front_model->get_list($dataStart, $dataLen, $filter);
		 
		 // print_r($news_results);
		 // die;

		// use Fuel_page to render so it will grab all opt-in variables and do any necessary parsing
		$vars['pagination'] = $this->pagination->create_links();
		$vars['views'] = 'news_front';
		// $vars['prod_detail_url'] = base_url()."prod/detail/";
		// $vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		// $vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$vars['news_results'] = $news_results;
		$vars['base_url'] = base_url();
		$page_init = array('location' => 'news_front');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR
	}
	
}