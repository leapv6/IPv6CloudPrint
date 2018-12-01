<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="云打印,打印云,校园云打印,在线打印,校园打印,校园自助打印,校园网络打印,网络打印,寝室打印,大学校园打印">
<title>v6网印</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/images/favicon.ico" type="image/x-icon" />
<link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
<link href="<?php echo base_url(); ?>style/index.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url();?>scripts/jquery-1.8.2.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer/layer.js" id = "layer"></script>
    <script type="text/javascript">
        function print_now(){
            if(!<?php if(isset($_SESSION['userPhone'])){
                echo 1;
            }else{echo 0;}
            ?>){
                layer.tips('请先登录哦！', '#login', {
                    tips: [1, '#3595CC'],
                    time: 4000
                });
            }
            else{
                location.href="user/user_index";
            }
        }

        function user_reg(){
            layer.open({
                type: 2,
                title: false,
                content: 'welcome/user_reg',
                area: ['500px','605px'],
                shadeClose: true
            });
        }

        function user_login(){
            layer.open({
                type: 2,
                title: false,
                content: 'welcome/user_login',
                area: ['478px','578px'],
                shadeClose: true
            });
        }

        function logout(){
            layer.confirm('确定要退出么？', {
                btn: ['确定','取消'], //按钮
                shade: [0.5, '#393D49'], //不显示遮罩
                title : "退出",
                shift:3
            }, function(){
                $.ajax({
                    url : "<?php echo site_url('welcome/logout') ?>",
                    data : "",
                    type : "POST",
                    dataType : "json",
                    async:true,
                    success : function(data){
                        if(data.status == 1){
                            parent.location.href = "<?php echo base_url(); ?>";
                            layer.msg("退出成功！",{time:1000});
                        }else{
                            var index = layer.load();
                            layer.msg("退出失败，请重试！",{time:1000});
                            layer.close(index);
                        }

                    },
                    beforeSend:function(){
                        layer.load('加载中…');
                    }
                });
            }, function(){

            });

        }

    </script>
</head>
<?php  error_reporting(E_ALL^E_NOTICE^E_WARNING);?>

<body>
<div id="header">
    <a href="index.php"  class="logo"><div id="logo"></div></a>
    <div id="menu">
        <?php if($this->session->userdata['userNick']){
            echo '<a href="user/user_index">'.$this->session->userdata['userNick'].'</a>';
        }else{?>
                <a id="login" onclick="user_login();">登录</a> <?php  }?>|

        <a id="reg" onclick="user_reg();" >用户注册</a> | <a href="business/index">商家登录</a> | <a href="admin/admin_index">后台登录</a> | <a onclick="logout();" class="logout">退出</a></font>
    </div>
</div>

<div id="content">
    <div class="conbg">
     	<a href="#" onclick="print_now()" class="print_btn"></a>
        <div class="remark">更快，更好，更省！</div>
    </div>

</div>

<div id="step">
    <div class="box">
        <img src="<?php echo base_url(); ?>images/1.png" />
        <h1>上传附件</h1>
        <p>当前支持doc,pdf,xls等主流格式</p>
    </div>
    <div class="box">
        <img src="<?php echo base_url(); ?>images/2.png" />
        <h1>页面设置</h1>
        <p>设置打印参数，份数</p>
    </div>
    <div class="box">
        <img src="<?php echo base_url(); ?>images/3.png" />
        <h1>支付打印</h1>
        <p>通过微信简单充值</p>
    </div>
    <div class="box">
        <img src="<?php echo base_url(); ?>images/4.png" />
        <h1>线下取单</h1>
        <p>打印完成后去店铺免排队优先领取</p>
    </div>
</div>
    
<div id="footer"> 
    <p class="footer_nav">
        <a href="<?php echo site_url('welcome/fresherGuide'); ?>">新手指南</a>  |  <a href="<?php echo site_url('welcome/shopGuide'); ?>">商家指南</a>  |
        <a href="<?php echo site_url('welcome/userProt'); ?>">用户注册协议 </a>| <a href="<?php echo site_url('welcome/joinProt'); ?>"> 打印店加盟协议</a>  | <a href="welcome/shop_enter" class="red"> 商家入驻</a> |
        <a href="<?php echo site_url('welcome/schoolSpread'); ?>" class="red">校园推广</a> |<a href="<?php echo site_url('welcome/download_client'); ?>"> 客户端下载</a> </p>
    <p class="copyright">V6网印 &copy; 2016 <!--重庆威陆网络科技有限公司-->
        <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1256700729'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1256700729%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
    </p>
</div>

</body>
</html>
