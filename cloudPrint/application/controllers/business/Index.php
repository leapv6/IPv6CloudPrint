<?php
    class Index extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->model('business/shop_model');
            $this->load->helper('url');

        }

        public function index(){
                if(isset($this->session->userdata['businessId'])){
                    $this->login();
                }else{
                    $this->load->view('business/login');
                }

        }

        public function login(){
            $this->load->view('business/index');
        }

        //检查账号密码
        public function check_login(){
            $phone = $this->input->post('phone');
            $psw = md5($this->input->post('password'));
            $data = $this->shop_model->select_psw('business_info',$phone,$psw);
            if($data){
                    $this->session->set_userdata('businessNick', $data['businessNick']);
                    $this->session->set_userdata('businessId', $data['businessId']);
                    $this->session->set_userdata('shopName', $data['shopName']);
                    $this->session->set_userdata('class',$data['class']);
                    $this->session->set_userdata('businessPhone', $phone);
                    echo json_encode(array(
                        'status' => 1
                    ));
                }else{
                echo json_encode(array(
                    'status' => 0
                ));

            }

        }

        public function system_introduce(){
            $this->load->view('business/system_introduce');
        }

        //退出系统
        public function logout(){
            unset($_SESSION['businessId']);
            unset($_SESSION['businessNick']);
            unset($_SESSION['businessPhone']);
            header('Location:'.base_url());

        }


        //微信支付测试
        public function wxpay(){
            ini_set('date.timezone','Asia/Shanghai');
            $this->load->library("WxLib/WxPayApi");
            $this->load->library("WxLib/NativePay");

            $notify = new NativePay();

            $input = new WxPayUnifiedOrder();
            $input->SetBody("航哥交保护费!!!");
            $input->SetAttach("test");
            $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
            $input->SetTotal_fee("1");
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("test");
            $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id("123456789");
            $result = $notify->GetPayUrl($input);
            $url2 = urlencode($result["code_url"]);

            echo "
            <img alt=\"模式二扫码支付\" src=\"http://192.168.199.115/business/index/paycode/?data=". $url2 ."\" style=\"width:150px;height:150px;\"/>
            ";

        }

        //测试支付二维码
        public function paycode(){
            error_reporting(E_ERROR);
            require_once 'phpqrcode/phpqrcode.php';
            $url = urldecode($_GET["data"]);
            QRcode::png($url);
        }

    }