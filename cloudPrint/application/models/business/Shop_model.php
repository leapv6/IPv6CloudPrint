<?php
    class Shop_model extends CI_Model{

        public function __construct(){
            $this->load->database();
        }

        //查询店铺信息
        public function get_shop_info($phone){
            $query = $this->db->get_where('business_info',array('businessPhone' =>$phone));
            return $query->result_array();
        }

        //验证登录
        public function select_psw($table,$phone,$pass){
            $this->db->select('businessPsw,businessNick,businessId,shopName,class');
            $data = $this->db->get_where($table,array('businessPhone'=>$phone,'businessPsw'=>$pass))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }

        }

        //更新店铺
        public function update_shop_info($table,$id,$data){
            $this->db->where('businessId',$id);
            return $this->db->update($table,$data);
        }

        //获取订单信息
        public function get_order_list($table,$per,$offset){
            $this->db->order_by("makeTime","desc");
            $data = $this->db->get_where($table,array('businessId'=>$this->session->userdata['businessId']),$per,$offset)->result_array();
            $data['total'] = count($this->db->get_where($table,array('businessId'=>$this->session->userdata['businessId']))->result_array());
            return $data;
        }

        //获取评论信息
        public function get_comment_list($table,$per,$offset){
            $this->db->order_by("comTime","desc");
            $data = $this->db->get_where($table,array('businessId'=>$this->session->userdata['businessId']),$per,$offset)->result_array();
            $data['total'] = count($this->db->get_where($table,array('businessId'=>$this->session->userdata['businessId']))->result_array());
            return $data;
        }

        //获取指定评论信息
        public function get_comment($id){
            $data = $this->db->get_where('comment',array('orderId'=>$id,'businessId'=>$this->session->userdata['businessId']))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }
        }

        //获取店家位置信息
        public function get_location($id){
            $this->db->select('lat,lng');
            $data = $this->db->get_where('business_info',array('businessId'=>$id))->result_array();
            if(!empty($data)){
                return $data[0];
            }
        }

        //更新位置信息
        public function update_location($id,$data){
            $info['lat'] = $data['yy'];
            $info['lng'] = $data['xx'];
            $this->db->where('businessId',$id);
            return $this->db->update('business_info',$info);
        }

        //获取员工信息分页(隶属于该公司)
        public function get_info_page($table,$per,$offset,$search){
            $this->db->like($search);
            $data = $this->db->get_where($table,array('belongComId'=>$this->session->userdata['businessId']),$per,$offset)->result_array();
            $data['total'] = count($this->db->get_where($table,array('belongComId'=>$this->session->userdata['businessId']))->result_array());
            return $data;
        }

        //查询企业对应的区域id
        public function get_admin_by_comid($bid){
            $this->db->select('belongAdmin');
            $data = $this->db->get_where('business_info',array('businessId'=>$bid))->result_array();
            if(!empty($data)){
                return $data[0]['belongAdmin'];
            }
        }

        //获取指定文档信息
        public function get_doc_info($id){
            $data = $this->db->get_where('doc_info',array('docId'=>$id))->result_array();
            if(!empty($data)){
                return $data[0];
            }

        }

        //获取指定订单信息
        public function get_order_info($id){
            $data = $this->db->get_where('order',array('orderId'=>$id))->result_array();
            return $data[0];
        }

        //查询文档路径
        public function find_location($id){
            $this->db->select('docLocation,docName');
            $data = $this->db->get_where('doc_info',array('docId'=>$id))->result_array();
            return $data[0];
        }

        //获取打印单价
        public function get_price($bid){
            $this->db->select('price');
            $res1 = $this->db->get_where('print_price',array('businessId'=>$bid,'colorPrint'=>0,'doublePrint'=>0))->result_array();
            if(!empty($res1)){
                $data['blackOnePrint'] = $res1[0]['price'];
            }else{
                $data['blackOnePrint'] = 0.0;
            }

            $res2 = $this->db->get_where('print_price',array('businessId'=>$bid,'colorPrint'=>0,'doublePrint'=>1))->result_array();
            if(!empty($res1)){
                $data['blackDoublePrint'] = $res2[0]['price'];
            }else{
                $data['blackDoublePrint'] = 0.0;
            }

            $res3 = $this->db->get_where('print_price',array('businessId'=>$bid,'colorPrint'=>1,'doublePrint'=>0))->result_array();
            if(!empty($res1)){
                $data['colorOnePrint'] = $res3[0]['price'];
            }else{
                $data['colorOnePrint'] = 0.0;
            }

            $res4 = $this->db->get_where('print_price',array('businessId'=>$bid,'colorPrint'=>1,'doublePrint'=>1))->result_array();
            if(!empty($res1)){
                $data['colorDoublePrint'] = $res4[0]['price'];
            }else{
                $data['colorDoublePrint'] = 0.0;
            }

            return $data;
        }

        //更新打印单价价格
        public function update_price($bid,$data){
            $this->db->select('price');
            $check = $this->db->get_where('print_price',array('businessId'=>$bid,'colorPrint'=>0,'doublePrint'=>0))->result_array();
            //如果没有设置过价格，则插入新的信息
            $info = array(
                array(
                    'businessId' => $bid,
                    'pageSize' => 0,
                    'doublePrint' => 0,
                    'colorPrint' => 0,
                    'printTypeNum' => 0,
                    'pageType' => 0,
                    'price' => $data['blackOnePrint']
                ),
                array(
                    'businessId' => $bid,
                    'pageSize' => 0,
                    'doublePrint' => 1,
                    'colorPrint' => 0,
                    'printTypeNum' => 0,
                    'pageType' => 0,
                    'price' => $data['blackDoublePrint']
                ),
                array(
                    'businessId' => $bid,
                    'pageSize' => 0,
                    'doublePrint' => 0,
                    'colorPrint' => 1,
                    'printTypeNum' => 0,
                    'pageType' => 0,
                    'price' => $data['colorOnePrint']
                ),
                array(
                    'businessId' => $bid,
                    'pageSize' => 0,
                    'doublePrint' => 1,
                    'colorPrint' => 1,
                    'printTypeNum' => 0,
                    'pageType' => 0,
                    'price' => $data['colorDoublePrint']
                )
            );

            if(empty($check)){
                for($i=0 ; $i < count($data);$i++){
                    $this->db->insert('print_price',$info[$i]);
                }
                return true;
            }else{
                $this->db->update('print_price',$info[0],array('businessId'=>$bid,'colorPrint'=>0,'doublePrint'=>0));
                $this->db->update('print_price',$info[1],array('businessId'=>$bid,'colorPrint'=>0,'doublePrint'=>1));
                $this->db->update('print_price',$info[2],array('businessId'=>$bid,'colorPrint'=>1,'doublePrint'=>0));
                $this->db->update('print_price',$info[3],array('businessId'=>$bid,'colorPrint'=>1,'doublePrint'=>1));
                return true;
            }
        }

        //更新订单信息
        public function update_order_info($id,$data){
            $user = $this->get_order_info($id);
            $userPhone = $user['userPhone'];
            $business = $this->get_order_info($id);
            $businessID = $business['businessId'];
            $a = $this->db->query('UPDATE cp_user SET finishOrders = finishOrders + 1 WHERE userPhone = ?',$userPhone);
            $this->db->query('UPDATE cp_business_info SET finishOrders = finishOrders + 1 WHERE businessId = ?',$businessID);
            $this->db->where('orderId',$id);
            $b =$this->db->update('order',$data);
            return ($a && $b);
        }

        //更改密码
        public function update_psw($data,$id){
            $this->db->select('businessPsw');
            $psw1 = $this->db->get_where('business_info',array('businessId' => $id))->result_array();
            $psw = $psw1[0]['businessPsw'];
            if($psw == md5($data['old'])){
                return $this->change_psw($data['new'],$id);
            }else{
                return false;
            }
        }

        public function change_psw($new,$id){
            $data['businessPsw'] = md5($new);
            $this->db->where('businessId',$id);
            return $this->db->update('business_info',$data);
        }

    }