<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Edm_manage_model extends MY_Model {
	
	function __construct()
	{
		$CI =& get_instance();
		$CI->config->module_load(EDM_FOLDER, EDM_FOLDER);
		$tables = $CI->config->item('tables');
		parent::__construct($tables['mod_edm']); // table name
	}

	public function get_total_rows()
	{
		$sql = @"SELECT COUNT(*) AS total_rows FROM mod_edm ORDER BY send_time ASC";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row->total_rows;
		}

		return 0;
	}

	public function get_edm_list($dataStart, $dataLan)
	{
		$sql = @"SELECT * FROM mod_edm ORDER BY send_time DESC LIMIT $dataStart, $dataLan";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function do_add_edm($post_data)
	{
		$sql = @"INSERT INTO mod_edm (subject, content, modi_time) VALUES (?, ?, NOW())";
		$para = array($post_data['subject'], $post_data['content']);
		$success = $this->db->query($sql, $para);
		$edm_id = $this->db->insert_id();

		if($success)
		{
			return true;
		}

		return;
	}

	public function do_add_edm_and_send($post_data)
	{
		$sql = @"INSERT INTO mod_edm (subject, content, modi_time) VALUES (?, ?, NOW())";
		$para = array($post_data['subject'], $post_data['content']);
		$success = $this->db->query($sql, $para);
		$edm_id = $this->db->insert_id();

		if($success)
		{
			$this->add_log($post_data, $edm_id);
			return true;
		}

		return;
	}

	public function add_log($post_data, $edm_id)
	{
		$sql = @"SELECT member_id, member_account FROM mod_member WHERE agree_edm=1 ORDER BY member_id ASC";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0 )
		{
			$result = $query->result();

			if(isset($result))
			{
				foreach($result as $key=>$row)
				{
					$sql = @"INSERT INTO mod_edm_log (edm_id, subject, has_send, msg, content, target, member_id) VALUES (?, ?, 0, 0, ?, ?, ?)";
					$para = array($edm_id, $post_data['subject'], $post_data['content'], $row->member_account, $row->member_id);
					$this->db->query($sql, $para);
				}
			}
		}

		return true;
	}

	public function get_edm_detail($edm_id)
	{
		$sql = @"SELECT * FROM mod_edm WHERE edm_id=?";
		$para = array($edm_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function do_multi_del_edm($edm_ids)
	{
		$sql = @"DELETE FROM mod_edm WHERE edm_id IN ($edm_ids)";
		$success = $this->db->query($sql);

		return $success;
	}

	public function do_del_edm($edm_id)
	{
		$sql = @"DELETE FROM mod_edm WHERE edm_id=?";
		$para = array($edm_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function scan_job()
	{
		$sql = @"SELECT * FROM mod_edm_log WHERE has_send=0 ORDER BY edm_log_id ASC LIMIT 0, 1000";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function update_send_status($edm_log_id)
	{
		$today = date("Y-m-d H:i:s");
		$data = array(
		               'has_send' => 1,
		               'run_date' => $today
		            );

		$this->db->where('edm_log_id', $edm_log_id);
		$this->db->update('mod_edm_log', $data);

		return true;
	}

	public function update_send_date($edm_id)
	{
		$today = date("Y-m-d H:i:s");
		$data = array(
		               'send_time' => $today
		            );

		$this->db->where('edm_id', $edm_id);
		$this->db->update('mod_edm', $data);

		return true;
	}

	public function get_send_record_list($edm_id, $dataStart, $dataLen)
	{
		$sql = @"SELECT el.edm_log_id, el.target, m.member_name, el.run_date, el.has_send FROM mod_edm_log el, mod_member m WHERE el.member_id=m.member_id AND edm_id=? ORDER BY el.run_date DESC LIMIT $dataStart, $dataLen";
		$para = array($edm_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function send_member_email($edm_id, $order_ids, $email_data)
	{
		//$sql = @"SELECT m.member_account, m.member_id, m.member_name, p.pro_name FROM mod_member m, mod_order o, mod_product p, mod WHERE o.member_id=m.member_id AND o.product_id=p.pro_id AND o.order_id IN ($order_ids)";
		$sql = @"SELECT mo.member_id, mo.order_id, mo.product_plan, mo.order_name, mo.order_email, mo.order_mobile, mo.order_addr, mo.order_vat_number, mo.order_invoice_title, mo.RtnCode, 
						mo.order_status, mo.order_ship_status, mo.order_inv_status, mo.order_addressee_name, mo.order_addressee_addr,
						mo.order_addressee_mobile, mo.order_time, mo.order_price, mpr.pro_name, mpr.pro_id, mpl.plan_desc, mpl.plan_id, mpl.plan_price
				 FROM mod_order mo, mod_product mpr, mod_plan mpl 
				 WHERE mo.product_id=mpr.pro_id AND mo.product_plan=mpl.plan_id AND mo.order_id IN ($order_ids)";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			if(isset($result))
			{
				foreach($result as $row)
				{
					$msg = $email_data['content'];
					$msg = str_replace("[++member_name++]", $row->order_name, $msg);
					$msg = str_replace("[++product_name++]", $row->pro_name, $msg);
					$msg = str_replace("[++plan_desc++]", $row->plan_desc, $msg);
					$sql = @"INSERT INTO mod_edm_log (edm_id, subject, has_send, msg, content, target, member_id) VALUES (?, ?, 0, 0, ?, ?, ?)";
					$para = array($edm_id, $email_data['subject'], $msg, $row->order_email, $row->member_id);
					$this->db->query($sql, $para);
				}
			}

			return true;
		}

		return;
	}

	public function do_update_edm($post_data, $edm_id)
	{
		$sql = @"UPDATE mod_edm SET subject=?, content=? WHERE edm_id=?";
		$para = array($post_data['subject'], $post_data['content'], $edm_id);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return true;
		}

		return;
	}
	
}