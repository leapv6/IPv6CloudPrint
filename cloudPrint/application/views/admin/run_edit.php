<!DOCTYPE html>
<html>
<head>
<title>后台管理系统</title>
    <?php  error_reporting(E_ALL^E_NOTICE^E_WARNING);?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/info.css" rel="stylesheet" type="text/css">

    <link href="<?php echo base_url(); ?>style/jquery-labelauty.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        ul { list-style-type: none;}
        li { display: inline-block;}
        li { margin: 10px 0;}
        input.labelauty + label { font: 12px "Microsoft Yahei";}
    </style>
    <!-- radio美化 -->
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-labelauty.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/authority/jquery.fancybox-1.3.4.css" media="screen"></link>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/artDialog/artDialog.js?skin=default"></script>
<script type="text/javascript">

    $(function(){
        $(':input').labelauty();
    });

	$(document).ready(function() {
		/*
		 * 提交
		 */
        $("#submitButton").click(function() {
            $.ajax({
                url : "<?php echo base_url(); ?>admin/business/update_run",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("修改成功！");
                        var index = layer.load();
                        layer.close(index);
                    }else{
                        layer.msg("修改失败，请检查所填数据格式");
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
<form id="form1" name="submitForm" >
	<div id="container">
		<div id="nav_links">
			当前位置：编辑商户&nbsp;>&nbsp;<span style="color: #1A5CC6;">商户信息编辑</span>
			<div id="page_close">
				<a href="javascript:parent.$.fancybox.close();">
					<img src="<?php echo base_url(); ?>images/common/page_close.png" width="20" height="20" style="vertical-align: text-top;"/>
				</a>
			</div>
		</div>

        <div id="form_box">
            <input type="hidden" name="businessId" value="<?php echo $businessId;?>"/>

            <div class="form_content">
                <label>商户用户名:</label>
                <input type="text" readonly="true" id="businessNick" name="businessNick" value="<?php echo $businessNick;?>"  >
            </div>

            <div class="form_content">
                <label style="padding-top: 15px;">营业状态：</label>
                <ul>
                    <li><input type="radio" value="1"  <?php if($runStatus == "1") echo "checked";?> name="runStatus" data-labelauty="正常"></li>
                    <li><input type="radio" value="0"  <?php if($runStatus == "0") echo "checked";?> name="runStatus" data-labelauty="休息"></li>
                </ul>
            </div>
            <div class="form_content">
                <label style="padding-top: 15px;">繁忙程度：</label>
                <ul>
                    <li><input type="radio" value="0"  <?php if($busyStatus == "0") echo "checked";?> name="busyStatus" data-labelauty="正常"></li>
                    <li><input type="radio" value="1"  <?php if($busyStatus == "1") echo "checked";?> name="busyStatus" data-labelauty="繁忙"></li>
                </ul>
            </div>

            <div class="form_content">
                <label style="padding-top: 25px;">性质：</label>
                <ul>
                    <li><input type="radio" value="0"  <?php if($property == 0) echo "checked";?> name="property" data-labelauty="工商经营"></li>
                    <li><input type="radio" value="1"  <?php if($property == 1) echo "checked";?> name="property" data-labelauty="自然人经营"></li>
                </ul>
            </div>

            <p style="padding-left: 20px;height: 140px; width: 340px;margin-top: 30px;" >
                <button  id="submitButton"  />保  存
                <button  id="cancelButton"  />关  闭
            </p>
        </div>

	</div>
</form>

</body>
</html>