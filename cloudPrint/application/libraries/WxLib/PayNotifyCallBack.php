<?php
/**
 * Created by PhpStorm.
 * User: Rei
 * Date: 16/4/5
 * Time: 14:20
 * Description: 微信回调接口
 */

require_once "WxPayApi.php";
require_once 'WxPayNotify.php';
require_once 'CLogFileHandler.php';

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            $return = $this->UpdatePayStatus($result["attach"]);
            Log::DEBUG("update: ". $result["attach"] . "value: " . $return);
            return true;
        }
        return false;
    }

    //更新数据库订单支付结果
    public function UpdatePayStatus($ordId){
        $DB = new PDO('mysql:host=127.0.0.1;port=3306;dbname=cloudprint;charset=UTF8;','root','roshamon');
        $update_sql = "update cp_order SET payStatus = 1 WHERE orderId = " . $ordId;
        return $DB->exec($update_sql);
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }
}