<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><title>打印云-校园云打印|校园自助打印|在线打印|云打印|校园网络打印|网络打印|寝室打印|大学校园打印|中国在线打印第一品牌</title>
    <meta name="keywords" content="云打印,打印云,校园云打印,在线打印,校园打印,校园自助打印,校园网络打印,网络打印,寝室打印,大学校园打印">
    <meta name="description" content="打印云是恒泰科技云旗下系列产品之一,帮助用户实现网络资料打印,文件存储,文件共享,无需U盘,无需零钱,无需排队,自助提取,方便快捷."><meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="webkit" name="renderer">
    <link href="<?php echo base_url();?>style/common_all.css"  rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url();?>style/base.css"  rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>style/mian.css"  rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url();?>scripts/jquery-1.8.2.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer/layer.js" id = "layer"></script>
    <script type="text/javascript">
        function print_now(){
            if(!<?php if(isset($_SESSION['userPhone'])){
                echo 1;
            }else{echo 0;}
            ?>){
                layer.tips('请先登录哦！', '#poplogin', {
                    tips: [1, '#3595CC'],
                    time: 4000
                });
            }
            else{
                location.href="user/user_index";
            }
        }
    </script>
    <link rel="shortcut icon" type="image/x-icon" href="images/face.png" /></head>
<?php  error_reporting(E_ALL^E_NOTICE^E_WARNING);?>
<body class="g-doc"><div class="g-hd m-hd m-hd-1"><div class="w960 f-pr">
        <a href="index.php"  class="logo"></a>
        <dl class="u-nav f-ib"><dd>
                <?php if($this->session->userdata['userNick']){
                    echo '<a >'.$this->session->userdata['userNick'].'</a>';
                }else{?>
                <a id="poplogin" onclick="user_login()">登录</a></dd> <?php  }?>

            <dt>
            <dd><a id="reg" onclick="user_reg()">用户注册</a></dd> <dt></dt>
            <dd><a href="welcome/logout">退出</a></dd><dt></dt>
            <dd><a href="admin/admin_index">后台管理</a></dd><dt></dt>
            <dd><a href="business/index">商家登录</a></dd>
        </dl>


        <script>


            function user_reg(){
                layer.open({
                    type: 2,
                    title:'用户注册',
                    content: 'welcome/user_reg',
                    border : [5, 0.5, '#666'],
                    area: ['520px','450px'],
                    shadeClose: true,
                    closeBtn: [1, true]
                });
            }

            function user_login(){
                layer.open({
                    type: 2,
                    title:'用户登录',
                    content: 'welcome/user_login',
                    border : [5, 0.5, '#666'],
                    area: ['520px','300px'],
                    shadeClose: true,
                    closeBtn: [1, true]
                });
            }



	</script>

    </div>
</div>
<div class="g-bd">

    <div class="m-main"><div class="m-ct-1"><div class="w960 f-pr">
                <div class="u-msg"><h1 class="title">在线打印</h1>
                    <p class="f-wwb">不出门就能打印，网上支付，1角1张无需U盘，还不用排队哟！</p>
                    <a  id="printnow" onclick="print_now()" class="btn" title="云打印立刻打印">立刻打印</a>
                    <div class="msg"></div></div></div></div><div class="m-ct-2">
            <dl class="u-lst w960 f-cb"><dd><h2 class="title">上传附件</h2><p class="f-wwb">当前仅支持doc、docx或pdf格式文件</p>
                </dd><dt></dt><dd><h2 class="title">页面设置</h2>
                    <p class="f-wwb">设置打印份数，布局、装订方式</p></dd>
                <dt></dt
                    ><dd><h2 class="title">支付打印</h2>
                    <p class="f-wwb">通过支付宝简单充值，首充10元送1元哦</p></dd>
                <dt></dt><dd><h2 class="title">线下取单</h2>
                    <p class="f-wwb">打印完成后，去店铺免排队优先领取</p></dd></dl></div></div></div>
<div class="m-foot"><div class="w960"><div class="cprt"><div class="link">
                <a href="#"  target="_blank">新手指南</a><span>|</span>
                <a href="#"  target="_blank">打印店指南</a><span>|</span>
                <a href="#"  target="_blank">文档分享协议</a><span>|</span>
                <a href="#"  target="_blank">用户注册协议</a><span>|</span>
                <a href="#"  target="_blank">打印店加盟协议</a><span>|</span>
                <a href="#"  target="_blank" title="在线打印店商家列表">在线打印商家列表</a><span>|</span>
                <a href="welcome/shop_reg" style="color:red;" target="_blank">商家入驻</a><span>|</span>
                <a href="" style="color:red;" target="_blank">校园文库</a><span>|</span>
                </div>
            <div class="msg">				打印云&copy;2012&nbsp;&nbsp;&nbsp;&nbsp; 安信中保网络科技有限公司&nbsp;&nbsp; 陕ICP备11001048号-1&nbsp;&nbsp;
				<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1253029175'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/stat.php%3Fid%3D1253029175' type='text/javascript'%3E%3C/script%3E"));
                </script></div></div></div></div></body>
</html>
