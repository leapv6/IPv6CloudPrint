<html><head><meta charset="utf-8" /><title>中国在线打印第一品牌—云打印</title>
    <meta name="keyword" content="在线打印,校园打印,校园自助打印,校园网络打印,网络打印,寝室打印,云打印,校园打印,大学校园打印"/>
    <link href="<?php echo base_url(); ?>style/common_all.css"  rel="stylesheet" />
    <link href="<?php echo base_url(); ?>style/base.css"  rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>style/mian.css"  rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>style/shangjiazhuce.css"  rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>images/face.png" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js" ></script>
    <script type="text/javascript" src="http://oss.dayinyun.cn/Public/Home/Js/layer/layer.min.js" id = "layer"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=CiEwAVN72cVAuHLzNRAMjzpY"></script>
    
    </script><style type="text/css">
        #allmap {width: 540px;height: 420px;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
</head>
<body class="g-doc"><div class="g-bd"><div class="m-main">
        <form  method="post" id="loginform" name="loginform">
            <div class="cont980"><div class="title">

                    <span class="bt" id="a">商家注册</span></div><div class="content register"><p>亲爱的商家： 感谢您注册使用打印云。</p><h4>
                        基本信息
                        <span>(请认真填写以下信息，<font>*</font>为必填项)</span></h4>
                    <div class="clear"></div><dl><dt><table width="100%" border="0" class="table1" cellpadding="0" cellspacing="1"><tbody><tr>
                                <td width="24%" align="right">手机号：</td>
                                <td width="76%" align="left"><input value="" type="text" name="businessPhone" class="inp1" id="businessPhone"><font>*</font>
                                    </td></tr><tr>
                                <td align="right">用户名：</td>
                                <td align="left">
                                    <input type="text" name="businessNick" id="businessNick" class="inp1" value=""><font>*</font><br>
                                    <span id="spansellername"></span></td></tr><tr>
                                <td align="right">店铺名称：</td>
                                <td align="left">
                                    <input type="text" name="shopName" id="shopName" class="inp1" value=""><font>*</font><br>
                                    <span id="spansellername"></span></td></tr><tr>
                                <td align="right">邮箱：</td>
                                <td align="left">
                                    <input type="text" name="businessEmail" id="businessEmail" class="inp1" value=""><font>*</font><br>
                                    <span id="spansellername"></span></td></tr><tr>
                                <td align="right">密码：</td>
                                <td align="left"><input value="" type="password" name="businessPsw" id="businessPsw" class="inp1"><font>*</font><br>
                                    <span id="spanpassword">(6~16位数字、字母并区分大小写)</span></td></tr><tr>
                                <td align="right">确认密码：</td>
                                <td align="left"><input type="password" name="pwd2" id="UserConfirmPassword" class="inp1"><font>*</font><br>
                                    <span id="spanrepassword"></span></td></tr><tr>
                                <td align="left"></td></tr><tr></tr><tr>
                                <td align="right" valign="top">地址：</td>
                                <td align="left">
                                    <font>详细地址:</font><br>
                                    <input value="" type="text" class="inp1" name="businessAddress" id="businessAddress" size="30"><font>*</font><br>
                                    <span id="spanaddress"></span></td></tr><tr><td align="right">负责人姓名：</td>
                                <td align="left"><input value="" type="text" name="businessName" id="businessName" class="inp1"><br>
                                    <span id="spanname"></span></td>
                                </tr><tr>

                                <td align="left">
                                    <input name="cb" type="checkbox" id="cb" style="padding:0; margin:0; border:1px solid #bdd8ff;" value="checkbox" checked="checked">我已阅读并同意<a>打印云注册声明</a><br>
                                    <span id="spancheckbox"></span></td></tr><tr>
                                <td align="right"></td><td align="left">
                                    <input type="button" class="btn1" value=" " style="background:url('<?php echo base_url(); ?>images/btn_register.jpg')" id="btnsubmit"></td></tr></tbody></table></dt><dd>
                            <p>请用鼠标在地图上点击定位您所在的具体位置</p><div id="allmap"></div><div><p></p><p><span style="display:block">											经度：
	<input type="text" name="xx" class="inp1" id="xx" style="color:#ff0000; background:#f7f7f7; width:160px;"
           value="" readonly="">											&nbsp;&nbsp;纬度：
	<input type="text" name="yy" id="yy" value="" style="color:#ff0000; background:#f7f7f7; width:160px;"
           class="inp1" readonly=""></span></p></div></dd></dl>

                    <div class="clear"></div></div><div class="btm"></div></div></form></div></div><div style="display:none;">
</div></body>

<script type="text/javascript">

    var map = new BMap.Map("allmap");
    var point = new BMap.Point(116.404,39.915);
    map.centerAndZoom(point,12); // 初始化地图,设置城市和地图级别。
    map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
    map.addEventListener("click", showclick);


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

    $('#btnsubmit').on('click', function(){
        $.ajax({
            //提交数据的类型 POST GET
            type:"POST",
            //提交的网址
            url:"reg_shop",
            //提交的数据
            data:{businessPhone:$("#businessPhone").val(),businessNick:$("#businessNick").val(),shopName:$("#shopName").val(),businessPsw:$("#businessPsw").val(),businessEmail:$('#businessEmail').val(),businessAddress:$('#businessAddress').val(),businessName:$('#businessName').val(),xx:$("#xx").val(),yy:$("#yy").val()},
            //返回数据的格式
            datatype: "html",//"xml", "html", "script", "json", "jsonp", "text".
            //在请求之前调用的函数
            beforeSend:function(){
                if($("#businessPhone").val() == ''){
                    layer.tips('手机号不能为空', '#businessPhone',{guide:2,time: 1});
                    return false;
                }else if($("#businessNick").val() == ''){
                    layer.tips('用户名称不能为空', '#businessNick',{guide:2,time: 1});
                    return false;
                }else if($("#shopName").val() == ''){
                    layer.tips('店铺名称不能为空', '#shopName',{guide:2,time: 1});
                    return false;
                }else if($("#businessEmail").val() == ''){
                    layer.tips('邮箱不能为空', '#businessEmail',{guide:2,time: 1});
                    return false;
                }else if($("#businessPsw").val() == ''){
                    layer.tips('密码不能为空', '#businessPsw',{guide:2,time: 1});
                    return false;
                }else if($("#UserConfirmPassword").val() == ''){
                    layer.tips('确认密码不能为空', '#UserConfirmPassword',{guide:2,time: 1});
                    return false;
                }else if($("#businessPsw").val() !== $("#UserConfirmPassword").val()){
                    layer.tips('两次输入密码不相符', '#UserConfirmPassword',{guide:2,time: 1});
                    return false;
                }else if($("#businessAddress").val() == ''){
                    layer.tips('详细地址不能为空', '#businessAddress',{guide:2,time: 1});
                    return false;
                }else if($("#businessName").val() == '') {
                    layer.tips('联系人不能为空', '#businessName', {guide: 2, time: 1});
                    return false;
                }else if($("#xx").val() == ''){
                    layer.tips('经度不能为空', '#xx',{guide:2,time: 1});
                    return false;
                }else if($("#yy").val() == ''){
                    layer.tips('纬度不能为空', '#yy',{guide:2,time: 1});
                    return false;
                }else{
                    layer.load('加载中…');
                }

            },
            //成功返回之后调用的函数
            success:function(data){
                $("#msg").html(decodeURI(data));
            },
            //调用执行后调用的函数
            complete: function(XMLHttpRequest, textStatus){

                var returndata=eval('(' + XMLHttpRequest.responseText + ')');
                var content=returndata.content;
                var status=returndata.status;
                if(status > 0){
                    layer.alert(content,9); //风格一
                    //window.location.reload();
                }else{
                    layer.msg(content,2,3);
                }
                //HideLoading();
            },
            //调用出错执行的函数
            error: function(){
                //请求出错处理
            }
        });

    });

    var xmlHttp;
    var requestType="";
    function createXMLHttpRequest()
    {
        if(window.ActiveXObject)
        {
            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        else if(window.XMLHttpRequest)
        {
            xmlHttp=new XMLHttpRequest();
        }
    }

    function querychangecity(provinceid){
        document.getElementById("citylist").innerHTML='';
        createXMLHttpRequest();
        var url="/index.php/Shop/Ajax/get_city_list/provinceid/"+provinceid+"/"+Math.random();
        xmlHttp.open("GET",url,true);
        xmlHttp.onreadystatechange=ProvinceChange;
        xmlHttp.send(null);

    }
    function ProvinceChange(){
        if(xmlHttp.readyState==4)
        {
            if(xmlHttp.status==200)
            {
                document.getElementById("citylist").innerHTML=xmlHttp.responseText;
            }
        }
    }
    function querychangearea(cityid){
        document.getElementById("arealist").innerHTML='';
        createXMLHttpRequest();
        var url="/index.php/Shop/Ajax/get_area_list/cityid/"+cityid+"/"+Math.random();
        xmlHttp.open("GET",url,true);
        xmlHttp.onreadystatechange=AreaChange;
        xmlHttp.send(null);

    }
    function AreaChange(){
        if(xmlHttp.readyState==4)
        {
            if(xmlHttp.status==200)
            {
                document.getElementById("arealist").innerHTML=xmlHttp.responseText;
            }
        }
    }

</script></html>