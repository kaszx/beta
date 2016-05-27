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
    public static function findAll($sql){
        return self::$db->findAll($sql);
    }
    public static function num_row($sql){
        return self::$db->num_row($sql);
    }
    public static function aff_row(){
        return self::$db->aff_row();
    }
    public static function insert($table,$arr){
        return self::$db->get_insert_db_sql($table,$arr);
    }
    public static function replace($table,$arr){
        return self::$db->get_replace_db_sql($table,$arr);
    }
    public static function update($table,$arr,$where){
        return self::$db->get_update_db_sql($table,$arr,$where);
    }
    public static function del($table,$where){
        return self::$db->get_delete_db_sql($table,$where);
    }
}
?>