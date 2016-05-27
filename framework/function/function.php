<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/12
 * Time: 17:00
 */
/**调用一个控制类
 * @param $name:类名
 * @param $method:方法
 */
function C($name, $method){
    //白名单验证
    require_once 'framework/libs/Controller/adminController.class.php';
    $white_list = get_class_methods('adminController');
    $names = array('admin');
    if(in_array($method,$white_list)&&in_array($name,$names)){
        require_once 'framework/libs/Controller/'.$name.'Controller.class.php';
        $Controller = $name.'Controller';
        $obj = new $Controller();
        $obj->$method();
    }else{
        var_dump($_SERVER);
        VIEW::display('template/tpl/index/404.html');
    }
}
/**调用一个模型类
 * @param $name:类名
 * @return mixed
 */
function M($name){
    require_once 'framework/libs/Model/'.$name.'Model.class.php';
    $Model = $name.'Model';
    $obj = new $Model();
    return $obj;
}
/**调用一个视图类
 * @param $name:类名
 * @return mixed
 */
function V($name){
    require_once 'framework/libs/View/'.$name.'View.class.php';
    $View = $name.'View';
    $obj = new $View;
    return $obj;
}
/**格式化传入的函数
 * @param $str:要格式化的字符串
 * @return string
 */
function daddslashes($str){
    return (!get_magic_quotes_gpc())?addslashes($str):$str;
}
/**引用第三方类库
 * @param $path:路径
 * @param $name:类名
 * @param array $params:参数数组
 * @return mixed
 */
function ORG($path, $name, $params=array()){
    require_once 'framework/libs/ORG/'.$path.$name.'.class.php';
    $obj = new $name();
    if(!empty($params)){
        foreach ($params as $key=>$value){
            $obj->$key = $value;
        }
    }
    return $obj;
}
?>