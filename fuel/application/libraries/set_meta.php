<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Set_meta {

	private $_ci;

    public function __construct()
    {
        // Do something with $params
        $this->_ci =& get_instance();
        $this->_ci->load->model('product_model');
        
    }

	public function set_meta_data($id="", $mod="")
	{
		switch ($mod) {
			case 'mod_product':
				$meta_data = $this->_ci->product_model->get_pro_meta($id);
				break;
			
			default:
				$meta_data = null;
				break;
		}

		if(isset($meta_data))
		{
			
			fuel_set_var('page_title', $meta_data['page_title']);
			fuel_set_var('meta_keywords', $meta_data['meta_kw']);
			fuel_set_var('meta_description', $meta_data['meta_desc']);
			fuel_set_var('og_title', $meta_data['page_title']);
			fuel_set_var('og_desc', $meta_data['meta_desc']);
			fuel_set_var('og_image', base_url().$meta_data['og_image']);			
		}
		else
		{
			fuel_set_var('meta_keywords', "Taste it, 團購網, 海鮮");
		}


	}

}
