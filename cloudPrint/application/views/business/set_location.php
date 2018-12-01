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
            if(checkLat() && checkLng()){
                $.ajax({
                    url : "<?php echo site_url('business/shop_manage/update_location') ?>",
                    data : $("#form1").serialize(),
                    type : "POST",
                    dataType : "json",
                    async:false,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("修改定位成功！");
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
            }else{
                layer.msg("请检查所填信息格式！",{time:1500});
            }
        }

        function show(){

            map.clearOverlays();
            //alert(e.point.lng + "," + e.point.lat);
            $('#xx').val(<?php echo $lng; ?>);
            $('#yy').val(<?php echo $lat; ?>);
            var jingdu = '<?php echo $lng; ?>';
            var weidu = '<?php echo $lat; ?>';
            var marker_1 = new BMap.Marker(new BMap.Point(jingdu,weidu));
            map.addOverlay(marker_1);              // 将标注添加到地图中
            marker_1.enableMassClear();
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


    <title>地图定位</title>

</head>

<body onload="show()">


<div id="box">
    <div id="right">
        <div id="top"> &nbsp;&nbsp;&nbsp;<font color="red">请用鼠标在地图上点击定位您所在的具体位置&nbsp;&nbsp;&nbsp;</font>
            快速定位请输入：<input id="suggestId" type="text" placeholder="请输入你的地址（学校，写字楼或街道）" style="width:300px;" value=""  autocomplete="off">
        </div>
        <div id="allmap"></div>
        <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
        <div id="foot">
            <form id="form1">
                经度：<input type="text" name="xx" id="xx" style="color:#ff0000;  width:160px;"/>
                纬度: <input type="text" name="yy" id="yy" style="color:#ff0000;  width:160px;"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="button" value="保存" onclick="check_all();"/>
            </form>

        </div>

    </div>

    </form>
</div>



</body>

<script type="text/javascript">

    var map = new BMap.Map("allmap");
    var point = new BMap.Point(<?php echo $lng; ?>,<?php echo $lat; ?>);
    map.centerAndZoom(point,15); // 初始化地图,设置城市和地图级别。
    map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
    map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用
    map.addEventListener("click", showclick);

    /*function myFun(result){
     var cityName = result.name;
     map.setCenter(cityName);
     //alert("当前定位城市:"+cityName);
     }
     var myCity = new BMap.LocalCity();
     myCity.get(myFun);*/


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