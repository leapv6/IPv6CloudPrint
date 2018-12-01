<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/user_login.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/common.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/form_css.css"  rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<title>用户登录</title>

<script type="text/javascript">
    /*回车事件*/
    function EnterPress(e){ //传入 event//
        var e = e || window.event;
        if(e.keyCode == 13){
            $.ajax({
                url : "<?php echo site_url('welcome/user_check') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("登录成功！");
                        parent.location.href = "<?php echo site_url('user/user_index/index') ?>";
                    }else{
                        var index = layer.load();
                        layer.msg("手机号或密码错误，请重试！",{time:1000});
                        layer.close(index);
                    }

                },
                beforeSend:function(){
                    layer.load('加载中…');
                }
            });
        }
    }

    function jump(){
        parent.location.href = "<?php echo site_url('welcome/forget_pass') ?>";
    }
	
    $(document).ready(function(){

        $("#loginButton").click(function(){
            $.ajax({
                url : "<?php echo site_url('welcome/user_check') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("登录成功！");
                        parent.location.href = "<?php echo site_url('user/user_index/index') ?>";
                    }else{
                    	var index = layer.load();
                        layer.msg("手机号或密码错误，请重试！",{time:1000});
                        layer.close(index);
                    }

                },
                beforeSend:function(){
                    layer.load('加载中…');
                }
            });
        });

    });




</script>

    <style type="text/css">
        #forget{
            margin-top: 5px;
            float: right;
            font-size: 13px;
            color: blue;
            cursor: pointer;

        }

        input.middle{
            vertical-align: middle;
        }

        input.roundedRectangle:hover{
            border: solid 1px #FF8C00;
        }

        label{
            display: inline-block;
            vertical-align: middle;
            font-size: 13px;
            color: blue;
            margin-top: 5px;

        }

        #rem{
            margin-top: 5px;
            width: 15px;
        }
        a:hover {text-decoration:underline ;}
    </style>
</head>

<body>
<form id="form1" name="userLogin">

    <div id="content">
        <div id="top">
			<div id="top_content">
				<div class="head"></div>
				<div class="text">用户登录</div>
			</div>
        </div>

        <?php
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


        <div id="middle">
			<input class="roundedRectangle" name="userPhone" placeholder="请输入手机号" value="<?php echo $phone; ?>" type="num" maxlength="11" id="userPhone" />
			<input class="roundedRectangle" name="userPsw"   type="password" maxlength="18" value="<?php echo $password; ?>" id="userPsw" onkeypress="EnterPress(event)" onkeydown="EnterPress(event)" />

            <input name="remember" id="rem" checked="checked" type="checkbox" class="middle" />
            <label>记住我</label>

            <a onclick="jump();" id="forget">忘记密码？</a>
			<input type="button" id="loginButton" value="登 录" />
        </div>
    </div>
</form>

</body>
</html>
