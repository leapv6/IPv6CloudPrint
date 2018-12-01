<html>
<head>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>
    <style>
        img{
            margin: 0 auto;
        }
        p{
            font-family: 微软雅黑;
            font-size: 14pt;
        }
        div{
            text-align: center;
            padding-top: 20px;
        }
    </style>

    <script>
        function check_has_pay(){
            $.ajax({
                url : "<?php echo site_url('user/user_index/check_has_pay') ?>",
                data : {orderId:<?php echo $order_id?>},
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg('支付成功O(∩_∩)O~',{time:2000});
                        top.location.href = "<?php echo site_url('user/user_index/my_order') ?>";

                    }else{

                    }
                },
                beforeSend:function(){

                }
            });
        }

        setInterval(check_has_pay,1500);
    </script>
</head>
    <div>
        <img alt="微信扫码支付" src="http://www.v6cp.com/user/user_index/create_code/?data=<?php echo $code_url;?>" style="width=300px; height=300px" />
        <p>文件名:<?php echo $docName;?></p>
        <p>微信二维码扫码支付</p>
    </div>


</html>