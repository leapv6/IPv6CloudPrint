<?php
    class User_model extends CI_Model{

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

        //生成订单
        public function insert_order($table,$data){
            if($this->db->insert($table,$data))
            {
                $id = $this->db->query("select last_insert_id() AS id;")->result_array();
                if($id){
                   return $id[0]['id'];
                }else{
                    return -1;
                }
            }
            else {
                return false;
            }
        }

        //查询用户电子邮件地址、token重发邮件
        public function get_email_info($phone){
            $this->db->select('email,token,userName');
            $query = $this->db->get_where('user',array('userPhone'=>$phone))->result_array();
            return $query[0];
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

        //重发邮件 更新过期时间
        public function update_time($phone,$time){
            $data = array(
                'tokenExptime' => $time
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

        //查询密码
        public function select_psw($table,$id){
            $this->db->select('userPsw,userNick');
            $data = $this->db->get_where($table,array('userPhone'=>$id))->result_array();
            return $data[0];
        }

        //查询基本信息
        public function get_info($table,$id){
            $user_class = $this->session->userdata('class');
            //判断是否属于公司
            if($user_class == 2){
                $this->db->select('email,belongComId');
                $data = $this->db->get_where($table,array('userPhone'=>$id))->result_array();
                $bid = $data[0]['belongComId'];
                $this->db->select('businessNick');
                $bname_raw = $this->db->get_where('business_info',array('businessId'=>$bid))->result_array();
                $data[0]['businessNick'] = $bname_raw[0]['businessNick'];
            }else{
                $this->db->select('email');
                $data = $this->db->get_where($table,array('userPhone'=>$id))->result_array();
                $data[0]['businessNick'] = '';
            }

            return $data[0];
        }

        //查询商家单价
        public function get_price($bid,$double,$color){
            $this->db->select('price');
            $res = $this->db->get_where('print_price',array('businessId'=>$bid,'doublePrint'=>$double,'colorPrint'=>$color))->result_array();
            $price = 0.0;
            if(empty($res)){
                return $price;
            }else{
                return $res[0]['price'];
            }
        }

        //查询本用户上传文档信息
        public function get_files_info($per,$offset,$phone){
            $this->db->order_by("docTime","desc");
            $data = $this->db->get_where('doc_info',array('docBelongPhone'=>$phone,'isExist'=>1),$per,$offset)->result_array();
            $data['total'] = count($this->db->get_where('doc_info',array('docBelongPhone'=>$phone,'isExist'=>1))->result_array());
            return $data;
        }

        //查询本用户订单信息
        public function get_orders_info($per,$offset,$phone){
            $this->db->order_by("makeTime","desc");
            $data = $this->db->get_where('order',array('userPhone'=>$phone),$per,$offset)->result_array();
            $data['total'] = count($this->db->get_where('order',array('userPhone'=>$phone))->result_array());
            return $data;
        }

        //查询用户个人信息
        public function search_user($phone){
            $this->db->select('userNick');
            $data = $this->db->get_where('user',array('userPhone'=>$phone))->result_array();
            return $data[0];
        }

        //查询个人信息
        public function get_user_info($phone){
            $data = $this->db->get_where('user',array('userPhone'=>$phone))->result_array();
            if(!empty($data)){
                return $data[0];
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

        //插入上传文档信息
        public function insert_file_info($data){
            $info['docName'] = $data['docName'];
            $info['docNewName'] = $data['newName'];
            $a = $this->search_user($data['docBelongPhone']);
            $info['docBelongNick'] = $a['userNick'];
            $info['docBelongPhone'] = $data['docBelongPhone'];
            $info['docLocation'] = $data['docLocation'];
            $info['docSize'] = $data['file_size'];
            $b = $this->find_format(substr($data['docType'],1));
            $info['docFormat'] = $b['id'];
            $info['docTime'] = date("Y-m-d H:i:s");
            $info['isExist'] = 1;
            if($this->db->insert('doc_info',$info))
            {
                return true;
            }
            else {
                return false;
            }
        }

        //查询文档路径
        public function find_location($id){
            $this->db->select('docLocation,docName');
            $data = $this->db->get_where('doc_info',array('docId'=>$id))->result_array();
            return $data[0];
        }

        //删除文档
        public function delete_file($loca,$id){
            $a = unlink($loca);
            $data = array(
                'isExist' => 0
            );
            $this->db->where('docId', $id);
            $b = $this->db->update('doc_info', $data);
            if ($a && $b){
                return true;
            }
            else{
                return false;
            }
        }

        //查询所有商家信息
        public function get_shop(){
            $this->db->select('shopName,businessId');
            $data = $this->db->get('business_info')->result_array();
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

        //查询支付订单信息
        public function get_order_info($order_id){
            //$this->db->select('docName,price,userPhone,payStatus');
            $a = $this->db->get_where('order',array('orderId'=>$order_id))->result_array();
            if($a && $a[0]['userPhone'] == $this->session->userdata('userPhone')){
                return $a[0];
            }else{
                return false;
            }

        }

        //查询指定商家店铺名
        public function get_shop_name($id){
            $this->db->select('shopName');
            $a = $this->db->get_where('business_info',array('businessId'=>$id))->result_array();
            $data = $a[0]['shopName'];
            return $data;
        }

        //查询商家类型（公司，普通）
        public function get_bus_class($id){
            $this->db->select('class');
            $a = $this->db->get_where('business_info',array('businessId'=>$id))->result_array();
            $data = $a[0]['class'];
            return $data;
        }

        //查询文档信息
        public function get_doc_info($docId){
            $this->db->select('docName,docBelongNick,docBelongPhone,docTime,docFormat');
            $docInfo = $this->db->get_where('doc_info',array('docId'=>$docId))->result_array();
            return $docInfo[0];
        }

        //获取商家v6地址和端口
        public function get_bus_addr($bid){
            $this->db->select('clientAddr,port');
            $a = $this->db->get_where('business_info',array('businessId'=>$bid))->result_array();
            if($a[0]['clientAddr'] == ''){
                return false;
            }else{
                return $a[0];
            }
        }

        //查询打印状态
        public function get_print_status($orderId){
            $this->db->select('printStatus');
            $res = $this->db->get_where('order',array('orderId' =>$orderId))->result_array();
            $status = $res[0]['printStatus'];
            return $status;
        }

        //查询用户名称
        public function select_user_name($phone){
            $this->db->select('userName');
            $name1 = $this->db->get_where('user',array('userPhone' =>$phone))->result_array();
            $name = $name1[0];
            return $name['userName'];
        }

        //删除订单
        public function del_order($table,$id){
            $this->db->select('printStatus');
            $res = $this->db->get_where($table,array('orderId' =>$id))->result_array();
            $status = $res[0]['printStatus'];
            if($status){
                return false;
            }else{
                return $this->db->delete($table, array('orderId' => $id));
            }

        }

        //更新订单信息
        public function update_order_info($id,$data){
            $this->db->where('orderId',$id);
            return $this->db->update('order',$data);
        }

        //更新用户信息
        public function update_user_info($phone,$data){
            $this->db->where('userPhone',$phone);
            return $this->db->update('user',$data);
        }

        //更改密码
        public function update_psw($data,$phone){
            $this->db->select('userPsw');
            $psw1 = $this->db->get_where('user',array('userPhone' => $phone))->result_array();
            $psw = $psw1[0]['userPsw'];
            if($psw == $data['old']){
                    return $this->change_psw($data['new'],$phone);
            }else{
                return false;
            }
        }

        //查询订单编号对应的商家名称和评论
        public function get_comment_by_orderId($orderId){
            $this->db->select('shopName');
            $query = $this->db->get_where('order',array('orderId'=>$orderId))->result_array();
            $shopName = $query[0]['shopName'];
            $a = $this->db->get_where('comment',array('orderId'=>$orderId))->result_array();
            if(!empty($a)){
                $res = $a[0];
            }else{
                $res['comment'] = "";
            }
            $res['shopName'] = $shopName;
            return $res;
        }

        //保存用户评价
        public function save_comment($data){
            $this->db->select('userName,userPhone,businessId');
            $query = $this->db->get_where('order',array('orderId'=>$data['orderId']))->result_array();
            $res =$query[0];
            $res['orderId'] = $data['orderId'];
            $res['comment'] = $data['comment'];
            $res['comTime'] = date("Y-m-d H:i:s");
            $a = $this->db->get_where('comment',array('orderId'=>$data['orderId']))->result_array();
            if(empty($a) && $this->db->insert('comment',$res))
            {
                return true;
            }else {
                return false;
            }
        }

        public function change_psw($new,$phone){
            $data['userPsw'] = $new;
            $this->db->where('userPhone',$phone);
            return $this->db->update('user',$data);
        }


    }