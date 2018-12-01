<?php

class WXapi extends CI_Controller{
    public $appid = "wx0a968b67ab627fe2";
    public $appsecret = "5e3f9f3f3b283b8636d4c234bfa8a737";
    public function __construct(){
        parent::__construct();
        //$this->create_menu();
        //$this->responseMsg();
        $this->load->library('form_validation');
        $this->load->model('wx_model');
        $this->load->model('user/user_model');
        $this->load->model('client_model');
        $this->load->model('index_model');
        include('Security.php');
        //$this->load->helper('download');

    }

    public function index()
    {
        $this->create_menu();
        $this->responseMsg();
    }

    //获取accessToken
    function accessToken() {
        $tokenFile = "/home/wwwroot/default/cp/application/controllers/access_token.txt";//缓存文件名
        if(!file_exists($tokenFile)){
            $fp = fopen($tokenFile, "w");
            fclose($fp);
        }
        $data = json_decode(file_get_contents($tokenFile));
        if ($data->expire_time < time() || !$data->expire_time) {
            $appid = "wx0a968b67ab627fe2";
            $appsecret = "5e3f9f3f3b283b8636d4c234bfa8a737";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $res = $this->getJson($url);
            $access_token = $res['access_token'];
            if($access_token) {
                $data_new['expire_time'] = time() + 7200;
                $data_new['access_token'] = $access_token;
                $fp = fopen($tokenFile, "w");
                fwrite($fp, json_encode($data_new));
                fclose($fp);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    //取得微信返回的JSON数据
    function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }

    //发送给客户端的josn数据
    public function postJson($url,$post){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_HEADER, 0 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $post );
        curl_exec ($ch);
        curl_close ($ch);
    }

    //创建页面菜单
    public function create_menu(){
        $token = $this->accessToken();
        $post_munu_arr  = array(
            'button' =>array(
                array(
                    'name' => '我要打印',
                    'sub_button'=>array(
                        array(
                            'name' => '地图定位打印',
                            'type' => 'view',
                            //'key'  => 'code_print',
                            'url' =>'http://www.v6cp.com/WXapi/map_select'
                        ),
                        array(
                            'name' => '扫码打印',
                            'type' => 'scancode_push',
                            'key'  => 'scan_print'
                        )
                    )
                ),
                array(
                    'name' => '智慧v6',
                    'type' => 'click',
                    'key'  => 'zhihui'
                ),
                array(
                    'name' => 'v6助手',
                    'sub_button' => array(
                        array(
                            'name' => '意见反馈',
                            'type' => 'click',
                            'key'  => 'comment'
                        ),
                        array(
                            'name' => '了解我们',
                            'type' => 'click',
                            'key'  => 'about_us'
                        )
                    )
                )
            )
        );

        $menu_json = json_encode($post_munu_arr, JSON_UNESCAPED_UNICODE);
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$token";
        $this->postJson($url,$menu_json);
    }

    //向用户返回消息
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            //判断是文本消息还是事件消息
            $msgType = $postObj->MsgType;
            switch($msgType){
                case "text" :
                    $this->recv_text_msg($postObj);
                    break;
                case "event" :
                    $this->recv_event_msg($postObj);
                    break;
            }


        }else {
            echo "";
            exit;
        }
    }

    //接收到文本消息
    public function recv_text_msg($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        if(is_numeric($keyword)){
            $contentStr = "稍后添加~";
        }else{
            $contentStr = "请输入正确的数字！";
        }
        $this->send_text_msg($fromUsername,$toUsername,$time,$contentStr);
    }

    //发送文本消息
    public function send_text_msg($fromUsername, $toUsername, $time, $contentStr){
        $msgType = "text";
        //构建文本消息回复格式
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo $resultStr;

    }

    //接收事件消息
    public function recv_event_msg($postObj){
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();
        //获取用户点击事件的类型
        $event = $postObj->Event;
        if($event == "CLICK"){
            $key_value = $postObj->EventKey;
            switch($key_value){
                case 'code_print':
                    $contentStr = "请输入打印点编号：";
                    break;
                case 'zhihui':
                    $contentStr = "智慧生活大门即将开启，敬请期待...";
                    break;
                case 'comment':
                    $contentStr = "请发送给我们您对咱的意见哦，我们会认真听取您的宝贵建议，(*^__^*) ";
                    break;
                case 'about_us':
                    $contentStr = "v6网印产品是以下一代互联网为基础，整合打印设备资源，构建漫游共享打印平台，向全社会提供随时随地的质量标准化的打印服务为目的打造的简化打印流程、降低打印成本、节约打印时间的一款服务性产品。";
                    break;
                default:
                    $contentStr = "欢迎使用v6网印！O(∩_∩)O";
                    break;
            }
        }else if($event == "subscribe"){
            $contentStr = "欢迎使用v6网印！O(∩_∩)O";
        }

        $this->send_text_msg($fromUsername,$toUsername,$time,$contentStr);
    }

    //检查用户是否登录
    public function check_login(){
        if($this->session->userdata('userPhone')){
            return true;
        }else{
            $this->get_user_auth();
        }
    }

    //获取用户授权，获得用户openId
    public function get_user_auth(){
        $redirect_url = "http://www.v6cp.com/WXapi/check_auth";
        $get_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".urlencode($redirect_url)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header("Location:".$get_url);

    }

    //检查用户是否授权
    public function check_auth(){
        $code = $this->input->get('code');
        if(empty($code)){
            echo "请先授权再使用!";
        }else{
            $get_access_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
            $res = $this->getJson($get_access_url);
            $acc_token = array_key_exists('access_token',$res) ? $res['access_token'] : '';
            $open_id = array_key_exists('openid',$res) ? $res['openid'] : '';

            //获取用户基本信息
            $get_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$acc_token."&openid=".$open_id."&lang=zh_CN";
            $user_info = $this->getJson($get_info_url);
            $this->session->set_userdata('openid',array_key_exists('openid',$user_info) ? $user_info['openid'] : '');
            $this->session->set_userdata('nickname',array_key_exists('nickname',$user_info) ? $user_info['nickname'] : '');

            $this->login();
        }
    }

    //登录页面
    public function login(){
        $this->load->view('weixin/login');
    }

    //检查用户账号名和密码
    public function user_check(){
        $userPhone = $this->input->post('userPhone');
        $psw = md5($this->input->post('userPsw'));
        $data = $this->index_model->select_psw('user',$userPhone);
        if($data && $psw == $data['userPsw']){
            $this->session->set_userdata('userNick', $data['userNick']);
            $this->session->set_userdata('class', $data['class']);
            $this->session->set_userdata('belongAdmin', $data['belongAdmin']);
            $this->session->set_userdata('userPhone', $userPhone);

            setcookie('userPhone',$userPhone,time()+3600*24*7);
            setcookie('password',$this->input->post('userPsw'),time()+3600*24*7);
            //更新openid
            $this->wx_model->update_openid($userPhone,$this->session->userdata('openid'));
            echo json_encode(array(
                'status' => 1
            ));
        }else{
            echo json_encode(array(
                'status' => 0
            ));
        }
    }

    //地图选择打印
    public function map_select(){
        /*$this->check_login();
        if(!($this->session->userdata('openid') && $this->session->userdata('userPhone'))){
            $this->get_user_auth();
        }else{
            $this->load->view('weixin/map_select');
        }*/

        $this->get_user_auth();
    }

    //展示地图页面
    public function show_map(){
        if($this->check_login()){
            $this->load->view('weixin/map_select');
        }else{
            return;
        }
    }

    //检查商家是否在线
    public function check_online(){
        $bid = $this->input->post('shopid');
        $res = $this->wx_model->get_online($bid);
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



    //获取地图上区域打印点列表
    public function get_shop_list(){
        $min_jingdu = $this->input->post('min_xx');//左下角
        $min_weidu = $this->input->post('min_yy');
        $max_jingdu = $this->input->post('max_xx');//右上角
        $max_weidu = $this->input->post('max_yy');
        $data['content'] = $this->wx_model->get_near_shop($min_jingdu,$min_weidu,$max_jingdu,$max_weidu);
        if(!empty($data)){
            $data['status'] = 1;
            echo json_encode($data);
        }else{
            $data['status'] = 0;
            echo json_encode($data);
        }
    }

    //搜索框检索商家
    public function search_shop(){
        $shop_name = $this->input->post('value');
        $res = $this->wx_model->search_shop($shop_name);
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

    //扫码选中打印点
    public function scan_select_shop($bid){
        if($bid){
            $bus_info = $this->wx_model->get_bus_info($bid);
            $data['shopName'] = $bus_info['shopName'];
            $data['bid'] = $bid;
            $this->load->view('weixin/upload_file_new',$data);
        }

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
            //echo "文件上传失败！";

        }
        else
        {
            $data = $this->upload->data();
            $data['newName'] = $data['file_name'];
            $data['docName'] = $data['orig_name'];
            $data['docLocation'] = $data['file_name'];
            $data['docType'] = $data['file_ext'];
            $data['docBelongPhone'] = $this->session->userdata('userPhone');
            //$this->session->set_userdata('userPhone',$data['docBelongPhone']);
            if($this->wx_model->insert_file_info($data)){
                $docId_raw = $this->wx_model->get_docId($data['newName']);
                $docId = $docId_raw['docId'];
                $this->session->set_userdata('docId',$docId);
                echo "上传成功，请点击'开始打印'按钮开始打印~";
            }
            else{
                echo json_encode(array(
                    'status' => 0
                ));

            }
        }
    }

    //生成订单
    public function make_order(){
        $docId = $this->session->userdata('docId');
        $docInfo = $this->user_model->get_doc_info($docId);
        $orderInfo['docId'] = $docId;
        $orderInfo['docName'] = $docInfo['docName'];
        $orderInfo['userNick'] = $this->session->userdata('nickname') ? $this->session->userdata('nickname') : "test";
        $orderInfo['userPhone'] = $this->session->userdata('userPhone') ? $this->session->userdata('userPhone') :"666666";
        $orderInfo['userName'] = $this->session->userdata('openid') ? $this->session->userdata('openid') :"数博会";
        $orderInfo['upTime'] = $docInfo['docTime'];
        $orderInfo['makeTime'] = date("Y-m-d H:i:s");
        $orderInfo['docFormat'] = $docInfo['docFormat'];
        $orderInfo['businessId'] = $this->input->post('bid');
        $orderInfo['shopName'] = $this->wx_model->get_shop_name($orderInfo['businessId']);
        $orderInfo['message'] ="";
        if(empty($orderInfo['message'])){
            $orderInfo['message'] = "";
        }
        $orderInfo['belongAdmin'] = $this->wx_model->get_adminId_by_businessId($orderInfo['businessId']);
        $orderInfo['specialOrder'] =0;
        $orderInfo['doublePrint'] = 0;
        if($orderInfo['doublePrint'] == 1){
            $orderInfo['specialOrder'] = 1;
        }
        $orderInfo['colorPrint'] = 0;
        $orderInfo['sendType'] = 0;//配送模式
        $orderInfo['sendStatus'] = 0;//未配送
        $orderInfo['printCopis'] = 1;
        $orderInfo['payStatus'] = 0;
        $orderInfo['printStatus'] = 0;
        $orderInfo['ip'] = $this->GetIP();

        $page_num = 0;

        /*if(empty($page_num)){
            $page_num = 0;
        }else{
            $page_num = (int)$this->input->post('pageNum');
        }*/

        $cer_price = $this->user_model->get_price($orderInfo['businessId'],$orderInfo['doublePrint'],$orderInfo['colorPrint']);
        $total = $orderInfo['printCopis'] * $cer_price * $page_num;
        $orderInfo['price'] = $total;
        //发送订单socket
        $this->sendOrder($orderInfo['businessId'],$orderInfo);

        if($this->user_model->insert_info('order',$orderInfo)){
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

    //显示上传文件页面
    public function show_upload(){
        $this->load->view('weixin/upload_file');
    }


    //更新付款状态(微信支付回调API)
    function update_pay_status(){
        $this->load->library("WxLib/PayNotifyCallBack");

        //初始化日志
        $logHandler= new CLogFileHandler("application/logs/".date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);

        Log::DEBUG("begin notify");
        $callback = new PayNotifyCallBack();
        $callback->Handle(true);
    }

}






?>