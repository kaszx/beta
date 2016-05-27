<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/13
 * Time: 16:40
 */
class mysql{
    private $mysqli;
    public function __construct($DB_config = ''){
        extract($DB_config);
        @$mysqli = new mysqli($dbhost,$dbuser,$dbpasswd,$dbname,$dbport);
        if($mysqli->connect_errno){
            $this->err($mysqli->connect_errno,$mysqli->connect_error);
        }
        $mysqli->set_charset($dbcharset);
        return $this->mysqli = $mysqli;
    }
    public function query($sql){
        return $this->mysqli->query($sql);
    }
    public function findOne($sql){
        $res = $this->query($sql);
        return $res->fetch_assoc();
    }
    public function findAll($sql){
        $res = $this->query($sql);
        while ($row = $res->fetch_assoc()){
            $rows[] = $row;
        }
        return $rows;
    }
    public function aff_row(){
        return $this->mysqli->affected_rows;
    }
    public function num_row($sql){
        $res = $this->query($sql);
        return $res->num_rows;
    }
    public function err($errno,$error){
        die('程序出错！错误代码：'.$errno.'<br />错误信息：'.$error);
    }
}

