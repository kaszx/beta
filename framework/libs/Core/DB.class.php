<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/13
 * Time: 16:39
 */
class DB{
    public static $db;
    public static function init($db_type,$config){
        self::$db = new $db_type($config);
    }
    public static function query($sql){
        return self::$db->query($sql);
    }
    public static function findOne($sql){
        return self::$db->findOne($sql);
    }
    public static function findAll($table,$arr,$where){
        return self::$db->findAll($table,$arr,$where);
    }
    public static function num_row($table,$arr,$where = ''){
        return self::$db->num_row($table,$arr,$where);
    }
    public static function aff_row(){
        return self::$db->aff_row();
    }
    public static function insert($table,$arr){
        return self::$db->insert($table,$arr);
    }
    public static function insert_new($table,$arr){
        return self::$db->insert_new($table,$arr);
    }
    public static function select($table,$arr,$where){
        return self::$db->select($table,$arr,$where);
    }
    public static function replace($table,$arr){
        return self::$db->get_replace_db_sql($table,$arr);
    }
    public static function update($sql){
        return self::$db->query($sql);
    }
    public static function del($table,$where){
        return self::$db->get_delete_db_sql($table,$where);
    }
}
?>