<?php
    class User_index extends MY_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->model('user/user_model');
            $this->load->model('client_model');
            $this->load->helper('download');
            include('Security.php');
            if(!$this->_check_user_login()){
                redirect(base_url('/'));
            }
        }

        public function index(){
            $active = $this->user_model->check_active($this->session->userdata('userPhone'));
            if($active){
                $this->my_order(1);
            }else{
                $this->load->view('user_active_cfm');
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
            $mail->Encoding = "base64"; //编码方式

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

        //重发电子邮件
        public function send_email_again(){
            $data = $this->user_model->get_email_info($this->session->userdata['userPhone']);
            if($this->send_email($data)){
                $time = time()+60*10;
                $this->user_model->update_time($this->session->userdata['userPhone'],$time);
                echo json_encode(array(
                    'status' => 1
                ));
            }else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }

        //用户注册验证邮件页面
        public function show_active_page(){
            $this->load->view('user_active_cfm');
        }



        //我的订单
        public function my_order($page_num = 1){
            if(is_numeric($page_num) &&  $page_num >0){
                $offset = 10*($page_num - 1);
                $orders['info'] = $this->user_model->get_orders_info(10,$offset,$this->session->userdata('userPhone'));
                $orders['total'] = $orders['info']['total'];
                $orders['now_page'] = $page_num;
                $orders['all_page'] = $orders['total']/10 ? $orders['total']/10 : 1;
                $phone = $this->session->userdata('userPhone');
                $a = $this->user_model->get_info('user',$phone);
                $orders['email'] = $a['email'];
                $orders['bnick'] = "所属公司/单位：".$a['businessNick'];
                $this->load->view('user/index',$orders);
            }
            else{
                $this->my_order(1);
            }
        }

        //我的文件
        public function my_file($page_num = 1){
            if(is_numeric($page_num) &&  $page_num >0){
                $offset = 10*($page_num - 1);
                $files['info'] = $this->user_model->get_files_info(10,$offset,$this->session->userdata('userPhone'));
                $files['total'] = $files['info']['total'];
                $files['now_page'] = $page_num;
                $files['all_page'] = $files['total']/10 ? $files['total']/10 : 1;
                $this->load->view('user/my_files',$files);
            }
            else{
                $this->my_file(1);
            }
        }

        function upload(){
            $this->load->view('user/upload_file');
        }

        //上传文件
        function do_upload()
        {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'pptx|doc|docx|pdf|xlsx|xls|ppt|wps|gif|jpg|png|jpeg';
            $config['max_size'] = 0;
            $config['max_width']  = '40000';
            $config['max_height']  = '40000';
            $config['encrypt_name'] = true;

            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload('file'))
            {

                $error = array('error' => $this->upload->display_errors());
                var_dump($error);
                var_dump($this->upload->data());
                /*echo '<script type="text/javascript">
                        alert("上传失败！请选择doc,docx,pdf,gif,jpg,png格式的文件！");
                        location.href=document.referrer;
                        </script>';*/
            }
            else
            {
                $data = $this->upload->data();
                $data['newName'] = $data['file_name'];
                $data['docName'] = $data['orig_name'];
                $data['docLocation'] = $data['file_name'];
                $data['docType'] = $data['file_ext'];
                $data['docBelongPhone'] = $this->session->userdata['userPhone'];
                if($this->user_model->insert_file_info($data)){
                    echo json_encode(array(
                        'status' => 2105
                    ));
                    /*echo '<script type="text/javascript">
                        alert("上传成功！");
                        location.href=document.referrer;
                        </script>';*/
                }
                else{
                    echo json_encode(array(
                        'status' => '2106'
                    ));
                    /*alert("上传失败！");
                    echo '<script type="text/javascript">
                        location.href=document.referrer;
                        </script>';*/
                }
            }
        }

        //下载文档
        public function download($id){
            $info = $this->user_model->find_location($id);
            $location = "./uploads/".$info['docLocation'];
            $data = file_get_contents($location); // 读文件内容
            force_download($info['docName'], $data);
        }

        //删除文档
        public function delete(){
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

        //选择商家打印
        public function select_shop($businessId,$docId){
            $data['shopName'] = $this->user_model->get_shop_name($businessId);
            $data['businessId'] = $businessId;
            $data['docId'] = $docId;
            $data['price'] = $this->user_model->get_price($businessId,0,0);
            //判断商家类型
            //$class = $this->session->userdata('class');
            $class = $this->user_model->get_bus_class($businessId);
            if($class == 0){
                $this->load->view('user/select_shop',$data);
            }elseif($class == 1){
                $this->load->view('user/gov_select_shop',$data);
            }

        }

        //搜索框检索商家
        public function search_shop(){
            $shop_name = $this->input->post('value');
            $res = $this->user_model->search_shop($shop_name);
            $num = count($res) > 8 ? 8:count($res);

            if(empty($res)){
                echo '<li><a>查不到该打印店</a></li>';
                echo '<ul>';
            }else{
                for($i =0;$i < $num;$i++){
                    echo "<li><a onclick='select_this_shop(".$res[$i]['businessId'].")'>".$res[$i]['shopName'].'</a></li>';

                }
                echo '<li class="cls"><a href="javascript:;" onclick="$(this).parent().parent().fadeOut(100)">关闭</a& gt;</li>';
                echo '</ul>';
            }
        }

        //检查商家是否在线
        public function check_online(){
            $bid = $this->input->post('shopid');
            $res = $this->user_model->get_online($bid);
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

        //显示价格
        public function get_price(){
            $bid = $this->input->post('bid');
            $double = $this->input->post('double');
            $color = $this->input->post('color');
            $copis = $this->input->post('copis');
            $page = $this->input->post('page');
            if(empty($page)){
                $page = 1;
            }
            $cer_price = $this->user_model->get_price($bid,$double,$color);
            $total = $copis * $cer_price * $page;
            echo json_encode(array(
                'price' => $total
            ));
        }

        //地图展示商家
        public function map_show($docId){
            $info['docId'] = $docId;
            //判断获取用户所属公司id，快捷选取公司打印点
            if($this->session->userdata('class') == 2){
                $res = $this->user_model->get_user_info($this->session->userdata('userPhone'));
                $info['belongComId'] = $res['belongComId'];
            }
            $this->load->view('user/map_select',$info);
        }

        //获取附近商家列表
        public function get_shop_list(){
            $min_jingdu = $this->input->post('min_xx');//左下角
            $min_weidu = $this->input->post('min_yy');
            $max_jingdu = $this->input->post('max_xx');//右上角
            $max_weidu = $this->input->post('max_yy');
            $data['content'] = $this->user_model->get_near_shop($min_jingdu,$min_weidu,$max_jingdu,$max_weidu);
            if(!empty($data)){
                $data['status'] = 1;
                echo json_encode($data);
            }else{
                $data['status'] = 0;
                echo json_encode($data);
            }


        }

        //生成订单
        public function make_order(){
            $docId = $this->input->post('docId');
            $docInfo = $this->user_model->get_doc_info($docId);
            $orderInfo['docId'] = $docId;
            $orderInfo['docName'] = $docInfo['docName'];
            $orderInfo['userNick'] = $this->session->userdata['userNick'];
            $orderInfo['userPhone'] = $this->session->userdata['userPhone'];
            $orderInfo['userName'] = $this->user_model->select_user_name($orderInfo['userPhone']);
            $orderInfo['upTime'] = $docInfo['docTime'];
            $orderInfo['makeTime'] = date("Y-m-d H:i:s");
            $orderInfo['docFormat'] = $docInfo['docFormat'];
            $orderInfo['businessId'] = $this->input->post('businessId');
            $orderInfo['shopName'] = $this->user_model->get_shop_name($orderInfo['businessId']);
            $orderInfo['message'] =$this->input->post("message");
            if(empty($orderInfo['message'])){
                $orderInfo['message'] = "";
            }
            $orderInfo['belongAdmin'] = $this->session->userdata('belongAdmin');
            $orderInfo['specialOrder'] =$this->input->post("specialOrder");
            $orderInfo['doublePrint'] = $this->input->post('doublePrint');
            if($orderInfo['doublePrint'] == 1){
                $orderInfo['specialOrder'] = 1;
            }
            $orderInfo['colorPrint'] = $this->input->post('colorPrint');
            $orderInfo['sendType'] = $this->input->post('sendType') ? $this->input->post('sendType') : 0;//配送模式
            $orderInfo['sendStatus'] = 0;//未配送
            $orderInfo['printCopis'] = $this->input->post('printCopis');
            //判断是否需要支付
            if($this->user_model->get_bus_class($orderInfo['businessId']) ==1){
                $orderInfo['payStatus'] = 1;
            }else{
                $orderInfo['payStatus'] = 0;
            }
            $orderInfo['printStatus'] = 0;
            $orderInfo['ip'] = $this->GetIP();

            $page_num = $this->input->post('pageNum');

            if(empty($page_num)){
                $page_num = 0;
            }else{
                $page_num = (int)$this->input->post('pageNum');
            }

            $cer_price = $this->user_model->get_price($orderInfo['businessId'],$orderInfo['doublePrint'],$orderInfo['colorPrint']);
            $total = $orderInfo['printCopis'] * $cer_price * $page_num;
            $orderInfo['price'] = $total;


            if($ord_id = $this->user_model->insert_order('order',$orderInfo)){
                if($this->user_model->get_bus_class($orderInfo['businessId']) == 0){
                    if($ord_id > 0) {
                        echo json_encode(array(
                            'status' => 1,
                            //'qrcode_pic' => $this->weixin_qrcode($orderInfo['docName'], $total, $ord_id)
                            'order_id' => $ord_id,
                            'tips' => '正在生成支付二维码，请稍后...',
                            'class' => 0
                        ));
                    }else{
                        echo json_encode(array(
                            'status' => 1,
                            'tips' => "二维码生成失败",
                            'class'=> 0
                        ));
                    }
                }else {
                    echo json_encode(array(
                        'status' => 1,
                        'class' =>1 //企业、单位
                    ));
                    //发送订单socket
                    $this->sendOrder($orderInfo['businessId'],$orderInfo);
                }
            }else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }

        //显示二维码 界面
        public function show_qrcode($order_id){
            $res = $this->user_model->get_order_info($order_id);
            if($res){
                if($code_url = $this->weixin_qrcode($res['docName'],$res['price'],$order_id)){
                    $data['code_url'] = $code_url;
                    $data['docName'] = $res['docName'];
                    $data['order_id'] = $order_id;
                    $this->load->view('user/show_pay_code',$data);
                }else{
                    echo '二维码生成失败!';
                }
            }else{
                echo '订单号错误！';
            }
        }

        //生成微信支付二维码
        function weixin_qrcode($body, $total, $ord_id){
            $this->load->library("WxLib/WxPayApi");
            $this->load->library("WxLib/NativePay");

            $notify = new NativePay();

            $input = new WxPayUnifiedOrder();
            $input->SetBody($body);
            $input->SetAttach($ord_id);
            $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
            $input->SetTotal_fee($total*10);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 1200));
            $input->SetGoods_tag("v6网印");
            $input->SetNotify_url(site_url("WXapi/update_pay_status"));
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id($ord_id);
            $result = $notify->GetPayUrl($input);
            //var_dump($result);
            $url2 = urlencode($result["code_url"]);

            if($result["return_code"] == "SUCCESS")
                return $url2;
            else{
                return false;
            }
        }


        //生成二维码
        public function create_code(){
            require_once "phpqrcode/phpqrcode.php";
            $value = $this->input->get("data");
            $errorCorrectionLevel = 'M';//容错级别
            $matrixPointSize = 9;//生成图片大小
            QRcode::png($value, false, $errorCorrectionLevel, $matrixPointSize, 2);
        }

        //定时查询是否支付成功
        public function check_has_pay(){
            $oid = $this->input->post('orderId');
            $res = $this->user_model->get_order_info($oid);
            if($res && $res['payStatus'] == 1){
                echo json_encode(array(
                    'status' => 1
                ));

                //支付成功后发送订单socket
                //发送订单socket
                $this->sendOrder($res['businessId'],$res);
            }else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }

        //订单页面检查支付信息
        public function check_pay(){
            $oid = $this->input->post('orderId');
            $res = $this->user_model->get_order_info($oid);
            if($res && $res['payStatus'] == 1){
                echo json_encode(array(
                    'status' => 1
                ));

            }else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }


        function GetIP(){
            if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            else if (@$_SERVER["HTTP_CLIENT_IP"])
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            else if (@$_SERVER["REMOTE_ADDR"])
                $ip = $_SERVER["REMOTE_ADDR"];
            else if (@getenv("HTTP_X_FORWARDED_FOR"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else if (@getenv("HTTP_CLIENT_IP"))
                $ip = getenv("HTTP_CLIENT_IP");
            else if (@getenv("REMOTE_ADDR"))
                $ip = getenv("REMOTE_ADDR");
            else
                $ip = "Unknown";
            return $ip;
        }

        //通过userID查询手机号和密码组成的 秘钥
        public function get_key($userID){
            $data = $this->client_model->get_key($userID);
            return md5($data['businessPhone'].$data['businessPsw']);
        }

        //发送订单信息的socket
        public function sendOrder($bid,$order){
            //获取商家v6地址和端口
            $res = $this->user_model->get_bus_addr($bid);
            if($res){
                $security = new Security();
                $key = $this->get_key($bid);
                $enc_order = $security->encrypt(json_encode($order),$key);
                $this->sendSocketMsg($res['clientAddr'],$res['port'],$enc_order);
            }else{
                return false;
            }
        }

        public function sendSocketMsg($host,$port,$msg){
            $commonProtocol = getprotobyname("tcp");
            $socket = socket_create(AF_INET6,SOCK_STREAM,$commonProtocol);
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO,array("sec"=>2, "usec"=>0));//发送超时
            $result = @socket_connect($socket,$host,$port);
            if ($result == false){
                return false;
            }else{
                socket_write($socket,$msg,strlen($msg));
            }


        }

        //删除订单
        public function del_order(){
            $orderId = $this->input->post('orderId');
            if($this->user_model->del_order('order',$orderId)){
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

        //我的个人信息
        public function my_info(){
            $data = $this->user_model->get_user_info($this->session->userdata('userPhone'));
            $this->load->view('user/my_info',$data);
        }

        //更新个人信息
        public function update_user_info(){
            $data = $this->input->post();
            if($this->user_model->update_user_info($this->session->userdata('userPhone'),$data)){
                $this->session->set_userdata('userNick', $data['userNick']);
                echo json_encode(array(
                    'status' =>1
                ));
            }else{
                echo json_encode(array(
                    'status' =>0
                ));
            }
        }

        //更改密码界面
        public function change_psw(){
            $this->load->view('user/change_psw');
        }

        //更新密码
        public function update_psw(){
            $data['old'] = md5($this->input->post('oldPsw'));
            $data['new'] = md5($this->input->post('newPsw'));
            if($this->user_model->update_psw($data,$this->session->userdata('userPhone'))){
                echo json_encode(array(
                    'status' =>1
                ));
            }else{
                echo json_encode(array(
                    'status' =>0
                ));
            }
        }

        //检查用户能否评价，未完成订单不能评价
        public function check_print_status(){
            $orderId = $this->input->post('orderId');
            $status = $this->user_model->get_print_status($orderId);
            if($status == 0){
                echo json_encode(array(
                    'status' =>0
                ));
            }else{
                echo json_encode(array(
                    'status' =>1
                ));
            }
        }

        //用户评价
        public function judge($orderId){
            $data = $this->user_model->get_comment_by_orderId($orderId);
            $data['orderId'] = $orderId;
            $this->load->view('user/judge',$data);
        }

        //保存用户评价
        public function make_comment(){
            $data = $this->input->post();
            if($this->user_model->save_comment($data)){
                echo json_encode(array(
                    'status' => 1
                ));
            }else{
                echo json_encode(array(
                    'status' => 0
                ));
            }
        }

        //退出系统
        public function logout(){
            unset($_SESSION['userPhone']);
            unset($_SESSION['userNick']);
            unset($_SESSION['userName']);
            header('Location:'.base_url());


        }



    }