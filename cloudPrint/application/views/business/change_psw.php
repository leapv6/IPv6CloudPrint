<html>
<head>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>scripts/layer.js" id = "layer"></script>
    <link href="<?php echo base_url(); ?>style/form_css.css"  rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        function close_layer(){
            layer.msg("loading...");
        }
    </script>

    <style type="text/css">
        #form_box{
            margin-left: 166px;
        }
    </style>
</head>

<body>
    <div id="form_box">
        <form  action="update_psw" method="post">

                <label for="name">原密码</label>
                <div class="text_box">
                    <input type="password"  name="oldPsw" placeholder="请输入原密码！"  />
                </div>

                
                <label for="email">新密码</label>
                <div class="text_box">
                     <input type="password" name="newPsw" />
                </div>
                    
                

                <label for="email">确认新密码</label>
                <div class="text_box">
                    <input type="password" name="new2" recheck="newPsw" />
                </div>
                    
                

                <p class="submit">
                    <input type="submit" value="确认修改" onclick="close_layer()"/>
                </p>
                
        </form>
    </div>
    
</div>
</body>


