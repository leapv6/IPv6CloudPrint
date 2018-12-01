<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>v6网印</title>
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer_m.js" ></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/wx_up.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#submitButton").click(function(){
                if(check_has_file() && check_upload_finish() && fileSelected()){
                    $.ajax({
                        url : "<?php echo site_url('WXapi/make_order') ?>",
                        data : $("#form1").serialize(),
                        type : "POST",
                        dataType : "json",
                        async:false,
                        success : function(data){
                            if(data.status == 1){
                                layer.open({
                                    content: '订单提交成功O(∩_∩)O~，请等待打印机打印！',
                                    style: 'background-color:#67d978; color:#fff; border:none;',
                                    time: 2
                                });
                                setTimeout(function(){
                                    parent.location.href = "<?php echo site_url('WXapi/map_select') ?>";
                                },2500);

                            }else{
                                layer.open({
                                    content: '订单提交失败，请重试！',
                                    style: 'background-color:#67d978; color:#fff; border:none;',
                                    time: 2
                                });
                            }

                        },
                        beforeSend:function(){

                        }
                    });
                }else{
                    layer.open({
                        content: '文档上传后才能打印哦！',
                        style: 'background-color:#67d978; color:#fff; border:none;',
                        time: 2
                    });
                }

            });
        });

        function check_has_file(){
            return $("#image_file").val();
        }

        function start_upload(){
            if(check_has_file() && fileSelected()){
                startUploading();
            }else{
                layer.open({
                    content: '请选择要打印的文档和正确的格式哦！',
                    style: 'background-color:#67d978; color:#fff; border:none;',
                    time: 2
                });
            }
        }
        function windowHeight() {
            var de = document.documentElement;
            var h = self.innerHeight||(de && de.clientHeight)||document.body.clientHeight;
            $("#body").css("height",h-15);
        }

        function select_file(){
            $("#image_file").click();
        }


    </script>
    <style>
        #body{
            background-color: white;
            -webkit-box-shadow: 5px 5px 12px #b9b9b9,-5px -5px 12px #b9b9b9,-5px 5px 12px #b9b9b9,5px -5px 12px #b9b9b9;
            -moz-box-shadow: 5px 5px 12px #b9b9b9,-5px -5px 12px #b9b9b9,-5px 5px 12px #b9b9b9,5px -5px 12px #b9b9b9;
            box-shadow: 5px 5px 12px #b9b9b9,-5px -5px 12px #b9b9b9,-5px 5px 12px #b9b9b9,5px -5px 12px #b9b9b9;
        }

        body{
            padding: 8px 8px;
            background-color: white;

        }

        #content{
            padding: 0 10px;
        }

        #content #details{
            width: 100%;
            height: 150px;
            background-color: #c9c9c9;
        }

        #content span{
            display: block;
            margin-top: 25px;
            text-align: center;
        }
        input{
            border:0px;
            background: inherit;
            width: 70%;
            height: 30px;
            padding: 0;
            font-size: 14pt;
        }

        .btn{
            border: medium none;
            display: block;
            border-radius:10px;
            -moz-border-radius:10px;
            -ms-border-radius:10px;
            -o-border-radius:10px;
            -webkit-border-radius:10px;
            width: 100%;
            height: 40px;
            margin: 15px auto;
            background-color: #f95e64;
            font-family: 微软雅黑;
            font-size: 12pt;
            color: white;
            cursor: pointer;
        }

        img{

            -webkit-box-shadow: 0px 5px 12px #e1e1e1;
            -moz-box-shadow: 0px 5px 12px #e1e1e1;
            box-shadow: 0px 5px 12px #e1e1e1;
        }

        .shopName{
            font-family: 微软雅黑;
            font-size: 12pt;

        }

        <!-- 上传进度条css -->
        #progress_info {
            font-size:10pt;
        }
        #fileinfo,#error,#error2,#abort,#warnsize {
            color:#aaa;
            display:none;
            font-size:10pt;
            font-style:italic;
            margin-top:10px;
        }
        #progress {
            border:1px solid #ccc;
            display:none;
            float:left;
            height:14px;

            border-radius:10px;
            -moz-border-radius:10px;
            -ms-border-radius:10px;
            -o-border-radius:10px;
            -webkit-border-radius:10px;

            background: -moz-linear-gradient(#66cc00, #4b9500);
            background: -ms-linear-gradient(#66cc00, #4b9500);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #66cc00), color-stop(100%, #4b9500));
            background: -webkit-linear-gradient(#66cc00, #4b9500);
            background: -o-linear-gradient(#66cc00, #4b9500);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#66cc00', endColorstr='#4b9500');
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#66cc00', endColorstr='#4b9500')";
            background: linear-gradient(#66cc00, #4b9500);
        }
        #progress_percent {
            float:right;
        }
        #upload_response {
            margin-top: 10px;
            padding: 20px;
            overflow: hidden;
            display: none;
            border: 1px solid #ccc;

            border-radius:10px;
            -moz-border-radius:10px;
            -ms-border-radius:10px;
            -o-border-radius:10px;
            -webkit-border-radius:10px;

            box-shadow: 0 0 5px #ccc;
            background: -moz-linear-gradient(#bbb, #eee);
            background: -ms-linear-gradient(#bbb, #eee);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #bbb), color-stop(100%, #eee));
            background: -webkit-linear-gradient(#bbb, #eee);
            background: -o-linear-gradient(#bbb, #eee);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#bbb', endColorstr='#eee');
            -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#bbb', endColorstr='#eee')";
            background: linear-gradient(#bbb, #eee);
        }

        #submitButton{
          margin-top: 30px;
        }




    </style>

</head>
<?php error_reporting(E_ALL^E_NOTICE);
      error_reporting(0)?>

<body onload="windowHeight()">
<div id="body">
    <div style="margin-bottom: 10px;">
        <img src="<?php echo base_url() ?>/images/wx_login_head.png" alt="" width="100%"/>

    </div>
    <div id="content">
        <label class="shopName">当前位置：<?php if(isset($shopName)){
                echo $shopName;
            } ?>打印点</label>


        <button class="btn" onclick="select_file()">选择打印的文件或图片</button>

        <button class="btn" onclick="start_upload()">开始上传</button>

        <div id="details">
            <form id="upload_form" enctype="multipart/form-data" >
            <input type="file" name="file" id="image_file" onchange="fileSelected();" style="display: none;"/>
        </form>
            <p style="text-align: center;">文件名：<label id="file_name" ></label></p>
            <div id="fileinfo">
                <div id="filename"></div>
                <div id="filesize"></div>
                <div id="filetype"></div>
                <div id="filedim"></div>
            </div>
            <div id="error">不能上传该文档格式</div>
            <div id="error2">上传过程中发生错误！</div>
            <div id="abort">The upload has been canceled by the user or the browser dropped the connection</div>
            <div id="warnsize">上传的文件过大！</div>

            <div id="progress_info">
                <div id="progress"></div>
                <div id="progress_percent">&nbsp;</div>
                <div class="clear_both"></div>
                <div>
                    <div id="speed">&nbsp;</div>
                    <div id="remaining">&nbsp;</div>
                    <div id="b_transfered">&nbsp;</div>
                    <div class="clear_both"></div>
                </div>
                <div id="upload_response"></div>
            </div>
        </div>



        <form class="form1"  id="form1" >

            <input type="hidden"  maxlength="16"  name="bid"  id="bid" value="<?php if(isset($bid)){
                echo $bid;
            } ?>"/>

        </form>
        <button id="submitButton" class="btn">开始打印</button>
    </div>



</div>
</body>
</html>