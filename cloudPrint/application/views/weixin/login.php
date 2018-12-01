<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>v6网印</title>
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js""></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer_m.js" id = "layer"></script>

    <script type="text/javascript">
        function windowHeight() {
            var de = document.documentElement;
            var h = self.innerHeight||(de && de.clientHeight)||document.body.clientHeight;
            $("#body").css("height",h-15);
        }

        function login(){
            $.ajax({
                url : "<?php echo site_url('WXapi/user_check') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.open({
                            content: '登录成功！',
                            style: 'background-color:#67d978; color:#fff; border:none;',
                            time: 1
                        });
                        parent.location.href = "<?php echo site_url('WXapi/show_map') ?>";
                    }else{
                        layer.open({
                            content: '手机号或密码错误/(ㄒoㄒ)/~，请重试！',
                            style: 'background-color:#67d978; color:#fff; border:none;',
                            time: 2
                        });
                    }

                },
                beforeSend:function(){

                }
            });
        }
    </script>
    <style>
        #body{
            background-color: white;
            -webkit-box-shadow: 5px 5px 12px #b9b9b9,-5px -5px 12px #b9b9b9,-5px 5px 12px #b9b9b9,5px -5px 12px #b9b9b9;
            -moz-box-shadow: 5px 5px 12px #b9b9b9,-5px -5px 12px #b9b9b9,-5px 5px 12px #b9b9b9,5px -5px 12px #b9b9b9;
            box-shadow: 5px 5px 12px #b9b9b9,-5px -5px 12px #b9b9b9,-5px 5px 12px #b9b9b9,5px -5px 12px #b9b9b9;
        }

        body{
            padding: 8px 8px;
            background-color: white;

        }

        #content{
            padding: 0 10px;
        }

        .input {
            border-radius:10px;
            -moz-border-radius:10px;
            -ms-border-radius:10px;
            -o-border-radius:10px;
            -webkit-border-radius:10px;
            width: 100%;
            height: 40px;
            background-color: #f99da1;
            margin: 16px auto;
            border:1px solid #ccc;
            font-family: 微软雅黑;
            font-size:14pt;
            color: white;
        }

        .input_content{
            margin-left: 20px;
            margin-top: 6px;
        }

        input{
            tap-highlight-color:rgba(0,0,0,0);
            -webkit-tap-highlight-color:rgba(0,0,0,0);
            border:0px;
            background: inherit;
            width: 70%;
            height: 30px;
            padding: 0;
            font-size: 14pt;
        }

        #login_btn {
            border: medium none;
            display: block;
            border-radius:10px;
            -moz-border-radius:10px;
            -ms-border-radius:10px;
            -o-border-radius:10px;
            -webkit-border-radius:10px;
            width: 100%;
            height: 60px;
            margin: 80px auto;
            background-color: #f95e64;
            font-family: 微软雅黑;
            font-size: 14pt;
            color: white;
            cursor: pointer;
        }

        img{
            display: block;
            -webkit-box-shadow: 0px 5px 12px #e1e1e1;
            -moz-box-shadow: 0px 5px 12px #e1e1e1;
            box-shadow: 0px 5px 12px #e1e1e1;
        }

        label{
            color: #dcdcdc;
        }


    </style>

</head>

<?php
error_reporting(E_ALL^E_NOTICE);
if(empty($_COOKIE['userPhone'])){
    $phone = "";
}else{
    $phone = $_COOKIE['userPhone'];
}

if(empty($_COOKIE['password'])){
    $password = "";
}else{
    $password = $_COOKIE['password'];
}


?>

<body onload="windowHeight()">
    <div id="body">
        <div style="margin-bottom: 90px;">
            <img src="<?php echo base_url() ?>/images/wx_login_head.png" alt="" width="100%"/>

        </div>
        <div id="content">
            <form id="form1">
                <div class="input">
                    <div class="input_content">
                        <span>账号：</span>
                        <input style="outline: none;"  type="tel" maxlength="11" name="userPhone" value="<?php echo $phone;  ?>"  placeholder = "请输入手机号"/><br/>
                    </div>

                </div>
                <div class="input">
                    <div class="input_content">
                        <span>密码：</span>
                        <input style="outline: none;" type="password" maxlength="20" name = "userPsw" value="<?php echo $password;?>" placeholder = "" /><br/>
                    </div>

                </div>
            </form>
                <button id="login_btn" onclick="login()">登  录</button>
        </div>



    </div>
</body>
</html>