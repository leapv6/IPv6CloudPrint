<?php
    class Shop_manage extends MY_Controller{

        public function __construct(){
            parent::__construct();
            $this->load->helper('download');
            $this->load->model('business/shop_model');
            $this->load->model('admin/business_model');
            if(!$this->_check_business_login()){
                redirect(base_url('/'));
            }
            include("phpqrcode/phpqrcode.php");
        }

        //展示店铺信息
        public function shop_info(){
            $data = $this->get_shop_info();
            $class = $this->session->userdata('class');
            switch($class){
                case 0 :
                    $this->load->view('business/shop_edit',$data);
                    break;
                case 1 :
                    $this->load->view('business/gov_edit',$data);
                    break;
            }

        }

        //获取店铺信息
        public function get_shop_info(){
            $data =$this->shop_model->get_shop_info($this->session->userdata('businessPhone'));
            return $data[0];
        }

        //更新店铺信息
        public function update_shop_info(){
            $data = $this->input->post();
            if($this->shop_model->update_shop_info('business_info',$data['businessId'],$data)){
                echo json_encode(array(
                   'status' =>1
                ));
            }else{
                echo json_encode(array(
                    'status' =>0
                ));
            }
        }

        //设置打印店位置定位
        public function set_location_view(){
            $res = $this->shop_model->get_location($this->session->userdata('businessId'));
            $this->load->view('business/set_location',$res);
        }

        //更新定位信息
        public function update_location(){
            $data = $this->input->post();
            $res = $this->shop_model->update_location($this->session->userdata('businessId'),$data);
            if($res){
                echo json_encode(array(
                    'status' =>1
                ));
            }else{
                echo json_encode(array(
                    'status' =>0
                ));
            }
        }

        //设置打印价格页面
        public function set_price(){
            $res['data'] = $this->shop_model->get_price($this->session->userdata('businessId'));
            $this->load->view('business/set_price',$res);
        }

        //更新商家价格到数据库
        public function update_price(){
            $bid = $this->session->userdata('businessId');
            $data = $this->input->post();
            $res = true;
            foreach($data as $v){
                $v = (float)$v;
                if(!is_float($v)){
                    $res = false;
                }
            }
            if($res){
                if($this->shop_model->update_price($bid,$data)){
                    echo json_encode(array(
                        'status' =>1
                    ));
                }else{
                    echo json_encode(array(
                        'status' =>0
                    ));
                }
            }else{
                echo json_encode(array(
                    'status' =>0
                ));
            }

        }

        //订单列表
        public function order_list($data = null){
            $this->load->view('business/order_list',$data);
        }

        public function gov_order_list($data = null){
            $this->load->view('business/gov_order_list',$data);
        }

        //获取订单信息
        public function get_order_list($page_num = 1){
            /*$search['name'] = $this->input->post('searchName');
            $search['nick'] = $this->input->post('searchNick');
            $search['phone'] = $this->input->post('searchPhone');
            $array = array('userNick' => $search['nick'], 'userName' => $search['name'], 'userPhone' => $search['phone']);*/
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $orders['info'] = $this->shop_model->get_order_list('order',10,$offset);
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10;
                $this->order_list($orders);
            }
            else{
                $this->get_order_list(1);
            }
        }

        //获取政府订单
        public function get_gov_order_list($page_num = 1){
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $orders['info'] = $this->shop_model->get_order_list('order',10,$offset);
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10;
                $this->gov_order_list($orders);
            }
            else{
                $this->get_order_list(1);
            }
        }



        //详情页面
        public function order_detail($id){
            $order = $this->shop_model->get_order_info($id);
            $doc = $this->shop_model->get_doc_info($order['docId']);
            $data = array_merge($doc,$order);
            $this->load->view('business/order_detail',$data);
        }

        //评论列表
        public function comment_list($data = null){
            $this->load->view('business/comment_list',$data);
        }

        //获取评论信息
        public function get_comment_list($page_num = 1){
            /*$search['name'] = $this->input->post('searchName');
            $search['nick'] = $this->input->post('searchNick');
            $search['phone'] = $this->input->post('searchPhone');
            $array = array('userNick' => $search['nick'], 'userName' => $search['name'], 'userPhone' => $search['phone']);*/
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $orders['info'] = $this->shop_model->get_comment_list('comment',10,$offset);
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10;
                $this->comment_list($orders);
            }
            else{
                $this->get_comment_list(1);
            }
        }

        //评论详情页面
        public function comment_detail($id){
            $data = $this->shop_model->get_comment($id);
            $this->load->view('business/comment_detail',$data);
        }

        //下载文档
        public function download($id){
            $info = $this->shop_model->find_location($id);
            $location = "./uploads/".$info['docLocation'];
            $data = file_get_contents($location); // 读文件内容
            force_download($info['docName'], $data);
        }

        //打印完成更新
        public function update_order($id){
            if($this->shop_model->update_order_info($id,array('printStatus'=>1))){
                echo "更新打印状态成功！";
                echo '<script type="text/javascript">
                        location.href=document.referrer;
                        </script>';
            }
            else{
                echo "更新打印状态失败！";
                echo '<script type="text/javascript">
                        location.href=document.referrer;
                        </script>';
            }
        }

        //生成二维码
        public function create_code(){
            $bid = $this->session->userdata('businessId');
            $value = "http://www.v6cp.com/WXapi/scan_select_shop/".$bid; //二维码内容
            $errorCorrectionLevel = 'M';//容错级别
            $matrixPointSize = 9;//生成图片大小
            //生成二维码图片
            QRcode::png($value, 'images/qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
            $logo = 'images/logo1.png';//准备好的logo图片
            $QR = 'images/qrcode.png';//已经生成的原始二维码图

            if ($logo !== FALSE) {
                $QR = imagecreatefromstring(file_get_contents($QR));
                $logo = imagecreatefromstring(file_get_contents($logo));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logo);//logo图片宽度
                $logo_height = imagesy($logo);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width/$logo_qr_width;
                $logo_qr_height = $logo_height/$scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //重新组合图片并调整大小
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                    $logo_qr_height, $logo_width, $logo_height);
            }
            //输出图片
            imagepng($QR,'images/helloweixin.png');
            echo '<img src="../../images/helloweixin.png">';
        }


        //分页获取企业员工信息
        public function get_staff_info($page_num=1){
            $search['name'] = $this->input->post('searchName');
            $search['nick'] = $this->input->post('searchNick');
            $search['phone'] = $this->input->post('searchPhone');

            $array = array('userNick' => $search['nick'], 'userName' => $search['name'], 'userPhone' => $search['phone']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $users['info'] = $this->shop_model->get_info_page('user',10,$offset,$array);
                $users['total'] = $users['info']['total'];
                $users['now_page'] = $page_num;
                $users['all_page'] = $users['total']/10 < 1 ? '1' : $users['total']/10;
                $this->load->view('business/staff_info_list',$users);
            }
            else{
                $this->user_info();
            }
        }


        //企业员工管理界面
        public function staff_list(){
            $this->get_staff_info(1);
        }

        //新增员工页面
        public function staff_add(){
            $this->load->view('business/staff_add');
        }

        //添加员工
        public function add_staff(){
            $class = 2;//企业员工，可使用区域打印机和普通商家付费打印机
            //通过企业id查询对应的区域管理员id
            $belongAdminId = $this->shop_model->get_admin_by_comid($this->session->userdata('businessId'));
            $data = array(
                'userNick' => $this->input->post('userNick'),
                'userPsw'  => md5($this->input->post('userPsw')),
                'userName' => $this->input->post('userName'),
                'userPhone'=> $this->input->post('userPhone'),
                'email'    => $this->input->post('email'),
                'regTime'  => date("Y-m-d H:i:s"),
                'class'    => $class,
                'activeStatus' =>1,
                'status'   =>'正常',
                'schoolName' => $this->input->post('schoolName')? $this->input->post('schoolName') : '',
                'belongAdmin' => $belongAdminId,
                'belongComId' =>$this->session->userdata('businessId')
            );
            if($this->business_model->insert_info('user',$data)){
                echo json_encode(array(
                    'status' =>1
                ));
            }
            else{
                echo json_encode(array(
                    'status' =>0
                ));
            }
        }

        //编辑员工信息
        public function staff_edit($phone){
            if($phone!=''){
                $data = $this->business_model->get_info($phone);
                $this->load->view('business/staff_edit',$data[0]);

            }
        }

        //更新员工信息
        public function update_staff(){
            $id = $this->input->post('id');
            $data = array(
                'userPsw' => md5($this->input->post('userPsw')),
                //'userPhone' =>$this->input->post('userPhone'),
                'userName' => $this->input->post('userName'),
                'email' => $this->input->post('email'),
                'schoolName' => $this->input->post('schoolName'),
                'status' =>'正常'
            );
            if($this->business_model->update_info('user',$id,$data)){
                echo json_encode(array(
                    'status' =>1
                ));
            }
            else{
                echo json_encode(array(
                    'status' =>0
                ));
            }

        }


        //删除员工
        public function del_staff(){
            $id = $this->input->post('ID');
            if($this->business_model->del_info('user',$id)){
                echo json_encode(array(
                    'status' =>1
                ));
            }
            else{
                echo json_encode(array(
                    'status' =>0
                ));
            }
        }



        //修改密码
        public function change_psw(){
            $this->load->view('business/change_psw');
        }

        //更改密码
        public function update_psw(){
            $data['old'] = $this->input->post('oldPsw');
            $data['new'] = $this->input->post('newPsw');
            if($this->shop_model->update_psw($data,$this->session->userdata('businessId'))){
                echo '<script type="text/javascript">
                        alert("更改密码成功！");
                        location.href=document.referrer;
                </script>';
            }else{
                echo '<script type="text/javascript">
                        alert("更改密码失败！");
                        location.href=document.referrer;
                </script>';

            }
        }

        //退出系统
        public function logout(){
            if(session_destroy()){
                header('Location:/CP');
            }

        }


    }