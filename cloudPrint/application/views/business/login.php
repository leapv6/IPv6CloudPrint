<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/bg_login.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<title>商家登录</title>

<script type="text/javascript">

	/*回车事件*/
    function EnterPress(e){ //传入 event 
        var e = e || window.event; 
        if(e.keyCode == 13){ 
            $.ajax({
                url : "<?php echo site_url('business/index/check_login') ?>",
                data : $("#submitForm").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("登录成功！");
                        parent.location.href = "<?php echo site_url('business/index/login') ?>";
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

    function show(){
        //alert(document.documentElement.clientHeight);
        //document.getElementById("back").style.width = document.documentElement.clientWidth+"px";
        //document.getElementById("back").style.height = document.documentElement.clientHeight+"px";
    }

    function jump(){
        location.href = "<?php echo site_url('welcome/business_forget_pass') ?>";
    }

    $(document).ready(function(){
        
        $("#loginButton").click(function(){
            $.ajax({
                url : "<?php echo site_url('business/index/check_login') ?>",
                data : $("#submitForm").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("登录成功！");
                        parent.location.href = "<?php echo site_url('business/index/login') ?>";
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
            margin: 0px;
            float: right;
            font-size: 13px;
            color: blue;
            cursor: pointer;

        }
        a:hover {text-decoration:underline ;}
    </style>
</head>

<body onload="show()">
    <div id="back">
        <div id="middle">
        <form id="submitForm" >
            <div id="head">          </div>
            <!--<div id="title">  </div>-->
            <div id="login_bg">
                <div id="header">
                    <div id="left"></div>
                    <div id="right"></div>
                </div>
                <div id="content">
                    <div id="login_head">
                        <div id="head_title">
                            <div id="head_pic"></div>
                            <div><span class="text1">商家登录</span></div>
                        </div>
                        <div class="text">
                            <div class="shuru"><span class="tip">手  机：</span><input class="put" type="text" name="phone" maxlength="16" /></div>
                            <div class="shuru"><span class="tip">密  码：</span><input class="put" type="password" name="password" maxlength="16" onkeypress="EnterPress(event)" onkeydown="EnterPress()"/></div>
                            <a onclick="jump();" id="forget">忘记密码？</a>
                            <input type="button" style="margin-top: 5px;" id="loginButton" value="登 录" />
                        </div>

                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</body>
</html>
