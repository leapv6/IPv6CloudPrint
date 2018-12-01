<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    class Admin_index extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->model('admin/index_model');

        }

        public function index(){
            if(isset($this->session->userdata['adminId'])){
                if($this->session->userdata('authority') == 1){
                    $this->login();
                }else{
                    $this->sec_login();
                }

            }else{
                $this->load->view('admin/login');

            }

        }

        //一级管理员首页
        public function login(){
            $this->load->view('admin/index');
        }

        //二级管理员首页
        public function sec_login(){
            $this->load->view('admin/sec_index');
        }

        //检查账号密码
        public function check_login(){
            $name = $this->input->post('adminName');
            $psw = md5($this->input->post('adminPsw'));
            $data = $this->index_model->select_psw('admin',$name,$psw);
            if($data){
                $this->session->set_userdata('adminName', $data['adminName']);
                $this->session->set_userdata('adminId', $data['id']);
                $this->session->set_userdata('nick', $data['adminNick']);
                $this->session->set_userdata('authority',$data['authority']);
                if($data['authority'] == 1){
                    echo json_encode(array(
                        'status' => 1
                    ));
                }else if($data['authority'] == 2){
                    echo json_encode(array(
                        'status' => 2
                    ));
                }

            }else{
                echo json_encode(array(
                    'status' => 0
                ));

            }

        }

        public function system_introduce(){
            $this->load->view('admin/system_introduce');
        }



        //退出系统
        public function logout(){
            unset($_SESSION['adminId']);
            unset($_SESSION['adminName']);
            unset($_SESSION['authority']);
            unset($_SESSION['nick']);
            header('Location:'.base_url());

        }


    }


?>