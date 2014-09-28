<?php
class Core_model extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

public function do_update_fbid2resume($account,$fbid){
    $sql = " UPDATE mod_resume SET fb_account = '$fbid' WHERE account = '$account' ";
    $res_1 = $this->db->query($sql);
    return true;

}

    public function is_mobile(){
    

        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            return true; //手機版
        else
            return false;  //電腦版
    }
    public function send_mail_by_id($id,$target=""){

        $sql = @" SELECT * FROM mod_edm WHERE edm_id = ? LIMIT 1";
        $para = array(
            $id
        );

        $res_1 = $this->db->query($sql, $para);

        if($res_1->num_rows() > 0)
        {
            $result = $res_1->result();
            //print_r($result);
            //echo $result[0]->edm_id;
           // echo fuel_var("mail_from_name");
            $from_name = $this->get_site_var("mail_from_name");
            $from_mail = $this->get_site_var("mail_from");
            $subject = $result[0]->subject;
            $content = "<html><header></header><body>";
            $content .= htmlspecialchars_decode($result[0]->content);
            $content .= "</body></html>";
            $this->load->library('email');
            //$this->email->mailtype('html');

            $this->email->from($from_mail, $from_name);
            $this->email->to($target); 
            //$this->email->cc('another@another-example.com'); 
            //$this->email->bcc('them@their-example.com'); 

            $this->email->subject($subject);
            $this->email->message($content); 

            //$this->email->send();

            if($this->email->send())
            {
                //echo "success";
                $this->set_mail_log($id,$subject,'1',$content,$target);
                
               // echo $this->email->print_debugger();
            }
            else
            {
                $this->set_mail_log($id,$subject,'0',$content,$target);
               // echo $this->email->print_debugger();
            }
            return $this->email->print_debugger();
        }else{
            return false;
        }
    }

    public function set_mail_log($edm_id,$subject,$has_send,$content,$target){
        $sql = "INSERT INTO `mod_edm_log`( `edm_id`, `subject`, `has_send`, `msg`, `run_date`, `content`, `target`, `member_id`) 
        VALUES (?,?,?,?,NOW(),?,?,?)";

        $para = array(
            $edm_id,
            $subject,
            $has_send,
            '0',
            $content,
            $target,
            $target
        );

        $res_1 = $this->db->query($sql, $para);
    }

    public function get_site_var($name){
        $sql = @"SELECT value FROM fuel_site_variables WHERE name = '$name' LIMIT 1 ";
        $query = $this->db->query($sql);
        //echo $sql;exit;
        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result[0]->value;
        }

        return null;
    }
    public function get_fb_data($redirect_uri="user/do_fb_regi"){
        $this->load->library('facebook'); 
        $user = $this->facebook->getUser();
        //print_r($user);
        //die();
        
        if ($user) {
            try {
                $data['user_profile'] = $this->facebook->api('/me');
            } catch (FacebookApiException $e) {
                //print_r($e);
                //die();
                $user = null;
            }
        }else {
            $this->facebook->destroySession();
        }



        if ($user) {

            $data['logout_url'] = site_url('user/logout'); // Logs off application
            // OR 
            // Logs off FB!
            // $data['logout_url'] = $this->facebook->getLogoutUrl();

        } else {
            $data['login_url'] = $this->facebook->getLoginUrl(array(
                'redirect_uri' => site_url($redirect_uri), 
                'scope' => array("email") // permissions here
            ));
        }

        if(!isset($data['login_url'])){
            $data['login_url'] = site_url($redirect_uri);
        }
        return $data;
    }

    public function get_school_list($filter = "")
    {
        $sql = @"SELECT * FROM mod_school ".$filter." ORDER BY id DESC ";
        $query = $this->db->query($sql);
        //echo $sql;exit;
        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return null;
    }

    public function do_logged_in($account,$password){

        $sql = @" SELECT * FROM mod_resume WHERE account = ? AND password = MD5(?) LIMIT 1";
        $para = array(
            $account,
            $password
        );

        $res_1 = $this->db->query($sql, $para);

        if($res_1->num_rows() > 0)
        {

            return true;
        }else{
            return false;
        }
    }
    function is_account_logged_in($account){
        //$this->load->helper('cookie');
        $cookie = $this->input->cookie("ytalent_account",TRUE);

        //print_r($cookie);
      // print_r($this->input->cookie());
       // die();
        if(isset($cookie) && !empty($cookie) && $cookie == $account){
            return true;
        }else{
            delete_cookie("ytalent_account");
            return false;
        }
    }
    function is_logged_in(){
        //$this->load->helper('cookie');
        $cookie = $this->input->cookie("ytalent_account",TRUE);

        //print_r($cookie);
      // print_r($this->input->cookie());
       // die();
        if(isset($cookie) && !empty($cookie)){
            return true;
        }else{
            delete_cookie("ytalent_account");
            return false;
        }
    }
    function get_logged_in_account(){
        //$this->load->helper('cookie');
        $cookie = $this->input->cookie("ytalent_account",TRUE);

        //print_r($cookie);
      // print_r($this->input->cookie());
       // die();
        if(isset($cookie) && !empty($cookie)){
            return $cookie;
        }else{
            //delete_cookie("ytalent_account");
            return null;
        }
    }

     public function get_user_not_skill($account)
    { 
        // $b = urldecode($q);

        $sql = @"SELECT code_id,code_name FROM mod_code WHERE codekind_key ='skill' 
                and code_id not in (select skill_id from mod_skill where account = '$account') 
         ";
        $para = array($account);
        // echo $sql;
        $query = $this->db->query($sql,$para);

        if($query->num_rows() > 0)
        {
            $result = $query->result(); 
            return $result;
        }

        return;
    }
    public function get_skill_list($filter = "")
    {
        $sql = @"SELECT b.code_id,b.code_name FROM mod_skill a left join mod_code b on a.skill_id = b.code_id ".$filter." ORDER BY id DESC ";
        $query = $this->db->query($sql);
        //echo $sql;exit;
        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return null;
    }
     public function get_school($name="")
    { 
        // $b = urldecode($q);

        $sql = @"SELECT code_id,code_name FROM mod_code WHERE codekind_key ='school' ";
        $para = array();
        if (!empty($name)) {
            $sql .= " AND code_name = ? ";
            array_push($para, $name);
        }
        // $para = array($q);
        // echo $sql;
        $query = $this->db->query($sql,$para);

        if($query->num_rows() > 0)
        {
            $result = $query->result(); 
            return $result;
        }

        return;
    }

    public function get_skill()
    { 
        // $b = urldecode($q);

        $sql = @"SELECT code_id,code_name FROM mod_code WHERE codekind_key ='skill' ";
        // $para = array($q);
        // echo $sql;
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $result = $query->result(); 
            return $result;
        }

        return;
    }

    public function do_register($email, $password,$name="",$fb_email="",$fb_id="",$birth="",$contact_tel="")
    {

        $check_sql = @" SELECT member_id FROM mod_member where member_account = '$email' ";
        $query = $this->db->query($check_sql);
        //echo $sql;exit;
        if($query->num_rows() > 0)
        {
            return false;
        }
        if($fb_email == ""){
            $fb_email = $email;
        }
        //if(strpos($fb_email, "@") > -1 )
        //    $mail_res  = $this->code_model->send_mail_by_id("4",$fb_email);

        if($fb_id==""){
            $sql = @" INSERT INTO mod_member(member_account,member_pass,member_name,member_mobile,create_date,modi_time)VALUES(?,MD5(?),?,?,NOW(),NOW())";
            $para = array($email,$password, $name, $birth,$contact_tel);
        }else{
            $sql = @" INSERT INTO mod_member(member_id,member_account,member_pass,member_name,member_mobile,create_date,modi_time)VALUES(?,?,MD5(?),?,?,NOW(),NOW())";
            $para = array($fb_id,$email,$password, $name,$contact_tel);
        }


        $success = $this->db->query($sql, $para);

        if($success)
        {
            return true;
        }

        return;
    }
    public function do_contact($data)
    {
        //print_r($data);

        $contact_arr = array();
        $job_arr = array();
        $skill_require_arr = array();
        $skill_learn_arr = array();

        foreach ($data as $key => $value) {
            //echo $key."-".strpos($key,"job_");
            if(strpos($key,"learn_skill")>-1){
                $skill_learn_arr = $value;
            }elseif(strpos($key,"require_skill")>-1){
                $skill_require_arr = $value;
            }elseif(strpos($key,"job_")>-1){
                $job_arr[$key] = $value;
            }else{
                $contact_arr[$key] = $value;
            }
        }
        //print_r($contact_arr);
        //print_r($job_arr);
        //print_r($skill_require_arr);
        //print_r($skill_learn_arr);
        //die();


        $insert_contact_sql = " INSERT INTO mod_contact(
                                name,
                                email,
                                contact_tel ,
                                contact_type,
                                content,
                                contact_status)VALUES(?,?,?,?,?,?)";


        $para = array(
                $contact_arr["name"],
                $contact_arr["mail"], 
                $contact_arr["phone"], 
                $contact_arr["contact_type"], 
                $contact_arr["content"], 
                0
            );
        $res_1 = $this->db->query($insert_contact_sql, $para);

        $sql = "SELECT last_insert_id() as ID";
        $id_result= $this->db->query($sql);
        $contact_id = $id_result->row()->ID; 


        $insert_job_sql = " INSERT INTO mod_job(
                                contact_id,
                                company_name,
                                job_address ,
                                salary_hour,
                                salary_week,
                                salary_month)VALUES(?,?,?,?,?,?)";


        $para = array(
                $contact_id,
                $job_arr["job_company_name"], 
                $job_arr["job_address"], 
                $job_arr["job_salary_hour"], 
                $job_arr["job_salary_week"],
                $job_arr["job_salary_month"]
            );
        $res_2 = $this->db->query($insert_job_sql, $para);

        $sql = "SELECT last_insert_id() as ID";
        $id_result= $this->db->query($sql);
        $job_id = $id_result->row()->ID; 


        foreach ($skill_require_arr as $key) {
            $insert_skill_sql = " INSERT INTO  mod_skill (account,skill_id,job_id,skill_type)VALUES(?,?,'$job_id','0')";


            $para = array(
                "",
                $key
            );

            $this->db->query($insert_skill_sql, $para);
        }

        foreach ($skill_learn_arr as $key) {
            $insert_skill_sql = " INSERT INTO  mod_skill (account,skill_id,job_id,skill_type)VALUES(?,?,'$job_id','1')";


            $para = array(
                "",
                $key
            );

            $this->db->query($insert_skill_sql, $para);
        }

        return true;
    }
    public function do_update_resume($data)
    {
        //print_r($data);

        $account_arr = array();
        $school_arr = array();
        $exp_arr = array();
        $skill_arr = array();

        foreach ($data as $key => $value) {
            //echo $key."-".strpos($key,"job_");
            if(strpos($key,"school_")>-1){
                $school_arr[$key] = $value;
            }elseif(strpos($key,"job_")>-1){
                $exp_arr[$key] = $value;
            }elseif(strpos($key,"skill")>-1){
                $skill_arr = $value;
            }else{
                $account_arr[$key] = $value;
            }
        }

        $update_accunt_sql = " UPDATE mod_resume SET 
                                name = ?,
                                birth = ?,
                                contact_tel = ?,
                                address_city = ?,
                                address_area = ?,
                                address_zip = ?,
                                address = ?,
                                job_status = ?,
                                find_job_kind = ?,
                                about_self = ?,
                                sex = ?
                                WHERE account = ? ";


        $para = array(
            $account_arr["name"],
            $account_arr["birth"], 
            $account_arr["contact_tel"], 
            $account_arr["address_city"], 
            $account_arr["address_area"], 
            $account_arr["address_zip"], 
            $account_arr["address"],  
            $account_arr["now_status"], 
            $account_arr["find_kind"], 
            $account_arr["about_self"], 
            $account_arr["sex"],
            $account_arr["account"]
            );
        $res_1 = $this->db->query($update_accunt_sql, $para);
 //print_r($account_arr);
       //die();

        if(isset($account_arr["recommended"]) && $account_arr["recommended"] != null && $account_arr["recommended"] != ""){
            
            $update_sql_1 = " UPDATE mod_resume SET 
                        recommended = ?
                        WHERE account = ? ";
            $para = array(
                $account_arr["recommended"], 
                $account_arr["account"]
            );

            $res = $this->db->query($update_sql_1, $para);
        }
        if(isset($account_arr["avatar"]) && $account_arr["avatar"] != null && $account_arr["avatar"] != ""){
            $update_sql_2 = " UPDATE mod_resume SET 
                        avatar = ?
                        WHERE account = ? ";
            $para = array(
                $account_arr["avatar"], 
                $account_arr["account"]
            );                       
            $res = $this->db->query($update_sql_2, $para);
        }

       

        $delete_school_sql = "DELETE FROM mod_school WHERE account = ?";
        $para = array(
                    $account_arr["account"], 
                );
        $this->db->query($delete_school_sql, $para);

     
 
        for($i = 1; $i < 100 ;$i++){
            if(isset($school_arr["school_id_$i"]) && $school_arr["school_id_$i"] != ""){
                $school_id = $this->get_school($school_arr["school_id_$i"]);
                if (sizeof($school_id)>0) {
                    $school_id = $school_id[0]->code_id; 
                }else{
                      $insert_school_code_sql = " INSERT INTO  mod_code (codekind_key,code_name,parent_id,modi_time,lang_code)VALUES(?,?,'-1',NOW(),'tw')";
                      $para = array(
                        "school",
                        $school_arr["school_id_$i"]                         
                    );

                    $this->db->query($insert_school_code_sql, $para);
                    $sql = "SELECT last_insert_id() as ID";
                    $id_result= $this->db->query($sql);
                    $school_id = $id_result->row()->ID; 
                }
                $insert_school_sql = " INSERT INTO  mod_school (account,school_id,is_grad,is_attend)VALUES(?,?,?,?)";
                if($school_arr["school_state_$i"] == 'G'){
                    $is_grad = "1";
                    $is_attend = "0";
                }else{
                    $is_grad = "0";
                    $is_attend = "1";
                }

                $para = array(
                    $account_arr["account"],
                    $school_id ,
                    $is_grad, 
                    $is_attend  
                );

                $res_2 = $this->db->query($insert_school_sql, $para);
            }else{
                if(!isset($school_arr["school_id_$i"])){
                    break;
                }
            }
        }

        $delete_exp_sql = "DELETE FROM mod_exp WHERE account = ?";
        $this->db->query($delete_exp_sql, $para);

        for($i = 1; $i < 100 ;$i++){
            if(isset($exp_arr["job_company_name_$i"]) && $exp_arr["job_company_name_$i"] != ""){
                $insert_exp_sql = " INSERT INTO  mod_exp (account,company_name,job_title,job_start_date,job_end_date)VALUES(?,?,?,?,?)";


                $para = array(
                    $account_arr["account"],
                    $exp_arr["job_company_name_$i"], 
                    $exp_arr["job_title_$i"], 
                    $exp_arr["job_start_date_$i"], 
                    $exp_arr["job_end_date_$i"]
                );

                $res_3 = $this->db->query($insert_exp_sql, $para);
            }else{
                if(!isset($exp_arr["job_company_name_$i"])){
                    break;
                }
            }
        }

        $delete_skill_sql = "DELETE FROM mod_skill WHERE account = ?";        
        $this->db->query($delete_skill_sql, $para);

        foreach ($skill_arr as $key) {
            $insert_skill_sql = " INSERT INTO  mod_skill (account,skill_id)VALUES(?,?)";


            $para = array(
                $account_arr["account"],
                $key
            );

            $this->db->query($insert_skill_sql, $para);
        }

        return true;
    }


    public function get_account_data($account)
    {
        $sql = @"SELECT * FROM mod_resume WHERE account = '$account' ";
        $query = $this->db->query($sql);
        $account_arr = array();

        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $key => $value) {
                
                $account_arr[$key] = $value;
            }

            $skill_sql = " SELECT skill_id,(SELECT code_name FROM mod_code WHERE code_id = skill_id LIMIT 1 ) as skill_name FROM mod_skill WHERE account = '$account' ";
            $skill_query = $this->db->query($skill_sql);
            if($skill_query->num_rows() > 0)
            {
                $account_arr["skills"] = $skill_query->result();
            }else{
                $account_arr["skills"] = null;
            }

            $school_sql = " SELECT school_id,(SELECT code_name FROM mod_code WHERE code_id = school_id LIMIT 1 ) as school_name,is_grad,is_attend FROM mod_school WHERE account = '$account'  ";
            $school_query = $this->db->query($school_sql);
            if($school_query->num_rows() > 0)
            {
                $account_arr["schools"] = $school_query->result();
            }else{
                $account_arr["schools"] = null;
            }

            $exp_sql = " SELECT * FROM mod_exp WHERE account = '$account'  ";
            $exp_query = $this->db->query($exp_sql);
            if($exp_query->num_rows() > 0)
            {
                $account_arr["exp"] = $exp_query->result();
            }else{
                $account_arr["exp"] = null;
            }

            return $account_arr;
        }else{
            return null;
        }

        return 0;
    }

    public function get_case_list($dataStart, $dataLen, $filter)
    {
        $sql = @"SELECT * FROM data_case_detail ".$filter." ORDER BY cd_id DESC LIMIT $dataStart, $dataLen";
        $query = $this->db->query($sql);
        //echo $sql;exit;
        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return false;
    }

    public function get_case_detail($cd_id)
    {
        $sql = @"SELECT * FROM data_case_detail WHERE cd_id=?";
        $para = array($cd_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row;
        }

        return;
    }

    public function get_case_url($cd_id)
    {
        $sql = @"SELECT cd_url FROM data_case_detail WHERE cd_id=?";
        $para = array($cd_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row->cd_url;
        }

        return;
    }

    public function do_insert_help($insert_data)
    {
        $sql = @"INSERT INTO data_case_help (cd_id, cli_id, case_url, cli_intro, modi_time) VALUES (?, ?, ?, ?, NOW())";
        $para = array(
                $insert_data['cd_id'],
                $insert_data['cli_id'],
                $insert_data['case_url'],
                $insert_data['cli_intro']
            );
        $success = $this->db->query($sql, $para);

        if($success)
        {
            return true;
        }

        return;
    }

    public function get_case_help_by_cli_id($insert_data)
    {
        $sql = @"SELECT ch_id FROM data_case_help WHERE cli_id=? AND case_url=?";
        $para = array($insert_data['cli_id'], $insert_data['case_url']);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

        return;
    }

    public function get_case_cate_code()
    {
        $sql = @"SELECT * FROM mod_code WHERE codekind_key='case_cate_group' AND parent_id='-1'";
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return;
    }

    public function get_case_sub_cate($parent_id)
    {
        $sql = @"SELECT * FROM mod_code WHERE codekind_key='case_cate_group' AND parent_id=?";
        $para = array($parent_id);
        $query = $this->db->query($sql, $para);
        
        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return;
    }

    public function get_parent_id($code_id)
    {
        $sql = @"SELECT parent_id FROM mod_code WHERE codekind_key='case_cate_group' AND code_id=?";
        $para = array($code_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row->parent_id;
        }

        return;
    }

    public function get_case_cate_sub_ids($code_id)
    {
        $tmp = array();
        $sql = @"SELECT code_id FROM mod_code WHERE codekind_key='case_cate_group' AND parent_id=?";
        $para = array($code_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $result = $query->result();

            foreach ($result as $key => $row) 
            {
                array_push($tmp, $row->code_id);
            }

            $txt = implode(",", $tmp);

            return $txt;
        }

        return;
    }

    public function get_relation_case($filter, $cd_id)
    {
        $sql = @"SELECT cd_id, cd_title FROM data_case_detail ".$filter." AND cd_id<>'$cd_id' ORDER BY run_date DESC LIMIT 0, 5";
        $query = $this->db->query($sql);

        if($query->num_rows() > 0)
        {
            $result = $query->result();

            return $result;
        }

        return;
    }

    public function get_case_cate_title($code_id)
    {
        $sql = @"SELECT code_name FROM mod_code WHERE code_id=?";
        $para = array($code_id);
        $query = $this->db->query($sql, $para);

        if($query->num_rows() > 0)
        {
            $row = $query->row();

            return $row->code_name;
        }

        return;
    }

}