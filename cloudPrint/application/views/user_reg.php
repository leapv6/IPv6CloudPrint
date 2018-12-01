<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/user_reg.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/common.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/form_css.css"  rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<title>用户注册</title>

<script type="text/javascript">
//去掉字符串头尾空格   
function trim(str){
    return str.replace(/(^\s*)|(\s*$)/g, "");
}
function checkMobilePhone(){
    var str = $('#userPhone').val();
    if (str.match(/^1\d{10}$/) == null) {
        layer.tips('请输入正确的手机号！','#userPhone',{
                tips:3,
                time:1500
            });
        return false;
    }
    else {
        var res = false;
        $.ajax({
                url : "<?php echo site_url('welcome/check_unique') ?>",
                data : {
                            phone:trim($("#userPhone").val())
                        },
                type : "POST",
                dataType : "json",
                async: false,
                success : function(data){
                    if(data.status == 1){
                        var index = layer.load();
                        layer.close(index);
                        res = true;
                    }else{
                        layer.tips('手机号已存在啦，请换一个！','#userPhone',{
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
    }
}

function checkNick(){
        if($('#userNick').val()){
            return true;
        }else{
            layer.tips('请输入用户名！','#userNick',{
                tips:3,
                time:1500
            });
            return false;
        }
    }

function checkChinese(){
    var str = $('#userName').val();
    if (escape(str).indexOf("%u") != -1) {
        return true;
    }
    else {
        layer.tips('请输入正确的姓名！','#userName',{
                tips:3,
                time:1500
            });
        return false;
    }
}

function checkSchoolName(){
    var str = $('#schoolName').val();
    if(str != '' && str != null){
        if (escape(str).indexOf("%u") != -1) {
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

function checkEmail(){
    var str = $('#email').val();
    if (str.match(/[A-Za-z0-9_-]+[@](\S*)(net|com|cn|org|cc|tv|[0-9]{1,3})(\S*)/g) == null) {
        layer.tips('请输入正确的邮箱！','#email',{
                tips:3,
                time:1500
            });
        return false;
    }
    else {
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

    }
}

function checkPass(){
    var str = $('#pass').val();
    if (str.match(/^[\w]{6,16}$/)) {
        return true;
    }
    else {
        layer.tips('密码为6-16位字符、数字和下划线！','#pass',{
                tips:3,
                time:1500
            });
        return false;
    }
}
    
function checkAll(){
            if(checkMobilePhone() && checkNick() && checkChinese() && checkPass() && checkEmail() && checkSchoolName() ){
                $.ajax({
                    url : "<?php echo site_url('welcome/reg_user') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("注册成功！");
                            var index = layer.load();
                            layer.close(index);
                            parent.location.href = "<?php echo site_url('/') ?>"
                        }else if(data.status == 0){
                            layer.msg("注册失败，请检查所填信息！");
                            var index = layer.load();
                            layer.close(index);
                        }else if(data.status == 2){
                            layer.msg("邮件发送失败");
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

function reload(){
        parent.location.reload();
    }
</script>

</head>

<body>
<form class="form1" name="userReg" id="form1" >
	
    <div id="content">
        <div id="top">
			<div id="top_content">
				<div class="head"></div>
				<div class="text">用户注册</div>
			</div>
        </div>

        <div id="middle">
			<table name="reg">
                <tr>
                    <td class="word" >手机号*：</td>
                    <td class="tdinput" ><input class="rR" type="text" id="userPhone" placeholder="请输入手机号" maxlength="11" name="userPhone" onblur="checkMobilePhone()" /></td>
                </tr>
                <tr>
                    <td class="word" >用户名*：</td>
                    <td class="tdinput"><input class="rR" type="text" name="userNick" id="userNick" maxlength="16" onblur="checkNick()" /></td>
                </tr>
                <tr>
                    <td class="word" >姓 名*：</td>
                    <td class="tdinput" ><input class="rR" type="text" name="userName" id="userName" maxlength="20" onblur="checkChinese()" /></td>
                </tr>
                <tr>
                    <td class="word" >密 码*：</td>
                    <td class="tdinput"><input class="rR"  type="password" name="userPsw" id="pass"  maxlength="16" onblur="checkPass()" /></td>
                </tr>
                <tr>
                    <td class="word" >邮 箱*：</td>
                    <td class="tdinput"><input class="rR" name="email" id="email" maxlength="40" onkeyup="checkEmail()" /></td>
                </tr>
                <tr>
                    <td class="word" >学校名称：</td>
                    <td class="tdinput"><input class="rR" name="schoolName" id="schoolName" maxlength="20" onkeyup="checkSchoolName()" /></td>
                </tr>
            </table>
            
            <input type="button" onclick="checkAll();" id="regButton" value="注  册" />
			
        </div>
    </div>


</form>


</body>
</html>
