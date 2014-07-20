<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Member_manage_model extends MY_Model {
	
	function __construct()
	{
		$CI =& get_instance();
		$CI->config->module_load(MEMBER_FOLDER, MEMBER_FOLDER);
		$tables = $CI->config->item('tables');
		parent::__construct($tables['mod_member']); // table name
	}

	public function get_total_rows($filter="")
	{
		$sql = @"SELECT COUNT(*) AS total_rows FROM mod_member ".$filter." ORDER BY modi_time DESC";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row->total_rows;
		}

		return 0;
	}

	public function get_member_list($dataStart, $dataLen, $filter)
	{
		$sql = @"SELECT * FROM mod_member".$filter." ORDER BY modi_time DESC LIMIT $dataStart, $dataLen";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_member_detail($member_id)
	{
		$sql = @"SELECT * FROM mod_member WHERE member_id=?";
		$para = array($member_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_member_detail_row($member_id)
	{
		$sql = @"SELECT * FROM mod_member WHERE member_id=?";
		$para = array($member_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function do_add_member($member_account, $member_pass, $member_name, $member_mobile, $member_city, $member_addr, $vat_num, $inv_title)
	{
		$sql = @"INSERT INTO mod_member (member_account, 
										member_pass, 
										member_name, 
										member_mobile,
										member_city,
										member_addr, 
										vat_number, 
										invoice_title, 
										create_time,
										modi_time
										) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
		$para = array(
						$member_account, 
						md5($member_pass),
						$member_name,
						$member_mobile,
						$member_city,
						$member_addr, 
						$vat_num,
						$inv_title
					);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			$member_id = $this->db->insert_id();

			return $member_id;
		}

		return;
	}

	public function do_edit_member($member_id, $member_account, $member_name, $member_mobile, $member_addr, $vat_num, $inv_title, $member_city)
	{
		$sql = @"UPDATE mod_member SET member_account=?,member_name=?,member_mobile=?,member_addr=?,vat_number=?, invoice_title=?, member_city=?, modi_time=NOW() WHERE member_id=?";

		$para = array($member_account, $member_name, $member_mobile, $member_addr, $vat_num, $inv_title, $member_city, $member_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function update_pwd($pwd, $member_id)
	{
		$sql = @"UPDATE mod_member SET member_pass=? WHERE member_id=?";
		$para = array(md5($pwd), $member_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_del_member($member_id)
	{
		$sql = @"DELETE FROM mod_member WHERE member_id=?";
		$para = array($member_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_multi_del_member($member_ids)
	{
		$sql = @"DELETE FROM mod_member WHERE member_id IN ($member_ids)";
		$success = $this->db->query($sql);

		return $success;
	}

	public function valid_user($user, $pwd)
	{
		$sql = @"SELECT member_id, member_name FROM mod_member WHERE member_account=? AND member_pass=? LIMIT 0,1";
		$para = array($user, md5($pwd));
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{

			return $query->result_array();
		}

		return;
	}

	public function chk_email($email)
	{
		$sql = @"SELECT COUNT(member_account) as cnt FROM mod_member WHERE member_account=?";
		$para = array($email);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			if($row->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}

		return FALSE;
	}

	public function update_pwd_by_email($new_pwd, $member_account)
	{
		$md_pwd = md5($new_pwd);
		$sql = @"UPDATE mod_member SET member_pass=? WHERE member_account=?";
		$para = array($md_pwd, $member_account);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return TRUE;
		}

		return FALSE;
	}
	
}