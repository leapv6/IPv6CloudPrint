<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/gov_reg.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/form.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/jquery-labelauty.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <!-- radio美化 -->
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-labelauty.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=CiEwAVN72cVAuHLzNRAMjzpY"></script>
    <script type="text/javascript">
        //检查表单信息格式
        //去掉字符串头尾空格
        function trim(str){
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
        function checkMobilePhone(){
            var str = $('#businessPhone').val();
            if (str.match(/^1\d{10}$/) == null) {
                layer.tips('请输入正确的手机号！','#businessPhone',{
                    tips:3,
                    time:1500
                });
                return false;
            }
            else {
                var res = false;
                $.ajax({
                    url : "<?php echo site_url('welcome/check_shop_unique') ?>",
                    data : {
                        phone:trim($("#businessPhone").val())
                    },
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            var index = layer.load();
                            layer.close(index);
                            res = true;
                        }else{
                            layer.tips('手机号已存在啦，请换一个！','#businessPhone',{
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

        function checkNick(){
            if($('#businessNick').val()){
                return true;
            }else{
                layer.tips('请输入用户名！','#businessNick',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkShopName(){
            if($('#shopName').val()){
                return true;
            }else{
                layer.tips('请输入单位全称！','#shopName',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkChinese(){
            var str = $('#businessName').val();
            if (escape(str).indexOf("%u") != -1) {
                return true;
            }
            else {
                layer.tips('请输入正确的姓名！','#businessName',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkAddress(){
            if($('#businessAddress').val()){
                return true;
            }else{
                layer.tips('请输入街道地址！','#businessAddress',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkName(){
            if($('#businessName').val()){
                return true;
            }else{
                layer.tips('请输入负责人姓名！','#businessName',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }


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
                        email:trim($("#businessEmail").val())
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

        function checkPass(){
            var str = $('#businessPsw').val();
            if (str.match(/^[\w]{6,12}$/)) {
                return true;
            }
            else {
                layer.tips('密码为6-16位字符、数字和下划线！','#businessPsw',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function passComfirm(){
            if($('#comfirmPsw').val() == $('#businessPsw').val()){
                return true;
            }else{
                layer.tips('两次输入密码不一致，请重新输入！','#comfirmPsw',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkAgree(){
            if($('#agree').is(':checked')){
                return true;
            }else{
                layer.tips('请先同意该声明后在注册！','#agree',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkLat(){
            var str = $('#xx').val();
            if(str.match(/^-?\d+(\.\d+)?$/g)){
                return true;
            }else{
                layer.tips('请在地图上确定打印店的位置！','#xx',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }

        function checkLng(){
            var str = $('#yy').val();
            if(str.match(/^-?\d+(\.\d+)?$/g)){
                return true;
            }else{
                layer.tips('请在地图上确定打印店的位置！','#yy',{
                    tips:3,
                    time:1500
                });
                return false;
            }
        }


        function check_all(){
            if(checkMobilePhone() && checkNick() && checkShopName()  && checkEmail() && checkPass() && passComfirm() && checkAddress() &&checkAgree() &&checkLat() &&checkLng() ){
                $.ajax({
                    url : "<?php echo site_url('admin/gov/reg_gov') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("添加成功！");
                            var index = layer.load();
                            layer.close(index);
                        }else{
                            layer.msg("注册失败，请检查所填信息！");
                            var index = layer.load();
                            layer.close(index);
                        }

                    },
                    beforeSend:function(){
                        layer.load('加载中…');
                    }
                });
            }else{
                layer.msg("请检查所填信息格式！",{time:1500});
            }
        }

        function show(){
            var wid = parseInt(document.documentElement.clientWidth) - 520;
            //alert(wid);
            document.getElementById("right").style.width = wid+"px";
            change();
        }

        //根据radio选中值改变提示语
        function change(){
            var radio = document.getElementsByName("property");
            var radioLength = radio.length;
            for(var i = 0;i < radioLength;i++)
            {
                if(radio[i].checked)
                {
                    var radioValue = radio[i].value;
                    if(radioValue == 0){
                        $('#businessName').attr({"placeholder":'请填写企业法人营业执照上法人代表姓名'});
                        $('#shopName').attr({"placeholder":'请填写企业法人营业执照上主体全称'});
                        $('#businessAddress').attr({"placeholder":'请填写企业法人营业执照上地址'});
                    }else if(radioValue == 1){
                        $('#businessName').attr({"placeholder":'请填写个人身份证姓名'});
                        $('#shopName').attr({"placeholder":'请填写店铺名称'});
                        $('#businessAddress').attr({"placeholder":'学生请填写楼栋+寝室号'});
                    }
                }
            }
        }

        $(function(){
            $('.radio').labelauty();
        });
    </script>
    <style type="text/css">
        ul { list-style-type: none;}
        li { display: inline-block;}
        input.labelauty + label { font: 12px "Microsoft Yahei";}
        input{
            padding-left: 5px;
            font-size: 15px;
        }
    </style>


    <title>单位注册</title>

</head>

<body onload="show()">
<?php include(VIEWPATH."/white_top.php") ?>

<div id="box">
    <div id="left">
        <div id="left_top">

        </div>
        <div id="left_mid">
            <div id="left_content">
                <div id="title">
                    <p >基本信息<font color="#54c1f3">（请认真填写以下信息，*为必填项）</font></p>
                    <hr style="border:1px dashed #54c1f3;margin-top:13px;">
                </div>
                <form id="form1">
                    <div id="form_box">
                        <div class="one">
                            <div class="form">
                                <span>手机号<font color="red">*</font>：</span>
                                <input value="" type="text" name="businessPhone" placeholder="请输入手机号" id="businessPhone" maxlength="11" onblur="checkMobilePhone();" />
                            </div>

                        </div>

                        <div class="one">
                            <div class="form">
                                <span>用户名<font color="red">*</font>：</span>
                                <input type="text" name="businessNick" id="businessNick" placeholder="请输入汉字、字母、数字" maxlength="50" value="" onblur="checkNick();" />
                            </div>
                        </div>

                        <!--<div class="form_radio" style="padding-top: 15px;">
                            <div class="form" style="display: inline;">
                                <span style="margin-right: 51px;">性 质<font color="red">*</font>：</span>
                            </div>
                            <ul>
                                <li><input class="radio" type="radio" checked value="0" onchange="change();"   name="property" data-labelauty="工商经营"></li>
                                <li><input class="radio" type="radio" value="1" onchange="change();" name="property" data-labelauty="自然人经营"></li>
                            </ul>

                        </div>-->

                        <div class="one">
                            <div class="form">
                                <span>负责人姓名<font color="red">*</font>：</span>
                                <input value="" type="text" name="businessName" id="businessName" maxlength="10" onblur="checkName();"  />
                            </div>

                        </div>

                        <div class="one">
                            <div class="form">
                                <span>单位名称<font color="red">*</font>：</span>
                                <input type="text" name="shopName" id="shopName" placeholder="请输入单位的全称"  value="" maxlength="50" onblur="checkShopName();" />
                            </div>

                        </div>

                        <div class="one">
                            <div class="form">
                                <span>地 址<font color="red">*</font>：</span>
                                <input value="" type="text" name="businessAddress" placeholder="请输入单位的街道地址" id="businessAddress" size="30" maxlength="70" onblur="checkAddress();" />
                            </div>

                        </div>

                        <div class="one">
                            <div class="form">
                                <span>密 码<font color="red">*</font>：</span>
                                <input value="" type="password" name="businessPsw" id="businessPsw" maxlength="16" onblur="checkPass();" />
                            </div>

                        </div>

                        <div class="one">
                            <div class="form">
                                <span>确认密码<font color="red">*</font>：</span>
                                <input value="" type="password" name="Psw2" id="comfirmPsw" maxlength="16" onblur="passComfirm();" />
                            </div>

                        </div>

                        <div class="one">
                            <div class="form">
                                <span>邮 箱<font color="red">*</font>：</span>
                                <input type="text" name="businessEmail" id="businessEmail" maxlength="50"  value="" onblur="checkEmail();" />
                            </div>

                        </div>

                    </div>


                    <div id="bottom">
						<span class="shengming">
						<div id="checkbox"><input type="checkbox" id="agree" name="agree_btn" /></div> 
						&nbsp;&nbsp;&nbsp;我已阅读并同意<a href="<?php echo site_url('welcome/joinProt'); ?>" target="_blank"><font color="red">IPv6打印云注册声明</font></a>
						</span>
                        <br/><br/><br/>

                        <img onclick="check_all();" class="reg_button" src="<?php echo base_url(); ?>images/gov_reg_button.png" width="372px" height="45px">
                    </div>
            </div>
        </div>
    </div>

    <div id="right">
        <div id="top"> &nbsp;&nbsp;&nbsp;<font color="red">请用鼠标在地图上点击定位您所在的具体位置&nbsp;&nbsp;&nbsp;</font>
            快速定位请输入：<input id="suggestId" type="text" placeholder="请输入你的地址（学校，写字楼或街道）" style="width:300px;" value=""  autocomplete="off">
        </div>
        <div id="allmap"></div>
        <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
        <div id="foot">
            经度：<input type="text" name="xx" id="xx" style="color:#ff0000;  width:160px;"/>
            纬度: <input type="text" name="yy" id="yy" style="color:#ff0000;  width:160px;"/>
        </div>

    </div>

    </form>
</div>



</body>

<script type="text/javascript">

    var map = new BMap.Map("allmap");
    var point = new BMap.Point(106.546148,29.410482);
    map.centerAndZoom(point,15); // 初始化地图,设置城市和地图级别。
    map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
    map.addEventListener("click", showclick);

    function myFun(result){
        var cityName = result.name;
        map.setCenter(cityName);
        //alert("当前定位城市:"+cityName);
    }
    var myCity = new BMap.LocalCity();
    myCity.get(myFun);


    function showclick(e){
        map.clearOverlays();
        //alert(e.point.lng + "," + e.point.lat);
        $('#xx').val(e.point.lng);
        $('#yy').val(e.point.lat);
        var jingdu = e.point.lng;
        var weidu = e.point.lat;
        var marker_1 = new BMap.Marker(new BMap.Point(jingdu,weidu));
        map.addOverlay(marker_1);              // 将标注添加到地图中
        marker_1.enableMassClear();
    }

    function G(id) {
        return document.getElementById(id);
    }

    var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
        {"input" : "suggestId"
            ,"location" : map
        });

    ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
        var str = "";
        var _value = e.fromitem.value;
        var value = "";
        if (e.fromitem.index > -1) {
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }
        str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;

        value = "";
        if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        }
        str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
        G("searchResultPanel").innerHTML = str;
    });

    var myValue;
    ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
        var _value = e.item.value;
        myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;

        setPlace();
    });

    function setPlace(){
        map.clearOverlays();    //清除地图上所有覆盖物
        function myFun1(){
            var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
            map.centerAndZoom(pp, 14);
            map.addOverlay(new BMap.Marker(pp));    //添加标注
        }
        var local = new BMap.LocalSearch(map, { //智能搜索
            onSearchComplete: myFun1
        });
        local.search(myValue);
    }






</script></html>