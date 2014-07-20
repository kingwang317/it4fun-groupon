<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_manage_model extends MY_Model {
	
	function __construct()
	{
		$CI =& get_instance();
		$CI->config->module_load(ORDER_FOLDER, ORDER_FOLDER);
		$tables = $CI->config->item('tables');
		parent::__construct($tables['mod_order']); // table name
		date_default_timezone_set("Asia/Taipei");
	}

	public function get_total_rows($filter="")
	{
		$sql = @"SELECT COUNT(*) AS total_rows FROM mod_order mo, mod_product mpr, mod_plan mpl
				WHERE mo.product_id=mpr.pro_id AND mo.product_plan=mpl.plan_id".$filter.
				" ORDER BY mo.modi_time DESC";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row->total_rows;
		}

		return 0;
	}

	public function get_order_list($dataStart, $dataLen, $filter)
	{
		$sql = @"SELECT mo.order_id, mo.product_plan, mo.order_name, mo.order_email, mo.order_mobile, mo.order_addr, mo.order_vat_number, mo.order_invoice_title, mo.RtnCode, 
						mo.order_status, mo.order_ship_status, mo.order_inv_status, mo.order_addressee_name, mo.order_addressee_addr,
						mo.order_addressee_mobile, mo.order_time, mo.order_price, mpr.pro_name, mpr.pro_id, mpl.plan_desc, mpl.plan_id, mpl.plan_price
				 FROM mod_order mo, mod_product mpr, mod_plan mpl 
				 WHERE mo.product_id=mpr.pro_id AND mo.product_plan=mpl.plan_id".$filter.
				 " ORDER BY mo.modi_time DESC LIMIT $dataStart, $dataLen";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_order_list_excel($filter)
	{
		$sql = @"SELECT mo.order_id, mo.product_plan, mo.order_name, mo.order_email, mo.order_mobile, mo.order_addr, mo.order_vat_number, mo.order_invoice_title,
						mo.order_status, mo.order_ship_status, mo.order_inv_status, mo.order_addressee_name, mo.order_addressee_addr,
						mo.order_addressee_mobile, mo.order_time, mo.order_price, mpr.pro_name, mpr.pro_id, mpl.plan_desc, mpl.plan_id, mpl.plan_price
				 FROM mod_order mo, mod_product mpr, mod_plan mpl 
				 WHERE mo.product_id=mpr.pro_id AND mo.product_plan=mpl.plan_id AND mo.RtnCode=1".$filter.
				 " ORDER BY mo.modi_time DESC";
		$query = $this->db->query($sql);

		return $query;
	}

	public function get_order_detail($order_id)
	{
		$sql = @"SELECT mo.order_id, mo.product_plan, mo.order_name, mo.order_email, mo.order_mobile, mo.order_addr, mo.order_vat_number, mo.order_invoice_title, mo.RtnCode, 
						mo.order_status, mo.order_ship_status, mo.order_inv_status, mo.order_addressee_name, mo.order_addressee_addr, mo.order_ship_time,
						mo.order_addressee_mobile, mo.order_note, mo.order_time, mo.order_price, mpr.pro_name, mpr.pro_id, mpl.plan_desc, mpl.plan_id
				 FROM mod_order mo, mod_product mpr, mod_plan mpl WHERE mo.product_id=mpr.pro_id AND mo.product_plan=mpl.plan_id AND order_id=?";
		$para = array($order_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function create_empty_order($member_id, $order_email, $order_name, $order_mobile, $order_city, $order_addr, $vat_number, $invoice_title, $order_addressee_name, $order_addressee_addr, $order_addressee_mobile, $product_plan, $order_ship_time)
	{
		$today = date("Ymd");
		$num = $this->get_conut_order();
		$num = (int)$num + 1;
		$tmp = str_pad($num,4,'0',STR_PAD_LEFT);

		$order_id = $today.$tmp;

		$sql = @"INSERT INTO mod_order (order_id,
										member_id,
										product_id,
										product_plan,
										order_name,
										order_email,
										order_addr,
										order_mobile,
										order_vat_number,
										order_invoice_title,
										order_addressee_name,
										order_addressee_addr,
										order_addressee_mobile,
										order_status, 
										order_ship_status, 
										order_inv_status,
										order_price,
										RtnCode,
										order_ship_time,
										order_time,
										modi_time
										) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
		$para = array(
						$order_id,
						$member_id,
						$product_plan->pro_id,
						$product_plan->plan_id,
						$order_name,
						$order_email,
						$order_city.$order_addr,
						$order_mobile,
						$vat_number,
						$invoice_title,
						$order_addressee_name,
						$order_addressee_addr,
						$order_addressee_mobile,
						'order_status_0001',
						'ship_status_0001',
						'inv_status_0001',
						$product_plan->plan_price,
						0,
						$order_ship_time
					);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return $order_id;
		}

		return;	
	}

	public function do_add_order($order_name, $order_email, $order_mobile, $order_addr, $order_vat_num, $order_inv_title, $oa_name, $oa_mobile, $oa_addr, $pro_id, $pro_plan)
	{
		$today = date("Ymd");
		$num = $this->get_conut_order();
		$num = (int)$num + 1;
		$tmp = str_pad($num,4,'0',STR_PAD_LEFT);

		$order_id = $today.$tmp;

		$sql = @"INSERT INTO mod_order (order_id,
										order_name, 
										order_email, 
										order_addr, 
										order_mobile, 
										order_vat_number, 
										order_invoice_title, 
										order_addressee_name, 
										order_addressee_addr, 
										order_addressee_mobile, 
										order_status, 
										order_ship_status, 
										order_inv_status,
										product_id,
										product_plan,
										order_time,
										modi_time
										) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
		$para = array(
						$order_id,
						$order_name, 
						$order_email,
						$order_addr,
						$order_mobile,  
						$order_vat_num, 
						$order_inv_title, 
						$oa_name, 
						$oa_addr, 
						$oa_mobile,
						'order_status_0001',
						'ship_status_0001',
						'inv_status_0001',
						$pro_id,
						$pro_plan,
					);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function get_conut_order()
	{
		$today = date("Y-m-d");
		$date_start = $today." 00:00:00";
		$date_end = $today." 23:59:59";

		$sql = @"SELECT order_id FROM mod_order WHERE order_time >= ? AND order_time <= ? ORDER BY order_time DESC";
		$para = array($date_start, $date_end);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return substr($row->order_id, 8,12);
		}

		return 0;
	}

	public function do_edit_order($order_id, $order_name, $order_email, $order_mobile, $order_addr, $order_vat_num, $order_inv_title, $oa_name, $oa_mobile, $oa_addr, $order_status, $order_ship_status, $order_inv_status, $plan_id, $order_ship_time)
	{
		$sql = @"UPDATE mod_order SET order_name=?,
										order_email=?,
										order_mobile=?,
										order_addr=?,
										order_vat_number=?,
										order_invoice_title=?,
										order_addressee_name=?,
										order_addressee_mobile=?,
										order_addressee_addr=?,
										order_status=?,
										order_ship_status=?,
										order_inv_status=?,
										product_plan=?,
										order_ship_time=?,
										modi_time=NOW() WHERE order_id=?";
		$para = array($order_name, $order_email, $order_mobile, $order_addr, $order_vat_num, $order_inv_title, $oa_name, $oa_mobile, $oa_addr, $order_status, $order_ship_status, $order_inv_status, $plan_id, $order_ship_time, $order_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_del_order($order_id)
	{
		$sql = @"DELETE FROM mod_order WHERE order_id=?";
		$para = array($order_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_multi_del_order($order_ids)
	{
		$sql = @"DELETE FROM mod_order WHERE order_id IN ($order_ids)";
		$success = $this->db->query($sql);

		return $success;
	}

	public function get_code($codekind_key, $filter="")
	{
		$sql = @"SELECT code_name, code_key, code_value1 FROM mod_code WHERE codekind_key=?".$filter;
		$para = array($codekind_key);
		$query 	= $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$results = $query->result();

			return $results;
		}

		return;
	}

	public function get_prod_by_code($code_key)
	{
		$sql = @"SELECT pro_id, pro_name FROM mod_product WHERE pro_cate=? AND pro_status='pro_status_0001' ORDER BY modi_time DESC";
		$para = array($code_key);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_plan_by_prod($pro_id)
	{
		$sql = @"SELECT plan_id, plan_desc FROM mod_plan WHERE pro_id=?";
		$para  = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function update_order_status($order_ids, $status)
	{
		$sql = @"UPDATE mod_order SET order_status=?, modi_time=NOW() WHERE order_id IN ($order_ids)";
		$para = array($status);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return TRUE;
		}

		return;
	}

	public function update_ship_status($order_ids, $status)
	{
		$sql = @"UPDATE mod_order SET order_ship_status=?, modi_time=NOW() WHERE order_id IN ($order_ids)";
		$para = array($status);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return TRUE;
		}

		return;
	}

	public function update_inv_status($order_ids, $status)
	{
		$sql = @"UPDATE mod_order SET order_inv_status=?, modi_time=NOW() WHERE order_id IN ($order_ids)";
		$para = array($status);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return TRUE;
		}

		return;
	}

	public function update_rtn_code($order_id, $status)
	{
		$sql = @"UPDATE mod_order SET RtnCode=?, modi_time=NOW() WHERE order_id=$order_id";
		$para = array($status);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return TRUE;
		}

		return;
	}

	public function fail_order_del($order_id)
	{
		$sql = @"DELETE FROM mod_order WHERE order_id=?";
		$para = array($order_id);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return TRUE;
		}

		return;
	}

	public function getExcel($pro_id, $filter)
	{
		if($pro_id)
		{
			$sql = @"SELECT mo.order_addressee_name AS `收件人名稱`, mo.order_addressee_addr AS `收件人地址`, mo.order_addressee_mobile AS `電話`, mo.order_ship_time AS `送達時間`, mo.order_note AS `備註`, mpr.pro_name AS `產品名稱`, mp.plan_desc AS `方案` 
					FROM mod_order mo, mod_plan mp, mod_product mpr WHERE mo.product_plan = mp.plan_id AND mo.product_id = mpr.pro_id AND mo.product_id=? AND mo.RtnCode=1 ".$filter." ORDER BY mo.order_time ASC";			
		}
		else
		{
			$sql = @"SELECT mo.order_addressee_name AS `收件人名稱`, mo.order_addressee_addr AS `收件人地址`, mo.order_addressee_mobile AS `電話`, mo.order_ship_time AS `送達時間`, mo.order_note AS `備註`, mpr.pro_name AS `產品名稱`, mp.plan_desc AS `方案` 
				FROM mod_order mo, mod_plan mp, mod_product mpr WHERE mo.product_plan = mp.plan_id AND mo.product_id = mpr.pro_id AND mo.RtnCode=1 ".$filter." ORDER BY mo.order_time ASC";
		}
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);
		return $query;
	}

	public function get_member_order($dataStart, $dataLen, $filter)
	{
		$sql = @"SELECT o.order_id, o.order_time, p.pro_name, pl.plan_desc, pl.plan_price, o.order_status, o.order_ship_status, o.order_inv_status, o.order_note FROM mod_order o, mod_product p, mod_plan pl WHERE o.product_id=p.pro_id AND o.product_plan=pl.plan_id ".$filter." ORDER BY order_time DESC LIMIT $dataStart, $dataLen";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function update_plan_tmp_num($plan_id)
	{

		$sql = @"SELECT plan_order_tmp_num FROM mod_plan WHERE plan_id=?";
		$para = array($plan_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$current_num = $row->plan_order_tmp_num;
			$num = $current_num + 1;
			$sql = @"UPDATE mod_plan SET plan_order_tmp_num=? WHERE plan_id=?";
			$para = array($num, $plan_id);
			$success = $this->db->query($sql, $para);

			if($success)
			{
				return true;
			}
			else
			{
				return;
			}
		}

		return;			
		
	}

	public function update_plan_num($plan_id)
	{
		$sql = @"SELECT plan_num FROM mod_plan WHERE plan_id=?";
		$para = array($plan_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$current_num = $row->plan_num;
			$num = $current_num - 1;
			$sql = @"UPDATE mod_plan SET plan_num=? WHERE plan_id=?";
			$para = array($num, $plan_id);
			$success = $this->db->query($sql, $para);

			$sql = @"UPDATE mod_plan SET plan_order_tmp_num = plan_order_tmp_num-1 WHERE plan_id=?";
			$para = array($plan_id);
			$success = $this->db->query($sql, $para);

			if($success)
			{
				return true;
			}
			else
			{
				return;
			}
		}

		return;
	}

	public function update_order_tmp_num()
	{
		$sql = @"UPDATE mod_plan SET plan_order_tmp_num = 0";
		$success = $this->db->query($sql);

		if($success)
		{
			return true;
		}

		return;
	}
	
}