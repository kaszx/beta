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
    public $_table4 = 's_workgroup';
    public $_table5 = 's_querygroup';
    //取用户信息
    public function login_check($username){
        $arr = array(
            '*'
        );
        $where = "LoginName='".$username."'";
        return $this->userinfo = DB::findOne($this->_table1,$arr,$where);
    }
    public function findOne_by_username($username){
        $arr1 = array(
            't1.Personnel',
            't1.LoginName',
            't1.Department',
            't1.Sex',
            't1.Birthday',
            't1.Mobile',
            't1.Closed',
            't1.CardNo',
            't1.Remark',
            't2.Name AS WorkName',
            't3.Name AS QueryName'
        );
        $arr2 = array(
            'Personnel',
        );
        $where = "LoginName='".$username."'";
        $table = $this->_table1.' AS t1 LEFT JOIN s_workgroup AS t2 ON t1.WorkGroupID = t2.ID LEFT JOIN s_querygroup AS t3 ON t1.QueryGroupID = t3.ID';
        $res['num'] = DB::num_row($this->_table1,$arr2,$where);
        $res['list'] = DB::findAll($table,$arr1,$where);
        return $res;
    }
    public function up_login_info(){
        $arr = array(
            'userID' => $this->userinfo['ID'],
            'ip' => $_SERVER['REMOTE_ADDR'],
            'time' => date("Y-m-d H:i:s")
        );
        return DB::insert_new($this->_table3,$arr);
    }
    public function get_login_info(){
        $arr = array(
            'ip',
            'time',
        );
        $where1 = "userID = '{$this->userinfo['ID']}'";
        if(($num['num'] = DB::num_row($this->_table3,$arr,$where1)) > 1){
            $where2 = "userID = '{$this->userinfo['ID']}' ORDER BY time DESC LIMIT 1,1";
            $info = DB::findOne($this->_table3,$arr,$where2);
            return $info + $num;
        }else{
            $info['ip'] = '首次登录';
            $info['time'] = '首次登录';
            return $info + $num;
        }
    }
    public function admin_list(){
        $arr1 = array(
            't1.ID',
            't1.Personnel',
            't1.LoginName',
            't1.Department',
            't1.Sex',
            't1.Birthday',
            't1.Mobile',
            't1.Closed',
            't1.CardNo',
            't1.Remark',
            't2.Name AS WorkName',
            't3.Name AS QueryName'
        );
        $arr2 = array(
            'Personnel',
        );
        $where = '';
        $table = $this->_table1.' AS t1 LEFT JOIN s_workgroup AS t2 ON t1.WorkGroupID = t2.ID LEFT JOIN s_querygroup AS t3 ON t1.QueryGroupID = t3.ID';
        $res['num'] = DB::num_row($this->_table1,$arr2,$where);
        $res['list'] = DB::findAll($table,$arr1,$where);
        return $res;
    }
    public function get_admin($id){
        $arr = array(
            't1.ID',
            't1.Personnel',
            't1.LoginName',
            't1.Department',
            't1.Sex',
            't1.Birthday',
            't1.Mobile',
            't1.Closed',
            't1.CardNo',
            't1.Remark',
            't2.Name AS WorkName',
            't3.Name AS QueryName'
        );
        $where = "t1.ID = '{$id}'";
        $table = $this->_table1.' AS t1 LEFT JOIN s_workgroup AS t2 ON t1.WorkGroupID = t2.ID LEFT JOIN s_querygroup AS t3 ON t1.QueryGroupID = t3.ID';
        $res = DB::findAll($table,$arr,$where);
        return $res['0'];
    }
    public function admin_table(){
        $arr = array(
            '*'
        );
        $where = '';
        $res['department'] = DB::findAll($this->_table2,$arr,$where);
        $res['workgroup'] = DB::findAll($this->_table4,$arr,$where);
        $res['querygroup'] = DB::findAll($this->_table5,$arr,$where);
        return $res;
    }
    public function admin_add(){
        extract($_POST);
        $name = addslashes($LoginName);
        $where = "LoginName = '{$name}'";
        $res = DB::num_row($this->_table1,array('*'),$where);
        if($res !== 0){
            return false;
        }else{
            $adminRole = explode(',',$adminRole);
            $arr = array(
                'DepartmentID' => addslashes($adminRole['0']),
                'WorkGroupID' => addslashes($adminWork),
                'QueryGroupID' => addslashes($adminQuery),
                'Password' => strtoupper(md5(addslashes($password))),
                'ID' => $this->create_guid($LoginName),
                'Personnel' => addslashes($adminName),
                'Department' => addslashes($adminRole['1']),
                'LoginName' => addslashes($LoginName),
                'Sex' => addslashes($sex),
                'Closed' => null,
                'Birthday' => addslashes($birthday),
                'Mobile' => addslashes($phone),
                'CardNo' => addslashes($CardNo),
                'Remark' => addslashes($remark)
            );
            return DB::insert_new($this->_table1,$arr);
        }
    }
    public function admin_edit($id){
        extract($_POST);
        $name = addslashes($LoginName);
        $adminRole = explode(',',$adminRole);
        $arr = array(
            'DepartmentID' => addslashes($adminRole['0']),
            'WorkGroupID' => addslashes($adminWork),
            'QueryGroupID' => addslashes($adminQuery),
            'Password' => strtoupper(md5(addslashes($password))),
            'Personnel' => addslashes($adminName),
            'Department' => addslashes($adminRole['1']),
            'LoginName' => addslashes($LoginName),
            'Sex' => addslashes($sex),
            'Birthday' => addslashes($birthday),
            'Mobile' => addslashes($phone),
            'CardNo' => addslashes($CardNo),
            't1.Remark' => addslashes($remark)
        );
        $where = "t1.ID = '{$id}'";
        $table = $this->_table1.' AS t1 LEFT JOIN s_workgroup AS t2 ON t1.WorkGroupID = t2.ID LEFT JOIN s_querygroup AS t3 ON t1.QueryGroupID = t3.ID';
        return DB::update($table,$arr,$where);
    }
    public function create_guid($namespace = '') {
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= @$_SERVER['LOCAL_ADDR'];
        $data .= @$_SERVER['LOCAL_PORT'];
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = '{' .
            substr($hash,  0,  8) .
            '-' .
            substr($hash,  8,  4) .
            '-' .
            substr($hash, 12,  4) .
            '-' .
            substr($hash, 16,  4) .
            '-' .
            substr($hash, 20, 12) .
            '}';
        return $guid;
    }
    public function search(){
        @$name = addslashes($_POST['name']);
        if ($res = $this->findOne_by_username($name)){
            return $res;
        }else{
            return false;
        }
    }
}