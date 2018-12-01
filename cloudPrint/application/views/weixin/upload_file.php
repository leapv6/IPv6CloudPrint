<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IPv6云打印</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/up_main.css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js""></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/wx_up.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#submitButton").click(function(){
                if(check_has_file() && check_upload_finish()){
                    $.ajax({
                        url : "<?php echo site_url('WXapi/make_order') ?>",
                        data : $("#form1").serialize(),
                        type : "POST",
                        dataType : "json",
                        async:false,
                        success : function(data){
                            if(data.status == 1){
                                layer.msg("订单提交成功,请等待打印机打印！",{time:2000});
                                var index = layer.load();
                                layer.close(index);
                                setTimeout(function(){
                                    parent.location.href = "<?php echo site_url('WXapi/map_select') ?>";
                                },2500);

                            }else{
                                var index = layer.load();
                                layer.msg("订单提交失败，请重试！",{time:1000});
                                layer.close(index);
                            }

                        },
                        beforeSend:function(){
                            layer.load('加载中…');
                        }
                    });
                }else{
                    var index = layer.load();
                    layer.msg("文档上传完成后才能打印哦！",{time:1500,shift:6});
                    layer.close(index);
                }

            });
        });

        function check_has_file(){
            return $("#image_file").val();
        }

        function start_upload(){
            if(check_has_file()){
                startUploading();
            }else{
                var index = layer.load();
                layer.msg("请选择要上传的文件哦！",{time:1500,shift:6});
                layer.close(index);
            }
        }
    </script>
    <style type="text/css">
        button{
            border: medium none;
            border-radius: 5px;
            color: #fff;
            display: block;
            font-size: 16px;
            padding: 12px 0;
            text-align: center;
            text-decoration: none;
            width: 100%;
        }
    </style>

</head>

<body>
<div>
    <img src="<?php echo base_url() ?>/images/head.jpg" alt="" width="100%"/>
</div>
<label class="shopName">当前为：<?php echo $shopName; ?>打印点</label>
<div class="container">

    <div class="upload_form_cont">
        <form id="upload_form" enctype="multipart/form-data" method="post" action="<?php echo site_url('WXapi/do_upload') ?>">
            <div>
                <div><label for="image_file">请选择要上传的文件</label></div>
                <div><input type="file" name="file" id="image_file" onchange="fileSelected();" /></div>
            </div>
            <div>
                <input type="button" value="上传" onclick="start_upload()" />
            </div>
            <div id="fileinfo">
                <div id="filename"></div>
                <div id="filesize"></div>
                <div id="filetype"></div>
                <div id="filedim"></div>
            </div>
            <div id="error">不能上传该文档格式</div>
            <div id="error2">An error occurred while uploading the file</div>
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
        </form>

        <!--<img id="preview" />-->
    </div>
</div>
<div>
    <form class="form1"  id="form1" >

        <input type="hidden"  maxlength="16"  name="bid"  id="bid" value="<?php echo $bid; ?>"/>

    </form>
    <button id="submitButton" style="background-color: #eea236;">开始打印</button>
</div>
</body>
</html>