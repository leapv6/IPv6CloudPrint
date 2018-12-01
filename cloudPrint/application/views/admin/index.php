<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>云打印-后台系统</title>
	<link href="<?php echo base_url(); ?>style/authority/main_css.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url(); ?>style/authority/zTreeStyle.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/zTree/jquery.ztree.core-3.2.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/authority/commonAll.js"></script>
	<script type="text/javascript">
		/**退出系统**/
		function logout(){
			if(confirm("您确定要退出本系统吗？")){
				window.location.href = "<?php echo site_url('admin/admin_index/logout'); ?>";
			}
		}
		
		/**获得当前日期**/
		function  getDate01(){
			var time = new Date();
			var myYear = time.getFullYear();
			var myMonth = time.getMonth()+1;
			var myDay = time.getDate();
			if(myMonth < 10){
				myMonth = "0" + myMonth;
			}
			document.getElementById("yue_fen").innerHTML =  myYear + "." + myMonth;
			document.getElementById("day_day").innerHTML =  myYear + "." + myMonth + "." + myDay;
		}
		
		/**加入收藏夹**/
		function addfavorite(){
			var ua = navigator.userAgent.toLowerCase();
			 if (ua.indexOf("360se") > -1){
			  	art.dialog({icon:'error', title:'友情提示', drag:false, resize:false, content:'由于360浏览器功能限制，加入收藏夹功能失效', ok:true,});
			 }else if (ua.indexOf("msie 8") > -1){
			  	window.external.AddToFavoritesBar('${dynamicURL}/authority/loginInit.action','云打印管理平台');//IE8
			 }else if (document.all){
			  	window.external.addFavorite('${dynamicURL}/authority/loginInit.action','云打印管理平台');
			 }else{
			  	art.dialog({icon:'error', title:'友情提示', drag:false, resize:false, content:'添加失败，请用ctrl+D进行添加', ok:true,});
			 }
		} 
	</script>
	<script type="text/javascript">
		/* zTree插件加载目录的处理  */
		var zTree;
		
		var setting = {
				view: {
					dblClickExpand: false,
					showLine: false,
					expandSpeed: ($.browser.msie && parseInt($.browser.version)<=6)?"":"fast"
				},
				data: {
					key: {
						name: "resourceName"
					},
					simpleData: {
						enable:true,
						idKey: "resourceID",
						pIdKey: "parentID",
						rootPId: ""
					}
				},
				callback: {
	// 				beforeExpand: beforeExpand,
	// 				onExpand: onExpand,
					onClick: zTreeOnClick			
				}
		};
		 
		var curExpandNode = null;
		function beforeExpand(treeId, treeNode) {
			var pNode = curExpandNode ? curExpandNode.getParentNode():null;
			var treeNodeP = treeNode.parentTId ? treeNode.getParentNode():null;
			for(var i=0, l=!treeNodeP ? 0:treeNodeP.children.length; i<l; i++ ) {
				if (treeNode !== treeNodeP.children[i]) {
					zTree.expandNode(treeNodeP.children[i], false);
				}
			}
			while (pNode) {
				if (pNode === treeNode) {
					break;
				}
				pNode = pNode.getParentNode();
			}
			if (!pNode) {
				singlePath(treeNode);
			}
	
		}
		function singlePath(newNode) {
			if (newNode === curExpandNode) return;
			if (curExpandNode && curExpandNode.open==true) {
				if (newNode.parentTId === curExpandNode.parentTId) {
					zTree.expandNode(curExpandNode, false);
				} else {
					var newParents = [];
					while (newNode) {
						newNode = newNode.getParentNode();
						if (newNode === curExpandNode) {
							newParents = null;
							break;
						} else if (newNode) {
							newParents.push(newNode);
						}
					}
					if (newParents!=null) {
						var oldNode = curExpandNode;
						var oldParents = [];
						while (oldNode) {
							oldNode = oldNode.getParentNode();
							if (oldNode) {
								oldParents.push(oldNode);
							}
						}
						if (newParents.length>0) {
							for (var i = Math.min(newParents.length, oldParents.length)-1; i>=0; i--) {
								if (newParents[i] !== oldParents[i]) {
									zTree.expandNode(oldParents[i], false);
									break;
								}
							}
						}else {
							zTree.expandNode(oldParents[oldParents.length-1], false);
						}
					}
				}
			}
			curExpandNode = newNode;
		}
	
		function onExpand(event, treeId, treeNode) {
			curExpandNode = treeNode;
		}
		
		/** 用于捕获节点被点击的事件回调函数  **/
		function zTreeOnClick(event, treeId, treeNode) {
			var zTree = $.fn.zTree.getZTreeObj("dleft_tab1");
			zTree.expandNode(treeNode, null, null, null, true);
	// 		zTree.expandNode(treeNode);
			// 规定：如果是父类节点，不允许单击操作
			if(treeNode.isParent){
	// 			alert("父类节点无法点击哦...");
				return false;
			}
			// 如果节点路径为空或者为"#"，不允许单击操作
			if(treeNode.accessPath=="" || treeNode.accessPath=="#"){
				//alert("节点路径为空或者为'#'哦...");
				return false;
			}
		    // 跳到该节点下对应的路径, 把当前资源ID(resourceID)传到后台，写进Session
		    rightMain(treeNode.accessPath);
		    
		    if( treeNode.isParent ){
			    $('#here_area').html('当前位置：'+treeNode.getParentNode().resourceName+'&nbsp;>&nbsp;<span style="color:#1A5CC6">'+treeNode.resourceName+'</span>');
		    }else{
			    $('#here_area').html('当前位置：系统&nbsp;>&nbsp;<span style="color:#54c1f3">'+treeNode.resourceName+'</span>');
		    }
		};
		
		/* 上方菜单 */
		function switchTab(tabpage,tabid){
		var oItem = document.getElementById(tabpage).getElementsByTagName("li"); 
		    for(var i=0; i<oItem.length; i++){
		        var x = oItem[i];    
		        x.className = "";
			}
			if('left_tab1' == tabid){
				$(document).ajaxStart(onStart).ajaxSuccess(onStop);
				// 异步加载"业务模块"下的菜单
			  	loadMenu('yewu', 'dleft_tab1');
			}else  if('left_tab2' == tabid){
				$(document).ajaxStart(onStart).ajaxSuccess(onStop);
				// 异步加载"系统管理"下的菜单
				loadMenu('xitong', 'dleft_tab1');
			}else  if('left_tab3' == tabid){
				$(document).ajaxStart(onStart).ajaxSuccess(onStop);
				// 异步加载"其他"下的菜单
				loadMenu('qita', 'dleft_tab1');
			} 
		}
		
		
		$(document).ready(function(){
			$(document).ajaxStart(onStart).ajaxSuccess(onStop);
			/** 默认异步加载"业务模块"目录  **/
			loadMenu('yewu', "dleft_tab1");
			// 默认展开所有节点
			if( zTree ){
				// 默认展开所有节点
				zTree.expandAll(true);
			}
		});
		
		function loadMenu(resourceType, treeObj){
			/*$.ajax({
				type:"POST",
				url:"${dynamicURL}/authority/modelPart.action?resourceType=" + resourceType,
				dataType : "json",
				success:function(data){
					// 如果返回数据不为空，加载"业务模块"目录
					if(data != null){
						// 将返回的数据赋给zTree
						$.fn.zTree.init($("#"+treeObj), setting, data);
 						alert(treeObj);
						zTree = $.fn.zTree.getZTreeObj(treeObj);
						if( zTree ){
							// 默认展开所有节点
							zTree.expandAll(true);
						}
					}
				}
			});*/
            data1 = [{"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":3,"resourceName":"用户管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/user_info'); ?>","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":7,"resourceName":"账户信息","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/order_manage'); ?>","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":8,"resourceName":"订单管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/comment_manage'); ?>","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":32,"resourceName":"客户评价","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/file_manage'); ?>","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":35,"resourceName":"文档管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":5,"resourceName":"商户管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/business_info'); ?>","checked":false,"delFlag":0,"parentID":5,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":17,"resourceName":"商户账户信息","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/business_run_info'); ?>","checked":false,"delFlag":0,"parentID":5,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":52,"resourceName":"商户营业状态","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":7,"resourceName":"管理员账户管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"<?php echo site_url('admin/business/change_psw'); ?>","checked":false,"delFlag":0,"parentID":7,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":38,"resourceName":"修改密码","resourceOrder":0,"resourceType":""}];

            /*data2 = [{"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":3,"resourceName":"网站管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"index","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":7,"resourceName":"网站信息配置","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":8,"resourceName":"网站备份","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":32,"resourceName":"数据导出","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":5,"resourceName":"信息获取","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":5,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":17,"resourceName":"网站流量统计","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":5,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":52,"resourceName":"商户统计","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":7,"resourceName":"广告链接","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":7,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":38,"resourceName":"友情链接","resourceOrder":0,"resourceType":""}];

            data3 = [{"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":3,"resourceName":"内容管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"index","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":7,"resourceName":"添加内容","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":3,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":8,"resourceName":"新闻管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":5,"resourceName":"栏目管理","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":5,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":17,"resourceName":"添加栏目","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":5,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":52,"resourceName":"修改栏目信息","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":1,"resourceCode":"","resourceDesc":"","resourceGrade":2,"resourceID":7,"resourceName":"返回","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":7,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":38,"resourceName":"返回首页","resourceOrder":0,"resourceType":""},
                {"accessPath":"","checked":false,"delFlag":0,"parentID":7,"resourceCode":"","resourceDesc":"","resourceGrade":3,"resourceID":39,"resourceName":"安全退出","resourceOrder":0,"resourceType":""}];*/
            // 如果返回数据不为空，加载"业务模块"目录
            if(data1 != null ){
                // 将返回的数据赋给zTree
                if(resourceType == 'yewu'){
                    $.fn.zTree.init($("#"+treeObj), setting, data1);
                }
                else if(resourceType == 'xitong'){
                    $.fn.zTree.init($("#"+treeObj), setting, data2);
                }
                else if(resourceType == 'qita') {
                    $.fn.zTree.init($("#" + treeObj), setting, data3);
                }
                zTree = $.fn.zTree.getZTreeObj(treeObj);
                if( zTree ){
                    // 默认展开所有节点
                    zTree.expandAll(true);
                }
            }
		}
		
		//ajax start function
		function onStart(){
			$("#ajaxDialog").show();
		}
		
		//ajax stop function
		function onStop(){
	// 		$("#ajaxDialog").dialog("close");
			$("#ajaxDialog").hide();
		}
	</script>
</head>
<body onload="getDate01()">
    <div id="top">
		<div id="top_logo">
			<a href="<?php echo base_url(); ?>"><img alt="logo" src="<?php echo base_url(); ?>images/common/logo.png" width="181" height="58" style="vertical-align:middle;"></a>
		</div>
		<div id="top_links">
			<div id="top_op">
				<ul>
					<li>
						<img alt="当前用户" src="<?php echo base_url(); ?>images/common/user.jpg">：
						<span><?php echo $this->session->userdata('adminName'); ?></span>
					</li>
					<li>
						<img alt="事务月份" src="<?php echo base_url(); ?>images/common/month.jpg">：
						<span id="yue_fen"></span>
					</li>
					<li>
						<img alt="今天是" src="<?php echo base_url(); ?>images/common/date.jpg">：
						<span id="day_day"></span>
					</li>
				</ul> 
			</div>
			<div id="top_close">
				<a href="javascript:void(0);" onclick="logout();" target="_parent">
					<img alt="退出系统" title="退出系统" src="<?php echo base_url(); ?>images/common/close.jpg" style="position: relative; top: 10px; left: 25px;">
				</a>
			</div>
		</div>
	</div>
    <!-- side menu start -->
	<div id="side">
		<div id="left_menu">
		 	<ul id="TabPage2" style="height:200px; margin-top:50px;">
				<li id="left_tab1" class="selected" onClick="javascript:switchTab('TabPage2','left_tab1');" title="功能模块">
					<img alt="功能模块" title="功能模块" src="<?php echo base_url(); ?>images/common/hover.png" width="41" height="34" style="margin-left: 0">
				</li>
				<!--<li id="left_tab2" onClick="javascript:switchTab('TabPage2','left_tab2');" title="系统管理">
					<img alt="系统管理" title="系统管理" src="/images/common/2.jpg" width="33" height="31">
				</li>		
				<li id="left_tab3" onClick="javascript:switchTab('TabPage2','left_tab3');" title="其他">
					<img alt="其他" title="其他" src="/images/common/3.jpg" width="33" height="31">
				</li>-->
			</ul>
			
			
			<div id="nav_show" style="position:absolute; bottom:0px; padding:10px;">
				<a href="javascript:;" id="show_hide_btn">
					<img alt="显示/隐藏" title="显示/隐藏" src="<?php echo base_url(); ?>images/common/nav_hide.png" width="35" height="35">
				</a>
			</div>
		 </div>
		 <div id="left_menu_cnt">
		 	<div id="nav_module">
		 		<img src="<?php echo base_url(); ?>images/common/module_1.png" width="210" height="58"/>
		 	</div>
		 	<div id="nav_resource">
		 		<ul id="dleft_tab1" class="ztree"></ul>
		 	</div>
		 </div>
	</div>
	<script type="text/javascript">
		$(function(){
			$('#TabPage2 li').click(function(){
				var index = $(this).index();
				$(this).find('img').attr('src', '<?php echo base_url(); ?>images/common/'+ (index+1) +'_hover.png');
				$(this).css({background:'#fff'});
				$('#nav_module').find('img').attr('src', '<?php echo base_url(); ?>images/common/module_'+ (index+1) +'.png');
				$('#TabPage2 li').each(function(i, ele){
					if( i!=index ){
						$(ele).find('img').attr('src', '<?php echo base_url(); ?>images/common/'+ (i+1) +'.jpg');
						$(ele).css({background:'#044599'});
					}
				});
				// 显示侧边栏
				switchSysBar(true);
			});
			
			// 显示隐藏侧边栏
			$("#show_hide_btn").click(function() {
		        switchSysBar();
		    });
		});
		
		/**隐藏或者显示侧边栏**/
		function switchSysBar(flag){
			var side = $('#side');
	        var left_menu_cnt = $('#left_menu_cnt');
			if( flag==true ){	// flag==true
				left_menu_cnt.show(500, 'linear');
				side.css({width:'280px'});
				$('#top_nav').css({width:'77%', left:'304px'});
	        	$('#main').css({left:'280px'});
			}else{
		        if ( left_menu_cnt.is(":visible") ) {
					left_menu_cnt.hide(10, 'linear');
					side.css({width:'60px'});
		        	$('#top_nav').css({width:'100%', left:'60px', 'padding-left':'28px'});
		        	$('#main').css({left:'60px'});
		        	$("#show_hide_btn").find('img').attr('src', '<?php echo base_url(); ?>images/common/nav_show.png');
		        } else {
					left_menu_cnt.show(500, 'linear');
					side.css({width:'280px'});
					$('#top_nav').css({width:'77%', left:'304px', 'padding-left':'0px'});
		        	$('#main').css({left:'280px'});
		        	$("#show_hide_btn").find('img').attr('src', '<?php echo base_url(); ?>images/common/nav_hide.png');
		        }
			}
		}
	</script>
    <!-- side menu start -->
    <div id="top_nav">
	 	<span id="here_area" >当前位置：系统&nbsp;>&nbsp;<font style="color:#54c1f3">系统介绍</font></span>
	</div>
    <div id="main">
      	<iframe name="right" id="rightMain" src="<?php echo site_url('admin/admin_index/system_introduce'); ?>" frameborder="no" scrolling="auto" width="100%" height="100%" allowtransparency="true"/>
    </div>

</body>
</html>
   
 