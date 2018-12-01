<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>

<script type="text/javascript">

    function checkName(){
        var str = $('#name').val();
        if(str){
            return true;
        }else{
            layer.tips('请输入姓名！','#name',{
                tips:3,
                time:1500
            });
            return false;
        }
    }

    function trim(str){
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }

    var a = '<?php echo $email; ?>';
    function checkEmail(){
        var str = $('#email').val();
        if (str.match(/[A-Za-z0-9_-]+[@](\S*)(net|com|cn|org|cc|tv|[0-9]{1,3})(\S*)/g) == null) {
            layer.tips('请输入正确的邮箱！','#email',{
                tips:3,
                time:1500
            });
            return false;
        }
        else if(str != a){
            var res = false;
            $.ajax({
                url : "<?php echo site_url('welcome/check_email_unique') ?>",
                data : {
                    email:trim($("#email").val())
                },
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        res = true;
                        var index = layer.load();
                        layer.close(index);
                    }else{
                        layer.tips('邮箱已经存在啦，请换一个！','#email',{
                            tips:3,
                            time:1500
                        });
                        var index = layer.load();
                        layer.close(index);
                        res = false;
                    }

                },
                beforeSend:function(){
                    layer.load('验证中…');
                }
            });
            return res;

        }else{
            return true;
        }
    }

    function checkNick(){
        var str = $('#userNick').val();
        if (str.match( /^[\u4E00-\u9FA5a-zA-Z0-9_]{2,20}$/)) {
            return true;
        }
        else {
            layer.tips('用户名为2-20位汉字数字下划线组合！','#userNick',{
                tips:3,
                time:1500
            });
            return false;
        }
    }

    function checkSchool(){
        var str = $('#schoolName').val();
        if(str != ''){
            if (str.match(/^[\u4e00-\u9fa5]+$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的学校名称！','#schoolName',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }else{
            return true;
        }

    }


    $(document).ready(function(){
        $("#submitButton").click(function(){
            if(checkName() && checkEmail() && checkNick() && checkSchool()){
                $.ajax({
                    url : "<?php echo site_url('user/user_index/update_user_info') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("个人信息修改成功！",{time:1000});
                            var index = layer.load();
                            layer.close(index);
                        }else{
                            var index = layer.load();
                            layer.msg("个人信息填写错误，请重试！",{time:1000});
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
        margin: 0 auto;
    }

    #submitButton{
        margin-right: 80px;
        margin-top: 20px;
    }
</style>

<body>
<br>

<div id="form_box">
    <form class="form1" name="userInfo" id="form1" >

        <div class="form_content">
            <label for="name">用户名：</label>
            <input type="text"  name="userNick" maxlength="20" value="<?php echo $userNick; ?>" id="userNick" onblur="checkNick();"  />
        </div>

        <div class="form_content">
            <label for="email">姓名：</label>&nbsp;&nbsp;&nbsp;
            <input type="text"  name="userName" maxlength="10" value="<?php echo $userName; ?>" id="name" onblur="checkName();" />
        </div>

        <div class="form_content">
            <label for="email">手机号：</label>&nbsp;&nbsp;&nbsp;
            <input type="text"  name="userPhone" maxlength="11" value="<?php echo $userPhone; ?>" id="name" onblur="checkPhone();" disabled/>
        </div>

        <div class="form_content">
            <label for="email">邮箱：</label>&nbsp;&nbsp;&nbsp;
            <input type="text"  name="email" maxlength="30" value="<?php echo $email; ?>" id="email" onblur="checkEmail();" />
        </div>

        <div class="form_content">
            <label for="email">学校名称：</label>&nbsp;&nbsp;
            <input type="text"  name="schoolName" maxlength="20" value="<?php echo $schoolName; ?>" id="schoolName" onblur="checkSchool();" />
        </div>

        </p>

        <p class="submit">
            <input id="submitButton" type="button" class="submitButton" value="保   存" />
        </p>

    </form>
</div>

</body>
