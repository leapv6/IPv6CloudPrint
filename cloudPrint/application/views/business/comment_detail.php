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
    <link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/authority/jquery.fancybox-1.3.4.css" media="screen"></link>
    <link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>

    <script type="text/javascript">

        $(document).ready(function() {

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
        #form_box{
            margin: 0 auto;
        }

        #cancelbutton{
            margin-right: 80px;
            margin-top: 20px;
        }
    </style>

</head>
<body>
<div id="container">
    <div id="nav_links">
        当前位置：评论详情&nbsp;>&nbsp;<span style="color: #1A5CC6;">详情</span>
        <div id="page_close">
            <a href="javascript:parent.$.fancybox.close();">
                <img src="<?php echo base_url(); ?>images/common/page_close.png" width="20" height="20" style="vertical-align: text-top;"/>
            </a>
        </div>
    </div>

    <div id="form_box">
        <div class="form_content">
            <label>顾客姓名:</label>
            <input type="text" name="shopName" value="<?php echo $userName; ?>" disabled>
        </div>

        <div class="form_content">
            <label>顾客电话:</label>
            <input type="text" name="shopName" value="<?php echo $userPhone; ?>" disabled>
        </div>

        <div class="form_content">
            <label>评价内容:</label>
            <textarea style="height: 100px;width:160px"  name="comment"  maxlength="150" disabled ><?php echo $comment; ?></textarea>
        </div>
        <br>
        <p class="submit">
            <input id="cancelbutton" type="button" value="关    闭"/>
        </p>

    </div>


</div>


</body>
</html>