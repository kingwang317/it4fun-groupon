<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once(FUEL_PATH.'models/base_module_model.php');

class Codekind_lists_model extends Base_module_model {
	
	public $required = array('codekind_name', 'codekind_value1', 'codekind_key');
	public $hidden_fields = array('modi_time');
	
	function __construct()
	{
		parent::__construct('mod_codekind'); // table name
	}

	/*
	* 內建function
	* 說明：列表頁，可做select, join您想要的列表
	*/
	function list_items($limit = NULL, $offset = NULL, $col = 'modi_time', $order = 'desc')
	{
		$this->db->select('id, codekind_name, codekind_value1, modi_time' , FALSE);
		$data = parent::list_items($limit, $offset, $col, $order);

		return $data;
	}
	
	/*
	* 內建function
	* 說明：新增頁面(create)
	*/
	function form_fields($values = array())
	{
		$fields = parent::form_fields();
		
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