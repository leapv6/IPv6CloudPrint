<?php
    /*
     * 与PC客户端通信接口
     * 数据传输用JSON格式,aes128加密
     */
defined('BASEPATH') OR exit('No direct script access allowed');


class Clientapi extends CI_Controller{
    public function __construct(){
        parent::__construct();

        $this->load->model('client_model');
        $this->load->library('form_validation');
        $this->load->helper('download');
        include('Security.php');

    }

    //通过userID查询手机号和密码组成的 秘钥
    public function get_key($userID){
        $data = $this->client_model->get_key($userID);
        return md5($data['businessPhone'].$data['businessPsw']);
    }


    //检查商家登录信息接口
    public function checkLogin(){
        $login_key = "IPv6Cloud";
        $security = new Security();
        $data_arr = $this->input->post('data');
        $data_arr = json_decode($data_arr,true);
        $json = json_encode($data_arr['data']);
        //解密
        //$dec1 = $security->encrypt($json,$login_key);
        $dec = $security->decrypt($json,$login_key);
        $dec = json_decode($dec,true);
        //var_dump($dec);
        $phone = $dec['businessPhone'];
        $psw = $dec['businessPsw'];
        $res = $this->client_model->get_psw($phone);
        if($res){
            if($psw == $res['businessPsw']){
                //检查该商户是否已经登录
                if($this->client_model->get_online_status($phone)['runStatus']){
                    /*$success = true;
                    $userID = $res['businessId'];
                    $userNick = $res['businessNick'];
                    $reason = '登录成功！';*/

                    $success = false;
                    $userID = '';
                    $userNick ='';
                    $reason = '该账户已经登录！';

                }else{
                    $success = true;
                    $userID = $res['businessId'];
                    $userNick = $res['shopName'];
                    $reason = '登录成功！';
                }

            }else{
                $success = false;
                $userID = '';
                $userNick ='';
                $reason = '手机号或者密码错误！';
            }
        }else{
            $success = false;
            $userID = '';
            $userNick = '';
            $reason = '手机号或者密码错误！';
        }
        $info = array(
            'success' => $success,
            'userNick' =>$userNick,
            'userID' => $userID,
            'reason' => $reason
        );
        $enc_info_json = json_encode($info);
        //var_dump($enc_info_json);
        $enc_info = $security->encrypt($enc_info_json,$login_key);
        echo $enc_info;
        //var_dump(base64_decode($enc_info));

        //var_dump($enc_info);

        //$dd = $security->decrypt($enc_info,$login_key);
        //var_dump($dd);
    }

    //用户强制登录接口，向已在线用户发送socket提示下线
    public function force_offline(){
        $login_key = "IPv6Cloud";
        $security = new Security();
        $data_arr = $this->input->post('data');
        $data_arr = json_decode($data_arr,true);
        $json = json_encode($data_arr['data']);
        //解密
        //$dec1 = $security->encrypt($json,$login_key);
        $dec = $security->decrypt($json,$login_key);
        $dec = json_decode($dec,true);
        //var_dump($dec);
        $phone = $dec['businessPhone'];
        //获取当前用户ip地址
        $res = $this->client_model->get_bus_addr($phone);
        if($res){
            $data = array(
                'offline' => 1
            );
            $this->sendSocketMsg($res['clientAddr'],$res['port'],json_encode($data));
            $success = true;
            $reason = '已发送下线socket';
        }else{
            $success = true;
            $reason = "";
        }
        $this->client_model->update_online_by_phone($phone,0);

        $data = array(
            'success' =>$success,
            'reason' =>$reason
        );

        echo $security->encrypt(json_encode($data),$login_key);


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

    //未处理订单
    public function unhandled_orders(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $security = new Security();
        $key = $this->get_key($userID);
        $input_json_enc = json_encode($input_arr_enc['data']);
        $input_json_dec = $security->decrypt($input_json_enc,$key);
        $input_arr_dec  = json_decode($input_json_dec,true);

        $data_arr =$input_arr_dec;
        $start = $data_arr['start'];
        $limit = $data_arr['limit'];
        $orders_info = $this->client_model->get_unhandled_orders($userID,$start,$limit);

        $info['total'] = $orders_info['total'];
        $info['special'] = $orders_info['special'];
        $info['nodelivery'] = $orders_info['nodelivery'];
        $info['data'] = $orders_info['data'];

        echo $security->encrypt(json_encode($info),$key);
    }


    //配置客户端ipv6地址
    public function set_client_addr(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $security = new Security();
        $key = $this->get_key($userID);
        $input_json_enc = json_encode($input_arr_enc['data']);
        $input_json_dec = $security->decrypt($input_json_enc,$key);
        $input_arr_dec  = json_decode($input_json_dec,true);

        //$userid = $input_arr_dec['userID'];
        $clientAddr = $input_arr_dec['clientAddr'];
        $port = $input_arr_dec['port'];

        $res = $this->client_model->update_client_addr($userID,$clientAddr,$port);
        if($res){
            $success = true;
            $reason = '';
        }else{
            $success = false;
            $reason = "错误";
        }

        $data = array(
            'success' =>$success,
            'reason' =>$reason
        );

        echo $security->encrypt(json_encode($data),$key);
    }

    //客户端查询自己的ipv6地址
    public function get_client_addr(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $security = new Security();
        $key = $this->get_key($userID);
        $res = $this->client_model->get_addr($userID);
        if($res){
            $success = true;
            $clientAddr = $res['clientAddr'];
        }else{
            $success = false;
            $clientAddr = '';
        }
        $data = array(
            'success' => $success,
            'clientAddr' =>$clientAddr
        );

        echo $security->encrypt(json_encode($data),$key);

    }

    //获取文档路径给客户端下载
    public function get_file_location(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $security = new Security();
        $key = $this->get_key($userID);
        $input_json_enc = json_encode($input_arr_enc['data']);
        $input_json_dec = $security->decrypt($input_json_enc,$key);
        $input_arr_dec  = json_decode($input_json_dec,true);
        $data_arr = $input_arr_dec;
        $orderId = $data_arr['orderId'];

        $res = $this->client_model->get_location($orderId,$userID);
        $location = $res['docLocation'];
        $docName = $res['docName'];
        $data = array(
            'file_location' => base_url().'clientapi/down_file?url='.$location
        );
        echo $security->encrypt(json_encode($data),$key);

    }

    //下载api
    public function down_file(){
        $location = $this->input->get('url');
        $data = file_get_contents("./uploads/".$location); // 读文件内容
        force_download($location, $data);
    }

    //处理修改订单状态
    public function handled_orders(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $security = new Security();
        $key = $this->get_key($userID);
        $input_json_enc = json_encode($input_arr_enc['data']);
        $input_json_dec = $security->decrypt($input_json_enc,$key);
        $input_arr_dec  = json_decode($input_json_dec,true);
        $data_arr = $input_arr_dec;
        //获取订单修改状态
        $orderId = $data_arr['orderId'];
        $data['printStatus'] = $data_arr['printStatus'];
        $data['payStatus'] = $data_arr['payStatus'];
        $data['sendStatus'] = $data_arr['sendStatus'];
        $res = $this->client_model->handled_orders($orderId,$data);

        if($res){
            $success = true;
            $reason = '修改订单状态成功！';
        }else{
            $success = false;
            $reason = "修改订单状态失败！";
        }

        $info = array(
            'success' =>$success,
            'reason' =>$reason
        );

        echo $security->encrypt(json_encode($info),$key);
    }

    //获取历史订单
    public function history_orders(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $key = $this->get_key($userID);
        $security = new Security();

        $input_json_enc = json_encode($input_arr_enc['data']);
        $input_json_dec = $security->decrypt($input_json_enc,$key);
        $input_arr_dec  = json_decode($input_json_dec,true);

        $data_arr =$input_arr_dec;
        $start = $data_arr['start'];
        $limit = $data_arr['limit'];

        $res = $this->client_model->get_history($userID,$start,$limit);
        echo $security->encrypt(json_encode($res),$key);

    }

    //更新在线信息
    public function update_online(){
        $input_arr_enc = json_decode($this->input->post('data'),true);
        $userID = $input_arr_enc['userid'];
        $security = new Security();
        $key = $this->get_key($userID);
        $input_json_enc = json_encode($input_arr_enc['data']);
        $input_json_dec = $security->decrypt($input_json_enc,$key);
        $input_arr_dec  = json_decode($input_json_dec,true);
        $data_arr = $input_arr_dec;

        $online = $data_arr['online'];
        $lastUpdate = date("Y-m-d H:i:s");

        $res = $this->client_model->update_online($userID,$online,$lastUpdate);
        if($res){
            $success = true;
            $reason = '更新在线状态成功！';
        }else{
            $success = false;
            $reason = "更新在线状态失败！";
        }

        $info = array(
            'success' =>$success,
            'reason' =>$reason
        );

        echo $security->encrypt(json_encode($info),$key);

    }








    public function test(){
        $login_key = "IPv6Cloud";
        $security = new Security();
        $data_arr = $this->input->post('data');
        $json = json_encode($data_arr);

        /*var_dump($json);

        $enc = $security->encrypt($json,$login_key);
        var_dump($enc);

        $dec = $security->decrypt($enc,$login_key);
        var_dump($dec);*/



        echo $this->GetIP();
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

}


