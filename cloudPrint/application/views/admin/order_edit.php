<!DOCTYPE html>
<html>
<head>
<title>后台管理系统</title>
    <?php  error_reporting(E_ALL^E_NOTICE^E_WARNING);?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/info.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/jquery-labelauty.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        ul { list-style-type: none;}
        li { display: inline-block;}
        input.labelauty + label { font: 12px "Microsoft Yahei";}
    </style>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <!-- radio美化 -->
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-labelauty.js"></script>
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
                url : "<?php echo site_url('admin/business/update_order') ?>",
                data : $("#form1").serialize(),
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    if(data.status == 1){
                        layer.msg("保存成功",{time:1500});
                        var index = layer.load();
                        layer.close(index);
                    }else{
                        layer.msg("保存失败,请重试！",{time:1000,shift:6});
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
        #form_box{
            width: 325px;
            margin-left: 55px;
        }
        ul{
            margin-right: 22px;

        }
        .center1{
            margin-top: 20px;;
        }
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
    </style>
</head>
<body>
<form id="form1" name="submitForm" >
	<div id="container">
		<div id="nav_links">
			当前位置：编辑订单&nbsp;>&nbsp;<span style="color: #1A5CC6;">订单信息编辑</span>
			<div id="page_close">
				<a href="javascript:parent.$.fancybox.close();">
					<img src="<?php echo base_url(); ?>images/common/page_close.png" width="20" height="20" style="vertical-align: text-top;"/>
				</a>
			</div>
		</div>

        <div id="form_box">
            <div class="form_content">
                <label>订单编号:</label>
                <input type="text" id="orderId" name="orderId" value="<?php echo $orderId;?>" readonly="true">
            </div>

            <div class="form_content">
                <label>顾客姓名:</label>
                <input type="text" id="userName" name="userName" value="<?php echo $userName;?> "disabled>
            </div>

            <div class="form_radio">
                <label class="center1">单双打印：</label>
                <ul>
                    <li><input type="radio" value="0" <?php if($doublePrint== 0) echo "checked";?> name="doublePrint" data-labelauty="单面打印"></li>
                    <li><input type="radio" value="1" <?php if($doublePrint== 1) echo "checked";?>  name="doublePrint" data-labelauty="双面打印"></li>
                </ul>
            </div>

            <div class="form_radio">
                <label class="center1">是否彩印：</label>
                <ul>
                    <li><input type="radio" value="0" <?php if($colorPrint== 0) echo "checked";?>  name="colorPrint" data-labelauty="黑白打印"></li>
                    <li><input type="radio" value="1" <?php if($colorPrint== 1) echo "checked";?> name="colorPrint" data-labelauty="彩色打印"></li>
                </ul>
            </div>

            <br>
            <div class="form_content">
                <label>打印份数:</label>
                <input type="text" id="printCopis" name="printCopis" value="<?php echo $printCopis;?>" >
            </div>


            <div class="form_content">
                <label>价  格:</label>
                <input type="text" id="price" name="price" value="<?php echo $price;?>" >
            </div>

            <div class="form_radio">
                <label class="center1">打印状态：</label>
                <ul>
                    <li><input type="radio" value="0" <?php if($printStatus== 0) echo "checked";?> name="printStatus" data-labelauty="未打印"></li>
                    <li><input type="radio" value="1" <?php if($printStatus== 1) echo "checked";?> name="printStatus" data-labelauty="打印完成"></li>
                </ul>
            </div>

            <div class="form_radio">
                <label class="center1">支付状态：</label>
                <ul>
                    <li><input type="radio" value="0" <?php if($payStatus== 0) echo "checked";?>  name="payStatus" data-labelauty="未支付"></li>
                    <li><input type="radio" value="1" <?php if($payStatus== 1) echo "checked";?> name="payStatus" data-labelauty="支付成功"></li>
                </ul>
            </div>

            <div class="form_radio">
                <label class="center1">配送方式：</label>
                <ul>
                    <li><input type="radio" value="0" <?php if($sendType== 0) echo "checked";?>  name="sendType" data-labelauty="上门自取"></li>
                    <li><input type="radio" value="1" <?php if($sendType== 1) echo "checked";?> name="sendType" data-labelauty="商家配送"></li>
                </ul>
            </div>
            <br>
            <p style="padding-left: 20px;height: 50px; width: 340px;margin-top: 40px;" >
                <button  id="submitButton"  />保  存
                <button  id="cancelButton"  />关  闭
            </p>
        </div>

	</div>
</form>

</body>
</html>