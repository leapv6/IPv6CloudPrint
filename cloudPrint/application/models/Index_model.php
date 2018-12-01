<?php
    class Index_model extends CI_Model{

        public function __construct(){
            $this->load->database();
        }

        //插入信息
        public function insert_info($table,$data){
            if($this->db->insert($table,$data))
            {
                return true;
            }
            else {
                return false;
            }
        }

        //查询密码
        public function select_psw($table,$id){
            $this->db->select('userPsw,userNick,class,belongAdmin');
            $data = $this->db->get_where($table,array('userPhone'=>$id))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }

        }

        //查询电话是否存在
        public function check_phone($phone){
            $query = $this->db->get_where('user',array('userPhone'=>$phone))->result_array();
            return $query;
        }


        //查询电话是否存在
        public function check_business_phone($phone){
            $query = $this->db->get_where('business_info',array('businessPhone'=>$phone))->result_array();
            return $query;
        }

        //验证用户激活token
        public function cfm_token($token){
            $this->db->select('userPhone,tokenExptime,userNick');
            $query = $this->db->get_where('user',array('token'=>$token))->result_array();
            if(!empty($query)){
                return $query[0];
            }else{
                return false;
            }
        }

        //激活成功更改激活状态
        public function update_active($phone){
            $data = array(
                'activeStatus' => 1
            );
            $this->db->where('userPhone',$phone);
            if($this->db->update('user',$data)){
                return true;
            }else{
                return false;
            }
        }

        //检查用户是否激活
        public function check_active($phone){
            $this->db->select('activeStatus');
            $data = $this->db->get_where('user',array('userPhone'=>$phone))->result_array();
            return $data[0]['activeStatus'];
        }

        //查询邮箱是否存在
        public function check_email_exist($email){
            $this->db->select('userPhone,userName,userNick,userPsw,getPassTime');
            $data = $this->db->get_where('user',array('email' =>$email))->result_array();
            if(empty($data)){
                return false;
            }else{
                return $data[0];
            }
        }

        //查询商家邮箱是否存在
        public function check_business_email_exist($email){
            $this->db->select('businessEmail,businessId,businessPhone,shopName,businessNick,businessPsw,businessName,getPassTime');
            $data = $this->db->get_where('business_info',array('businessEmail' =>$email))->result_array();
            if(empty($data)){
                return false;
            }else{
                return $data[0];
            }
        }

        //更新passtime信息
        public function update_business_pass_time($time,$phone){
            $data = array(
                'getPassTime' => $time
            );
            $this->db->where('businessPhone',$phone);
            if($this->db->update('business_info',$data)){
                return true;
            }else{
                return false;
            }
        }

        //更新passtime信息
        public function update_pass_time($time,$phone){
            $data = array(
                'getPassTime' => $time
            );
            $this->db->where('userPhone',$phone);
            if($this->db->update('user',$data)){
                return true;
            }else{
                return false;
            }
        }

        //更新密码
        public function update_pass($pass,$phone){
            $data = array(
                'userPsw' => $pass
            );
            $this->db->where('userPhone',$phone);
            if($this->db->update('user',$data)){
                return true;
            }else{
                return false;
            }
        }

        //更新商家密码
        public function update_bus_pass($pass,$phone){
            $data = array(
                'businessPsw' => $pass
            );
            $this->db->where('businessPhone',$phone);
            if($this->db->update('business_info',$data)){
                return true;
            }else{
                return false;
            }
        }

    }