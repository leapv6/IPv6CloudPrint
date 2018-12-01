<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>文档上传</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>style/bootstrap.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url(); ?>style/uploader.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>style/demo.css" rel="stylesheet" />


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <![endif]-->
    <script>
        function close_page(){
            parent.location.href = "<?php echo site_url('user/user_index/my_file') ?>";
        }
    </script>
</head>
<body role="document" >

<div class="container demo-wrapper" style="top: 36px;">
    <div class="row demo-columns" style="float:left;width: 1000px">
        <div class="col-md-6" style="float:left;">
            <!-- D&D Zone-->
            <div id="drag-and-drop-zone" class="uploader" >
                <div>请拖拽文件到这里</div>
                <div class="or">-或-</div>
                <div class="browser">
                    <label>
                        <span>点击这里选择上传文件</span>
                        <input type="file" name="files[]" multiple="multiple" title='Click to add Files'>
                    </label>
                </div>
            </div>
            <!-- /D&D Zone -->

            <!-- Debug box -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">日志</h3>
                </div>
                <div class="panel-body demo-panel-debug">
                    <ul id="demo-debug">
                    </ul>
                </div>
            </div>
            <!-- /Debug box -->
        </div>
        <!-- / Left column -->

        <div class="col-md-6" style="float:left;width: 400px">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">上传列表</h3>
                </div>
                <div class="panel-body demo-panel-files" id='demo-files'>
                    <span class="demo-note">暂无文件...</span>
                </div>
            </div>
        </div>
        <input type="button" onclick="close_page();" id="regButton" value="确  定" />
        <!-- / Right column -->
    </div>

</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url(); ?>scripts/jquery-1.8.2.min.js"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script> -->

<script type="text/javascript" src="<?php echo base_url(); ?>scripts/demo.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>scripts/dmuploader.min.js"></script>

<script type="text/javascript">
    $('#drag-and-drop-zone').dmUploader({
        url: '<?php echo site_url('user/user_index/do_upload'); ?>',
        dataType: 'json',
        /*allowedTypes: 'application/*'*/
        extFilter: 'doc;docx;pdf;xlsx;xls;ppt;pptx;gif;jpg;png;jpeg',
        onInit: function(){
            $.danidemo.addLog('#demo-debug', 'default', '上传控件加载成功,本平台支持上传和打印的格式有:wps、doc、docx、ppt、pptx、xls、xlsx、pdf、jpg、jpeg、png、gif');
        },
        onBeforeUpload: function(id){
            $.danidemo.addLog('#demo-debug', 'default', '开始上传 #' + id);

            $.danidemo.updateFileStatus(id, 'default', '上传文件中...');
        },
        onNewFile: function(id, file){
            $.danidemo.addFile('#demo-files', id, file);
        },
        onComplete: function(){
            $.danidemo.addLog('#demo-debug', 'default', '所有文件上传完成');
        },
        onUploadProgress: function(id, percent){
            var percentStr = percent + '%';

            $.danidemo.updateFileProgress(id, percentStr);
        },
        onUploadSuccess: function(id, data){
            $.danidemo.addLog('#demo-debug', 'success', '上传文件 #' + id + ' completed');

            /*$.danidemo.addLog('#demo-debug', 'info', 'Server Response for file #' + id + ': ' + JSON.stringify(data));*/

            $.danidemo.updateFileStatus(id, 'success', '上传完成');

            $.danidemo.updateFileProgress(id, '100%');
        },
        onUploadError: function(id, message){
            $.danidemo.updateFileStatus(id, 'error', '上传文件失败！请检查文件格式、大小');

            $.danidemo.addLog('#demo-debug', 'error', '上传文件失败 #' + id + ': ' + message);
        },
        onFileTypeError: function(file){
            $.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' 格式不支持');
        },
        onFileSizeError: function(file){
            $.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' 不能上传: 大小限制');
        },
        /*onFileExtError: function(file){
         $.danidemo.addLog('#demo-debug', 'error', 'File \'' + file.name + '\' has a Not Allowed Extension');
         },*/
        onFallbackMode: function(message){
            $.danidemo.addLog('#demo-debug', 'info', 'Browser not supported(do something else here!): ' + message);
        }
    });
</script>

</body>
</html>
