<?php
class Member_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function user_login($member_account, $member_pass)
    {
        $pwd = md5($member_pass);

        $sql = @"SELECT COUNT(*) AS cnt FROM mod_member WHERE member_account = '$member_account' AND member_pass = '$pwd' ";
        $para = array($member_account, $pwd);
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $row = $query->row();
            if($row->cnt > 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        return false;
    }

     public function get_order_total_rows($member_id)
    {
        $sql = @"SELECT COUNT(*) AS total_rows 
                 FROM mod_order_detail a
                 LEFT JOIN mod_order b ON a.order_id = b.order_id
                 INNER JOIN mod_plan c ON c.plan_id = a.plan_id
                 INNER JOIN mod_product d ON d.pro_id = c.pro_id
                 WHERE b.member_id = ? ";

        $para = array($member_id);
        $query = $this->db->query($sql,$para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row->total_rows;
        }

        return 0;
    }

    public function order_info($dataStart, $dataLen,$member_id){
        $sql = @"SELECT a.order_id,b.order_time,d.pro_name,a.num * a.amount total_amount,
                b.order_status,b.order_ship_status ,b.order_note
                FROM mod_order_detail a
                LEFT JOIN mod_order b ON a.order_id = b.order_id
                INNER JOIN mod_plan c ON c.plan_id = a.plan_id
                INNER JOIN mod_product d ON d.pro_id = c.pro_id
                WHERE b.member_id = ?
                ORDER BY b.order_time DESC LIMIT $dataStart, $dataLen
                ";
        $para = array($member_id);
        $query = $this->db->query($sql,$para);

        if($query->num_rows() > 0)
        {
             $result = $query->result();
             return $result;
        }

        return;
    }

}