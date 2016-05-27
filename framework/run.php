<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/13
 * Time: 16:31
 */
//设定当前目录
$currentdir = dirname(__FILE__);
//包含文件列表
include_once ($currentdir.'/include.list.php');
//加载包含文件
foreach ($paths as $path){
    include_once ($currentdir.'/'.$path);
}
class RUN{
    public static $controller;
    public static $method;
    private static $config;
    private static function init_db(){
        DB::init('mysql',self::$config['DB_config']);
    }
    private static function init_view(){
        VIEW::init('Smarty',self::$config['viewconfig']);
    }
    private static function init_controllor(){
        self::$controller = isset($_GET['controller'])?daddslashes($_GET['controller']):'index';
    }
    private static function init_method(){
        self::$method = isset($_GET['method'])?daddslashes($_GET['method']):'index';
    }
    public static function start($config){
        self::$config = $config;
        self::init_db();
        self::init_view();
        self::init_controllor();
        self::init_method();
        C(self::$controller,self::$method);
    }
}
?>