<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/12
 * Time: 11:55
 */
//定义编码
header("Content-type: text/html; charset=utf8");
//默认时区
date_default_timezone_set('Asia/Shanghai');
//开启session验证
session_start();
//包含配置文件
require_once 'config/config.php';
//包含主文件
require_once 'framework/run.php';
//启动程序
RUN::start($config);
?>