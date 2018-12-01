<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>打印云-校园云打印|校园自助打印|在线打印|云打印|校园网络打印|网络打印|寝室打印|大学校园打印|中国在线打印第一品牌</title>
    <link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url();?>scripts/jquery-1.8.2.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>scripts/layer.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            $("#submitButton").click(function(){
                $.ajax({
                    url : "<?php echo site_url('user/user_index/make_comment') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("评价成功！");
                            parent.location.href = "<?php echo site_url('user/user_index/my_order') ?>";
                        }else{
                            layer.msg("已经评论过啦~");
                        }

                    }

                });
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
</head>
<br>
<div id="form_box">
    <form id="form1">
        <input type="hidden" name="orderId"  value="<?php echo $orderId;?>"  />
        <div class="form_content">
            <label>店铺名称:</label>
            <span style="display:inline-block;margin-left:50px;padding-top: 5px;"><?php echo $shopName; ?></span>
        </div>

        <div class="form_content">
            <label>评价信息:</label>
            <textarea style="height: 100px;width:160px"  name="comment"  maxlength="120" ><?php echo $comment; ?></textarea>
        </div>
        <br>

        <p class="submit">
            <input id="submitButton" type="button" value="提交评价"/>
        </p>

    </form>
</div>




</body>
</html>