<!DOCTYPE html>
<html>
<head>
<title>后台管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <link href="<?php echo base_url(); ?>style/authority/basic_layout.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>style/authority/common_style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>style/authority/jquery.fancybox-1.3.4.css" media="screen"></link>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/artDialog/artDialog.js?skin=default"></script>
<script type="text/javascript">
	$(document).ready(function() {
		/*
		 * 提交
		 */
		/*$("#submitbutton").click(function() {
			if(validateForm()){
				checkFyFhSubmit();
			}
		});
		
		/*
		 * 取消
		 */
		$("#cancelbutton").click(function() {
			/**  关闭弹出iframe  **/
			window.parent.$.fancybox.close();
		});
		
		var result = 'null';
		if(result =='success'){
			/**  关闭弹出iframe  **/
			window.parent.$.fancybox.close();
		}
	});

</script>
</head>
<body>
<form id="submitForm" name="submitForm" action="add_business" method="post">
	<input type="hidden" name="fyID" value="14458625716623" id="fyID"/>
	<div id="container">
		<div id="nav_links">
			当前位置：新增商户&nbsp;>&nbsp;<span style="color: #1A5CC6;">商户信息编辑</span>
			<div id="page_close">
				<a href="javascript:parent.$.fancybox.close();">
					<img src="/images/common/page_close.png" width="20" height="20" style="vertical-align: text-top;"/>
				</a>
			</div>
		</div>
		<div class="ui_content">
			<table  cellspacing="0" cellpadding="0" width="100%" align="left" border="0">
				<tr>
					<td class="ui_text_rt" width="120">商户用户名：</td>
                    <td class="ui_text_lt">
                        <input type="text" id="businessNick" name="businessNick" value=""  style="width:120px;" class="ui_input_txt01"/>
                    </td>
				</tr>
                <tr>
                    <td class="ui_text_rt" width="80">密码：</td>
                    <td class="ui_text_lt">
                        <input type="text" id="businessPsw" name="businessPsw" value=""  style="width:120px;" class="ui_input_txt01"/>
                    </td>
                </tr>
                <tr>
                    <td class="ui_text_rt" width="80">手机号：</td>
                    <td class="ui_text_lt">
                        <input type="text" id="businessPhone" name="businessPhone" value=""  style="width:120px;" class="ui_input_txt01"/>
                    </td>
                </tr>
                <tr>
                    <td class="ui_text_rt" width="120">负责人姓名：</td>
                    <td class="ui_text_lt">
                        <input type="text" id="businessName" name="businessName" value=""  style="width:120px;" class="ui_input_txt01"/>
                    </td>
                </tr>
                <tr>
                    <td class="ui_text_rt" width="80">邮箱：</td>
                    <td class="ui_text_lt">
                        <input type="text" id="businessEmail" name="businessEmail" value=""  style="width:120px;" class="ui_input_txt01"/>
                    </td>
                </tr>
                <tr>
                    <td class="ui_text_rt" width="80">地址：</td>
                    <td class="ui_text_lt">
                        <input type="text" id="businessAddress" name="businessAddress" value=""  style="width:120px;" class="ui_input_txt01"/>
                    </td>
                </tr>



				<tr>
					<td>&nbsp;</td>
					<td class="ui_text_lt">
						&nbsp;<input id="submitbutton" type="submit" value="提交" class="ui_input_btn01"/>
						&nbsp;<input id="cancelbutton" type="button" value="取消" class="ui_input_btn01"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>

</body>
</html>