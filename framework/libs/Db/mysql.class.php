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
    public function findOne($table,$arr,$where){
        $arr = implode(',',$arr);
        $sql = "SELECT {$arr} FROM {$table} WHERE {$where}";
        if($res = $this->query($sql)){
            return $res->fetch_assoc();
        }else{
            die('操作失败'.$this->mysqli->error);
        }
    }
    public function findAll($table,$arr,$where){
        $arr = implode(',',$arr);
        if($where === ''){
            $sql = "SELECT {$arr} FROM {$table}";
        }else{
            $sql = "SELECT {$arr} FROM {$table} WHERE {$where}";
        }
        $res = $this->query($sql);
        while ($row = $res->fetch_assoc()){
            $rows[] = $row;
        }
        if (isset($rows)){
            $_SESSION['time'] = time();
            return $rows;
        }else{
            return false;
        }
    }
    public function aff_row(){
        return $this->mysqli->affected_rows;
    }
    public function num_row($table,$arr,$where){
        $arr = implode(',',$arr);
        if($where === ''){
            $sql = "SELECT {$arr} FROM {$table}";
        }else{
            $sql = "SELECT {$arr} FROM {$table} WHERE {$where}";
        }
        if ($this->mysqli->errno){
            $this->err($this->mysqli->errno,$this->mysqli->error);
        }
        $res = $this->query($sql);
        return $res->num_rows;
    }
    public function update($table,$arr,$where){
        $res='';
        foreach($arr as $key => $value){
            $res .= $key." = '".$value."', ";

        }
        $res = substr($res,0,-2);
        if($where == ''){
            $sql = "UPDATE {$table} SET {$res}";
        }else{
            $sql = "UPDATE {$table} SET {$res} WHERE {$where}";
        }
        return $this->query($sql);
    }
    public function insert($table,$values){
        $value = implode("','",$values);
        $sql = "INSERT INTO {$table} VALUES('{$value}')";
        return $this->query($sql);
    }
    public function insert_new($table,$values){
        foreach ($values as $key => $row){
            $keys[] = $key;
            $rows[] = $row;
        }
        $key = implode(",",$keys);
        $value = implode("','",$rows);
        $sql = "INSERT INTO {$table}({$key}) VALUES('{$value}')";
        return $this->query($sql);
    }
    public function err($errno,$error){
        die('程序出错！错误代码：'.$errno.'<br />错误信息：'.$error);
    }
}

