<?php
class Product_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    function get_top_list($pro_id)
    {
        $today = date('Y-m-d h:y:s');
        $sql = @"SELECT p.pro_id, p.pro_name, p.pro_add_time, p.pro_off_time, p.pro_cover_photo, COUNT(o.order_id) AS sell_cnt
                FROM mod_product p, mod_order o  
                WHERE p.pro_status='pro_status_0001' AND p.pro_off_time > '$today' AND p.pro_id=o.product_id  AND p.pro_id <> '$pro_id'
                GROUP BY p.pro_id 
                ORDER BY sell_cnt DESC, p.modi_time DESC LIMIT 0, 4";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $result = $query->result();
            
            if(isset($result))
            {
                foreach($result as $key=>$row)
                {
                    $photo = $this->get_pro_cover($row->pro_id, 'product', $row->pro_cover_photo);

                    if(isset($photo))
                    {
                        $result[$key]->photo = $photo;
                    }
                    else
                    {
                        $result[$key]->photo->ga_name = "no image";
                        $result[$key]->photo->ga_url = "templates/images/about_logo.jpg";
                        $result[$key]->photo->ga_w = "240";
                        $result[$key]->photo->ga_h = "240";
                    }
                }
            }

            return $result;
        }

        return;
    }

    public function get_code($codekind_key, $filter="")
    {
        $sql = @"SELECT id,code_name, code_key, code_value1 FROM mod_code WHERE codekind_key=?".$filter;
        $para = array($codekind_key);
        $query  = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $results = $query->result();

            return $results;
        }

        return;
    }

    function get_cart_pro_list($pro_ids)
    {
        $result = FALSE;
        $today = date('Y-m-d h:y:s');

        $sql = @"SELECT * FROM mod_product a inner join  (
                    SELECT pro_id AS p_id ,MAX(plan_price) AS plan_price FROM mod_plan GROUP BY pro_id
                )  b on a.pro_id = b.p_id
                WHERE pro_status='pro_status_0001' AND pro_off_time > '$today'  
                AND a.pro_id in ($pro_ids)
                ORDER BY pro_id  DESC ";

                // print_r($sql);
                // die;

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $result = $query->result();
            
            if(isset($result))
            {
                foreach($result as $key=>$row)
                {
                    $photo = $this->get_pro_cover($row->pro_id, 'product', $row->pro_cover_photo);

                    if(isset($photo))
                    {
                        $result[$key]->photo = $photo;
                    }
                    else
                    {
                        $result[$key]->photo = new stdClass();
                        $result[$key]->photo->ga_name = "no image";
                        $result[$key]->photo->ga_url = "templates/images/about_logo.jpg";
                        $result[$key]->photo->ga_w = "240";
                        $result[$key]->photo->ga_h = "194";
                    }
                }
            }

            return $result;
        }

        return;
    }

    function get_pro_list($filter,$limit)
    {
        $result = FALSE;
        $today = date('Y-m-d h:y:s');

        $sql = @"SELECT pro_id, pro_name, pro_add_time, pro_off_time, pro_cover_photo ,pro_summary,pro_promote,
                pro_group_price,pro_original_price , count(pro_id)  as pro_selled_cnt
                FROM mod_product p left join
                mod_order mo on p.pro_id=mo.product_id
                WHERE pro_status='pro_status_0001' AND pro_off_time > '$today' 
                $filter
                GROUP BY pro_id, pro_name, pro_add_time, pro_off_time, pro_cover_photo ,pro_summary,pro_promote,
                pro_group_price,pro_original_price
                ORDER BY pro_order ASC, p.modi_time DESC $limit ";

                // print_r($sql);
                // die;

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $result = $query->result();
            
            if(isset($result))
            {
                foreach($result as $key=>$row)
                {
                    $photo = $this->get_pro_cover($row->pro_id, 'product', $row->pro_cover_photo);

                    if(isset($photo))
                    {
                        $result[$key]->photo = $photo;
                    }
                    else
                    {
                        $result[$key]->photo = new stdClass();
                        $result[$key]->photo->ga_name = "no image";
                        $result[$key]->photo->ga_url = "templates/images/about_logo.jpg";
                        $result[$key]->photo->ga_w = "240";
                        $result[$key]->photo->ga_h = "194";
                    }
                }
            }

            return $result;
        }

        return;
    }

    function get_pro_cover($pro_id, $prog_id, $ga_id)
    {
        $sql = @"SELECT ga_name, ga_url, ga_w, ga_h FROM mod_gallery WHERE f_id=? AND prog_id=? AND ga_id=?";
        $para = array($pro_id, $prog_id, $ga_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row;
        }

        return;
    }

    function get_pro_photo_url($pro_id, $prog_id, $ga_id)
    {
        $sql = @"SELECT ga_url, ga_w, ga_h FROM mod_gallery WHERE f_id=? AND prog_id=? AND ga_id=?";
        $para = array($pro_id, $prog_id, $ga_id);
        $query = $this->db->query($sql, $para);
        $row = new stdClass();

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row;
        }
        else
        {
            $row->ga_url = "templates/images/about_logo.jpg";

            return $row;
        }

        return;
    }

    function get_pro_detail($pro_id)
    {
        $sql = @"SELECT * FROM mod_product WHERE pro_id=? AND pro_status = 'pro_status_0001'";
        $para = array($pro_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row;
        }

        return;
    }

    function get_selled_cnt($pro_id)
    {
        $sql = @"SELECT COUNT(*) AS cnt FROM mod_product mp, mod_order mo WHERE mp.pro_id=mo.product_id AND pro_id=?";
        $para = array($pro_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row;
        }

        return;
    }

    function get_ad_data()
    {
        $today = date("Y-m-d H:i:s");
        $sql = @"SELECT a.pro_id, a.pro_name, a.pro_off_time, a.pro_summary, b.ga_url, b.ga_w, b.ga_h FROM mod_product a, mod_gallery b WHERE a.pro_ad_photo=b.ga_id AND a.pro_off_time >= ? AND a.pro_status = 'pro_status_0001'";
        $para = array($today);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->result();

            return $row;
        }

        return;
    }

    function prod_is_existed($pro_id)
    {
        $sql = @"SELECT pro_id FROM mod_product WHERE pro_id=? AND pro_status='pro_status_0001'";
        $para = array($pro_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            return 1;
        }

        return 0;
    }

    function get_prod_plan($pro_id)
    {
        $sql = @"SELECT * FROM mod_plan WHERE pro_id=? ORDER BY plan_seq ASC, modi_time DESC";
        $para = array($pro_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $result = $query->result();

            if(isset($result))
            {
                foreach ($result as $key => $row) 
                {
                    $is_empty = $this->chk_plan_num($row->plan_id);

                    if($is_empty)
                    {
                        $result[$key]->is_empty = 1;
                    }
                    else
                    {
                        $result[$key]->is_empty = 0;
                    }
                }
            }

            return $result;
        }

        return;
    }

    public function chk_plan_num($plan_id)
    {
        $sql = @"SELECT plan_num, plan_order_tmp_num FROM mod_plan WHERE plan_id=?";
        $para = array($plan_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            $num = $row->plan_num - $row->plan_order_tmp_num;

            if($num == 0 || $num < 0)
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

    public function get_pro_meta($pro_id)
    {
        $sql = @"SELECT mp.pro_name, mp.pro_summary, mg.ga_url, mp.seo_title, mp.seo_kw, mp.seo_desc FROM mod_product mp, mod_gallery mg WHERE mp.pro_text_photo=mg.ga_id AND pro_id=?";
        $para = array($pro_id);
        $query = $this->db->query($sql, $para);
        $meta_data = array();
        if($query->num_rows() > 0)
        {
            $row = $query->row();

            $meta_data['page_title']    = empty($row->seo_title)?$row->pro_name:$row->seo_title;
            $meta_data['meta_kw']       = empty($row->seo_kw)?"":$row->seo_kw;
            $meta_data['meta_desc']     = empty($row->seo_desc)?$row->pro_summary:$row->seo_desc;
            //$meta_data['og_title']        = isset($row->seo_title)?$row->seo_title:$row->pro_name;
            //$meta_data['og_desc']     = isset($row->seo_desc)?$row->seo_desc:$row->summary;
            $meta_data['og_image']      = empty($row->ga_url)?"":$row->ga_url;

            return $meta_data;
        }
    }

    public function get_old_prod()
    {
        $today = date("Y-m-d H:i:s");
        $sql = @"SELECT pro_id, pro_name, pro_add_time, pro_off_time, pro_cover_photo 
                FROM mod_product 
                WHERE pro_status='pro_status_0001' AND pro_off_time < '$today' AND pro_status = 'pro_status_0001'
                ORDER BY pro_order ASC, modi_time DESC";
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $result = $query->result();

            if(isset($result))
            {
                foreach($result as $key=>$row)
                {
                    $photo = $this->get_pro_cover($row->pro_id, 'product', $row->pro_cover_photo);

                    if(isset($photo))
                    {
                        $result[$key]->photo = $photo;
                    }
                    else
                    {
                        $result[$key]->photo = new stdClass();
                        $result[$key]->photo->ga_name = "no image";
                        $result[$key]->photo->ga_url = "/assets/images/logo_fuel.png";
                        $result[$key]->photo->ga_w = "240";
                        $result[$key]->photo->ga_h = "240";
                    }
                }
            }

            return $result;
        }

        return;
    }

    public function update_click_num($pro_id)
    {
        $sql = @"UPDATE mod_product SET click_num=click_num+1 WHERE pro_id = ?";
        $para = array($pro_id);
        $this->db->query($sql, $para);

        return;
    }

}