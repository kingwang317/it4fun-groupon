<?php
class News_front_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function get_total_rows($filter="")
    {
        $sql = @"SELECT COUNT(*) AS total_rows FROM mod_news ".$filter;
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row->total_rows;
        }

        return 0;
    }

    public function get_list($dataStart, $dataLen, $filter)
    { 

        $sql = @"SELECT * FROM mod_news $filter ORDER BY date DESC LIMIT $dataStart, $dataLen";
         
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return ;
    }

}