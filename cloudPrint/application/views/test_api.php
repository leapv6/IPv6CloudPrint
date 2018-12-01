<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/user_active.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <title>测试加解密</title>

    <script type="text/javascript">
        function change_pass(){
                $.ajax({
                    url : "<?php echo site_url('clientapi/get_file_location') ?>",
                    data : {data: {
                        orderId: '30'
                    }
                    },
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        /*if(data.status == 1){
                            layer.msg("成功！");
                            var index = layer.load();
                            layer.close(index);
                        }else if(data.status == 0){
                            layer.msg("密码修改失败，请检查所填信息！");
                            var index = layer.load();
                            layer.close(index);
                        }*/

                    },
                    beforeSend:function(){
                        layer.load('加载中…');
                    }
                });

        }


    </script>

    <style type="text/css">
        .input {
            border: 1px solid #999;
            height: 24px;
            line-height: 24px;
            padding: 2px;
            width: 240px;
            float: right;
            font-size:14px;
        }

        p{
            height: 35px;
        }

        #box{
            width: 350px;
        }

        label{
            display: inline-block;
            padding-top: 5px;
            font-family: 微软雅黑;
            font-size: 15px;
        }
    </style>

</head>

<body>
<?php include(VIEWPATH."/white_top.php") ?>

<hr>
<div id="main">
    <h1 class="top_title">test</h1>

    <div class="content">


            <input style="cursor: pointer;" id="change_btn" class="button" type="button" onclick="change_pass()" name="change_btn" value="测试">


    </div>


</div>

</body>

