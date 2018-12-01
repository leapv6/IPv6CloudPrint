<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/forget_pass.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <title>忘记密码</title>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#sub_btn').click(function(){
                var email = $("#email").val();
                var preg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/; //匹配Email
                if(email=='' || !preg.test(email)){
                    layer.msg("请填写正确的邮箱！",{time:1500,shift:6});
                }else{
                    $.ajax({
                        url : "<?php echo site_url('welcome/check_business_email_exist') ?>",
                        data : {email:email},
                        type : "POST",
                        dataType : "json",
                        async:false,
                        success : function(data){
                            if(data.status == 1){
                                var index = layer.load();
                                layer.close(index);
                                layer.msg("邮件已发送，请登录您的邮箱查看！如果收件箱未收到邮件，请查看垃圾箱邮件是否存在。");

                                $('#sub_btn').attr('disabled',true);
                                t = 30;
                                for(i=1;i<=t;i++) {
                                    window.setTimeout("update_p(" + i + ","+t+")", i * 1000);
                                }

                            }else if(data.status == 'send_fail'){
                                var index = layer.load();
                                layer.close(index);
                                layer.msg("邮件发送失败，请重试！");
                            }else{
                                var index = layer.load();
                                layer.msg("该邮箱不存在！",{time:1500});
                                layer.close(index);
                            }

                        },
                        beforeSend:function(){
                            layer.load('加载中…');
                        }
                    });
                }
            });
        });

        function update_p(num,t) {
            if(num == t) {
                $('#sub_btn').val("重新发送");
                $('#sub_btn').attr('disabled',false);
            }
            else {
                printnr = t-num;
                $('#sub_btn').val(" (" + printnr +")秒后重新发送");;
            }
        }

    </script>

</head>

<body>
<?php include(VIEWPATH."/white_top.php") ?>

<hr>
<div id="main">
    <h1 class="top_title">用户账号忘记密码</h1>

    <div class="content">
        <p>用户可以通过绑定邮箱找回密码</p>
        <p><b>请输入您注册时绑定的邮箱，找回密码：</b></p>
        <input id="email" class="input" type="text" name="email">
        <p style="margin-top:20px;">
            <input id="sub_btn" class="btn" type="button" value="发送邮件" style="cursor: pointer;">
        </p>
    </div>


</div>

</body>

