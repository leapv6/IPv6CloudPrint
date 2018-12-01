<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/user_active.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<title>用户账号激活</title>

<script type="text/javascript">
	function showtime(t){
        $.ajax({
            url : "<?php echo site_url('user/user_index/send_email_again') ?>",
            data : {},
            type : "POST",
            dataType : "json",
            async:false,
            success : function(data){
                if(data.status == 1){
                    layer.msg("邮件发送成功！");
                    var index = layer.load();
                    layer.close(index);
                }else{
                    layer.msg("邮件发送失败！");
                    var index = layer.load();
                    layer.close(index);
                }

            },
            beforeSend:function(){
                layer.load('加载中…');
            }
        });
    document.myform.mail.disabled=true; 
    for(i=1;i<=t;i++) { 
        window.setTimeout("update_p(" + i + ","+t+")", i * 1000); 
    	} 
 
	} 
 
	function update_p(num,t) { 
	    if(num == t) { 
	        document.myform.mail.value =" 重新发送 "; 
	        document.myform.mail.disabled=false; 
	    } 
	    else { 
	        printnr = t-num; 
	        document.myform.mail.value = " (" + printnr +")秒后重新发送"; 
	    } 
	} 
</script>

</head>

<body>
	<?php include(VIEWPATH."/white_top.php") ?>

	<hr>	
	<div id="main">
		<h1 class="top_title">用户账号激活</h1>
		
		<div class="content">
			<h1>恭喜您，您的账号注册成功！</h1>
			<br>
			<h2>您的账号验证邮件已经发到邮箱中，请登录邮箱点击验证链接完成验证，链接有效时长10分钟。如果收件箱未收到邮件，请查看垃圾箱邮件是否存在。</h2>
			<br>
			<h2>点击下面按钮可重发链接:</h2>

			<br><br>
			<form name="myform" >
				<input id="mail_btn" class="button" type="button" onclick="showtime(30)" name="mail" value="重新获取验证邮件">
			</form>
			
		</div>


	</div>

</body>

