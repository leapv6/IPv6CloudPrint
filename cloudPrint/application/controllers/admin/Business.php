<?php
    class Business extends MY_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->helper('download');
            $this->load->model('admin/business_model');
            $this->load->model('user/user_model');
            if(!$this->_check_admin_login()){
                redirect(base_url('/'));
            }
        }

        public function user_info(){
            $this->get_user_info(1);
        }

        //显示添加用户界面
        public function user_add(){
            if($this->session->userdata('authority') == 1){
                $this->load->view('admin/user_add');
            }else{
                $this->load->view('admin/gov/gov_user_add');
            }

        }

        //新增用户到数据库
        public function add_user(){
            if($this->session->userdata('authority') == 1){
                $class = 0;
            }else{
                $class = 1;
            }
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
                'belongAdmin' => $this->session->userdata('adminId')
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

        //读取用户信息
        public function get_user_info($page_num=1){
            $search['name'] = $this->input->post('searchName');
            $search['nick'] = $this->input->post('searchNick');
            $search['phone'] = $this->input->post('searchPhone');

            $array = array('userNick' => $search['nick'], 'userName' => $search['name'], 'userPhone' => $search['phone']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $users['info'] = $this->business_model->get_info_page('user',10,$offset,$array);
                $users['total'] = $users['info']['total'];
                $users['now_page'] = $page_num;
                $users['all_page'] = $users['total']/10;
                $this->load->view('admin/user_info_list',$users);
            }
            else{
                $this->user_info();
            }

        }

        //读取政府信息
        public function get_gov_info($page_num=1){
            $search['name'] = $this->input->post('searchName');
            $search['nick'] = $this->input->post('searchNick');
            $search['phone'] = $this->input->post('searchPhone');

            $array = array('userNick' => $search['nick'], 'userName' => $search['name'], 'userPhone' => $search['phone']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $users['info'] = $this->business_model->get_info_page('user',10,$offset,$array);
                $users['total'] = $users['info']['total'];
                $users['now_page'] = $page_num;
                $users['all_page'] = $users['total']/10;
                $this->load->view('admin/user_info_list',$users);
            }
            else{
                $this->user_info();
            }

        }

        //查询用户信息
        public function search_user(){
            $userName = $this->input->post('searchName');
            $userNick = $this->input->post('searchNick');
            $userPhone = $this->input->post('searchPhone');
            $users['info'] = $this->business_model->search_user($userName,$userPhone,$userNick);
            $users['now_page'] = 1;
            $users['all_page'] = 1;
            $this->load->view('admin/user_info_list',$users);
        }

        //编辑用户信息
        public function user_edit($phone){
            if($phone!=''){
                $data = $this->business_model->get_info($phone);
                if($this->session->userdata('authority') == 1){
                    $this->load->view('admin/user_edit',$data[0]);
                }elseif($this->session->userdata('authority') == 2){
                    $this->load->view('admin/gov/gov_user_edit',$data[0]);
                }

            }

        }

        //更新用户信息
        public function update_user(){
            $id = $this->input->post('id');
            $data = array(
                'userPsw' => $this->input->post('userPsw'),
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

        //删除用户信息
        public function del_user(){
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

        //显示订单管理界面
        public function order_manage(){
            $this->get_order_info(1);
        }

        //读取订单信息
        public function get_order_info($page_num=1){
            $search['name'] = $this->input->post('searchUserName');
            $search['nick'] = $this->input->post('searchUserNick');
            $array = array('userNick' => $search['nick'], 'userName' => $search['name']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $orders['info'] = $this->business_model->get_order_page('order',10,$offset,$array);
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10;
                $this->load->view('admin/order_list',$orders);
            }
            else{
                $this->order_manage();
            }
        }

        //编辑订单信息
        public function order_edit($orderId){
            if($orderId!=''){
                $data = $this->business_model->get_order_info($orderId);
                $this->load->view('admin/order_edit',$data[0]);
            }

        }

        //下载文档
        public function down_file($orderId){
            $info = $this->business_model->get_file_location($orderId);
            $location = "./uploads/".$info['docLocation'];
            $data = file_get_contents($location); // 读文件内容
            force_download($info['docName'], $data);
        }

        //显示评价管理界面
        public function comment_manage(){
            $this->get_comment_info(1);
        }

        //读取评价信息
        public function get_comment_info($page_num=1){
            $search['phone'] = $this->input->post('searchUserPhone');
            $search['name'] = $this->input->post('searchUserName');
            $array = array('userPhone' => $search['phone'], 'userName' => $search['name']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $orders['info'] = $this->business_model->get_info_page('comment',10,$offset,$array);
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10;
                $this->load->view('admin/comment_list',$orders);
            }
            else{
                $this->comment_manage();
            }
        }

        //展示评价信息
        public function comment_detail($id){
            $data = $this->business_model->get_comment($id);
            $this->load->view('business/comment_detail',$data);

        }

        //文档管理界面
        public function file_manage(){
            $this->get_all_file_info(1);
        }

        //获取所有用户文档信息
        public function get_all_file_info($page_num=1){
            $search['phone'] = $this->input->post('searchUserPhone');
            $search['name'] = $this->input->post('searchUserName');
            $array = array('docBelongPhone' => $search['phone'], 'docBelongNick' => $search['name']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $files['info'] = $this->business_model->get_all_file_page('doc_info',10,$offset,$array);
                $files['total'] = $files['info']['total'];
                $files['now_page'] = $page_num;
                $files['all_page'] = $files['total']/10;
                $this->load->view('admin/file_manage',$files);
            }
            else{
                $this->file_manage();
            }
        }

        //删除文档
        public function del_file(){
            $id = $this->input->post('id');
            $info = $this->user_model->find_location($id);
            $location = "./uploads/".$info['docLocation'];

            if($this->user_model->delete_file($location,$id)){
                echo json_encode(array(
                    'status' => 1
                ));
            }
            else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }

        //通过docId删除文档
        public function del_file_by_id($id){
            $info = $this->user_model->find_location($id);
            $location = "./uploads/".$info['docLocation'];
            if($this->user_model->delete_file($location,$id)){
                return true;
            }
            else{
                return false;
            }
        }

        //批量删除文档
        public function batch_del_file(){
            $res = false;
            $doc_ids = $this->input->post('id');
            $id_array = explode(',',$doc_ids);
            foreach($id_array as $k=>$val){
                $r = $this->del_file_by_id($val);
                if($r){
                    $res = true;
                }else{
                    $res = false;
                    break;
                }
            }
            if($res){
                echo json_encode(array(
                    'status' => 1
                ));
            }else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }

        //更新订单信息
        public function update_order(){
            $orderId = $this->input->post('orderId');
            $data = array(
                'price' => $this->input->post('price'),
                'doublePrint' =>$this->input->post('doublePrint'),
                'colorPrint' => $this->input->post('colorPrint'),
                'payStatus' => $this->input->post('payStatus'),
                'printStatus' => $this->input->post('printStatus'),
                'sendType' => $this->input->post('sendType'),
                'printCopis' =>$this->input->post('printCopis')
            );
            if($this->business_model->update_order_info('order',$orderId,$data)){
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


        //删除订单信息
        public function del_order(){
            $orderId = $this->input->post('id');
            if($this->business_model->del_order_info('order',$orderId)){
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

        //批量删除订单
        public function batch_del_order(){
            $batch_id = $this->input->post('id');
            if($this->business_model->batch_del_order_info($batch_id)){
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

        //查询用户信息
        /*public function search_order(){
            $userName = $this->input->post('searchUserName');
            $userNick = $this->input->post('searchUserNick');
            $userPhone = $this->input->post('searchPhone');
            $orders['info'] = $this->business_model->search_order($userName,$userPhone,$userNick);
            $orders['now_page'] = 1;
            $orders['all_page'] = 1;
            $this->load->view('admin/order_list',$orders);
        }*/


        //商户账号信息显示
        public function business_info(){
            $this->get_business_info(1);
        }

        //读取商家信息
        public function get_business_info($page_num=1){
            $search['businessName'] = $this->input->post('searchBusinessName');
            $search['businessPhone'] = $this->input->post('searchPhone');
            $search['shopName'] = $this->input->post('searchBusinessShopName');
            $adminId = $this->session->userdata('adminId');
            $auth = $this->session->userdata('authority');
            if($auth == 2){
                $array = array('businessName' => $search['businessName'], 'businessPhone' => $search['businessPhone'],'shopName' => $search['shopName']);
            }else{
                $array = array('businessName' => $search['businessName'], 'businessPhone' => $search['businessPhone'],'shopName' => $search['shopName']);
            }

            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $business['info'] = $this->business_model->get_business_info_page('business_info',10,$offset,$array);
                $business['total'] =  $business['info']['total'];
                $business['now_page'] = $page_num;
                $business['all_page'] = $business['total']/10 ? $business['total']/10 : 1;
                $this->load->view('admin/business_list',$business);
            }
            else{
                $this->business_info();
            }
        }



        //显示添加用户界面
        public function business_add(){
            $this->load->view('admin/business_add');
        }

        //新增商家到数据库
        public function add_business(){
            $data = array(
                'businessNick' => $this->input->post('businessNick'),
                'businessPsw'  => $this->input->post('businessPsw'),
                'businessName' => $this->input->post('businessName'),
                'businessPhone'=> $this->input->post('businessPhone'),
                'runStatus'    => 1,
                'busyStatus'   =>"正常",
                'serveStar'  =>4,
                'businessEmail'    => $this->input->post('businessEmail'),
                'regTime'  => date("Y-m-d"),
                'businessAddress' => $this->input->post('businessAddress')
            );
            if($this->business_model->insert_info('business_info',$data)){
                echo "添加成功！";
                echo '<script type="text/javascript">
                        setTimeout("close()",1500);
                        function close(){
                            window.parent.$.fancybox.close();
                        }
                        </script>';
            }
            else{
                echo "添加失败！";
            }
        }

        //编辑商户信息
        public function business_edit($businessId){
            if($businessId!=''){
                $data = $this->business_model->get_business_info($businessId);
                $this->load->view('admin/business_edit',$data[0]);
            }

        }

        //更新商户信息
        public function update_business(){
            $businessId = $this->input->post('businessId');
            $data = array(
                'businessName' =>$this->input->post('businessName'),
                'businessPsw' => $this->input->post('businessPsw'),
                'businessAddress' => $this->input->post('businessAddress'),
                'businessEmail' => $this->input->post('businessEmail'),
                'businessPhone' =>$this->input->post('businessPhone'),
                'shopName' =>$this->input->post('shopName'),
            );
            if($this->business_model->update_business_info('business_info',$businessId,$data)){
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


        //删除商家信息
        public function del_business(){
            $businessId = $this->input->post('id');
            if($this->business_model->del_business_info('business_info',$businessId)){
                echo json_encode(array(
                    'status'=>1
                ));
            }
            else{
                echo json_encode(array(
                    'status'=>0
                ));
            }

        }

        //商户账号信息显示
        public function business_run_info(){
            $this->get_run_info(1);
        }

        //读取商户营业信息
        public function get_run_info($page_num=1){
            $search['businessName'] = $this->input->post('searchBusinessName');
            $search['businessPhone'] = $this->input->post('searchPhone');
            $search['shopName'] = $this->input->post('searchBusinessShopName');
            $array = array('businessName' => $search['businessName'], 'businessPhone' => $search['businessPhone'],'shopName' => $search['shopName']);
            if(is_numeric($page_num)){
                $offset = 10*($page_num - 1);
                $business['info'] = $this->business_model->get_info_page('business_info',10,$offset,$array);
                $business['total'] =  $business['info']['total'];
                $business['now_page'] = $page_num;
                $business['all_page'] = $business['total']/10;
                $this->load->view('admin/business_run_list',$business);
            }
            else{
                $this->business_run_info();
            }
        }

        //编辑商户信息
        public function run_edit($businessId){
            if($businessId!=''){
                $data = $this->business_model->get_business_info($businessId);
                $this->load->view('admin/run_edit',$data[0]);
            }

        }

        //更新商户信息
        public function update_run(){
            $businessId = $this->input->post('businessId');
            $data = array(
                'runStatus' => $this->input->post('runStatus'),
                'busyStatus' => $this->input->post('busyStatus'),
                'property' => $this->input->post('property')
            );
            if($this->business_model->update_business_info('business_info',$businessId,$data)){
                echo json_encode(array(
                    'status'=>1
                ));
            }
            else{
                echo json_encode(array(
                    'status'=>0
                ));
            }

        }

        //修改密码
        public function change_psw(){
            $this->load->view('admin/change_psw');
        }

        //更改密码
        public function update_psw(){
            $data['old'] = md5($this->input->post('oldPsw'));
            $data['new'] = md5($this->input->post('newPsw'));
            if($this->business_model->update_psw($data,$this->session->userdata('adminId'))){
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







    }

?>