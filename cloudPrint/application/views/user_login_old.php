<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/validform.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<link href="<?php echo base_url(); ?>style/form_css.css"  rel="stylesheet" type="text/css"/>



<div class="boxy-content">
    <form class="form1" name="userLogin" id="form1"  method="post">

        <p class="name">
            <label for="name">手机号：</label>
            <input type="text" placeholder="请输入手机号" name="userPhone"  id="userPhone" datatype="m" errormsg="请输入正确手机号！" />
        </p>

        <p class="email">
            <label for="email">密码：</label>&nbsp;&nbsp;&nbsp;
            <input type="password" name="userPsw" id="psw" datatype="*" errormsg="请输入密码！"/>
        </p>


        <p class="submit">
            <input type="submit" id="login" value="登录" />
        </p>

    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){

        $("#login").click(function(){
            $.ajax({
                url : "<?php echo site_url('welcome/user_check') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("登录成功！");
                        parent.location.href = "<?php echo site_url('user/user_index/my_file') ?>";
                    }else{
                        layer.msg("手机号或密码错误，请重试！");
                    }

                },
                beforeSend:function(){
                    layer.load('加载中…');
                }
            });
        });

    });


</script>
