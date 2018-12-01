$(document).ready(function(){
		var nScrollHight = 0; //滚动距离总长(注意不是滚动条的长度)
        var nScrollTop = 0;   //滚动到的当前位置
        var nDivHight = $("#files_list_warp").height();
		$('#files_list_warp').scroll(function(){
			var dangqianshuliang = $('[data-list="file"]').length;
			nScrollHight = $(this)[0].scrollHeight;
			nScrollTop = $(this)[0].scrollTop;
			if(nScrollTop + nDivHight >= nScrollHight){
				var next_page = $('#next_page').val();
				if(next_page>0&&dangqianshuliang>=20){
					$.ajax({
						//提交数据的类型 POST GET
						type:"POST",
						//提交的网址
						url:URL+"/get_files/page/"+next_page,
						//返回数据的格式
						datatype: "html",//"xml", "html", "script", "json", "jsonp", "text".
						//在请求之前调用的函数
						beforeSend:function(){
							layer.load('loading...');
						},
						//成功返回之后调用的函数             
						success:function(data){
							$("#msg").html(decodeURI(data));            
						}   ,
						//调用执行后调用的函数
						complete: function(XMLHttpRequest, textStatus){
							layer.closeAll();
							var returndata=eval(XMLHttpRequest.responseText);
							if(XMLHttpRequest.responseText == 'null'){
								$('#next_page').val('-1');
							}else{
								append_record(returndata,'a');
								$('#next_page').val(parseInt(next_page)+1);
							}
							
						}
					});
				}
			}
		});
	  });
	  
    document.getElementById("js_bd").style.height = (document.body.clientHeight - 42) +"px";
    document.getElementById("files_list_warp").style.height = (document.body.clientHeight - 245) +"px";
    document.getElementById("files_list_warp").style.overflow = "scroll";
	function unix_to_datetime(unix) {
	    var now = new Date(parseInt(unix) * 1000);
	    return now.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
	}
	function add_record(recordlist,op){
		var addrecord = [];
		var op = op;
		var records = recordlist;
		$.each(records || [],
			function(i, file,op) {
				var file_id = records[i].member_file_id;
				var member_file_short = records[i].member_file_short;
				var file_name = records[i].member_file_name;
				var file_size = renderSize(records[i].member_file_size);
				var file_time = unix_to_datetime(records[i].member_file_creattime);
				var icon_class = 'icon-' + records[i].member_file_ext;
				addrecord = '<div data-file-id data-list="file" class="list clear" data-record-id="wy-record-'+file_id+'" class="list clear" id="dataview1-wy-record-'+file_id+'">\r\n\<label onclick="checkthis('+file_id+')" class="checkbox" tabindex="0" aria-label="'+file_name+'"></label>\r\n\<span class="img"><i class="filetype '+icon_class+'"></i></span>\r\n\<span class="name"><p class="text"><em id="title-wy-record-'+file_id+'" data-name="file_name" title="'+file_name+'">'+file_name+'</em></p></span><span class="tool"><a data-action="delete" class="link-del" title="删除" href="#" tabindex="0" hidefocus="on"><i class="ico-del"></i></a><a data-action="rename" class="link-rename" title="重命名" href="#" tabindex="0" hidefocus="on"><i class="ico-rename"></i></a><a data-action="share" class="link-share" title="分享" onclick="share_file('+file_id+',\''+file_name+'\')" tabindex="0" hidefocus="on"><i class="ico-share"></i></a><a data-action="download" class="link-download" title="下载" href="'+APP+'/Down/getfile/short/'+member_file_short+'" tabindex="0" hidefocus="on"><i class="ico-download"></i></a></span><div class="size" tabindex="0">'+file_size+'</div>\r\n\<div class="time" tabindex="0">'+file_time+'</div><span onclick="get_dimensional_code('+file_id+')" class="dimensional">\r\n\<a class="link-dimensional" title="二维码" href="#"><i class="ico-dimensional"></i></a>\r\n\</span>\r\n\</div>';
				//alert(addrecord);
				
					$('.filelist').prepend(addrecord);
				
			});
			//alert(addrecord);
	}
	
	     /*
	  四舍五入保留小数位数
	  numberRound 被处理的数
	  roundDigit  保留几位小数位
	*/
	function  roundFun(numberRound,roundDigit)   //处理金额 -by hailang  
	{   
	   if(numberRound>=0) {   
			 var   tempNumber   =   parseInt((numberRound   *   Math.pow(10,roundDigit)+0.5))/Math.pow(10,roundDigit);   
			 return   tempNumber;   
		}else{   
		 numberRound1=-numberRound   
		 var   tempNumber   =   parseInt((numberRound1   *   Math.pow(10,roundDigit)+0.5))/Math.pow(10,roundDigit);   
		 return   -tempNumber;   
		}   
	}  

	/*附件大小格式处理*/
	function renderSize(value, p, record){
	  if(null==value||value==''){
		return "0 Bytes";
	  }
	  var unitArr = new Array("Bytes","KB","MB","GB","TB","PB","EB","ZB","YB");
	  var index=0;
	  
	  
	  var srcsize = parseFloat(value); 
	  var size =roundFun(srcsize/Math.pow(1024,(index=Math.floor(Math.log(srcsize)/Math.log(1024)))),2); 
	  return size+unitArr[index];
	}

	
	function append_record(recordlist,op){
		var addrecord = [];
		var op = op;
		var records = recordlist;
		$.each(records || [],
			function(i, file,op) {
				var file_id = records[i].member_file_id;
				var member_file_short = records[i].member_file_short;
				var file_name = records[i].member_file_name;
				var file_size = renderSize(records[i].member_file_size);
				var file_time = unix_to_datetime(records[i].member_file_creattime);
				var icon_class = 'icon-' + records[i].member_file_ext;
				addrecord = '<div data-file-id data-list="file" class="list clear" data-record-id="wy-record-'+file_id+'" class="list clear" id="dataview1-wy-record-'+file_id+'">\r\n\<label onclick="checkthis('+file_id+')" class="checkbox" tabindex="0" aria-label="'+file_name+'"></label>\r\n\<span class="img"><i class="filetype '+icon_class+'"></i></span>\r\n\<span class="name"><p class="text"><em id="title-wy-record-'+file_id+'" data-name="file_name" title="'+file_name+'">'+file_name+'</em></p></span><span class="tool"><a data-action="delete" class="link-del" title="删除" href="#" tabindex="0" hidefocus="on"><i class="ico-del"></i></a><a data-action="rename" class="link-rename" title="重命名" href="#" tabindex="0" hidefocus="on"><i class="ico-rename"></i></a><a data-action="share" class="link-share" title="分享" onclick="share_file('+file_id+',\''+file_name+'\')" tabindex="0" hidefocus="on"><i class="ico-share"></i></a><a data-action="download" class="link-download" title="下载" href="'+APP+'/Down/getfile/short/'+member_file_short+'" tabindex="0" hidefocus="on"><i class="ico-download"></i></a></span><div class="size" tabindex="0">'+file_size+'</div>\r\n\<div class="time" tabindex="0">'+file_time+'</div><span onclick="get_dimensional_code('+file_id+')" class="dimensional">\r\n\<a class="link-dimensional" title="二维码" href="#"><i class="ico-dimensional"></i></a>\r\n\</span>\r\n\</div>';
				//alert(addrecord);
				
					$('.filelist').append(addrecord);
				
			});
			//alert(addrecord);
	}
	
    $(function () {
        $(window).resize(function () {
            $("#js_bd").height($(window).height() - 42);
        });
    });
	
	var loginhtml,reghtml
		
	var pagelogin = {};
	layer.login = function(optionlogin){
		layer.closeAll();
		options = optionlogin || {};
		$.layer({
			type: 1,
			title: '上传文件',
			offset: [($(window).height() - 415)/2+'px', ''],
			border : [5, 0.5, '#666'],
			area: ['1170px','415px'],
			shadeClose: true,
			page: pagelogin,
			closeBtn: [0, true]
		});
	};
	$('#uploadbox').on('click', function(){
		//如果已经请求过，则直接读取缓存节点
		if(loginhtml){
			pagelogin.html = loginhtml;
		} else {
			pagelogin.url = URL+'/uploadbox'
			pagelogin.ok = function(datalogin){
				loginhtml = datalogin; //保存登录节点
			}
		}
		layer.login();    
	});

	$('#files_list_warp').ready(function(){
		$.ajax({
			//提交数据的类型 POST GET
			type:"POST",
			//提交的网址
			url:URL+"/get_files",
			//返回数据的格式
			datatype: "html",//"xml", "html", "script", "json", "jsonp", "text".
			//在请求之前调用的函数
			beforeSend:function(){
				layer.load('loading...');
			},
			//成功返回之后调用的函数             
			success:function(data){
				$("#msg").html(decodeURI(data));            
			}   ,
			//调用执行后调用的函数
			complete: function(XMLHttpRequest, textStatus){
				var returndata=eval(XMLHttpRequest.responseText);
				
				append_record(returndata,'a');
				layer.closeAll();
				$('.link-rename').click(function(){
					var list_div_id = $(this).parent().parent().attr('data-record-id');
					$('#title-'+list_div_id).hide();
					var title_name = $('#title-'+list_div_id).html();
					var title_p = $('#dataview1-'+list_div_id).find('[class="text"]')
					var innerhtml_edit = '<span class="fileedit" style="display:inline;"><input id="input-'+list_div_id+'" class="ui-input" type="text" value="'+title_name+'"></span>';
					var count=title_p.find('input').length; 
					if(count == 0){
						title_p.append(innerhtml_edit);
					}
					title_p.find('input').select();
					title_p.find('input').blur(function (){
						var inputtitle = $(this).val();
						var file_id = list_div_id.replace("wy-record-", "");
						title_p.find('span').remove();
						$.post(APP+"/User/change_file_name", { file_id: file_id,inputtitle:inputtitle }, function (data) {
							var returndata=eval('(' + data + ')');
							if(returndata.status > 0){
							$('#title-'+list_div_id).html(inputtitle)
							layer.msg(returndata.content,1,1);
							}else{
								$('#title-'+list_div_id).html(title_name);
								layer.msg(returndata.content,1,3);
							}		
							$('#title-'+list_div_id).show();	
						});
					});
				});
				
				$('.link-del').click(function(){
					var list_div_id = $(this).parent().parent().attr('data-record-id');
					var file_id = list_div_id.replace("wy-record-", "");
					$.layer({
						shade: [0],
						area: ['auto','auto'],
						dialog: {
							msg: '确定要删除这个文件吗？',
							btns: 2,                    
							type: 4,
							btn: ['确认','取消'],
							yes: function(){
								$.post(APP+"/User/delete_file", { file_id: file_id}, function (data) {
									var returndata=eval('(' + data + ')');
									if(returndata.status > 0){
										layer.msg(returndata.content,1,1);
										$('#dataview1-'+list_div_id).remove();
									}else{
										layer.msg(returndata.content,1,3);
									}		
								});
							}, no: function(){
								
							}
						}
					});
					
				});
				
			},
			//调用出错执行的函数
			error: function(){
			//请求出错处理
			}         
		});

	});
	

	
	$('#next_btn').click(function(){
		var arr = new Array();
		$(".ui-selected").each(function() {
			arr.push($(this).attr("data-record-id"));
		});
		if(arr == ''){
			$('#print_list').val();
			layer.msg('至少选择一个文件',1,3);
		}else{
			$('#print_list').val(arr);
			//alert(arr);
			layer.msg('文件选取完毕,请设置打印参数',1,1,function(){
				$('#printform').submit();
			});
		}
		
	});
		
	function checkthis(file_id){
		var files_div_id = '#dataview1-wy-record-'+file_id;
		if($(files_div_id).attr("class") == 'list clear'){
			$(files_div_id).attr("class" , "list clear ui-selected");
		}else{
			$(files_div_id).attr("class" , "list clear");
		}
		var xuanzhongshuliang = $('.ui-selected').length;
		if(xuanzhongshuliang > 0){
			$('#next_btn').attr("class" , "u-img-btn u-img-btn-7");
		}else{
			$('#next_btn').attr("class" , "u-img-btn u-img-btn-7 z-dis");
		}
	}
	
	function get_dimensional_code(file_id){
		var dimensional_code = {};
		dimensional_code.url = URL+'/dimensional_code/file_id/'+file_id;
		var pagei = $.layer({
			type: 1,
			title: false,
			offset: [($(window).height() - 391)/2+'px', ''],
			border : [5, 0.5, '#666'],
			area: ['280px','391px'],
			shadeClose: true,
			page: dimensional_code,
			closeBtn: [0, true]		});
	}
	
	function share_file(file_id,filename){
		var share_file = {};
		share_file.url = URL+'/share_file/file_id/'+file_id;
		var pagei = $.layer({
			type: 1,
			title: '分享:'+filename,
			offset: [($(window).height() - 350)/2+'px', ''],
			border : [5, 0.5, '#666'],
			area: ['482px','350px'],
			shadeClose: true,
			page: share_file,
			closeBtn: [0, true]		});
	}