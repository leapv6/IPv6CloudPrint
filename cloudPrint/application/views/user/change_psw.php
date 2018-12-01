<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>

<script type="text/javascript">
    function checkPass(){
        var str = $('#new_pass').val();
        if (str.match(/^[\w]{6,12}$/)) {
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

    function passComfirm(){
        if($('#new2').val() == $('#new_pass').val()){
            return true;
        }else{
            layer.tips('两次输入密码不一致，请重新输入！','#new2',{
                tips:3,
                time:1500
            });
            return false;
        }
    }

    function checkVal(){
        if($('#old_pass').val()){
            return true;
        }else{
            layer.tips('请输入原密码！','#old_pass',{
                tips:3,
                time:1500
            });
            return false;
        }
    }


    $(document).ready(function(){
        $("#submitButton").click(function(){
            if(checkVal() && checkPass() && passComfirm()){
                $.ajax({
                    url : "<?php echo site_url('user/user_index/update_psw') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("密码修改成功！",{time:2000});
                            var index = layer.load();
                            layer.close(index);
                        }else{
                            var index = layer.load();
                            layer.msg("密码修改失败，请重试！",{time:1000});
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
</script>

<style type="text/css">
    #form_box{
        margin:0 auto;
    }

    #submitButton{
        margin-right: 80px;
        margin-top: 20px;
    }
</style>
<body>

<br>
<div id="form_box">
    <form class="form1"  id="form1" >

        <div class="form_content">
            <label for="name">原密码：</label>
            <input type="password"  maxlength="16"  name="oldPsw"  id="old_pass" onblur="checkVal();"  />
        </div>

        <div class="form_content">
            <label for="email">新密码：</label>
            <input type="password"  maxlength="16" name="newPsw" id="new_pass" onblur="checkPass();" />
        </div>

        <div class="form_content">
            <label for="email">确认新密码：</label>
            <input type="password"  maxlength="16" name="new2"  id="new2" onblur="passComfirm();"/>
        </div>


        <p class="submit">
            <input type="button" id="submitButton" class="submitButton" value="保   存"/>
        </p>

    </form>
</div>


</body>
