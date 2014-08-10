<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_manage_model extends MY_Model {
	
	function __construct()
	{
		$CI =& get_instance();
		$CI->config->module_load(PRODUCT_FOLDER, PRODUCT_FOLDER);
		$tables = $CI->config->item('tables');
		parent::__construct($tables['mod_product']); // table name
	}

	public function get_total_rows($filter)
	{
		if($filter == "")
		{
			$filter = " WHERE pro_cate IS NOT NULL ";
		}
		$sql = @"SELECT COUNT(pro_id) AS total_rows FROM mod_product ".$filter;

		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row->total_rows;
		}

		return 0;
	}

	public function get_product_list($dataStart, $dataLan, $filter)
	{
		$sql = @"SELECT a.pro_id, a.pro_name, a.pro_add_time, a.pro_off_time, a.modi_time, b.code_name, COUNT(c.order_id) AS sell_cnt, SUM(c.order_price) AS sell_amt, click_num 
				FROM mod_product AS a
				INNER JOIN mod_code AS b ON a.pro_cate=b.id 
				LEFT JOIN mod_order AS c ON c.product_id = a.pro_id AND c.RtnCode=1 
				".$filter."
				GROUP BY a.pro_id
				ORDER BY a.modi_time DESC
				LIMIT $dataStart, $dataLan"; 
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_pro_detail($pro_id)
	{
		$sql = @"SELECT * FROM mod_product WHERE pro_id=?";
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_code($codekind_key, $filter="")
	{
		$sql = @"SELECT id,code_name, code_key, code_value1 FROM mod_code WHERE codekind_key=?".$filter;
		$para = array($codekind_key);
		$query 	= $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$results = $query->result();

			return $results;
		}

		return;
	}

	public function get_product_detail($pro_id)
	{
		$sql = @"SELECT * FROM mod_product WHERE pro_id=?";
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function do_add_product($r_data)
	{
		$sql = @"INSERT INTO mod_product (
										pro_name, 
										pro_cate, 
										pro_desc, 
										pro_format, 
										pro_ship_note,
										pro_original_price,
										pro_group_price,
										pro_add_time, 
										pro_off_time, 
										pro_order,
										pro_status,
										pro_promote,
										seo_title,
										seo_kw,
										seo_desc,
										create_time,
										modi_time
										) 
							VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
		$para = array(
						$r_data['pro_name'],
						$r_data['pro_cate'],
						$r_data['pro_desc'],
						$r_data['pro_format'],
						$r_data['pro_ship_note'],
						$r_data['pro_original_price'],
						$r_data['pro_group_price'],
						$r_data['pro_add_time'],
						$r_data['pro_off_time'],
						$r_data['pro_order'],
						$r_data['pro_status'],
						$r_data['pro_promote'],
						$r_data['seo_title'],
						$r_data['seo_kw'],
						$r_data['seo_desc']
					);
		$success = $this->db->query($sql, $para);
		$pro_id = $this->db->insert_id();

		$plan_num = count($r_data['plan_id']);
		if($plan_num > 0)
		{
			for($i=0; $i<$plan_num; $i++)
			{
				$this->do_set_plan_pro_id($r_data['plan_id'][$i], $pro_id);
			}
		}

		return $pro_id;
	}

	public function do_empty_product()
	{
		$sql = @"INSERT INTO mod_product (
										pro_name,
										pro_summary,
										pro_start_sell,
										pro_cate, 
										pro_desc, 
										pro_format, 
										pro_ship_note,
										pro_original_price,
										pro_group_price,
										pro_add_time, 
										pro_off_time, 
										pro_order,
										pro_status,
										pro_promote,
										seo_title,
										seo_kw,
										seo_desc,
										create_time,
										modi_time
										) 
							VALUES (null, null, null, null, null, null, null, null, null, null, null, null, 'pro_status_0002',null, null, null, null, NOW(), null)";

		$success = $this->db->query($sql);
		$pro_id = $this->db->insert_id();

		return $pro_id;		
	}

	public function insert_photo($r_data)
	{
		$sql = @"INSERT INTO mod_gallery (ga_name, ga_desc, ga_path, ga_url, ga_w, ga_h, prog_id, f_id, create_time, modi_time) 
					VALUES (null, null, ?, ?, null, null, ?, ?, NOW(), NOW())";
		$para  = array(
				$r_data['ga_path'],
				$r_data['ga_url'],
				$r_data['prog_id'],
				$r_data['f_id']
			);
		$success = $this->db->query($sql, $para);

		return $this->db->insert_id();
	}

	public function do_update_product($r_data)
	{
		$sql = @"UPDATE mod_product SET 
						pro_name=?,
						pro_summary=?,
						pro_start_sell=?,
						pro_cate=?,
						pro_desc=?,
						pro_format=?,
						pro_ship_note=?,
						pro_original_price=?,
						pro_group_price=?,
						pro_add_time=?,
						pro_off_time=?,
						pro_order=?,
						pro_status=?,
						pro_promote=?,
						seo_title=?,
						seo_kw=?,
						seo_desc=?,
						modi_time=NOW()
				WHERE pro_id=?";

		$para = array(
						$r_data['pro_name'],
						$r_data['pro_summary'],
						$r_data['pro_start_sell'],
						$r_data['pro_cate'],
						$r_data['pro_desc'],
						$r_data['pro_format'],
						$r_data['pro_ship_note'],
						$r_data['pro_original_price'],
						$r_data['pro_group_price'],
						$r_data['pro_add_time'],
						$r_data['pro_off_time'],
						$r_data['pro_order'],
						$r_data['pro_status'],
						$r_data['pro_promote'],
						$r_data['seo_title'],
						$r_data['seo_kw'],
						$r_data['seo_desc'],
						$r_data['pro_id']
					);
		$success = $this->db->query($sql, $para);

		$plan_num = count($r_data['plan_id']);
		if($plan_num > 0)
		{
			for($i=0; $i<$plan_num; $i++)
			{
				$this->do_set_plan_pro_id($r_data['plan_id'][$i], $r_data['pro_id']);
			}
		}

		return $success;
	}

	public function do_add_plan($plan_desc, $plan_price, $plan_num, $plan_seq, $pro_id)
	{
		$sql = @"INSERT INTO mod_plan (plan_desc, plan_price, plan_num, plan_seq, pro_id) VALUES (?, ?, ?, ?, ?)";
		$para = array($plan_desc, $plan_price, $plan_num, $plan_seq, $pro_id);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return  $this->db->insert_id();
		}

		return;
	}

	public function do_del_plan($plan_id)
	{
		$sql = @"DELETE FROM mod_plan WHERE plan_id=?";
		$para = array($plan_id);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return 1;
		}

		return;
	}

	public function do_del_product($pro_id)
	{
		$sql = @"DELETE FROM mod_product WHERE pro_id=?";
		$para = array($pro_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_multi_del_product($pro_ids)
	{
		$sql = @"DELETE FROM mod_product WHERE pro_id IN ($pro_ids)";
		$success = $this->db->query($sql);

		return $success;
	}

	public function get_ga_path($ga_ids)
	{
		$sql = @"SELECT ga_path FROM mod_gallery WHERE ga_id IN ($ga_ids)";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function do_del_photos($ga_ids)
	{
		$sql = @"DELETE FROM mod_gallery WHERE ga_id IN ($ga_ids)";
		$success = $this->db->query($sql);

		return $success;
	}

	public function do_update_photo_data($ga_id, $ga_name, $ga_desc, $ga_w, $ga_h)
	{
		$sql = @"UPDATE mod_gallery SET ga_name=?, ga_desc=?, ga_w=?, ga_h=? WHERE ga_id=?";
		$para = array($ga_name, $ga_desc, $ga_w, $ga_h, $ga_id);
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_update_pro_cover_photo($pro_cover_photo, $pro_id)
	{

		$sql = @"UPDATE mod_product SET pro_cover_photo=? WHERE pro_id=?";
		$para = array($pro_cover_photo, $pro_id);
		
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_update_pro_text_photo($pro_text_photo, $pro_id)
	{

		$sql = @"UPDATE mod_product SET pro_text_photo=? WHERE pro_id=?";
		$para = array($pro_text_photo, $pro_id);
		
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function do_update_pro_ad_photo($pro_ad_photo, $pro_id)
	{

		$sql = @"UPDATE mod_product SET pro_ad_photo=? WHERE pro_id=?";
		$para = array($pro_ad_photo, $pro_id);
		
		$success = $this->db->query($sql, $para);

		return $success;
	}

	public function get_photo_data_detail($pro_id, $prog_id)
	{
		$sql = @"SELECT ga_id, ga_name, ga_desc, ga_w, ga_h, ga_url FROM mod_gallery WHERE f_id=? AND prog_id=?";
		$para = array($pro_id, $prog_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$ga_result['photo_data'] = $query->result();
			$ga_result['cover_text'] = $this->get_pro_cover_text($pro_id);

			return $ga_result;
		}

		return;
	}

	public function get_pro_cover_text($pro_id)
	{
		$sql = @"SELECT pro_cover_photo, pro_text_photo, pro_ad_photo FROM mod_product WHERE pro_id=?";
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function do_set_plan_pro_id($plan_id, $pro_id)
	{
		$sql = @"UPDATE mod_plan SET pro_id=? WHERE plan_id=?";
		$para = array($pro_id, $plan_id);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return 1;
		}

		return;
	}

	public function do_update_plan($plan_id, $plan_desc, $plan_price, $plan_num, $plan_seq)
	{
		$sql = @"UPDATE mod_plan SET plan_desc=?, plan_price=?, plan_num=?, plan_seq=? WHERE plan_id=?";
		$para = array($plan_desc, $plan_price, $plan_num, $plan_seq, $plan_id);
		$success = $this->db->query($sql, $para);

		if($success)
		{
			return 1;
		}

		return;
	}

	public function get_plan_for_pro_id($pro_id)
	{
		$sql = @"SELECT * FROM mod_plan WHERE pro_id=?";
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return $result;
		}

		return;
	}

	public function get_plan_detail($plan_id)
	{
		$sql = @"SELECT plan_desc, plan_price, plan_num, plan_seq FROM mod_plan WHERE plan_id=?";
		$para = array($plan_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function get_pro_photo($pro_id)
	{
		$sql = @"SELECT ga_id, ga_name, ga_desc, ga_h, ga_w, ga_url FROM mod_gallery WHERE f_id=?";
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$result = $query->result();

			return 1;
		}

		return;
	}

	public function get_product_plan($plan_id)
	{
		$sql = @"SELECT prod.pro_name, prod.pro_id, pl.plan_id, pl.plan_price FROM mod_product prod, mod_plan pl WHERE prod.pro_id=pl.pro_id AND pl.plan_id=?";
		$para = array($plan_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			$row = $query->row();

			return $row;
		}

		return;
	}

	public function get_pro_plan_cnt($pro_id)
	{
		$sql = @"SELECT COUNT(*) FROM mod_plan WHERE pro_id=?";
		$para = array($pro_id);
		$query = $this->db->query($sql, $para);

		if($query->num_rows() > 0)
		{
			return 1;
		}

		return 0;
	}
	
}