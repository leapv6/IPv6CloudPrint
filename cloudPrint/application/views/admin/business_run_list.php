<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/authority/jquery.fancybox-1.3.4.css" media="screen"></link>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/artDialog/artDialog.js?skin=default"></script>
<title>订单管理</title>
<script type="text/javascript">
	$(document).ready(function(){


	    /**编辑   **/
	    $("a.edit").fancybox({
	    	'width' : 733,
	        'height' : 530,
	        'type' : 'iframe',
	        'hideOnOverlayClick' : false,
	        'showCloseButton' : false,
	        'onClosed' : function() { 
	        	window.location.href = '<?php echo site_url('admin/business/get_run_info'); ?>';
	        }
	    });
	});
	/** 用户角色   **/
	var userRole = '';

	/** 模糊查  **/
	function search(){
        if($("#searchBusinessNick").val() !='' || $("#searchBusinessName").val() !='' || $("#searchPhone").val() !=''){
            $("#submitForm").attr("action", "get_run_info").submit();
        }
        else{
            alert("请输入查询条件！");
        }

	}

	/** 普通跳转 **/
	function jumpNormalPage(page){
        if(page<1 | page><?php echo ceil($all_page); ?>){
            art.dialog({icon:'error', title:'友情提示', drag:false, resize:false, content:'没有信息啦，\n自动为您跳到首页', ok:true,});
            page = 1;
        }
		$("#submitForm").attr("action", "get_run_info/" + page).submit();
	}
	
	/** 输入页跳转 **/
	function jumpInputPage(totalPage){
		// 如果“跳转页数”不为空
		if($("#jumpNumTxt").val() != '' ){
			var pageNum = parseInt($("#jumpNumTxt").val());
			// 如果跳转页数在不合理范围内，则置为1
			if(isNaN($("#jumpNumTxt").val()) || pageNum<1 || pageNum>totalPage){
				art.dialog({icon:'error', title:'友情提示', drag:false, resize:false, content:'请输入合适的页数，\n自动为您跳到首页', ok:true,});
				pageNum = 1;
			}
			$("#submitForm").attr("action", "get_run_info/" + pageNum).submit();
		}else{
			// “跳转页数”为空
			art.dialog({icon:'error', title:'友情提示', drag:false, resize:false, content:'请输入合适的页数，\n自动为您跳到首页', ok:true,});
			$("#submitForm").attr("action", "get_run_info/" + 1).submit();
		}
	}
</script>
<style>
	.alt td{ background:black !important;}
</style>
</head>
<body>
<?php  error_reporting(E_ALL ^ E_NOTICE); ?>
	<form id="submitForm" name="submitForm" action="" method="post">
		<input type="hidden" name="allIDCheck" value="" id="allIDCheck"/>
		<input type="hidden" name="fangyuanEntity.fyXqName" value="" id="fyXqName"/>
		<div id="container">
			<div class="ui_content">
				<div class="ui_text_indent">
					<div id="box_border">
						<div id="box_top">搜索</div>
						<div id="box_center">

							商家店铺名&nbsp;&nbsp;<input type="text" id="searchBusinessName" name="searchBusinessShopName" class="ui_input_txt02" />
                            商家姓名 &nbsp;&nbsp;<input type="text" id="searchBusinessNick" name="searchBusinessName" class="ui_input_txt02" />
                            手机号&nbsp;&nbsp;<input type="text" id="searchPhone" name="searchPhone" class="ui_input_txt02" />
						</div>
						<div id="box_bottom">
							<input type="button" value="查询" class="ui_input_btn01" onclick="search();" />

                        </div>
					</div>
				</div>
			</div>
			<div class="ui_content">
				<div class="ui_tb">
					<table class="table" cellspacing="0" cellpadding="0" width="100%" align="center" border="0">
						<tr>

							<th>商家店铺名</th>
							<th>负责人姓名</th>
                            <th>已完成订单</th>
                            <!--<th>未完成订单</th>-->
							<th>营业状态</th>
							<th>繁忙程度</th>
							<!--<th>服务星级</th>-->
                            <th>总收入金额</th>
							<th>操作</th>
						</tr>
                        <?php
                            for($i=0;$i<count($info)-1;$i++) {
                                ?>

                                <tr>
                                    <td><?php echo $info[$i]['shopName'];  ?></td>
                                    <td><?php echo $info[$i]['businessName'];  ?></td>
                                    <td><?php echo $info[$i]['finishOrders'];  ?></td>
                                    <!--<td><?php echo $info[$i]['unfinishOrders'];  ?></td>-->
                                    <td><?php echo $info[$i]['runStatus'] ? "<font color='green'>正常</font>" : "<font color='red'>休息</font>";?></td>
                                    <td><?php echo $info[$i]['busyStatus'] ? "<font color='red'>繁忙</font>" : "<font color='green'>正常</font>";  ?></td>
                                    <!--<td><?php
                                        switch($info[$i]['serveStar']){
                                            case 1 : echo "★"; break;
                                            case 2 : echo "★★"; break;
                                            case 3 : echo "★★★"; break;
                                            case 4 : echo "★★★★"; break;
                                            case 5 : echo "★★★★★"; break;

                                        }
                                        ?></td>-->
                                    <td><?php echo $info[$i]['totalIncome'] ;?></td>

                                    <td>
                                        <a href="<?php echo site_url('admin/business/run_edit'); ?>/<?php echo $info[$i]['businessId'];?>" class="edit">编辑详情</a>
                                    </td>
                                </tr>

                            <?php
                            }
                        ?>

						
					</table>
				</div>
				<div class="ui_tb_h30">
					<div class="ui_flt" style="height: 30px; line-height: 30px;">
						共有
						<span class="ui_txt_bold04"><?php echo $total;  ?></span>
						条记录，当前第
						<span class="ui_txt_bold04"><?php echo $now_page?>/<?php echo ceil($all_page); ?></span>
						页
					</div>
					<div class="ui_frt">
						<!--    如果是第一页，则只显示下一页、尾页 -->
						
							<input type="button" value="首页" class="ui_input_btn01"
                                onclick="jumpNormalPage(1)"/>
							<input type="button" value="上一页" class="ui_input_btn01"
                                onclick="jumpNormalPage(<?php echo $now_page-1; ?>)"/>
							<input type="button" value="下一页" class="ui_input_btn01"
								onclick="jumpNormalPage(<?php echo $now_page+1; ?>);" />
							<input type="button" value="尾页" class="ui_input_btn01"
								onclick="jumpNormalPage(<?php echo ceil($all_page); ?>);" />
						
						
						
						<!--     如果是最后一页，则只显示首页、上一页 -->
						
						转到第<input type="text" id="jumpNumTxt" class="ui_input_txt01" />页
							 <input type="button" class="ui_input_btn01" value="跳转" onclick="jumpInputPage(<?php echo ceil($all_page); ?>);" />
					</div>
				</div>
			</div>
		</div>
	</form>

</body>
</html>
