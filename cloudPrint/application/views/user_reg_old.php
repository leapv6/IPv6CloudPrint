<script type="text/javascript" src="../scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="../scripts/validform.js"></script>
<link href="<?php echo base_url();?>style/form_css.css"  rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer/layer.js" id = "layer"></script>
<script type="text/javascript">

    $(document).ready(function(){

        $("#reg").click(function(){
            $.ajax({
                url : "<?php echo site_url('welcome/reg_user') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("注册成功！");
                        setTimeout('reload()',100);
                    }else{
                        layer.msg("注册失败，请重试！");
                    }

                },
                beforeSend:function(){
                    layer.load('加载中…');
                }
            });
        });

    });

    function reload(){
        parent.location.reload();
    }
</script>


<div class="boxy-content">
    <form class="form1" name="userReg" id="form1" action="reg_user" method="post">

        <p class="name">
            <label for="name">手机号：</label>
            <input type="text" placeholder="请输入手机号" name="userPhone" ajaxurl="check_unique" id="userPhone" datatype="m" errormsg="请输入正确手机号！" sucmsg="手机号验证通过！"/>
        </p>

        <p class="email">
            <label for="email">用户名：</label>
            <input type="text" name="userNick" id="email" datatype="s6-18" errormsg="用户名至少6个字符,最多18个字符！"/>
        </p>

        <p class="web">
            <label for="web">姓名：</label>&nbsp;&nbsp;&nbsp;
            <input type="text" name="userName" id="web" datatype="s6-18" errormsg="姓名至少6个字符,最多18个字符！"/>

        </p>

        <p class="web">
            <label for="web">密码：</label>&nbsp;&nbsp;&nbsp;
            <input type="password" name="userPsw" datatype="*6-16" errormsg="密码范围在6~16位之间！" />
        </p>

        <p class="web">
            <label for="web">邮箱：</label>&nbsp;&nbsp;&nbsp;
            <input type="text" name="email" id="web" datatype="e" errormsg="请输入正确的邮箱！" />

        </p>

        <p class="web">
            <label for="web">学校名称：</label>
            <input type="text" name="schoolName" id="web" datatype="s6-18" errormsg="请输入正确的学校名称！"/>
        </p>

        <p class="submit">
            <input type="submit" value="注册" id="reg"/>
        </p>

    </form>
</div>

<script type="text/javascript">
    $(function(){
        //$(".registerform").Validform();  //就这一行代码！;

        $(".form1").Validform({
            tiptype:3
        });
    })
</script>


