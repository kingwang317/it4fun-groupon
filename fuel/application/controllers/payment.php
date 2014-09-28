<?php
class Payment extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('ajax');
		$this->load->helper('cookie');
	}

	function payment_form()
	{
		$this->load->module_library(FUEL_FOLDER, 'fuel_auth');
		$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');

		// $plan_id = $this->input->get_post("pro_plan");
		$user_data = $this->fuel_auth->valid_user();
		//bowen 先寫死 $member_id = 5;
		$member_id = isset($user_data['member_id'])?$user_data['member_id']:$user_data['user_name'];
		$city_result = $this->product_manage_model->get_code('city', ' AND parent_id=-1 ORDER BY code_key ASC');
		$ship_time_result = $this->product_manage_model->get_code('ship_time', ' AND parent_id=-1 ORDER BY code_key ASC');
		
		$member_result = $this->member_manage_model->get_member_detail_row($member_id);
		// $this->order_manage_model->update_plan_tmp_num($plan_id);

		$vars['get_payment_url'] = base_url()."payment/create";
		$vars['city_result'] = $city_result;
		$vars['ship_time_result'] = $ship_time_result;
		// $vars['plan_id'] = $plan_id;
		$vars['views'] = 'paymentinfo';
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$vars['member_result'] = isset($member_result)?$member_result:array();
		$vars['is_logined'] = $this->fuel_auth->front_is_logined();
		$page_init = array('location' => 'paymentinfo');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR	

		return;
	}

	function addpadding($str, $blocksize = 16) {
	    $len = strlen($str);
	    $pad = $blocksize - ($len % $blocksize);
	    $str .= str_repeat(chr($pad), $pad);
	    return $str;
	}

	function strippadding($string) {
	    $slast = ord(substr($string, -1));
	    $slastc = chr($slast);
	    $pcheck = substr($string, -$slast);
	    if (preg_match("/$slastc{" . $slast . "}/", $string)) {
	        $string = substr($string, 0, strlen($string) - $slast);
	        return $string;
	    } else {
	        return false;
	    }
	}

	function get_base_xml() {
	    $xml = "
	    <Root>
	    <Data>
	    <MerchantID>[++merchant_id++]</MerchantID>
	    <MerchantTradeNo>[++merchant_trade_no++]</MerchantTradeNo>
	    <MerchantTradeDate>[++trade_date++]</MerchantTradeDate>
	    <TotalAmount>[++trade_amt++]</TotalAmount>
	    <TradeDesc>[++trade_desc++]</TradeDesc>
	    <CardNo>[++card_no++]</CardNo>
	    <CardValidMM>[++card_mm++]</CardValidMM>
	    <CardValidYY>[++card_yy++]</CardValidYY>
	    <CardCVV2>[++card_CVV2++]</CardCVV2>
	    <UnionPay>0</UnionPay>
	    <Installment>0</Installment>
	    <ThreeD>0</ThreeD>
	    <CharSet>[++charset++]</CharSet>
	    <Enn></Enn>
	    <BankOnly></BankOnly>
	    <Redeem></Redeem>
	    <ReturnURL>[++return_url++]</ReturnURL>
	    <ServerReplyURL>[++srerver_reply_url++]</ServerReplyURL>
	    <ClientBackURL>[++client_back_url++]</ClientBackURL>
	    <Remark></Remark>
	    </Data>
	    </Root>";

	    $xml = trim($xml);
	    $xml = str_replace("\t", "", $xml);
	    $xml = str_replace("\r\n", "", $xml);
	    $xml = str_replace("\r", "", $xml);
	    $xml = str_replace("\n", "", $xml);
	    $xml = str_replace(" ", "", $xml);
	    $xml = "<?xml version='1.0' encoding='utf-8' ?>" . $xml;
	    return $xml;
	}

	//xml加密
	function encrypt($str, $iv, $key) {
	    $data = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $this->addpadding($str), MCRYPT_MODE_CBC, $iv)));
	    return $data;
	}
	//xml解密
	function decrypt($sValue, $key, $iv) {
	    //$sValue=  urldecode($sValue);
	    $sValue = str_replace(' ', '+', $sValue);
	    $str = $this->strippadding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($sValue), MCRYPT_MODE_CBC, $iv));
	    return $str;
	}


	function create_order()
	{
		$this->load->module_library(FUEL_FOLDER, 'fuel_auth');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');
		$this->load->module_model(PRODUCT_FOLDER, 'product_manage_model');
		$this->load->module_model(MEMBER_FOLDER, 'member_manage_model');

		//if(is_ajax())
		if(true)
		{

			$key = ALLPAY_KEY;
			$iv = ALLPAY_IV;
			$user_data = $this->fuel_auth->valid_user();

			$plan_id 				= -1;//$this->input->get_post("plan_id");
			$order_name 			= $this->input->get_post("order_name");
			$order_email 			= $this->input->get_post("order_email");
			$pwd 					= $this->input->get_post("pwd");
			$chk_pwd 				= $this->input->get_post("chk_pwd");
			$order_city 			= $this->input->get_post("order_city");
			$order_addr 			= $this->input->get_post("order_addr");
			$order_mobile 			= $this->input->get_post("order_mobile");
			$vat_number 			= $this->input->get_post("vat_number");
			$invoice_title 			= $this->input->get_post("invoice_title");
			$order_addressee_city	= $this->input->get_post("order_addressee_city");
			$order_addressee_name 	= $this->input->get_post("order_addressee_name");
			$order_addressee_addr 	= $this->input->get_post("order_addressee_addr");
			$order_addressee_mobile = $this->input->get_post("order_addressee_mobile");
			$order_ship_time		= $this->input->get_post("order_ship_time");
			//bowen 先寫死 $member_id = 5; 
			$member_id = isset($user_data['member_id'])?$user_data['member_id']:"";
			 
			$product_plan = new stdClass;
			$product_plan->plan_id = -1;
			$product_plan->pro_id = -1;
			$product_plan->plan_price = '';

			//order detail

			$order_addressee_addr = $order_addressee_city.$order_addressee_addr;
			if($member_id)
			{
				$member_result = $this->member_manage_model->get_member_detail_row($member_id);
				//交易單號 == order_id	
				$trade_no = $this->order_manage_model->create_empty_order($member_id, $order_email, $order_name, $order_mobile, $order_city, $order_addr, $vat_number, $invoice_title, $order_addressee_name, $order_addressee_addr, $order_addressee_mobile, $product_plan, $order_ship_time);
				$cart = get_cookie("cart",TRUE);
				if (isset($cart) && !empty($cart)) { 
					$cart = stripslashes($cart);
					$cart = json_decode($cart, true); 
				    foreach ($cart as $row) {
				   	// print_r($row['plan_id']);
				    	$product_plan = $this->product_manage_model->get_product_plan($row['plan_id']);
				    	$this->order_manage_model->add_order_dt($trade_no,$row['plan_id'],$row['num'],$product_plan->plan_price);
				    }
				    //remove cookie				    
				    $this->load->helper('cookie'); 
					$config = array(
						'name' => 'cart',
						'path' => WEB_PATH
					);
					delete_cookie($config);
				} 
				$result['status'] = 1;
			}
			else
			{
				if($pwd == $chk_pwd)
				{
					$member_id = $this->member_manage_model->do_add_member($order_email, $pwd, $order_name, $order_mobile, $order_city, $order_addr, $vat_number, $invoice_title);
					if($member_id)
					{
						$success = $this->fuel_auth->front_login($order_email, $pwd);
						$session_key = $this->fuel_auth->get_session_namespace();
						$user_data = $this->session->userdata($session_key);
						$config = array(
							'name' => $this->fuel_auth->get_fuel_trigger_cookie_name(), 
							'value' => serialize(array('id' => $this->fuel_auth->user_data('id'), 'language' => $this->fuel_auth->user_data('language'))),
							'expire' => 0,
							'path' => WEB_PATH
						);
						set_cookie($config);
						//$trade_no = $this->order_manage_model->create_empty_order($member_id, $order_email, $order_name, $order_mobile, $order_city, $order_addr, $vat_number, $invoice_title, $order_addressee_name, $order_addressee_addr, $order_addressee_mobile, $product_plan, $order_ship_time);

						$trade_no = $this->order_manage_model->create_empty_order($member_id, $order_email, $order_name, $order_mobile, $order_city, $order_addr, $vat_number, $invoice_title, $order_addressee_name, $order_addressee_addr, $order_addressee_mobile, $product_plan, $order_ship_time);
						$cart = get_cookie("cart",TRUE);
						if (isset($cart) && !empty($cart)) { 
							$cart = stripslashes($cart);
							$cart = json_decode($cart, true); 
						    foreach ($cart as $row) { 
						    	$product_plan = $this->product_manage_model->get_product_plan($row['plan_id']);
						    	$this->order_manage_model->add_order_dt($trade_no,$row['plan_id'],$row['num'],$product_plan->plan_price);
						    }		
						    //remove cookie				    
						    $this->load->helper('cookie'); 
							$config = array(
								'name' => 'cart',
								'path' => WEB_PATH
							);
							delete_cookie($config);
						} 
					}

					$result['status'] = 1;
				}
				else
				{
					$result['status'] = -1;
					$result['msg'] = "密碼不一致";
				}
			}


			// //參數設定
			// //ALLPAY店號
			// $merchant_id = MERCHANT_ID;

			// //交易日期
			// $trade_date = date("Y/m/d H:i:s");
			// //交易金額
			// $trade_amt = (int)$product_plan->plan_price; 
			// //交易說明
			// $trade_desc = $product_plan->pro_name;
			// //卡號
			// $card_no = "0";//$this->input->get_post("card_no"); 
			//  //卡片有效月
			// $card_mm = "0";//$this->input->get_post("card_mm");
			// //卡片有效年
			// $card_yy = "0";//$this->input->get_post("card_yy");
			// //卡號檢核碼
			// $card_CVV2 = "0";//$this->input->get_post("card_CVV2");
			// //編碼設訂
			// $charset = "utf-8"; 
			// //回傳網址
			// $return_url = urlencode("http://taste-it.com.tw/payment/callback"); 
			// //告知授權與否網址
			// $rerver_reply_url = "";//urlencode("http://www.taste-it.com.tw/payment/callback"); 
			// //導回商家網址
			// $client_back_url = "";//urlencode("http://www.taste-it.com.tw/payment/callback"); 
			// //傳送資料網址
			// $gateway="http://pay-stage.allpay.com.tw/payment/gateway";

			// $base_xml = $this->get_base_xml();

			// $base_xml = str_replace("[++merchant_id++]", $merchant_id, $base_xml);
			// $base_xml = str_replace("[++merchant_trade_no++]", $trade_no, $base_xml);
			// $base_xml = str_replace("[++trade_date++]", $trade_date, $base_xml);
			// $base_xml = str_replace("[++trade_amt++]", $trade_amt, $base_xml);
			// $base_xml = str_replace("[++trade_desc++]", $trade_desc, $base_xml);
			// $base_xml = str_replace("[++card_no++]", $card_no, $base_xml);
			// $base_xml = str_replace("[++card_mm++]", $card_mm , $base_xml);
			// $base_xml = str_replace("[++card_yy++]", $card_yy , $base_xml);
			// $base_xml = str_replace("[++card_CVV2++]", $card_CVV2 , $base_xml);
			// $base_xml = str_replace("[++charset++]", $charset , $base_xml);
			// $base_xml = str_replace("[++return_url++]", $return_url, $base_xml);
			// $base_xml = str_replace("[++srerver_reply_url++]", $rerver_reply_url, $base_xml);
			// $base_xml = str_replace("[++client_back_url++]", $client_back_url, $base_xml);
			// //header('Content-Type: text/xml');
			// //print_r($base_xml);
			// //exit;
			// //xml 加密

			// $encode_data = $this->encrypt($base_xml, $iv, $key);

			
			// $result['encode_data'] = $encode_data;
			// $result['merchant_id'] = $merchant_id;
			// $result['gateway'] = $gateway;
			// $result['base_xml'] = $base_xml;

			echo json_encode($result);
		}
		else
		{
			redirect(site_url(), 'refresh');
		}

		die();

	} 

	function test()
	{
		$vars['views'] = 'payment_view';
		$vars['gateway'] = "http://pay-stage.allpay.com.tw/payment/gateway";
		$vars['encode_data'] = "";
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$page_init = array('location' => 'payment_view');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR	
	}


	function payment_callback()
	{
		$this->load->library('email');
		$this->load->module_model(EDM_FOLDER, 'edm_manage_model');
		$this->load->module_model(ORDER_FOLDER, 'order_manage_model');

		//ALLPAY提供IV與KEY
		$key = ALLPAY_KEY;
		$iv = ALLPAY_IV	;
		//接收資料
		$XMLData = $_POST["XMLData"];
		//解密資料
		$rs = $this->decrypt($XMLData,$key,$iv);
		//回傳結果解密
		$result_data = simplexml_load_string($rs);

		$this->order_manage_model->update_rtn_code($result_data->Data->MerchantTradeNo, $result_data->Data->RtnCode);

		if($result_data->Data->RtnCode == 1)
		{
			$order_result = $this->order_manage_model->get_order_detail($result_data->Data->MerchantTradeNo);
			$this->order_manage_model->update_plan_num($order_result->product_plan);
			$email_result = $this->edm_manage_model->get_edm_detail(5);

			$this->email->from('service@taste-it.com.tw', '【Taste it】海鮮團購網站');
			$this->email->to($order_result->order_email); 
			$msg = htmlspecialchars_decode($email_result->content);

			$msg = str_replace("[++order_name++]", $order_result->order_name, $msg);
			$msg = str_replace("[++product_name++]", $order_result->pro_name, $msg);
			$msg = str_replace("[++plan_desc++]", $order_result->plan_desc, $msg);

			$this->email->subject($email_result->subject);
			$this->email->message($msg);
			
			if(!$this->email->send())
			{
				log_message('error', $this->email->print_debugger());
			}

			$vars['order_name'] = $order_result->order_name;
		}


		$vars['views'] = 'send';
		$vars['RtnCode'] = (int)$result_data->Data->RtnCode;
		$vars['order_id'] = $result_data->Data->MerchantTradeNo;
		$vars['trade_price'] = $result_data->Data->amount;
		$vars['pro_cate_1'] = base_url()."prod/pro_list/pro_cate_0001";
		$vars['pro_cate_2'] = base_url()."prod/pro_list/pro_cate_0002";
		$page_init = array('location' => 'send');
		$this->load->module_library(FUEL_FOLDER, 'fuel_page', $page_init);
		$this->fuel_page->add_variables($vars);
		$this->fuel_page->render(FALSE, FALSE); //第二個FALSE為在前台不顯示ADMIN BAR	
	}

	function plu_redirect($url, $delay, $msg)
	{
	    if( isset($msg) )
	    {
	        $this->notify($msg);
	    }

	    echo "<meta http-equiv='Refresh' content='$delay; url=$url'>";
	}

	function notify($msg)
	{
	    $msg = addslashes($msg);
	    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	    echo "<script type='text/javascript'>alert('$msg')</script>\n";
	    echo "<noscript>$msg</noscript>\n";
	    return;
	}
}