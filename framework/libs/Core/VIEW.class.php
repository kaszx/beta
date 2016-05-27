<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/13
 * Time: 16:39
 */
class VIEW{
    public static $view;
    public static function init($viewtype,$config){
        self::$view = new $viewtype;
        foreach ($config as $key => $value){
            self::$view-> $key = $value;
        }
    }
    public static function assign($data){
        foreach ($data as $key => $value){
            self::$view->assign($key,$value);
        }
    }
    public static function assign_old($key,$data){
        self::$view->assign($key,$data);
    }
    public static function display($template){
        self::$view->display($template);
    }

}