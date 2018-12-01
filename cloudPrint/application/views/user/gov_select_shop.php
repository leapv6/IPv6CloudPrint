<html>
<head>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <link href="<?php echo base_url(); ?>style/info.css"  rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>style/jquery-labelauty.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        ul { list-style-type: none;}
        li { display: inline-block;}
        input.labelauty + label { font: 12px "Microsoft Yahei";}
    </style>
    <!-- radio美化 -->
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-labelauty.js"></script>

    <script type="text/javascript">
        $(function(){
            $(':input').labelauty();
        });

        function checkNumber(){
            var str = $('#printCopis').val();
            if (str.match(/^[0-9]*[1-9][0-9]*$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的数字！','#printCopis',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkNumber1(){
            var str = $('#pageNum').val();
            if (str.match(/^[0-9]*[1-9][0-9]*$/)) {
                return true;
            }
            else {
                layer.tips('请输入正确的页数！','#pageNum',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkSpecial(){
            var val = $('input:radio[name="specialOrder"]:checked').val();
            if(val==1){
                var str = $('#message').val();
                if(str ==""){
                    layer.msg("特殊打印订单请填写详细要求！",{time:1500});
                    return false;
                }else{
                    return true;
                }
            }else{
                return true;
            }
        }

        //更新价格
        function get_price(){
            $.ajax({
                url : "<?php echo site_url('user/user_index/get_price') ?>",
                data : {
                    'double' : $('input:radio[name="doublePrint"]:checked').val(),
                    'color' : $('input:radio[name="colorPrint"]:checked').val(),
                    'copis' : $('#printCopis').val(),
                    'page' : $('#pageNum').val(),
                    'bid' : <?php echo $businessId;?>
                },
                type : "POST",
                dataType : "json",
                async:false,
                success : function(data){
                    var index = layer.load();
                    layer.close(index);
                    $('#price').html(data.price+'元');
                },
                beforeSend:function(){
                    layer.load('加载中…');
                }
            });
        }

        $(document).ready(function(){
            $("#submitButton").click(function(){
                if(checkNumber() && checkSpecial()){
                    $.ajax({
                        url : "<?php echo site_url('user/user_index/make_order') ?>",
                        data : $("#form1").serialize(),
                        type : "POST",
                        dataType : "json",
                        async:false,
                        success : function(data){
                            if(data.status == 1){
                                layer.msg("订单提交成功！",{time:2000});
                                parent.location.href = "<?php echo site_url('user/user_index/my_order') ?>";

                            }else{
                                var index = layer.load();
                                layer.msg("订单信息格式错误，请重试！",{time:1000});
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
            width: 325px;
            margin-left: 55px;
        }
        ul{
            margin-right: 22px;

        }
        .center1{
            margin-top: 20px;;
        }
    </style>
</head>

<body>
<br>
<div id="form_box">
    <form  id="form1">
        <input type="hidden" name="docId"  value="<?php echo $docId;?>"  />
        <input type="hidden" name="businessId"  value="<?php echo $businessId;?>"  />
        <div class="form_content">
            <label >单位名称：</label>
            <input type="text" name="shopName" disabled value="<?php echo $shopName; ?>"/>
        </div>

        <div class="form_radio">
            <label class="center1">单双打印：</label>
            <ul>
                <li><input type="radio" value="0" checked  name="doublePrint" data-labelauty="单面打印" onchange="get_price()"></li>
                <li><input type="radio" value="1"    name="doublePrint" data-labelauty="双面打印" onchange="get_price()"></li>
            </ul>
        </div>

        <div class="form_radio">
            <label class="center1">是否彩印：</label>
            <ul>
                <li><input type="radio" value="0"  checked  name="colorPrint" data-labelauty="黑白打印" onchange="get_price()"></li>
                <li><input type="radio" value="1"   name="colorPrint" data-labelauty="彩色打印" onchange="get_price()"></li>
            </ul>
        </div>



        <div class="form_radio">
            <label class="center1">特殊打印：</label>
            <ul>
                <li><input type="radio" value="0" checked  name="specialOrder" onclick="$('#message').attr('disabled','disabled')" data-labelauty="普通打印"></li>
                <li><input type="radio" value="1"  name="specialOrder" onclick="$('#message').attr('disabled',false)" data-labelauty="特殊打印"></li>
            </ul>
        </div>


        <div class="form_content" >
            <label class="center1">打印份数：</label>
            <input type="number" id="printCopis" name="printCopis" value="1" maxlength="3" onchange="get_price()" style="margin-bottom: 10px" onblur="checkNumber();"/>
        </div>


        <div class="form_textarea">
            <label>留言信息：</label>
            <textarea style="height: 100px;width:160px" id="message" maxlength="120" disabled="disabled"  name="message" placeholder="有“特殊打印”要求请先选择上方“特殊打印”选项，然后留言备注哦~(例如：是否装订，ppt每页放几张)"></textarea>
        </div>


        <br/><br/><br/>
        <div class="submit" >
            <input id="submitButton"  type="button" value="提交订单" style="margin-right: 50px;"/>
        </div>

    </form>
</div>


</body>


