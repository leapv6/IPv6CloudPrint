<?php

    class Client_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        //检查登录信息
        public function get_psw($phone){
            $this->db->select('businessPsw,businessId,shopName');
            $data = $this->db->get_where('business_info',array('businessPhone'=>$phone))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }
        }

        //获取商家v6地址和端口
        public function get_bus_addr($phone){
            $this->db->select('clientAddr,port');
            $a = $this->db->get_where('business_info',array('businessPhone'=>$phone))->result_array();
            if($a[0]['clientAddr'] == ''){
                return false;
            }else{
                return $a[0];
            }
        }

        //检查用户的在线状态
        public function get_online_status($phone){
            $this->db->select('runStatus');
            $data = $this->db->get_where('business_info',array('businessPhone' =>$phone))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }
        }

        //通过userID查询电话、密码
        public function get_key($userID){
            $this->db->select('businessPhone,businessPsw');
            $data = $this->db->get_where('business_info',array('businessId'=>$userID))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }
        }


        //获取未处理订单
        public function get_unhandled_orders($userID,$start,$limit){
            $this->db->order_by("makeTime","desc");
            $data['data'] = $this->db->get_where('order',array('businessId'=>$userID,'printStatus'=>0),$limit,$start)->result_array();
            $data['total'] = count($this->db->get_where('order',array('businessId'=>$userID,'printStatus'=>0))->result_array());
            $this->db->where('specialOrder',1);
            $this->db->where('businessId',$userID);
            $this->db->where('printStatus',0);
            $data['special'] = count($this->db->get('order')->result_array());

            $this->db->where('sendStatus',0);
            $this->db->where('businessId',$userID);
            $data['nodelivery'] = count($this->db->get('order')->result_array());
            return $data;
        }

        //处理订单状态
        public function handled_orders($orderId,$data){
            $this->db->where('orderId',$orderId);
            if($this->db->update('order',$data)){
                return true;
            }else{
                return false;
            }
        }

        //设置客户端ipv6地址
        public function update_client_addr($userID,$addr,$port){
            $data['clientAddr'] = $addr;
            $data['port'] = $port;
            $this->db->where('businessId',$userID);
            if($this->db->update('business_info',$data)){
                return true;
            }else{
                return false;
            }
        }

        //获取客户端ipv6地址
        public function get_addr($userID){
            $this->db->select('clientAddr');
            $res = $this->db->get_where('business_info',array('businessId'=>$userID))->result_array();
            if(!empty($res)){
                return $res[0];
            }else{
                return false;
            }
        }

        //获取文档下载路径
        public function get_location($orderId,$userID){
            $this->db->select('docId');
            $res = $this->db->get_where('order',array('orderId'=>$orderId))->result_array();

            if(!empty($res)){
                $docId = $res[0]['docId'];
            }else{
                return false;
            }
            $this->db->select('docLocation,docName');
            $loc = $this->db->get_where('doc_info',array('docId' => $docId))->result_array();
            if(!empty($loc)){
                $location = $loc[0];
                return $location;
            }else{
                return false;
            }
        }

        //获取历史订单
        public function get_history($userID,$start,$limit){
            $this->db->order_by("makeTime","desc");
            $data['total'] = count($this->db->get_where('order',array('businessId'=>$userID,'printStatus'=>'1'))->result_array());
            $data['data'] = $this->db->get_where('order',array('businessId'=>$userID,'printStatus'=>'1'),$limit,$start)->result_array();
            return $data;
        }

        //更新在线信息
        public function update_online($userId,$online,$lastUpdate){
            $data['lastUpdateTime'] = $lastUpdate;
            $data['runStatus'] = $online;
            $this->db->where('businessId',$userId);
            if($this->db->update('business_info',$data)){
                return true;
            }else{
                return false;
            }
        }

        //强制登录更改在线状态
        public function update_online_by_phone($phone,$online){
            $data['runStatus'] = $online;
            $this->db->where('businessPhone',$phone);
            if($this->db->update('business_info',$data)){
                return true;
            }else{
                return false;
            }
        }


    }