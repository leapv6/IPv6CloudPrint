<html>
<head>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>style/jquery-labelauty.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        ul { list-style-type: none;}
        li { display: inline-block;}
        li { margin: 10px 0;}
        input.labelauty + label { font: 12px "Microsoft Yahei";}
    </style>
    <!-- radio美化 -->
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-labelauty.js"></script>

    <script type="text/javascript">
        $(function(){
            $(':input').labelauty();
        });

        function checkEmail(){
            var str = $('#businessEmail').val();
            if (str.match(/[A-Za-z0-9_-]+[@](\S*)(net|com|cn|org|cc|tv|[0-9]{1,3})(\S*)/g) == null) {
                layer.tips('请输入正确的邮箱！','#businessEmail',{
                    tips:3,
                    time:1500
                });
                return false;
            }
            else {
                var res = false;
                $.ajax({
                    url : "<?php echo site_url('welcome/check_business_email_unique') ?>",
                    data : {
                        email:$("#businessEmail").val()
                    },
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            res = true;
                            var index = layer.load();
                            layer.close(index);
                        }else{
                            layer.tips('邮箱已经存在啦，请换一个！','#businessEmail',{
                                tips:3,
                                time:1500
                            });
                            var index = layer.load();
                            layer.close(index);
                            res = false;
                        }

                    },
                    beforeSend:function(){
                        layer.load('验证中…');
                    }
                });
                return res;
            }
        }

        function close_layer(){
            layer.msg("loading...");
        }
        $(document).ready(function(){
            $("#submitbutton").click(function() {
                if(checkEmail()){
                    $.ajax({
                        url : "<?php echo base_url(); ?>business/shop_manage/update_shop_info",
                        data : $("#submitForm").serialize(),
                        type : "POST",
                        dataType : "json",
                        async:false,
                        success : function(data){
                            if(data.status == 1){
                                layer.msg("修改成功！",{time:1500});
                                var index = layer.load();
                                layer.close(index);
                            }else{
                                layer.msg("修改失败，请检查所填信息！");
                                var index = layer.load();
                                layer.close(index);
                            }

                        },
                        beforeSend:function(){
                            layer.load('加载中…');
                        }
                    });
                }


            });
        });

    </script>

    <style type="text/css">
        #form_box{
            margin-left: 166px;
        }

    </style>
</head>

<body>
<div id="form_box">
    <form  id="submitForm" name="submitForm">
        <input type="hidden" name="businessId" value="<?php echo $businessId;?>"/>
        <div class="form_content">
            <label>单位名称：</label>
            <input type="text" id="shopName" name="shopName" value="<?php echo $shopName;?>"/>
        </div>

        <div class="form_content">
            <label>负责人姓名：</label>
            <input type="text" id="businessName" name="businessName" value="<?php echo $businessName;?>"/>
        </div>


        <div class="form_content">
            <label>邮&nbsp;&nbsp;&nbsp;&nbsp;箱：</label>
            <input type="text" id="businessEmail" name="businessEmail" value="<?php echo $businessEmail;?>" onkeyup="checkEmail()"/>
        </div>

        <div class="form_content">
            <label>地&nbsp;&nbsp;&nbsp;&nbsp;址：</label>
            <input type="text" id="businessAddress" name="businessAddress" value="<?php echo $businessAddress;?>"/>
        </div>


        <div class="form_content">
            <label>运营状态：</label>
            <ul>
                <li><input type="radio" value="1"  <?php if($runStatus == "1") echo "checked";?> name="runStatus" data-labelauty="正常"></li>
                <li><input type="radio" value="0"  <?php if($runStatus == "0") echo "checked";?> name="runStatus" data-labelauty="休息"></li>
            </ul>
        </div>

        <div class="form_content">
            <label>繁忙程度：</label>
            <ul>
                <li><input type="radio" value="0"  <?php if($busyStatus == "0") echo "checked";?> name="busyStatus" data-labelauty="正常"></li>
                <li><input type="radio" value="1"  <?php if($busyStatus == "1") echo "checked";?> name="busyStatus" data-labelauty="繁忙"></li>
            </ul>
        </div>


        <!--<div class="form_content">
            <label>支持打印格式：</label>

        </div>-->
        <br/><br/><br/>
        <div class="submit" align="center">
            <input id="submitbutton"  type="button" value="保      存" style="margin-right: 50px;"/>
        </div>

    </form>
</div>


</body>


