<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('index_model');
        $this->load->helper('download');
        $this->load->library('form_validation');
        //$this->load->library('smtpExt');
        //include('smtpExt.php');
    }

	public function index()
	{
		$this->load->view('index');
	}

    //客户端下载
    public function download_client(){
        $location = site_url()."v6.rar";
        $data = file_get_contents($location); // 读文件内容
        force_download("v6网印客户端.rar", $data);
    }

    //用户注册
    public function user_reg(){

        $this->load->view('user_reg');
    }

    public function reg_user(){
        $data = $this->input->post();
        $data['status'] = "正常";
        $data['regTime'] = date("Y-m-d H:i:s");
        $data['class'] = 0;
        $data['belongAdmin'] = 0;
        $rules = array(
            array(
                'field' => 'userPhone',
                'label' => '手机号',
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'userName',
                'label' => '姓名',
                'rules' => 'trim|required|max_length[20]'
            ),
            array(
                'field' => 'userNick',
                'label' => '用户名',
                'rules' => 'trim|required|max_length[20]'
            ),
            array(
                'field' => 'userPsw',
                'label' => '密码',
                'rules' => 'trim|required|max_length[16]|alpha_numeric'
            ),
            array(
                'field' => 'email',
                'label' => '邮箱',
                'rules' => 'trim|required|max_length[50]|valid_email'
            ),
            array(
                'field' => 'schoolName',
                'label' => '学校名称',
                'rules' => 'trim|max_length[30]'
            )

        );
        $this->form_validation->set_rules($rules);
        if($this->form_validation->run()){
            $data['token'] = md5($data['userName'].$data['userPsw'].$data['regTime']);
            $data['tokenExptime'] = time()+60*10;
            $data['userPsw'] = md5($data['userPsw']);
            $data['getPassTime'] = 0;
            $data['totalMoney'] = 0;
            $data['finishOrders'] = 0;
            $data['unfinishOrders'] = 0;
            $data['activeStatus'] = 1;

            if($this->index_model->insert_info('user',$data)){
                $this->session->set_userdata('userNick', $data['userNick']);
                $this->session->set_userdata('userPhone', $data['userPhone']);

                //$res = $this->send_email($data);
                $res = 1;
                //发送
                if(!$res) {
                    echo json_encode(array(
                        'status' => 2
                    ));
                    //echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    echo json_encode(array(
                        'status' => 1
                    ));
                }


            }
            else{
                echo json_encode(array(
                    'status' => 0
                ));

            }
        }else{
            echo json_encode(array(
                'status' => 0
            ));
        }

    }

    //检查用户注册邮箱是否唯一
    public function check_email_unique(){
        $email = $this->input->post('email');
        $res = $this->index_model->check_email_exist(trim($email));
        if($res){
            echo json_encode(array(
                'status' =>'0'
            ));

        }else{
            echo json_encode(array(
                'status' =>'1'
            ));
        }
    }

    //发送电子邮件函数
    public function send_email($data){
        require_once('class.phpmailer.php'); //载入PHPMailer类

        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host = "smtp.sina.com"; //SMTP服务器 以163邮箱为例子
        $mail->Port = 25;  //邮件发送端口
        $mail->SMTPAuth   = true;  //启用SMTP认证

        $mail->CharSet  = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式-

        //$mail->SMTPDebug= true;

        $mail->Username = "axzbinternet@sina.com";  //你的邮箱
        $mail->Password = "axzbaxzb";  //你的密码
        $mail->Subject = "IPv6云打印用户邮箱验证"; //邮件标题

        $mail->From = "axzbinternet@sina.com";  //发件人地址（也就是你的邮箱）
        $mail->FromName = "IPv6云打印";  //发件人姓名

        $address = $data['email'];//收件人email
        $mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

        //$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
        $mail->IsHTML(true); //支持html格式内容
        //$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
        $mail->Body = "亲爱的".$data['userName']."：<br/>感谢您在IPv6云打印注册了新帐号。<br/>请点击链接激活您的帐号。<br/>
    <a href='http://www.v6cp.com/welcome/active_cfm/".$data['token']."' target=
'_blank'>http://www.v6cp.com/welcome/active_cfm/".$data['token']."</a><br/>
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接10分钟内有效。";//邮件主体内容

        if($mail->Send()){
            return true;
        }else{
            return false;
        }

    }


    //用户注册验证邮件页面
    public function show_active_page(){
        $this->load->view('user_active_cfm');
    }

    //用户点击激活链接后
    public function active_cfm($token){
        $nowtime = time();
        $query = $this->index_model->cfm_token($token);
        if($query){
            $this->session->set_userdata('userNick', $query['userNick']);
            $this->session->set_userdata('userPhone', $query['userPhone']);
            if($nowtime > $query['tokenExptime']){
                $msg['msg'] = "验证链接已过期，请重新获取！";
                $this->load->view('check_active',$msg);
            }else{
                if($this->index_model->update_active($query['userPhone'])){
                    $msg['msg'] = "恭喜您，账号激活成功！";
                    $this->load->view('check_active',$msg);
                }
            }
        }else{
            $msg['msg'] = "激活链接错误，请检查链接！";
            $this->load->view('check_active',$msg);
        }
    }

    //用户忘记密码
    public function forget_pass(){
        $this->load->view('forget_pass');
    }

    //检查用户输入邮箱是否存在
    public function check_email_exist(){
        $email = $this->input->post('email');
        $res = $this->index_model->check_email_exist(trim($email));
        if($res){
            $getpasstime = time();
            $data['token'] = md5($res['userPhone'].$res['userPsw']);
            $data['userName'] = $res['userName'];
            $data['email'] = $email;
            if($this->send_pass_email($data)){
                echo json_encode(array(
                    'status' =>'1'
                ));
                $this->index_model->update_pass_time($getpasstime,$res['userPhone']);
            }else{
                echo json_encode(array(
                    'status' =>'send_fail'
                ));
            }

        }else{
            echo json_encode(array(
                'status' =>'0'
            ));
        }
    }



    //发送修改密码邮件
    public function send_pass_email($data){
        require_once('class.phpmailer.php'); //载入PHPMailer类

        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host = "smtp.sina.com"; //SMTP服务器 以163邮箱为例子
        $mail->Port = 25;  //邮件发送端口
        $mail->SMTPAuth   = true;  //启用SMTP认证

        $mail->CharSet  = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式

        //$mail->SMTPDebug= true;

        $mail->Username = "axzbinternet@sina.com";  //你的邮箱
        $mail->Password = "axzbaxzb";  //你的密码
        $mail->Subject = "IPv6云打印 - 找回密码"; //邮件标题

        $mail->From = "axzbinternet@sina.com";  //发件人地址（也就是你的邮箱）
        $mail->FromName = "IPv6云打印";  //发件人姓名

        $address = $data['email'];//收件人email
        $mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

        //$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
        $mail->IsHTML(true); //支持html格式内容
        //$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
        $mail->Body = "亲爱的".$data['userName']."：<br/>此封邮件可以帮您修改您的密码。<br/>请及时点击下面的链接修改您的密码。<br/>
    <a href='http://www.v6cp.com/welcome/pass_reset/".$data['token'].'/'.$data['email']."' target=
'_blank'>http://www.v6cp.com/welcome/pass_reset/".$data['token'].'/'.$data['email']."</a><br/>
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接10分钟内有效。";//邮件主体内容

        if($mail->Send()){
            return true;
        }else{
            return false;
        }

    }

    //重置密码界面
    public function pass_reset($token,$email){
        $token = trim($token);
        $email = trim($email);
        $res = $this->index_model->check_email_exist($email);
        if($res){
            $mt = md5($res['userPhone'].$res['userPsw']);
            if($mt == $token){
                if(time() - $res['getPassTime'] > 60*10){
                    $data['msg'] = "链接已过期，请重试！";
                    echo $data['msg'];
                }else{
                    $data['msg'] = "请更改您的密码：";
                    $this->session->set_userdata('userNick', $res['userNick']);
                    $this->session->set_userdata('userPhone', $res['userPhone']);
                    $this->load->view('pass_reset',$data);
                }
            }else{
                $data['msg'] ="无效的链接";
                echo $data['msg'];
            }
        }else{
            $data['msg'] = "错误的链接";
            echo $data['msg'];
        }

    }

    //重置密码
    public function update_pass(){
        $new_pass = md5($this->input->post('new_pass'));
        if($this->index_model->update_pass($new_pass,$this->session->userdata['userPhone'])){
            echo json_encode(array(
                'status' =>1
            ));
        }else{
            echo json_encode(array(
                'status' =>0
            ));
        }
    }

    //商家重置密码
    public function update_bus_pass(){
        $new_pass = md5($this->input->post('new_pass'));
        if($this->index_model->update_bus_pass($new_pass,$this->session->userdata['businessPhone'])){
            echo json_encode(array(
                'status' =>1
            ));
        }else{
            echo json_encode(array(
                'status' =>0
            ));
        }
    }

    public function pass(){
        $data['msg'] = "请更改您的密码：";
        $this->load->view('pass_reset',$data);
    }

    //test加密
    public function test(){
        $this->load->view('test_api');
    }

    //用户登录
    public function user_login(){
        $this->load->view('user_login');
    }

    //检查用户登录信息
    public function user_check(){
        $userPhone = $this->input->post('userPhone');
        $psw = md5($this->input->post('userPsw'));
        $rem = $this->input->post('remember');
        $data = $this->index_model->select_psw('user',$userPhone);
        if($data && $psw == $data['userPsw']){
            if($rem == "on"){
                setcookie('userPhone',$userPhone,time()+3600*24*7);
                setcookie('password',$this->input->post('userPsw'),time()+3600*24*7);
                setcookie('remember',$rem,time()+3600*24*7);
            }else{
                setcookie('userPhone',$userPhone,time()-3600*24*7);
                setcookie('password',$this->input->post('userPsw'),time()-3600*24*7);
                setcookie('remember',$rem,time()-3600*24*7);
            }
            $this->session->set_userdata('userNick', $data['userNick']);
            $this->session->set_userdata('class', $data['class']);
            $this->session->set_userdata('belongAdmin', $data['belongAdmin']);
            $this->session->set_userdata('userPhone', $userPhone);
            echo json_encode(array(
                'status' => 1
            ));
        }else{
            echo json_encode(array(
                'status' => 0
            ));
        }
    }

    //检查手机号是否唯一
    public function check_unique(){
        $phone = $this->input->post('phone');
        $res = $this->index_model->check_phone($phone);
        if(empty($res)){
            echo json_encode(array(
                'status' => '1'
            ));
        }else{
            echo json_encode(array(
                'status' => '0'
            ));
        }
    }

    //检查商家手机号是否唯一
    public function check_shop_unique(){
        $phone = $this->input->post('phone');
        $res = $this->index_model->check_business_phone($phone);
        if(empty($res)){
            echo json_encode(array(
                'status' => '1'
            ));
        }else{
            echo json_encode(array(
                'status' => '0'
            ));
        }
    }

    //检查商家邮箱是否唯一
    public function check_business_email_unique(){
        $email = $this->input->post('email');
        $res = $this->index_model->check_business_email_exist(trim($email));
        if($res){
            echo json_encode(array(
                'status' =>'0'
            ));

        }else{
            echo json_encode(array(
                'status' =>'1'
            ));
        }
    }

    //检查邮箱是否存在
    public function check_business_email_exist(){
        $email = $this->input->post('email');
        $res = $this->index_model->check_business_email_exist(trim($email));
        if($res){
            $getpasstime = time();
            $data['token'] = md5($res['businessPhone'].$res['businessPsw']);
            $data['businessName'] = $res['businessName'];
            $data['email'] = $email;
            if($this->send_business_pass_email($data)){
                echo json_encode(array(
                    'status' =>'1'
                ));
                $this->index_model->update_business_pass_time($getpasstime,$res['businessPhone']);
            }else{
                echo json_encode(array(
                    'status' =>'send_fail'
                ));
            }

        }else{
            echo json_encode(array(
                'status' =>'0'
            ));
        }
    }

    //发送修改密码邮件
    public function send_business_pass_email($data){
        require_once('class.phpmailer.php'); //载入PHPMailer类

        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host = "smtp.sina.com"; //SMTP服务器 以163邮箱为例子
        $mail->Port = 25;  //邮件发送端口
        $mail->SMTPAuth   = true;  //启用SMTP认证

        $mail->CharSet  = "UTF-8"; //字符集
        $mail->Encoding = "base64"; //编码方式

        //$mail->SMTPDebug= true;

        $mail->Username = "axzbinternet@sina.com";  //你的邮箱
        $mail->Password = "axzbaxzb";  //你的密码
        $mail->Subject = "IPv6云打印 - 找回密码"; //邮件标题

        $mail->From = "axzbinternet@sina.com";  //发件人地址（也就是你的邮箱）
        $mail->FromName = "IPv6云打印";  //发件人姓名

        $address = $data['email'];//收件人email
        $mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

        //$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
        $mail->IsHTML(true); //支持html格式内容
        //$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
        $mail->Body = "亲爱的".$data['businessName']."：<br/>此封邮件可以帮您修改您的密码。<br/>请及时点击下面的链接修改您的密码。<br/>
    <a href='http://www.v6cp.com/welcome/bus_pass_reset/".$data['token'].'/'.$data['email']."' target=
'_blank'>http://www.v6cp.com/welcome/bus_pass_reset/".$data['token'].'/'.$data['email']."</a><br/>
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接10分钟内有效。";//邮件主体内容

        if($mail->Send()){
            return true;
        }else{
            return false;
        }

    }

    //商家重置密码界面
    public function bus_pass_reset($token,$email){
        $token = trim($token);
        $email = trim($email);
        $res = $this->index_model->check_business_email_exist($email);
        if($res){
            $mt = md5($res['businessPhone'].$res['businessPsw']);
            if($mt == $token){
                if(time() - $res['getPassTime'] > 60*10){
                    $data['msg'] = "链接已过期，请重试！";
                    echo $data['msg'];
                }else{
                    $data['msg'] = "请更改您的密码：";
                    $this->session->set_userdata('businessNick', $res['businessNick']);
                    $this->session->set_userdata('businessPhone', $res['businessPhone']);
                    $this->session->set_userdata('businessId', $res['businessId']);
                    $this->session->set_userdata('shopName', $res['shopName']);
                    $this->load->view('bus_pass_reset',$data);
                }
            }else{
                $data['msg'] ="无效的链接";
                echo $data['msg'];
            }
        }else{
            $data['msg'] = "错误的链接";
            echo $data['msg'];
        }

    }

    //商家忘记密码
    public function business_forget_pass(){
        $this->load->view('business_forget_pass');
    }

    //商家入驻
    public function shop_enter(){
        $this->load->view('shop_enter');
    }

    //商家注册
    public function shop_reg(){
        $this->load->view('shop_reg');
    }

    //保存商家信息
    public function reg_shop()
    {
        $data = $this->input->post();
        $data['regTime'] = date("Y-m-d H:i:s");
        $data['busyStatus'] = "正常";
        $data['runStatus'] = 1;
        $data['location'] = $data['yy'] . "," . $data['xx'];
        $data['lat'] = $data['yy'];//纬度
        $data['lng'] = $data['xx'];//经度
        $data['businessPsw'] = md5($data['businessPsw']);
        $data['class'] = 0;
        $data['belongAdmin'] = 0;
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
                'field' => 'property',
                'label' => '性质',
                'rules' => 'trim|required'
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

    //共用白色背景页面头
    public function white_top(){
        $this->load->view('white_top');
    }

    //用户注册协议
    public function userProt(){
        $this->load->view('userProt');
    }

    //商家指南
    public function shopGuide(){
        $this->load->view('shopGuide');
    }

    //新手指南
    public function fresherGuide(){
        $this->load->view('fresherGuide');
    }

    //校园推广
    public function schoolSpread(){
        $this->load->view('schoolSpread');
    }

    //商家加盟协议
    public function joinProt(){
        $this->load->view('joinProt');
    }

    //用户退出
    public function logout(){
        if(!$this->session->sess_destroy()){
            echo json_encode(array(
                'status' =>1
            ));
        }else{
            echo json_encode(array(
                'status' =>0
            ));
        }



    }


}
