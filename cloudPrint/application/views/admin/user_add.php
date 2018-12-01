<!DOCTYPE html>
<html>
<head>
<title>后台管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/info.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/authority/jquery.fancybox-1.3.4.css" media="screen"></link>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/artDialog/artDialog.js?skin=default"></script>
<script type="text/javascript">


	$(document).ready(function() {
		/*
		 * 提交
		 */
		$("#submitButton").click(function() {
            $.ajax({
                url : "<?php echo site_url('admin/business/add_user') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("新增成功！");
                        parent.location.href = 'user_info';
                    }else{
                        layer.msg("删除失败,请重试！",{time:1000,shift:6});
                        var index = layer.load();
                        layer.close(index);
                    }

                }

            });
		});
		
		/*
		 * 取消
		 */
		$("#cancelButton").click(function() {
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
<form id="form1" >

	<div id="container">
		<div id="nav_links">
			当前位置：新增用户&nbsp;>&nbsp;<span style="color: #1A5CC6;">用户信息编辑</span>
			<div id="page_close">
				<a href="javascript:parent.$.fancybox.close();">
					<img src="<?php echo base_url(); ?>images/common/page_close.png" width="20" height="20" style="vertical-align: text-top;"/>
				</a>
			</div>
		</div>

        <div id="form_box">
            <div class="form_content">
                <label>用户名:</label>
                <input type="text" id="userNick" name="userNick" >
            </div>

            <div class="form_content">
                <label>密码:</label>
                <input type="text" id="userPsw" name="userPsw" >
            </div>

            <div class="form_content">
                <label>手机号:</label>
                <input type="text" id="userPhone" name="userPhone" >
            </div>

            <div class="form_content">
                <label>姓名:</label>
                <input type="text" id="userName" name="userName" >
            </div>

            <div class="form_content">
                <label>邮箱:</label>
                <input type="text" id="email" name="email" >
            </div>

            <div class="form_content">
                <label>学校名称:</label>
                <input type="text" id="schoolName" name="schoolName" >
            </div>

            <p style="padding-left: 20px;height: 140px; width: 340px;" >
                <button  id="submitButton"   />提  交
                <button  id="cancelButton"  />关  闭
            </p>
        </div>


	</div>
</form>

</body>
</html>