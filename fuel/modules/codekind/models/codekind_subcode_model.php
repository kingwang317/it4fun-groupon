<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Codekind_subcode_model extends MY_Model {
	
	function __construct()
	{
		$CI =& get_instance();
		$CI->config->module_load(CODEKIND_FOLDER, CODEKIND_FOLDER);
		$tables = $CI->config->item('tables');
		parent::__construct($tables['mod_code']); // table name
	}

	public function get_subcode($code_id)
	{
		$sql = @"SELECT a.id, a.code_name, b.codekind_name, a.code_value1, a.modi_time FROM mod_code a, mod_codekind b WHERE a.codekind_key=b.codekind_key AND a.parent_id=?";
		$para = array($code_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function is_top($code_id)
	{
		$sql = @"SELECT parent_id FROM mod_code WHERE id=?";
		$para = array($code_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row->parent_id;
		}

		return;
	}

	public function get_subcode_detail($code_id)
	{
		$sql = @"SELECT * FROM mod_code WHERE id=?";
		$para = array($code_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function get_codekind()
	{
		$sql = @"SELECT * FROM mod_codekind ORDER BY modi_time desc";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return $result;
	}

	public function do_edit_code($code_id, $codekind_key, $code_name, $code_key, $code_value1, $code_value2, $code_value3)
	{
		$sql = @"UPDATE mod_code SET codekind_key=?, code_name=?, code_key=?, code_value1=?, code_value2=?, code_value3=? WHERE id=?";
		$para = array($codekind_key, $code_name, $code_key, $code_value1, $code_value2, $code_value3, $code_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_create_code($codekind_key, $code_name, $code_key, $code_value1, $code_value2, $code_value3, $parent_id)
	{
		$sql = @"INSERT INTO mod_code(codekind_key, code_name, code_key, code_value1, code_value2, code_value3, parent_id, modi_time) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
		$para = array($codekind_key, $code_name, $code_key, $code_value1, $code_value2, $code_value3, $parent_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_del_code($code_id)
	{
		$sql = @"DELETE FROM mod_code WHERE id=?";
		$para = array($code_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}
	
}