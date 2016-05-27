<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/20
 * Time: 16:53
 */
class adminModel{
    //定义表名
    public $userinfo = '';
    public $_table1 = 'u_personnel';
    public $_table2 = 'u_department';
    public $_table3 = 'logininfo';
    //取用户信息
    public function findOne_by_username($username){
        $sql = 'SELECT t1.*, t2.ID AS DID FROM '.$this->_table1.' AS t1 INNER JOIN '.$this->_table2.' AS t2 ON t1.DepartmentID = t2.ID  WHERE LoginName="'.$username.'"';
        return $this->userinfo = DB::findOne($sql);
    }
    public function up_login_info(){
        $arr = array(
            NULL,
            $this->userinfo['ID'],
            $_SERVER['REMOTE_ADDR'],
            date("Y-m-d H:i:s")
        );
        return DB::insert($this->_table3,$arr);
    }
    public function get_login_info(){
        $arr = array(
            'ip',
            'time',
        );
        $where1 = "userID = '{$this->userinfo['ID']}'";
        if(($num['num'] = DB::num_row($this->_table3,$arr,$where1)) !== 1){
            $where2 = "userID = '{$this->userinfo['ID']}' ORDER BY time DESC LIMIT 1,1";
            $info = DB::select($this->_table3,$arr,$where2);
            return $info + $num;
        }else{
            $info['ip'] = '首次登录';
            $info['time'] = '首次登录';
            return $info + $num;
        }
    }
    public function login_num(){
        
    }
}