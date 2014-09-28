<?php
class User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		define('FUELIFY', FALSE);
		$this->load->library('set_meta');
		$this->load->library('comm');
	}

	function user() 
	{
		parent::Controller();

	}

	
 	function logout()
    {
       // $this->fuel_auth->logout();
$this->load->helper('cookie');
        delete_cookie("ytalent_account");

        redirect(site_url());
    }

    function do_login()
    {
    	$this->load->helper('cookie');
        $this->set_meta->set_meta_data();
		$this->load->model('code_model');
        $account = $this->input->post("login_mail");
        $password = $this->input->post("login_password");

        $is_logined = $this->code_model->is_account_logged_in($account);
       
        if($is_logined)
        {
            redirect(site_url()."user/myinfo");
        }
        else
        {
            $login_result = $this->code_model->do_logged_in($account,$password);   
            
            if($login_result){
				$this->input->set_cookie("ytalent_account",$account, 3600);

            	$this->comm->plu_redirect(site_url()."user/myinfo", 0, "登入成功");
            }else{

            	$this->comm->plu_redirect(site_url(), 0, "登入失敗");
            }
			
			    
        }
    }
    public function do_fb_regi(){
    	$this->load->helper('cookie');
		$this->load->model('core_model');

		$data = $this->core_model->get_fb_data();


		if(isset($data['user_profile'])){

			$this->input->set_cookie("fuel_account","", time()-3600);
			$mail = $data['user_profile']['id'];
			$password = $data['user_profile']['id'];
			$name = "";
			$fb_email = "";
			if(isset($data['user_profile']['name'])){
				$name = $data['user_profile']['name'];
			}
			if(isset($data['user_profile']['email'])){
				$fb_email = $data['user_profile']['email'];
			}



			$result = $this->code_model->do_register($mail,$password,$name,$fb_email,$data['user_profile']['id']);
			$this->input->set_cookie("fuel_account",$mail, time()+3600);
			$this->input->set_cookie("fuel_fb_logout_url",$data['logout_url'], time()+3600);

			$this->comm->plu_redirect(site_url(), 0, "FACEBOOK登入成功");

		}else{
			$this->comm->plu_redirect(site_url(), 0, "FACEBOOK登入失敗");
		}
	}
	
}