<?php
/**
 * 定时检索商家客户端最后一次更新状态时间，对比时间差，更新数据库在线状态
 */

    $mysql_server_name="127.0.0.1"; //数据库服务器名称
    $mysql_username="root"; // 连接数据库用户名
    $mysql_password="roshamon"; // 连接数据库密码
    $mysql_database="cloudprint"; // 数据库的名字

    // 连接到数据库
    $conn=mysql_connect($mysql_server_name, $mysql_username, $mysql_password);

    $update_sql = "update cp_business_info SET runStatus = 0 WHERE (SELECT TIMESTAMPDIFF(MINUTE,lastUpdateTime,NOW()) > 10)";

    // 执行sql查询
    $result=mysql_db_query($mysql_database, $update_sql, $conn);
    mysql_close($conn);