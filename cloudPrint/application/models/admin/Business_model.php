<?php
    class Business_model extends CI_Model{

        public function __construct()
        {
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

        //更新信息
        public function update_info($table,$id,$data){
            $this->db->where('id',$id);
            return $this->db->update($table,$data);
        }

        //更新订单信息
        public function update_order_info($table,$id,$data){
            $this->db->where('orderId',$id);
            return $this->db->update($table,$data);
        }

        //查询单个用户信息，通过手机
        public function get_info($phone){
            $query = $this->db->get_where('user',array('userPhone' =>$phone));
            return $query->result_array();
        }

        //查询单个订单信息，通过编号
        public function get_order_info($orderId){
            $query = $this->db->get_where('order',array('orderId' =>$orderId));
            return $query->result_array();
        }

        //通过订单号查询文档路径
        public function get_file_location($orderId){
            $this->db->select('docId');
            $query = $this->db->get_where('order',array('orderId' =>$orderId))->result_array();
            $docId = $query[0]['docId'];
            $info = $this->find_location($docId);
            return $info;
        }

        //查询文档路径
        public function find_location($id){
            $this->db->select('docLocation,docName');
            $data = $this->db->get_where('doc_info',array('docId'=>$id))->result_array();
            return $data[0];
        }

        //查询订单信息分页
        /*4表联合查询*/
        public function get_order_page($table,$per,$offset,$search){
            $sql ="select a.orderId,a.docId,a.docName,a.userNick,a.userName,a.makeTime,b.shopName,d.format,a.price,a.payStatus,a.printStatus
                    from cp_order as a left join cp_business_info
                  as b on a.businessId = b.businessId left join
                    cp_doc_info as c on a.docId = c.docId left join cp_doc_format as d on c.docFormat = d.id WHERE a.userNick like '%".$search['userNick']."%'"."and a.userName like '%".$search['userName']."%'  order by a.makeTime DESC limit ?,? ";

            $sql2 ="select a.orderId,a.docId,a.docName,a.userNick,a.userName,a.makeTime,b.businessNick,d.format,a.price,a.payStatus,a.printStatus
                    from cp_order as a left join cp_business_info
                  as b on a.businessId = b.businessId left join
                    cp_doc_info as c on a.docId = c.docId left join cp_doc_format as d on c.docFormat = d.id ";
            $this->db->order_by("a.makeTime","desc");
            //$data = $this->db->get($table,$per,$offset)->result_array();
            //$data['total'] = count($this->db->like($search)->get($table)->result_array());
            $data = $this->db->query($sql,array($offset,$per))->result_array();
            $data['total'] = count($this->db->query($sql2)->result_array());
            return $data;
        }

        //获取政府订单信息
        public function get_gov_order_list($per,$offset,$search){
            $this->db->order_by("makeTime","desc");
            $this->db->like($search);
            $data = $this->db->get_where('order',array('belongAdmin'=>$this->session->userdata['adminId']),$per,$offset)->result_array();
            $data['total'] = count($this->db->get_where('order',array('belongAdmin'=>$this->session->userdata['adminId']))->result_array());
            return $data;
        }

        //查询信息分页
        public function get_info_page($table,$per,$offset,$search){
            $this->db->like($search);
            if($this->session->userdata('authority') == 2){
                $data = $this->db->get_where($table,array('belongAdmin'=>$this->session->userdata['adminId']),$per,$offset)->result_array();
                $data['total'] = count($this->db->get_where($table,array('belongAdmin'=>$this->session->userdata['adminId']))->result_array());
            }else{
                $data = $this->db->get($table,$per,$offset)->result_array();
                $data['total'] = count($this->db->like($search)->get($table)->result_array());
            }
            return $data;
        }

        //查询商家信息分页
        public function get_business_info_page($table,$per,$offset,$search){
            $this->db->like($search);
            if($this->session->userdata('authority') == 2){
                $data = $this->db->get_where($table,array('belongAdmin'=>$this->session->userdata['adminId']),$per,$offset)->result_array();
                //遍历二维数组 获取总订单数
                foreach($data as $k => &$val){
                    $val['finishOrders'] = count($this->db->get_where('order',array('businessId'=>$val['businessId'],'printStatus'=> 1))->result_array());
                }
                $data['total'] = count($this->db->get_where($table,array('belongAdmin'=>$this->session->userdata['adminId']))->result_array());
            }else{
                $data = $this->db->get($table,$per,$offset)->result_array();
                //遍历二维数组 获取总订单数
                foreach($data as $k => &$val){
                    $val['finishOrders'] = count($this->db->get_where('order',array('businessId'=>$val['businessId'],'printStatus'=> 1))->result_array());
                }
                $data['total'] = count($this->db->like($search)->get($table)->result_array());
            }
            return $data;
        }

        //查询所有文档信息分页
        public function get_all_file_page($table,$per,$offset,$search){
            $this->db->order_by("docTime","desc");
            $this->db->like($search);
            $data = $this->db->get_where($table,array('isExist'=> 1),$per,$offset)->result_array();
            $data['total'] = count($this->db->like($search)->get_where($table,array('isExist'=> 1))->result_array());
            return $data;
        }



        //获取指定评论信息
        public function get_comment($id){
            $data = $this->db->get_where('comment',array('orderId'=>$id))->result_array();
            if(!empty($data)){
                return $data[0];
            }else{
                return false;
            }
        }

        //输入框查询用户
        public function search_user($name,$phone,$nick){
            $this->db->where('userName',$name);
            $this->db->or_where('userNick',$nick);
            $this->db->or_where('userPhone',$phone);
            $data = $this->db->get('user')->result_array();
            return $data;
        }



        //删除信息
        public function del_info($table,$id){
            return $this->db->delete($table, array('id' => $id));
        }

        //删除订单信息
        public function del_order_info($table,$id){
            return $this->db->delete($table, array('orderId' => $id));
        }

        //批量删除订单信息
        public function batch_del_order_info($id){
            $sql = "DELETE FROM cp_order WHERE orderId IN (".$id.")";
            $res = $this->db->query($sql);
            if($res){
                return true;
            }else{
                return false;
            }
        }

        //统计表里面数据总数
        public function count($table){
            return $this->db->count_all_results($table);
        }

        //输入框查询用户
        public function search_order($name,$phone,$nick){
            $this->db->where('userName',$name);
            $this->db->or_where('userNick',$nick);
            $this->db->or_where('userPhone',$phone);
            $data = $this->db->get('order')->result_array();
            return $data;
        }

        //查询单个商户信息，通过编号
        public function get_business_info($businessId){
            $query = $this->db->get_where('business_info',array('businessId' =>$businessId));
            return $query->result_array();
        }

        //更新商户信息
        public function update_business_info($table,$id,$data){
            $this->db->where('businessId',$id);
            return $this->db->update($table,$data);
        }

        //删除订单信息
        public function del_business_info($table,$id){
            return $this->db->delete($table, array('businessId' => $id));
        }

        //更改密码
        public function update_psw($data,$id){
            $this->db->select('adminPsw');
            $psw1 = $this->db->get_where('admin',array('id' => $id))->result_array();
            $psw = $psw1[0]['adminPsw'];
            var_dump($data);
            var_dump($psw);
            if($psw == $data['old']){
                return $this->change_psw($data['new'],$id);
            }else{
                return false;
            }
        }

        public function change_psw($new,$id){
            $data['adminPsw'] = $new;
            $this->db->where('id',$id);
            return $this->db->update('admin',$data);
        }

    }


?>