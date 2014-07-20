<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');
require_once(MODULES_PATH.'/codekind/config/codekind_constants.php');

class Codekind_subcodelists_model extends Base_module_model {
	
	public $required = array('code_name', 'code_value1', 'code_key', 'codekind_key');
	public $hidden_fields = array('modi_time', 'parent_id');

	
	function __construct()
	{
		parent::__construct('mod_code', CODEKIND_FOLDER); // table name
	}

	/*
	* 內建function
	* 說明：列表頁，可做select, join您想要的列表
	*/
	function list_items($limit = NULL, $offset = NULL, $col = 'modi_time', $order = 'desc')
	{

		$this->db->select($this->_tables['mod_code'].'.id, '.$this->_tables['mod_code'].'.code_name, '.$this->_tables['mod_codekind'].'.codekind_name, '.$this->_tables['mod_code'].'.code_value1, '.$this->_tables['mod_code'].'.modi_time' , FALSE);
		$this->db->where('parent_id !=',  -1);
		$this->db->join($this->_tables['mod_codekind'], $this->_tables['mod_codekind'].'.codekind_key = '.$this->_tables['mod_code'].'.codekind_key', 'inner');
		
		$data = parent::list_items($limit, $offset, $col, $order);

		foreach ($data as $key => $rows) {
			$sec_url = 'sub/codelists/'.$rows['id'];
			$data[$key]['second'] = '<a href="'.$sec_url.'">下一層</a>';
		}
		
		return $data;
	}
	
	/*
	* 內建function
	* 說明：新增頁面(create)
	*/
	function form_fields($values = array())
	{
		$fields = parent::form_fields();
		$CI =& get_instance();
		$CI->load->module_model(CODEKIND_FOLDER, 'codekind_lists_model');
		$CI->load->module_model(CODEKIND_FOLDER, 'codekind_codelists_model');

		$sql = "SELECT id, code_name FROM mod_code WHERE parent_id = -1";
		$query = $this->db->query($sql);

		$code_options = array();
		//$code_options['-1'] = "上層";

		if($query->num_rows() > 0)
		{
			$result = $query->result();
		}
		else
		{
			$result = array();
		}

		foreach($result as $row)
		{
			$code_options[$row->id] = $row->code_name;
		}


		$codekind_options = $CI->codekind_lists_model->options_list('codekind_key');
		$codekind_values = (!empty($values['id'])) ? array_keys($CI->codekind_codelists_model->find_all_array_assoc('id', array('parent_id' => $values['id']))) : array();
		$fields['codekind_key'] = array('label' => '群組名稱', 'type' => 'select', 'options' => $codekind_options, 'first_option' => '請選擇群組...');
		$fields['parent_id'] = array('label' => '屬於', 'type' => 'select', 'options' => $code_options);
		//$fields['published']['order'] = 1000;
		return $fields;
	}

	/*
	* 內建function
	* 說明：表單submit的前處理，可在這裡做表單驗證
	*/
	function on_before_save($values)
	{
		$today = date("Y-m-d H:i:s");
		$values['modi_time'] = $today;

		return $values;
	}
	
	
}

?>