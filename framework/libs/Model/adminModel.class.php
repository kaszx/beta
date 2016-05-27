<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/20
 * Time: 16:53
 */
class adminModel{
    //定义表名
    public $_table = 'u_personnel';
    //取用户信息
    function findOne_by_username($username){
        $sql = 'SELECT * FROM '.$this->_table.' WHERE LoginName="'.$username.'"';
        return DB::findOne($sql);
    }
}