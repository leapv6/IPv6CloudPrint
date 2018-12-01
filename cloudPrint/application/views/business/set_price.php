<html>
<head>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <link href="<?php echo base_url(); ?>style/set_price.css"  rel="stylesheet" type="text/css"/>
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

        function checkNum1(){
            var str = $('#blackOnePrint').val();
            if (str.match(/^(\d+\.\d{1,1}|\d+)$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的价格！','#blackOnePrint',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkNum2(){
            var str = $('#blackDoublePrint').val();
            if (str.match(/^(\d+\.\d{1,1}|\d+)$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的价格！','#blackDoublePrint',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkNum3(){
            var str = $('#colorOnePrint').val();
            if (str.match(/^(\d+\.\d{1,1}|\d+)$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的价格！','#colorOnePrint',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkNum4(){
            var str = $('#colorDoublePrint').val();
            if (str.match(/^(\d+\.\d{1,1}|\d+)$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的价格！','#colorDoublePrint',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }



        $(document).ready(function(){
            $("#submitbutton").click(function() {
                if(checkNum1() && checkNum2() && checkNum3() && checkNum4()){
                    $.ajax({
                        url : "<?php echo base_url(); ?>business/shop_manage/update_price",
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
<font color="red"  size="2px" style="margin-left: 15px;">价格保留一位小数</font>
<div id="form_box">

    <form  id="submitForm" name="submitForm">

        <div class="form_content">
            <label>黑白单面打印/张(元)：</label>
            <input type="text" id="blackOnePrint" name="blackOnePrint" value="<?php echo $data['blackOnePrint']; ?>" maxlength="4" onblur="checkNum1();"/>
        </div>

        <div class="form_content">
            <label>黑白双面打印/张(元)：</label>
            <input type="text" id="blackDoublePrint" name="blackDoublePrint" value="<?php echo $data['blackDoublePrint']; ?>" maxlength="4" onblur="checkNum2();"/>
        </div>

        <div class="form_content">
            <label>彩色单面打印/张(元)：</label>
            <input type="text" id="colorOnePrint" name="colorOnePrint" value="<?php echo $data['colorOnePrint']; ?>" maxlength="4" onblur="checkNum3();"/>
        </div>

        <div class="form_content">
            <label>彩色双面打印/张(元)：</label>
            <input type="text" id="colorDoublePrint" name="colorDoublePrint" value="<?php echo $data['colorDoublePrint']; ?>" maxlength="4" onblur="checkNum4();"/>
        </div>

        <br/><br/><br/>
        <div class="submit" align="center">
            <input id="submitbutton"  type="button" value="保      存" style="margin-right: 50px;"/>
        </div>

    </form>
</div>


</body>


