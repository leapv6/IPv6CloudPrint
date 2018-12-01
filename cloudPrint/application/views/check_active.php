<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo base_url(); ?>style/reset.css" rel="stylesheet" style="text/css" />
    <link href="<?php echo base_url(); ?>style/user_active.css" rel="stylesheet" style="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
<title>账号激活</title>

<script type="text/javascript">
	function showtime(t){
        
    document.myform.mail.disabled=true; 
    for(i=1;i<=t;i++) { 
        window.setTimeout("update_p(" + i + ","+t+")", i * 1000); 
    	} 
 
	} 
 
	function update_p(num,t) { 
	    if(num == t) { 
	        document.myform.mail.value =" 重新发送 "; 
	        document.myform.mail.disabled=false; 
	    } 
	    else { 
	        printnr = t-num; 
	        document.myform.mail.value = " (" + printnr +")秒后重新发送"; 
	    } 
	} 

	function jump(){
		location.href = "<?php echo base_url(); ?>";
	}
</script>

</head>

<body>
	<?php include(VIEWPATH."/white_top.php") ?>

	<hr>	
	<div id="main">
		<h1 class="top_title">用户账号激活</h1>
		
		<div class="content">
			<h1><?php echo $msg; ?></h1>
			<br>

			<br><br>
			<form name="myform" >
				<input style="cursor: pointer;" id="mail_btn" class="button" type="button" onclick="jump()" name="mail" value="返回首页">
			</form>
			
		</div>


	</div>

</body>

