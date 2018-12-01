<?php
    class Wx_model extends CI_Model{
        public function __construct(){
            $this->load->database();
        }

        //通过businessID检索打印点信息
        public function get_bus_info($bid){
            $this->db->select('shopName');
            $data = $this->db->get_where('business_info',array('businessId'=>$bid))->result_array();
            if($data){
                return $data[0];
            }
        }

        //更新微信openid
        public function update_openid($phone,$openid){
            $data = array(
                'wxOpenId' => $openid
            );
            $this->db->where('userPhone',$phone);
            if($this->db->update('user',$data)){
                return true;
            }else{
                return false;
            }
        }

        //插入上传文档信息
        public function insert_file_info($data){
            $info['docName'] = $data['docName'];
            $info['docNewName'] = $data['newName'];
            $info['docBelongNick'] = $this->session->userdata('nickname') ? $this->session->userdata('nickname') :"渝洽会用户";
            $info['docBelongPhone'] = $data['docBelongPhone'] ? $data['docBelongPhone'] :"666666";
            $info['isExist'] = 1;
            $info['docLocation'] = $data['docLocation'];
            $info['docSize'] = $data['file_size'];
            $b = $this->find_format(substr($data['docType'],1));
            $info['docFormat'] = $b['id'];
            $info['docTime'] = date("Y-m-d H:i:s");
            if($this->db->insert('doc_info',$info))
            {
                return true;
            }
            else {
                return false;
            }
        }

        //查询文档格式编号
        public function find_format($type){
            $this->db->select('id');
            $data = $this->db->get_where('doc_format',array('format'=>$type))->result_array();
            if($data != NULL){
                return $data[0];
            }

        }

        //通过商家ID获得管理员的adminId
        public function get_adminId_by_businessId($id){
            $this->db->select('belongAdmin');
            $data = $this->db->get_where('business_info',array('businessId'=>$id))->result_array();
            if($data != NULL){
                return $data[0]['belongAdmin'];
            }
        }

        //通过文档加密名查询文档编号
        public function get_docId($name){
            $this->db->select('docId');
            $data = $this->db->get_where('doc_info',array('docNewName'=>$name))->result_array();
            if($data != NULL){
                return $data[0];
            }
        }

        //查询指定商家店铺名
        public function get_shop_name($id){
            $this->db->select('shopName');
            $a = $this->db->get_where('business_info',array('businessId'=>$id))->result_array();
            $data = $a[0]['shopName'];
            return $data;
        }

        //搜索框检索商家
        public function search_shop($name){
            $class = $this->session->userdata('class');
            //判断个人所属
            if($class == 2){
                $array1 = array('belongAdmin' => 0);
                $se = array('shopName' =>$name);
                $this->db->like($se);

                $this->db->select('businessId,shopName');
                $data1 = $this->db->get_where('business_info',$array1)->result_array();

                $this->db->select('belongAdmin');
                $res = $this->db->get_where('user',array('userPhone'=>$this->session->userdata('userPhone')))->result_array();
                $adminId = $res[0]['belongAdmin'];
                $array2 = array('belongAdmin'=>$adminId);
                $se = array('shopName' =>$name);
                $this->db->like($se);

                $this->db->select('businessId,shopName');
                $data2 = $this->db->get_where('business_info',$array2)->result_array();

                $data = array_merge($data1,$data2);

            }else{
                if($class == 0){
                    $array = array('belongAdmin' => 0);
                }elseif($class == 1){
                    $this->db->select('belongAdmin');
                    $res = $this->db->get_where('user',array('userPhone'=>$this->session->userdata('userPhone')))->result_array();
                    $adminId = $res[0]['belongAdmin'];
                    $array = array('belongAdmin'=>$adminId);
                }
                $se = array('shopName' =>$name);
                $this->db->like($se);

                $this->db->select('businessId,shopName');
                $data = $this->db->get_where('business_info',$array)->result_array();
            }

            return $data;

        }

        //查询在线状态
        public function get_online($bid){
            $this->db->select('runStatus');
            $data = $this->db->get_where('business_info',array('businessId'=>$bid))->result_array();
            return $data[0]['runStatus'];
        }

        //查询地图附近商家信息
        public function get_near_shop($min_jingdu,$min_weidu,$max_jingdu,$max_weidu){
            //判断使用者账号类别
            $class = $this->session->userdata('class');
            //企业员工账号，普通，单位打印点均可
            if($class == 2){
                //普通
                $array1 = array('lat<' =>$max_weidu,'lat>' => $min_weidu,'lng>' => $min_jingdu,'lng<' =>$max_jingdu,'belongAdmin'=>0);
                $this->db->select('businessId,shopName,businessAddress,runStatus,lat,lng,businessPhone,busyStatus');
                $data1 = $this->db->get_where('business_info',$array1)->result_array();
                //单位
                $this->db->select('belongAdmin');
                $res = $this->db->get_where('user',array('userPhone'=>$this->session->userdata('userPhone')))->result_array();
                $adminId = $res[0]['belongAdmin'];
                $array2 = array('lat<' =>$max_weidu,'lat>' => $min_weidu,'lng>' => $min_jingdu,'lng<' =>$max_jingdu,
                    'belongAdmin'=>$adminId);
                $this->db->select('businessId,shopName,businessAddress,runStatus,lat,lng,businessPhone,busyStatus');
                $data2 = $this->db->get_where('business_info',$array2)->result_array();

                $data = array_merge($data1,$data2);

            }else{
                if($class == 0){
                    $array = array('lat<' =>$max_weidu,'lat>' => $min_weidu,'lng>' => $min_jingdu,'lng<' =>$max_jingdu,'belongAdmin'=>0);
                }elseif($class == 1){
                    $this->db->select('belongAdmin');
                    $res = $this->db->get_where('user',array('userPhone'=>$this->session->userdata('userPhone')))->result_array();
                    $adminId = $res[0]['belongAdmin'];
                    $array = array('lat<' =>$max_weidu,'lat>' => $min_weidu,'lng>' => $min_jingdu,'lng<' =>$max_jingdu,
                        'belongAdmin'=>$adminId);
                }

                $this->db->select('businessId,shopName,businessAddress,runStatus,lat,lng,businessPhone,busyStatus');
                $data = $this->db->get_where('business_info',$array)->result_array();
            }


            return $data;

        }


    }


?>
