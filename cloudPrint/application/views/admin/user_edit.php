<!DOCTYPE html>
<html>
<head>
<title>后台管理系统</title>
    <?php  error_reporting(E_ALL^E_NOTICE^E_WARNING);?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery/jquery-1.7.1.js"></script>
<link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>style/info.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery/jquery-1.4.4.min.js"></script>
<script src="<?php echo base_url(); ?>scripts/My97DatePicker/WdatePicker.js" type="text/javascript" defer="defer"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/artDialog/artDialog.js?skin=default"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/*
		 * 提交
		 */
		$("#submitbutton").click(function() {
            $.ajax({
                url : "<?php echo base_url(); ?>admin/business/update_user",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("修改成功！");
                        parent.location.href = "<?php echo site_url('admin/business/user_info') ?>";
                    }else{
                        layer.msg("修改失败，请检查所填数据格式");
                    }

                }

            });
		});
		
		/*
		 * 取消
		 */
		$("#cancelbutton").click(function() {
			/**  关闭弹出iframe  **/
			window.parent.$.fancybox.close();
		});
		
		var result = 'null';
		if(result =='success'){
			/**  关闭弹出iframe  **/
			window.parent.$.fancybox.close();
		}
	});

</script>
    <style type="text/css">
        button{
            margin-right: 10px;
            width: 120px;
            height: 40px;
            padding: 9px 20px;
            background: #0078ff;
            border: 0;
            font-size: 16px;
            color: white;
            font-weight: bold;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            cursor: pointer;
        }

        #form_box{
            margin: 0 auto;
        }

        #cancelButton{
            margin-right: 80px;
            margin-top: 20px;
        }
    </style>

</head>
<body>
<form id="form1" name="submitForm" >
	<div id="container">
		<div id="nav_links">
			当前位置：编辑用户&nbsp;>&nbsp;<span style="color: #1A5CC6;">用户信息编辑</span>
			<div id="page_close">
				<a href="javascript:parent.$.fancybox.close();">
					<img src="<?php echo base_url(); ?>images/common/page_close.png" width="20" height="20" style="vertical-align: text-top;"/>
				</a>
			</div>
		</div>
        <div id="form_box">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form_content">
                <label>用户名:</label>
                <input type="text" id="userNick" name="userNick" value="<?php echo $userNick;?>" >
            </div>

            <div class="form_content">
                <label>密码:</label>
                <input type="text" id="userPsw" name="userPsw" value="<?php echo $userPsw;?>" >
            </div>

            <div class="form_content">
                <label>手机号:</label>
                <input type="text" id="userPhone" name="userPhone" value="<?php echo $userPhone;?>" >
            </div>

            <div class="form_content">
                <label>姓名:</label>
                <input type="text" id="userName" name="userName" value="<?php echo $userName;?>" >
            </div>

            <div class="form_content">
                <label>邮箱:</label>
                <input type="text" id="email" name="email" value="<?php echo $email;?>" >
            </div>

            <div class="form_content">
                <label>学校名称:</label>
                <input type="text" id="schoolName" name="schoolName" value="<?php echo $schoolName;?>" >
            </div>

            <p style="padding-left: 20px;height: 140px; width: 340px;margin-top: 30px;" >
                <button  id="submitbutton"  />保  存
                <button  id="cancelbutton"  />关  闭
            </p>
        </div>

	</div>
</form>

</body>
</html>