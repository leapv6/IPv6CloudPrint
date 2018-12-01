<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="<?php echo base_url();?>style/base.css"  rel="stylesheet" type="text/css" />

    <title>地图选择</title>
    <style type="text/css">
        html
        {
            height: 100%;
        }
        body
        {
            height: 100%;
            margin: 0px;
            padding: 0px;
        }
        #container
        {
            width:600px;
            height: 900px;
        }
    </style>
    <!-- 搜索框-->
    <style>
        .m-tt-1{display: inline-block; height: 56px;line-height: 56px;padding: 0 10px;font-size: 22px;color: #333;position: relative;}
        #search{font-size:14px;}
        #search .k{font-size:15px;padding:2px 1px; width:200px;}
        #search_auto{border:1px solid #817FB2; position:absolute; display:none;z-index:99;margin-left: 120px;}
        #search_auto li{background:#FFF; text-align:left;font-size: 15px;line-height: 30px;}
        #search_auto li.cls{text-align:right;}
        #search_auto li a{display:block; padding:0px 6px; cursor:pointer; color:#666;}
        #search_auto li a:hover{background:#D8D8D8; text-decoration:none; color:#000;}
    </style>
    <style type="text/css">

        .text-overflow {white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
        .info-window>.result-name {font-size: 18px;}
        .result-address {margin: 0 0 5px;font-size: 13px;color: #999;}
        .iw-btn {display: block;margin: 10px 0 0 0;width: 120px;padding: 7px 0;border-radius: 3px;background: #ff6000;color: #fff;text-align: center;font: 16px;}
        .map-navbar { position: absolute;top: 10px;left: 80px;right: 20px;z-index: 4000;height: 40px;width: 560px;}
        .module-locate { position: relative;float: left;margin: 0 4px 0 0;width: 560px;}
        .map-block.dark { box-shadow: 0 2px 6px rgba(0,0,0,0.3),0 4px 15px -5px rgba(0,0,0,0.3);}
        .search-bar {height: 40px;padding: 0 0 0 20px;}
        .map-block { -moz-box-sizing: border-box;box-sizing: border-box;border: 1px solid transparent;border-radius: 2px;background: #fff;box-shadow: 0 1px 6px rgba(0,0,0,0.35); }
        .twitter-typeahead {float: left;height: 38px;width: 90%;}
        .tt-dropdown-menu { -moz-box-sizing: border-box;box-sizing: border-box;border: 1px solid transparent;border-radius: 2px;background: #fff;box-shadow: 0 1px 6px rgba(0,0,0,0.35);width: 560px;margin: 2px 0 0 -21px;max-height: 204px;overflow-x: hidden;overflow-y: auto;}
        .glyph-search {  font-family: 'eleme';speak: none;font-style: normal;font-weight: normal;font-variant: normal;text-transform: none;line-height: 1;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;  }
        .search-input {width: 90%;padding: 5.8px 0;border: none;font: 16px/1.4 "Helvetica Neue",Arial,"Microsoft Yahei",sans-serif;outline:none;}
        .search-btn {float: right;width: 38px;font-size: 20px;color: #999;line-height: 38px;text-align: center;}
    </style>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" ></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=CiEwAVN72cVAuHLzNRAMjzpY"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
    <script>

        function search_shop(){
            $.ajax({
                url : "<?php echo site_url('WXapi/search_shop') ?>",
                data : {'value' : $(this).val()},
                type : "POST",
                dataType : "",
                async:false,
                success : function(data){
                    if(data=='0') $('#search_auto').html('').css('display','none');
                    else $('#search_auto').html(data).css('display','block');

                },
                beforeSend:function(){
                    var index = layer.load();
                    layer.close(index);
                }
            });
        }


        $(function(){
            $('#search_auto').css({'width':$('#search input[name="k"]').width()+4});
           /* $("#search_shop_name").change(function(){
                /!*$.post("",{'value':$(this).val()},function(data){
                    if(data=='0') $('#search_auto').html('').css('display','none');
                    else $('#search_auto').html(data).css('display','block');
                });*!/



            });*/



            $('#search_btn').click(function(){
                $.post("<?php echo site_url('WXapi/search_shop');?>",{'value':$('#search_shop_name').val()},function(data){
                    if(data=='0') $('#search_auto').html('').css('display','none');
                    else $('#search_auto').html(data).css('display','block');
                });
            });




        });
    </script>
</head>
<body>

<div id="search" style="display:inline-block;">
    <font style="font-size: 15px;">搜索打印店：</font>
    <input  type="text" id="search_shop_name" onkeyup="search_shop()" style="height: 20px;tap-highlight-color:rgba(0,0,0,0);-webkit-tap-highlight-color:rgba(0,0,0,0);" name="k" class="k" /> <input id="search_btn" type="button" name="s"style='font-size:15px' value="搜索" />
</div>
<div id="search_auto"></div>
<div id="container">
</div>






<input id="lng" type="hidden" runat="server" />
<input id="lat" type="hidden" runat="server" />

<script type="text/javascript">
    function select_this_shop(shopid){
        $.ajax({
            url : "<?php echo site_url('WXapi/check_online') ?>",
            data : {'shopid' : shopid},
            type : "POST",
            dataType : "json",
            async:false,
            success : function(data){
                if(data.status == 1){
                    location.href = "<?php echo site_url('WXapi/scan_select_shop') ?>"+"/"+shopid;
                }else{
                    notice();
                }


            },
            beforeSend:function(){
                var index = layer.load('加载中…');
                layer.close(index);
            }
        });
    }

    function notice(){
        var index = layer.load();
        layer.msg('对不起,该店铺尚未营业,不能下单!',{shift: 6});
        layer.close(index);
    }
    $(document).ready(function() {
        var map = new BMap.Map("container");
        var point = new BMap.Point(116.404,39.915);
        map.centerAndZoom(point,12); // 初始化地图,设置城市和地图级别。

        function myFun(result){
            var cityName = result.name;
            map.setCenter(cityName);
            //alert("当前定位城市:"+cityName);
        }
        var myCity = new BMap.LocalCity();
        myCity.get(myFun);

        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                map.addOverlay(mk);
                map.panTo(r.point);
                var bs = map.getBounds();   //获取可视区域
                var bssw = bs.getSouthWest();   //可视区域左下角
                var bsne = bs.getNorthEast();   //可视区域右上角
                get_shop_list(bssw,bsne);
            }
            else {
                map.panTo(116.404,39.915);
                var bs = map.getBounds();   //获取可视区域
                var bssw = bs.getSouthWest();   //可视区域左下角
                var bsne = bs.getNorthEast();   //可视区域右上角
                get_shop_list(bssw,bsne);
            }
        },{enableHighAccuracy: true})




        //添加缩放尺
        var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});// 左上角，添加比例尺
        var top_left_navigation = new BMap.NavigationControl();  //左上角，添加默认缩放平移控件
        var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}); //右上角，仅包含平移和缩放按钮
        map.addControl(top_left_control);
        map.addControl(top_left_navigation);
        map.addControl(top_right_navigation);
        //map.clearOverlays();//可以清楚标注

        map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
        map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

        var bs = map.getBounds();   //获取可视区域
        var bssw = bs.getSouthWest();   //可视区域左下角
        var bsne = bs.getNorthEast();   //可视区域右上角
        get_shop_list(bssw,bsne);

        map.addEventListener("dragend", function(){
            var bs = map.getBounds();   //获取可视区域
            var bssw = bs.getSouthWest();   //可视区域左下角
            var bsne = bs.getNorthEast();   //可视区域右上角
            //alert("当前地图可视范围是：" + bssw.lng + "," + bssw.lat + "到" + bsne.lng + "," + bsne.lat);
            get_shop_list(bssw,bsne)
        })

        map.addEventListener("zoomend", function(){
            var bs = map.getBounds();   //获取可视区域
            var bssw = bs.getSouthWest();   //可视区域左下角
            var bsne = bs.getNorthEast();   //可视区域右上角
            get_shop_list(bssw,bsne)
            //alert("当前地图可视范围是：" + bssw.lng + "," + bssw.lat + "到" + bsne.lng + "," + bsne.lat);
        })

        //复杂的自定义覆盖物
        function ComplexCustomOverlay(point, text, mouseoverText){
            this._point = point;
            this._text = text;
            this._overText = mouseoverText;
            this._borough_id = borough_id;
        };
        ComplexCustomOverlay.prototype = new BMap.Overlay();
        //在调用聚合方法时会将会调用标注的getPosition和getMap方法
        ComplexCustomOverlay.prototype.getPosition = function(){
            return this._point;
        };
        ComplexCustomOverlay.prototype.getMap = function(){
            return this._map;
        };






        function get_shop_list(bssw,bsne){

            var min_xx = bssw.lng; //左下角xx
            var min_yy = bssw.lat; //左下角YY

            var max_xx = bsne.lng; //右上角xx
            var max_yy = bsne.lat; //右上角yy


            $.ajax({
                url: "<?php echo site_url('WXapi/get_shop_list'); ?>",
                data : { min_xx: min_xx,min_yy: min_yy,max_xx: max_xx,max_yy: max_yy },
                type : "POST",
                dataType : "json",
                async:false,
                success :function(data){
                    var returndata=eval(data);
                    var content_array = returndata.content;
                    var shopIcon = new BMap.Icon("<?php echo base_url();?>images/face.png", new BMap.Size(32,32));
                    if (returndata.status > 0) {

                        map.clearOverlays();
                        var markers = [];
                        var pt = null;
                        var shop_list_count = content_array.length;

                        if(shop_list_count <=30){
                            for(var i=0;i<content_array.length;i++){
                                var marker = new BMap.Marker(new BMap.Point(content_array[i]['lng'],content_array[i]['lat']),{icon:shopIcon});  // 创建标注
                                if(content_array[i]["busyStatus"] == 1){
                                    content_array[i]["busy"] = "繁忙";
                                }else{
                                    content_array[i]["busy"] = "正常";
                                }
                                if(content_array[i]["runStatus"] > 0){
                                    var content = '<p class="result-name text-overflow" title="'+content_array[i]["shopName"]+'">'+content_array[i]["shopName"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessAddress"]+'">'+'地址：'+content_array[i]["businessAddress"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessPhone"]+'">'+'联系电话：'+content_array[i]["businessPhone"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["busy"]+'">'+'繁忙程度：'+content_array[i]["busy"] +'</p><a class="iw-btn" onclick="select_this_shop('+content_array[i]["businessId"]+')" >在这打印</a>';
                                }else{
                                    var content = '<p class="result-name text-overflow" title="'+content_array[i]["shopName"]+'">'+content_array[i]["shopName"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessAddress"]+'">'+'地址：'+content_array[i]["businessAddress"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessPhone"]+'">'+'联系电话：'+content_array[i]["businessPhone"]+'</p><a class="iw-btn" onclick="notice();" style="background: #a09e9e;" >未营业</a>';
                                }
                                map.addOverlay(marker);
                                marker.addEventListener("click",openInfo.bind(null,content));
                            }
                        }else{
                            for(var i=0;i<content_array.length;i++){
                                var marker = new BMap.Marker(new BMap.Point(content_array[i]['lng'],content_array[i]['lat']),{icon:shopIcon});  // 创建标注
                                if(content_array[i]["runStatus"] > 0){
                                    var content = '<p class="result-name text-overflow" title="'+content_array[i]["shopName"]+'">'+content_array[i]["shopName"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessAddress"]+'">'+'地址：'+content_array[i]["businessAddress"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessPhone"]+'">'+'联系电话：'+content_array[i]["businessPhone"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["busy"]+'">'+'繁忙程度：'+content_array[i]["busy"] +'</p><a class="iw-btn" onclick="select_this_shop('+content_array[i]["businessId"]+')" >在这打印</a>';
                                }else{
                                    var content = '<p class="result-name text-overflow" title="'+content_array[i]["shopName"]+'">'+content_array[i]["shopName"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessAddress"]+'">'+'地址：'+content_array[i]["businessAddress"]+'</p><p class="result-address text-overflow" title="'+content_array[i]["businessPhone"]+'">'+'联系电话：'+content_array[i]["businessPhone"]+'</p><a class="iw-btn" onclick="notice();" style="background: #a09e9e;" >未营业</a>';
                                }
                                markers.push(marker);
                                marker.addEventListener("click",openInfo.bind(null,content));
                            }
                            var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markers});
                        }


                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    //alert(XMLHttpRequest.status);
                }
            });




        }


        function G(id) {
            return document.getElementById(id);
        }

        var opts = {
            enableMessage:false//设置允许信息窗发送短息
        };

        function openInfo(content,e){
            var p = e.target;
            var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
            var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
            map.openInfoWindow(infoWindow,point); //开启信息窗口
        }



    });







</script>
</body>
</html>