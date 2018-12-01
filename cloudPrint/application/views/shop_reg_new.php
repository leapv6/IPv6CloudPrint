<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/shop_reg.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/form.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
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
	                        return true;
	                    }else{
	                        layer.tips('手机号已存在啦，请换一个！','#businessPhone',{
	                         tips:3,
	                         time:1500
	                            });
	                        var index = layer.load();
	                        layer.close(index);
	                        return false;
	                    }

	                },
	                beforeSend:function(){
	                    layer.load('验证中…');
	                }
	            });
	        return true;
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
	            layer.tips('请输入店铺名！','#shopName',{
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
	            layer.tips('请输入店铺名！','#businessAddress',{
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
	        return true;
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
                    url : "<?php echo site_url('welcome/reg_shop') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("注册成功,请返回首页登录！");
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
	}
	</script>


<title>商家注册</title>

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
								<span>手机号*：</span>
								<input value="" type="text" name="businessPhone" id="businessPhone" maxlength="11" onblur="checkMobilePhone();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>用户名*：</span>
								<input type="text" name="businessNick" id="businessNick" maxlength="20" value="" onblur="checkNick();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>店铺名称*：</span>
								<input type="text" name="shopName" id="shopName"  value="" maxlength="15" onblur="checkShopName();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>邮 箱*：</span>
								<input type="text" name="businessEmail" id="businessEmail" maxlength="50"  value="" onblur="checkEmail();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>密 码*：</span>
								<input value="" type="password" name="businessPsw" id="businessPsw" maxlength="16" onblur="checkPass();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>确认密码*：</span>
								<input value="" type="password" name="Psw2" id="comfirmPsw" maxlength="16" onblur="passComfirm();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>地 址*：</span>
								<input value="" type="text" name="businessAddress" id="businessAddress" size="30" maxlength="50" onblur="checkAddress();" />
							</div>
							
						</div>

						<div class="one">
							<div class="form">
								<span>负责人姓名：</span>
								<input value="" type="text" name="businessName" id="businessName" maxlength="10"  />
							</div>
							
						</div>
						
					</div>
				

					<div id="bottom">
						<span class="shengming">
						<div id="checkbox"><input type="checkbox" id="agree" name="agree_btn" /></div> 
						&nbsp;&nbsp;&nbsp;我已阅读并同意<a href="<?php echo site_url('welcome/shopGuide'); ?>" target="_blank">IPv6打印云注册声明</a> 
						</span>
						<br/><br/><br/>
	
						<img onclick="check_all();" class="reg_button" src="<?php echo base_url(); ?>images/shop_reg_button.png" width="372px" height="45px">
					</div>
				</div>
			</div>
		</div>

		<div id="right">
			<div id="top"> &nbsp;&nbsp;&nbsp;<font color="red">请用鼠标在地图上点击定位您所在的具体位置</font></div>
			<div id="allmap"></div>
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