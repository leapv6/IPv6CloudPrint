<?php
//二级管理员--区域管理员
include "Business.php";
    class Gov extends Business{
        public function __construct(){
            parent::__construct();
            $this->load->model('index_model');
            $this->load->library('form_validation');
            if(!$this->check_authority()){
                redirect(base_url('/'));
            }

        }

        //检查权限是否为区域管理员
        public function check_authority(){
            if($this->session->userdata('authority') == 1){
                return false;
            }else if($this->session->userdata('authority') == 2){
                return true;
            }
        }

        //显示单位注册页面
        public function gov_reg(){
            $this->load->view('admin/gov_reg');
        }

        //添加新单位
        public function reg_gov(){
            $data = $this->input->post();
            $data['regTime'] = date("Y-m-d H:i:s");
            $data['busyStatus'] = "正常";
            $data['runStatus'] = 0;
            $data['location'] = $data['yy'] . "," . $data['xx'];
            $data['lat'] = $data['yy'];//纬度
            $data['lng'] = $data['xx'];//经度
            $data['businessPsw'] = md5($data['businessPsw']);
            $data['class'] = 1;//公司类别
            $data['belongAdmin'] = $this->session->userdata('adminId');
            unset($data['xx']);
            unset($data['yy']);
            unset($data['Psw2']);
            unset($data['agree_btn']);
            $rules = array(
                array(
                    'field' => 'businessPhone',
                    'label' => '手机号',
                    'rules' => 'trim|required|numeric|exact_length[11]'
                ),
                array(
                    'field' => 'businessName',
                    'label' => '负责人姓名',
                    'rules' => 'trim|max_length[20]'
                ),
                array(
                    'field' => 'businessNick',
                    'label' => '用户名',
                    'rules' => 'trim|required|max_length[20]'
                ),
                array(
                    'field' => 'businessPsw',
                    'label' => '密码',
                    'rules' => 'trim|required|max_length[16]|alpha_numeric'
                ),
                array(
                    'field' => 'businessEmail',
                    'label' => '邮箱',
                    'rules' => 'trim|required|max_length[50]|valid_email'
                ),
                array(
                    'field' => 'businessAddress',
                    'label' => '地址',
                    'rules' => 'trim|required|max_length[60]'
                ),
                array(
                    'field' => 'xx',
                    'label' => '纬度',
                    'rules' => 'trim|required|max_length[16]'
                ),
                array(
                    'field' => 'yy',
                    'label' => '经度',
                    'rules' => 'trim|required|max_length[16]'
                )

            );
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run()){
                $res = $this->index_model->check_business_phone($data['businessPhone']);
                if(empty($res)){
                    if ($this->index_model->insert_info('business_info', $data)) {
                        echo json_encode(array(
                            'status' =>1
                        ));

                    } else {
                        echo json_encode(array(
                            'status' =>0
                        ));

                    }
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

        //显示用户管理界面
        public function user_manage(){
            $this->get_user_info(1);
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
                $orders['info'] = $this->business_model->get_gov_order_list(10,$offset,$array);
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10 ? $orders['total']/10 : 1;
                $this->load->view('admin/gov_order_list',$orders);
            }
            else{
                $this->order_manage();
            }
        }

        //展示政府信息编辑页面
        public function gov_edit($businessId){
            if($businessId!=''){
                $data = $this->business_model->get_business_info($businessId);
                $this->load->view('admin/gov_edit',$data[0]);
            }
        }

        //更新政府信息
        public function gov_update(){
            $businessId = $this->input->post('businessId');
            $data = $this->input->post();
            unset($data['businessPhone']);


            $data['location'] = $data['yy'] . "," . $data['xx'];
            $data['lat'] = $data['yy'];//纬度
            $data['lng'] = $data['xx'];//经度
            $data['businessPsw'] = md5($data['businessPsw']);

            unset($data['xx']);
            unset($data['yy']);
            unset($data['Psw2']);
            unset($data['agree_btn']);
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

    }




?>