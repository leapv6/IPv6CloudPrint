<!DOCTYPE html>
<html>
<head>
<title>商家后台管理系统</title>
    <?php  error_reporting(E_ALL^E_NOTICE^E_WARNING);
            $this->load->helper('url');
    ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/jquery-labelauty.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/info.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/authority/jquery.fancybox-1.3.4.css" media="screen"></link>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/artDialog/artDialog.js?skin=default"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-labelauty.js"></script>
<script type="text/javascript">

	$(document).ready(function() {
		/*
		 * 提交
		 */
		$("#submitbutton").click(function() {
			/*if(validateForm()){
				checkFyFhSubmit();
			}*/

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
    <script type="text/javascript">
        function download(id){
            window.open ('<?php echo site_url("business/shop_manage/download")?>'+'/'+id);
        }

        $(function(){
            $(':input').labelauty();
        });
    </script>

    <style type="text/css">
        #form_box{
            margin: 0 auto;
        }

        #cancelButton{
            margin-right: 80px;
            margin-top: 20px;
        }

        ul { list-style-type: none;}
        li { display: inline-block;}
        input.labelauty + label { font: 12px "Microsoft Yahei";}

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
	<div id="container">

        <div id="form_box">
            <div class="form_content">
                <label >文档名：</label>
                <input type="text" id="docName" name="docName" value="<?php echo $docName;?>" disabled />
            </div>

            <div class="form_content">
                <label >顾客用户名：</label>
                <input type="text" id="docBelongNick" name="docBelongNick" value="<?php echo $userNick;?>" disabled/>
            </div>

            <div class="form_content">
                <label >顾客电话：</label>
                <input type="text" id="docBelongPhone" name="docBelongPhone" value="<?php echo $userPhone;?>" disabled/>
            </div>

            <div class="form_content">
                <label >文档大小：</label>
                <input type="text" id="docSize" name="docSize" value="<?php echo $docSize;?>kb" disabled />
            </div>

            <div class="form_content">
                <label >打印份数：</label>
                <input type="text" id="printCopise" name="printCopis" value="<?php echo $printCopis;?>" disabled />
            </div>

            <div class="form_content">
                <label >单双打印：</label>
                <input type="text" name="colorPrint" value="<?php echo $doublePrint? "双面打印":"单面打印" ;?>" disabled />
            </div>

            <div class="form_content">
                <label >彩色打印：</label>
                <input type="text" name="colorPrint" value="<?php echo $colorPrint? "彩色打印":"黑白打印" ;?>" disabled />
            </div>

            <div class="form_content">
                <label >配送方式：</label>
                <input type="text" name="colorPrint" value="<?php echo $sendType? "商家配送":"上门自取" ;?>" disabled />
            </div>

            <div class="form_content">
                <label >备注：</label>
                <textarea style="height: 100px;width:160px" maxlength="120"  name="message" disabled><?php echo $message; ?></textarea>
            </div>

            <p style="padding-left: 20px;height: 140px; width: 340px;" >
                <button  onclick="download(<?php echo $docId;?>)"  />下  载
                <button  id="cancelButton"  />关  闭
            </p>

        </div>
	</div>


</body>
</html>