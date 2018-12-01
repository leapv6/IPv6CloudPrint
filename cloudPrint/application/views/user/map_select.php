<html><head>
    <?php $this->load->helper('url'); ?>
    <meta charset="utf-8" /><title>中国在线打印第一品牌—v6网印</title>
    <meta name="keyword" content="在线打印,校园打印,校园自助打印,校园网络打印,网络打印,寝室打印,云打印,校园打印,大学校园打印"/>
    <link href="<?php echo base_url();?>style/common_all.css"  rel="stylesheet" />
    <link href="<?php echo base_url();?>style/base.css"  rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>style/mian.css"  rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>style/filemanage.css"  rel="stylesheet" type="text/css" />
    <style type="text/css">
        #box{
            position:absolute; right: 0px; top: 0px;
        }
    </style>
    <script type="text/javascript" src="<?php echo base_url();?>scripts/jquery-1.8.2.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>scripts/layer.js"></script>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>/images/favicon.ico" />
    <style type="text/css">
        body, html{width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
        #allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
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
    <!-- 搜索框-->
    <style>
        .m-tt-1{display: inline-block; height: 56px;line-height: 56px;padding: 0 10px;font-size: 22px;color: #333;position: relative;}
        #search{font-size:14px;}
        #search .k{font-size:15px;padding:2px 1px; width:200px;}
        #search_auto{border:1px solid #817FB2; position:absolute; display:none;z-index:2;margin-left: 660px;}
        #search_auto li{background:#FFF; text-align:left;font-size: 15px;line-height: 30px;}
        #search_auto li.cls{text-align:right;}
        #search_auto li a{display:block; padding:0px 6px; cursor:pointer; color:#666;}
        #search_auto li a:hover{background:#D8D8D8; text-decoration:none; color:#000;}
    </style>

    <script>
        $(document).ready(function(){
            $('#search_auto').css({'width':$('#search input[name="k"]').width()+4});
            $('#search input[name="k"]').keyup(function(){
                $.post("<?php echo site_url('user/user_index/search_shop');?>",{'value':$(this).val()},function(data){
                    if(data=='0') $('#search_auto').html('').css('display','none');
                    else $('#search_auto').html(data).css('display','block');
                });
            });

            $('#search_btn').click(function(){
                $.post("<?php echo site_url('user/user_index/search_shop');?>",{'value':$('#search_shop_name').val()},function(data){
                    if(data=='0') $('#search_auto').html('').css('display','none');
                    else $('#search_auto').html(data).css('display','block');
                });
            });
        });

    </script>
</head>

<body class="g-doc" onload="show_tips()">
<div class="g-hd m-hd m-hd-2"><a href="<?php echo base_url() ?>" class="logo"></a>
    <dl class="u-nav f-ib"><dd><input type="hidden" id="infoCreidt" value="0.00"><div class="f-cb u-user">
                <a href="#" class="pic f-fl" target="_blank"><img src="<?php echo base_url();?>images/face.png" alt=""></a>
                <a href="#" class="name f-fl f-toe" title="<?php echo $this->session->userdata('userNick') ; ?>">
                    <?php echo $this->session->userdata('userNick') ; ?></a><span class="triangle_down"></span><div class="menu">
                    <a href="<?php echo site_url('user/user_index/my_file') ?>">我的打印</a>
                    <a onclick="my_info()">我的信息</a>
                    <a onclick="change_psw()">修改密码</a>
                    <a href="javascript:;" onclick="dologout();return false;">退出</a></div></div></dd>
        <dt style="margin-left: 5px;"></dt><dd>账户余额： <b style="color: #cc3300"><span id="UserCreditMoney">0.00</span>元</b></dd><dt></dt><dd>
            <a id="btnRecharge">充值</a></dd><!--<dt></dt><dd><a>余额提现</a></dd>--><dt style="padding-right: 5px;"></dt><dd class="u-menu">
            <a target="_blank" class="f-ib toggle" href="http://www.hilltek.net/" >更多
                <span class="triangle_down"></span></a><ul><li><a target="_blank" href="#" >智慧·云</a></li><li>
                    <a target="_blank" href="#" >智慧</a></li><li>
                    <a target="_blank" href="#" >智慧</a></li></ul></dd></dl>

    <script>
        function show_tips(){
            layer.tips('点击我可以快速选中所属单位的打印点哦~', '#fast_select_shop',{time:2000});
        }

        function dologout(){
            if(confirm("您确定要退出吗？")){
                window.location.href = "<?php echo site_url('user/user_index/logout'); ?>";
            }

        }

        function my_info(){
            layer.open({
                type: 2,
                title:'我的信息',
                content: '<?php echo site_url('user/user_index/my_info') ?>',
                border : [5, 0.5, '#666'],
                area: ['520px','380px'],
                shadeClose: true
            });
        }

        function change_psw(){
            layer.open({
                type: 2,
                title:'修改密码',
                content: '<?php echo site_url('user/user_index/change_psw') ?>',
                border : [5, 0.5, '#666'],
                area: ['520px','350px'],
                shadeClose: true
            });
        }


    </script></div><div class="g-bd f-cb" id="js_bd"><div class="g-sd1"><div class="m-menu-1">
            <a href="<?php echo site_url("user/user_index/my_order"); ?>" class="ico-1">我的订单</a>
            <a href="<?php echo site_url("user/user_index/my_file"); ?>" class="ico-2" data-step="3" data-intro="打印文件管理">新建打印</a>
        </div></div>

    <div class="g-mn1"><div class="g-mn1c">

            <div class="m-tt-1">                选择打印店
                <span class="msg">* 请拖拽地图选择一个最近的打印店(<a style="color:red;">地图中显示为蓝色打印云图标<img src="<?php echo base_url(); ?>images/face.png" width="24" height="24" /></a>)
                </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div id="search" style="display:inline-block;">
                        <font style="font-size: 16px;">搜索打印店：</font>
                        <input type="text" id="search_shop_name" name="k" class="k" /> <input id="search_btn" type="button" name="s" value="搜索" />
                    </div>
                    <div id="search_auto"></div>

                <a id="fast_select_shop" onclick="select_this_shop(<?php if(isset($belongComId)) echo $belongComId;?>)" class="u-img-btn u-img-btn-1" style="<?php if($this->session->userdata('class')==0) {echo 'display:none;';} ?>;right: -190px;" data-step="2" data-intro="点击快速选取所在单位打印点!" >快捷打印</a>
                    <!--,或</span><a id="text_select" style="width:200px;text-align:center;color:blue;">文字选址</a>--></div>
            <div class="m-tb-1" style="position:relative;"><div class="map-navbar group">
                    <div class="module-locate map_search"><div class="map-block dark search-bar group"><span id="r-result" class="twitter-typeahead" style="position: relative; display: inline-block; direction: ltr;"><input class="search-input tt-hint" type="search" data-suggestion="tt-autocomplete"
                                                                                                                                                                                                                               disabled="" autocomplete="off" spellcheck="false" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);">
	<input class="search-input tt-input" type="text" id="suggestId"  placeholder="请输入你的地址（学校，写字楼或街道）"
           data-suggestion="tt-autocomplete" autocomplete="off" spellcheck="false"
           dir="auto" style="position: relative; vertical-align: top; background-color: transparent;"><span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none; right: auto;"><div class="tt-dataset-eleme-dataset"></div></span></span><a class="glyph-search search-btn search_btn" role="button"></a></div></div></div>
                <div id="allmap"></div>
                <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div></div></div></div>
    <form action="<?php echo site_url('user/user_index/') ?>" method="post" id="shop_selected">
        <input type="hidden" id="print_list" name="docId" value=""/>
        <input type="hidden" id="shop_id" name="businessId"  value=""/></form>
</div>



</body>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=CiEwAVN72cVAuHLzNRAMjzpY"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>
<script type="text/javascript">
    var shop_list_html
    var page_shop_list = {};
    layer.shop_list = function(optionshoplist){
        layer.closeAll();
        options = optionshoplist || {};
        $.layer({
            type: 1,
            title: false,
            offset: [($(window).height() - 300)/2+'px', ''],
            border : [5, 0.5, '#666'],
            area: ['500px','300px'],
            shadeClose: true,
            page: page_shop_list,
            closeBtn: [0, true]
        });
    };
    $('#text_select').click(function(){
        if(shop_list_html){
            page_shop_list.html = shop_list_html;
        } else {
            page_shop_list.url = '/index.php/User/shop_list'
            page_shop_list.ok = function(datashop_list){
                shop_list_html = datashop_list; //保存登录节点
            }
        }
        layer.shop_list();
    });

    function select_this_shop(shopid){
        $.ajax({
            url : "<?php echo site_url('user/user_index/check_online') ?>",
            data : {'shopid' : shopid},
            type : "POST",
            dataType : "json",
            async:false,
            success : function(data){
                if(data.status == 1){
                    layer.open({
                        type: 2,
                        title:false,
                        content: '<?php echo site_url("user/user_index/select_shop") ?>'+'/'+shopid+'/'+'<?php echo $docId; ?>',
                        border : [5, 0.5, '#666'],
                        area: ['520px','500px'],
                        shadeClose: true
                    });
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
        var map = new BMap.Map("allmap");
        var point = new BMap.Point(116.404,39.915);
        map.centerAndZoom(point,12); // 初始化地图,设置城市和地图级别。
        function myFun(result){
            var cityName = result.name;
            map.setCenter(cityName);
            //alert("当前定位城市:"+cityName);
        }
        var myCity = new BMap.LocalCity();
        myCity.get(myFun);

        var bs = map.getBounds();   //获取可视区域
        var bssw = bs.getSouthWest();   //可视区域左下角
        var bsne = bs.getNorthEast();   //可视区域右上角
        get_shop_list(bssw,bsne);


        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                map.panTo(r.point);
                //$.post("/index.php/User/set_place", { member_xx:r.point.lng,member_yy:r.point.lat});
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
        map.clearOverlays();//可以清楚标注

        map.enableScrollWheelZoom();   //启用滚轮放大缩小，默认禁用
        map.enableContinuousZoom();    //启用地图惯性拖拽，默认禁用

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

            $.post("<?php echo site_url('user/user_index/get_shop_list'); ?>", { min_xx: min_xx,min_yy: min_yy,max_xx: max_xx,max_yy: max_yy }, function (data) {
                var returndata=eval('(' + data + ')');
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


        function get_shop_content(shop_id,e){
            if(shop_id == ''){
                alert(shop_id);
            }else{
                alert(shop_id);
            }
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
            function myFun(){
                var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
                map.centerAndZoom(pp, 14);
                map.addOverlay(new BMap.Marker(pp));    //添加标注
            }
            var local = new BMap.LocalSearch(map, { //智能搜索
                onSearchComplete: myFun
            });
            local.search(myValue);
        }
    });


</script></html>