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

}