<html>
<head>
    <meta charset="utf-8"/>
    <title>中国在线打印第一品牌—v6网印</title>
    <meta name="keyword" content="在线打印,校园打印,校园自助打印,校园网络打印,网络打印,寝室打印,云打印,校园打印,大学校园打印"/>
    <meta name="description" content="打印云是安信中保网络科技公司旗下系列产品之一,帮助用户实现网络资料打印,文件存储,文件共享,无需U盘,无需零钱,无需排队,自助提取,方便快捷。"/>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/images/favicon.ico" type="image/x-icon"/>
    <link href="<?php echo base_url(); ?>style/common_all.css" rel="stylesheet"/>
    <link href="<?php echo base_url(); ?>style/base.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>style/mian.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js">
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id="layer"></script>
    <style>.datalist:hover {
            background: #f3f8ff;
        }
    </style>
    <script type="text/javascript">
        function conf_dele(id) {
            layer.confirm('删除后不可恢复，确定要删除么？', {
                btn: ['确定', '取消'], //按钮
                shade: [0.5, '#393D49'], //遮罩
                title: "删除",
                shift: 3
            }, function () {
                $.ajax({
                    url: "<?php echo site_url('user/user_index/del_order'); ?>",
                    data: {orderId: id},
                    type: "POST",
                    dataType: "json",
                    async: true,
                    success: function (data) {
                        if (data.status == 1) {
                            parent.location.href = "<?php echo site_url('user/user_index/index') ?>"
                            layer.msg("删除成功！", {time: 1000});

                        } else {
                            var index = layer.load();
                            layer.msg("已完成订单不能删除！", {time: 1500, shift: 6});
                            layer.close(index);

                        }

                    },
                    beforeSend: function () {
                        layer.load('加载中…');
                    }
                });
            }, function () {

            });
        }

        function judge(orderId) {
            $.ajax({
                url: "<?php echo site_url('user/user_index/check_print_status'); ?>",
                data: {orderId: orderId},
                type: "POST",
                dataType: "json",
                async: true,
                success: function (data) {
                    var index = layer.load();
                    layer.close(index);
                    if (data.status == 1) {
                        layer.open({
                            type: 2,
                            title: '评价',
                            content: '<?php echo site_url('user/user_index/judge') ?>' + '/' + orderId,
                            border: [5, 0.5, '#666'],
                            area: ['480px', '380px'],
                            shadeClose: true
                        });

                    } else {
                        var index = layer.load();
                        layer.close(index);
                        layer.msg("未完成订单还不能评论哦！", {time: 1500, shift: 6});

                    }

                },
                beforeSend: function () {
                    layer.load('加载中…');
                }
            });


        }

        function wx_pay(orderId) {
            $.ajax({
                url: "<?php echo site_url('user/user_index/check_pay'); ?>",
                data: {orderId: orderId},
                type: "POST",
                dataType: "json",
                async: false,
                success: function (data) {
                    var index = layer.load();
                    layer.close(index);
                    if (data.status == 1) {
                        layer.msg("订单已经支付了哦，请等待打印！", {time: 2000});

                    } else {
                        layer.open({
                            type: 2,
                            title: false,
                            content: '<?php echo site_url("user/user_index/show_qrcode") ?>' + '/' + orderId,
                            border: [5, 0.5, '#666'],
                            area: ['520px', '500px'],
                            shadeClose: true
                        });
                    }

                },
                beforeSend: function () {
                    layer.load('加载中…');
                }
            });

        }

        function my_info() {
            layer.open({
                type: 2,
                title: '我的信息',
                content: '<?php echo site_url('user/user_index/my_info') ?>',
                border: [5, 0.5, '#666'],
                area: ['520px', '400px'],
                shadeClose: true,
                cancel: function () {
                    location.href = "<?php echo site_url('user/user_index/index'); ?>";
                }
            });
        }

        function change_psw() {
            layer.open({
                type: 2,
                title: '修改密码',
                content: '<?php echo site_url('user/user_index/change_psw') ?>',
                border: [5, 0.5, '#666'],
                area: ['520px', '350px'],
                shadeClose: true
            });
        }
    </script>
</head>
<body class="g-doc">
<?php error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); ?>
<?php $this->load->helper('url'); ?>
<div class="g-hd m-hd m-hd-2"><a href="<?php echo base_url(); ?>" class="logo"></a>
    <dl class="u-nav f-ib">
        <dd><input type="hidden" id="infoCreidt" value="0.00">
            <div class="f-cb u-user"><a href="#" class="pic f-fl" target="_blank">
                    <img src="<?php echo base_url(); ?>images/face.png" alt=""></a>
                <a href="#" class="name f-fl f-toe"
                   title="<?php echo $this->session->userdata('userNick'); ?>"><?php echo $this->session->userdata('userNick'); ?></a>
                <span class="triangle_down"></span>
                <div class="menu">
                    <a href="<?php echo site_url('user/user_index/my_order') ?>">我的订单</a>
                    <a onclick="my_info()">我的信息</a>
                    <a onclick="change_psw()">修改密码</a>
                    <a href="javascript:;" onclick="dologout();return false;">退出</a>
                </div>
            </div>
        </dd>
        <dt style="margin-left: 5px;"></dt>
        <dd>账户余额： <b style="color: #cc3300"><span id="UserCreditMoney">0.00</span>元</b></dd>
        <dt></dt>
        <dd>
            <a id="btnRecharge">充值</a></dd><!--<dt></dt><dd><a>余额提现</a></dd>-->
        <dt style="padding-right: 5px;"></dt>
        <dd class="u-menu">
            <a target="_blank" class="f-ib toggle" href="#">更多
                <span class="triangle_down"></span></a>
            <ul>
                <li><a href="#">小猪路由</a></li>
                <li>
                    <a href="#">智慧·云打印</a></li>
                <li>
                    <a href="#">智慧·云洗衣</a></li>
            </ul>
        </dd>
    </dl>
    <script>
        function dologout() {
            layer.confirm('确定要退出么？', {
                btn: ['确定', '取消'], //按钮
                shade: [0.5, '#393D49'], //遮罩
                title: "退出",
                shift: 3
            }, function () {
                window.location.href = "<?php echo site_url('user/user_index/logout'); ?>";
            }, function () {

            });

        }

        function forward_page(now, all) {
            var forward = now - 1;
            if (forward < 1) {
                layer.msg('别点啦，这是首页了！');
            } else {
                location.href = "<?php echo site_url('user/user_index/my_order'); ?>" + "/" + forward;
            }
        }

        function next_page(now, all) {
            var next = now + 1;
            if (next > all) {
                layer.msg('没有订单信息啦！');
            } else {
                location.href = "<?php echo site_url('user/user_index/my_order'); ?>" + "/" + next;
            }
        }

    </script>
</div>
<div class="g-bd f-cb" id="js_bd">
    <div class="g-sd1">
        <div class="m-menu-1">
            <a href="<?php echo site_url("user/user_index/my_order"); ?>" class="ico-1">我的订单</a>
            <a href="<?php echo site_url("user/user_index/my_file"); ?>" class="ico-2" data-step="3"
               data-intro="打印文件管理">新建打印</a>
        </div>
    </div>
    <div class="g-mn1">
        <div class="g-mn1c">
            <div class="m-tt-1"> 我的订单
                <small data-step="1" data-intro="首先通过邮箱验证!">邮箱地址：
                            <span id="member_tel_txt"><?php echo $email; ?><a style="color:green;">&nbsp;&nbsp;已绑定</a>
                </small>
                </span>
                <small>
                    我的手机：
                        <span id="member_tel_txt"><?php echo $this->session->userdata('userPhone'); ?>
                        </span>
                </small>
                <small>
                    <span style="color: blueviolet"><?php echo $bnick; ?></span>
                </small>

                <a href="<?php echo site_url("user/user_index/my_file"); ?>" class="u-img-btn u-img-btn-1" data-step="2"
                   data-intro="点击新建打印!">新建打印</a>
            </div>
            <div style="height:500px;">
                <table class="m-tb-1">
                    <tr>
                        <th style="width: 10%; text-align:center;">订单号</th>
                        <th style="width: 15%; text-align:center;">下单时间</th>
                        <th style="width: 15%; text-align:center;">文件名</th>
                        <th style="width: 10%; text-align:center;" class="f-tac">店铺名</th>
                        <th style="width: 10%; text-align:center;" class="f-tac">总价（元）</th>
                        <th style="width: 15%; text-align:center;" class="f-tac">支付状态</th>
                        <th style="width: 15%; text-align:center;" class="f-tac">打印状态</th>
                        <th style="width: 15%; text-align:center;" class="f-tac">操作</th>
                    </tr>

                    <?php for ($i = 0; $i < count($info) - 1; $i++) { ?>
                        <tr>
                            <th style="width: 5%; text-align:center;"><?php echo $info[$i]['orderId']; ?></th>
                            <th style="width: 15%; text-align:center;"><?php echo $info[$i]['makeTime']; ?></th>
                            <th style="width: 15%; text-align:center;"><font
                                    color="blue"><?php echo $info[$i]['docName']; ?></font></th>
                            <th style="width: 10%; text-align:center;"><?php echo $info[$i]['shopName']; ?></th>
                            <th style="width: 10%; text-align:center;"><?php echo $info[$i]['price']; ?></th>
                            <th style="width: 5%; text-align:center;"><?php echo $info[$i]['payStatus'] ? "<font color='green'>已支付</font>" : "<font color='red'>未支付</font>"; ?></th>
                            <th style="width: 15%; text-align:center;"><?php echo $info[$i]['printStatus'] ? "<font color='green'>完成打印</font>" : "<font color='red'>等待打印</font>"; ?></th>
                            <th style="width: 15%; text-align:center;">
                                <img title="微信支付" onclick="wx_pay(<?php echo $info[$i]['orderId']; ?>)"
                                     style="margin-bottom:-4px;cursor: pointer" width='20px' height='20px' src="<?php echo base_url()?>images/pay_pic.png">
                                <a onclick="judge(<?php echo $info[$i]['orderId']; ?>)">评价</a>
                                <a onclick="conf_dele(<?php echo $info[$i]['orderId']; ?>)">删除</a>
                            </th>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            &nbsp;&nbsp;总共<?php echo $total; ?>记录，当前第<?php echo $now_page; ?>/<?php echo ceil($all_page); ?> 页

            <div style="float: right;margin-right: 10px;">
                <a onclick="forward_page(<?php echo $now_page; ?>,<?php echo ceil($all_page); ?>)"
                   class="u-img-btn u-img-btn-7">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a onclick="next_page(<?php echo $now_page; ?>,<?php echo ceil($all_page); ?>)"
                   class="u-img-btn u-img-btn-7">下一页</a>
            </div>


            <div style="margin-top: 10px; margin-right: 10px;"></div>
        </div>
    </div>
</div>

</body>
</html>