<html><head>
    <?php $this->load->helper('url'); ?>
    <meta charset="utf-8" /><title>中国在线打印第一品牌—v6网印</title>
    <meta name="keyword" content="在线打印,校园打印,校园自助打印,校园网络打印,网络打印,寝室打印,云打印,校园打印,大学校园打印"/>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>/images/favicon.ico" type="image/x-icon" />
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
    <script type="text/javascript" src="<?php echo base_url();?>scripts/layer/layer.js"></script>


    <script type="text/javascript">
        function conf_del(id){
            layer.confirm('删除文档后不可恢复，确定要删除么？', {
                btn: ['确定','取消'], //按钮
                shade: true, //不显示遮罩
                shade: [0.5, '#393D49'], //遮罩
                title : "删除",
                shift:3
            }, function(){
                $.ajax({
                    url : "<?php echo site_url('user/user_index/delete'); ?>",
                    data : {id:id},
                    type : "POST",
                    dataType : "json",
                    async:true,
                    success : function(data){
                        if(data.status == 1){
                            layer.msg("删除成功！",{time:1000});
                            parent.location.href = "<?php echo site_url('user/user_index/my_file') ?>"
                        }else{
                            var index = layer.load();
                            layer.msg("删除失败，请重试！",{time:1000});
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

        function check_file(){
            if(document.getElementById("file").value ==""){
                alert("请选择要打印的文件!");
            }else{
                document.getElementById("upload").submit();
            }
        }

        function upload_file(){
            layer.open({
                title:'上传文件',
                closeBtn: 2,
                type: 2,
                content: '<?php echo site_url('user/user_index/upload'); ?>',
                border : [5, 0.5, '#666'],
                area: ['1100px','500px'],
                shadeClose: true,
                cancel: function(){
                    location.href = "<?php echo site_url('user/user_index/my_file'); ?>";
                }

            });
        }

        //选择打印店
        function select_shop(docId){
            location.href = "<?php echo site_url('user/user_index/map_show'); ?>" + '/' +docId;

        }

        function forward_page(now,all){
            var forward = now -1;
            if(forward < 1){
                layer.msg('别点啦，这是首页了！');
            }else{
                location.href = "<?php echo site_url('user/user_index/my_file'); ?>" + "/"+forward;
            }
        }

        function next_page(now,all){
            var next = now +1;
            if(next > all){
                layer.msg('没有文档信息啦！');
            }else{
                location.href = "<?php echo site_url('user/user_index/my_file'); ?>" + "/"+next;
            }
        }

        function my_info(){
            layer.open({
                type: 2,
                title:'我的信息',
                content: '<?php echo site_url('user/user_index/my_info') ?>',
                border : [5, 0.5, '#666'],
                area: ['520px','400px'],
                shadeClose: true,
                cancel: function(){
                    location.href = "<?php echo site_url('user/user_index/my_file'); ?>";
                }
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

        function dologout(){
            layer.confirm('确定要退出么？', {
                btn: ['确定','取消'], //按钮
                shade: [0.5, '#393D49'], //遮罩
                title : "退出",
                shift:3
            }, function(){
                window.location.href = "<?php echo site_url('user/user_index/logout'); ?>";
            }, function(){

            });

        }
        </script>
</head>
<body class="g-doc">
<div class="g-hd m-hd m-hd-2"><a href="<?php echo base_url(); ?>" class="logo"></a>
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
                    <a target="_blank" class="f-ib toggle" href="#" >更多
                        <span class="triangle_down"></span></a><ul><li><a  >智慧·云家居</a></li><li>
                            <a  >智慧·云打印</a></li><li>
                            <a  >智慧·云洗衣</a></li></ul></dd></dl>
   </div>
<div class="g-bd f-cb" id="js_bd"><div class="g-sd1"><div class="m-menu-1">
            <a href="<?php echo site_url("user/user_index/my_order"); ?>" class="ico-1">我的订单</a>
            <a href="<?php echo site_url("user/user_index/my_file"); ?>" class="ico-2" data-step="3" data-intro="打印文件管理">新建打印</a>
            </div></div>
    <div class="g-mn1"><div class="g-mn1c"><div class="m-tt-1">                新建打印
            <span class="msg">* 平台完美支持 word、xls、ppt各版本和PDF文档打印 <font color="red">建议上传pdf格式文档，以免格式混乱,确保格式100%正确，同时可减少文档大小</font>

                    <div id="box" style="margin: 10px">
                        <a onclick="upload_file()"  class="u-img-btn u-img-btn-7">＋点击上传</a>
                    </div>

            </span>




        </div><table class="m-tb-1" id="file_list">
            <tbody><tr><th style="width: 46%;">文件名称</th>
                <th style="width: 29%;" class="f-tac">操作</th>
                <th style="width: 14%;">文档大小</th>
                <th style="width: 8%;">上传时间</th></tr></tbody>
        </table><div class="filelist" id="files_list_warp" style="height:470px;" data-step="5" data-intro="上传后的新文件会在这里显示,点击选择框选择要打印的文件">

                <?php
                    for($i=0;$i<count($info)-1;$i++) {
                        ?>

                        <div id="dataview1-wy-record-2009" data-record-id="wy-record-2009" class="list clear"
                             data-list="file" data-file-id="">

                    <span class="name"><p class="text">
                            <span class="img"><img src="<?php echo base_url(); ?>images/doc.png"></span>
                            <em title="<?php echo $info[$i]['docName']; ?>" data-name="file_name" id="title-wy-record-2009"><?php echo $info[$i]['docName']; ?>
                            </em></p></span>
                            <span class="tool">
                        <a tabindex="0" onclick="conf_del(<?php echo $info[$i]['docId'] ;?>)"  title="删除" class="link-del" data-action="delete"><i>删除</i></a>
                        <a tabindex="0" onclick="select_shop(<?php echo $info[$i]['docId'] ;?>)" title="打印" class="link-del" data-action="print"><i>打印</i></a>
                        <a tabindex="0" href="download/<?php echo $info[$i]['docId'] ;?>" title="下载" class="link-download" data-action="download"><i>下载</i></a>

                            </span>

                            <div tabindex="0" class="size"><?php echo $info[$i]['docSize']; ?>KB</div>
                            <div tabindex="0" class="time"><?php echo $info[$i]['docTime']; ?></div>
                            </span>
                        </div>

                    <?php
                    }
                ?>

            </div>

            <div>
                &nbsp;&nbsp;总共<?php echo $total; ?>记录，当前第<?php echo $now_page; ?>/<?php echo ceil($all_page); ?> 页
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div style="float: right;margin-right: 10px;">
                    <a onclick="forward_page(<?php echo $now_page; ?>,<?php echo ceil($all_page); ?>)" class="u-img-btn u-img-btn-7">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a onclick="next_page(<?php echo $now_page; ?>,<?php echo ceil($all_page); ?>)"  class="u-img-btn u-img-btn-7">下一页</a>
                </div>

            </div>
        </div>
        </div>

        </body>

</html>