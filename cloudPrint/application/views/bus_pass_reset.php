<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/user_active.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <title>重置密码</title>

    <script type="text/javascript">
        function change_pass(){
            if(check_pass() && check_pass_again()){
                $.ajax({
                    url : "<?php echo site_url('welcome/update_bus_pass') ?>",
                    data : $("#myform").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("密码修改成功！");
                            var index = layer.load();
                            layer.close(index);
                        }else if(data.status == 0){
                            layer.msg("密码修改失败，请检查所填信息！");
                            var index = layer.load();
                            layer.close(index);
                        }

                    },
                    beforeSend:function(){
                        layer.load('加载中…');
                    }
                });
            }else{
                layer.msg("请检查所填信息格式！",{time:1500});
            }
        }



        function check_pass(){
            var str = $('#new_pass').val();
            if (str.match(/^[\w]{6,16}$/)) {
                return true;
            }
            else {
                layer.tips('密码为6-16位字符、数字和下划线！','#new_pass',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function check_pass_again(){
            var str = $('#new_pass').val();
            var str2 = $('#new_pass_again').val();
            if (str == str2) {
                return true;
            }
            else {
                layer.tips('两次输入密码不一致！','#new_pass_again',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }


    </script>

    <style type="text/css">
        .input {
            border: 1px solid #999;
            height: 24px;
            line-height: 24px;
            padding: 2px;
            width: 240px;
            float: right;
            font-size:14px;
        }

        p{
            height: 35px;
        }

        #box{
            width: 350px;
        }

        label{
            display: inline-block;
            padding-top: 5px;
            font-family: 微软雅黑;
            font-size: 15px;
        }
    </style>

</head>

<body>
<?php include(VIEWPATH."/white_top.php") ?>

<hr>
<div id="main">
    <h1 class="top_title">重置密码</h1>

    <div class="content">
        <h1><?php echo $msg; ?></h1>
        <br>

        <br><br>
        <form id="myform" >
            <div id="box">

                <p>
                    <label>新密码：</label>
                    <input id="new_pass" class="input" type="password" maxlength="20" name="new_pass" onblur="check_pass()">
                </p>
                <p>
                    <label>确认新密码：</label>
                    <input id="new_pass_again" class="input" type="password" maxlength="20" name="new_pass_again" onblur="check_pass_again()"></p>
                <br>
            </div>
            <input style="cursor: pointer;" id="change_btn" class="button" type="button" onclick="change_pass()" name="change_btn" value="修 改">
        </form>

    </div>


</div>

</body>

