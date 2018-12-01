<?php
    class Index_model extends CI_Model
    {

        public function __construct()
        {
            $this->load->database();
        }

        //验证登录
        public function select_psw($table,$name,$pass){
            $this->db->select('adminPsw,adminName,id,authority,adminNick');
            $data = $this->db->get_where($table,array('adminName'=>$name,'adminPsw'=>$pass))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }

        }

    }