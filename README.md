# IPv6CloudPrint
IPv6云打印，云网印
* 1.服务器端部署
> * 1.1  先安装lnmp环境，可以用 https://lnmp.org/ 一键安装 
> * 1.2  把cloudPrint中的代码复制到/home/wwwroot 目录下面
> * 1.3  新建mysql 数据库cloudprint 把cloudprint.sql 导入到cloudprint 数据库中
> * 1.4  更改数据库连接配置  修改下面文件
 /home/wwwroot/cloudPrint/application/config/database.php
> * 1.5  运行命令mkdir -p /home/wwwroot/cloudPrint/uploads
> * 1.6  运行命令 chown -R www:www /home/wwwroot/cloudPrint/uploads


* 2.客户端安装
> * 2.1直接下载客户端安装
> * 2.2 配置客户端运行远端服务器地址
