<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/20
 * Time: 16:41
 */
class authModel{
    private $auth = '';

    public function __construct(){
        if(isset($_SESSION['auth'])&&(!empty($_SESSION['auth']))){
            $this->auth = $_SESSION['auth'];
        }
        if(empty($_POST['username'])){
            $this->checkloging();
        }
    }
    public function loginsubmit(){
        if(empty($_POST['username'])||empty($_POST['password'])){
            return false;
        }
        $username = addslashes($_POST['username']);
        $password = addslashes($_POST['password']);
        if($this->auth = $this->checkuser($username,$password)){
            $_SESSION['auth'] = $this->auth;
            return true;
        }else{
            return false;
        }
    }
    public function checkloging(){
        if (empty($this->auth)){
            $err = M('error');
            $err->showmessage('请登录','/login.html');
        }
    }
    private function checkuser($username,$password){
        $adminobj = M('admin');
        $auth = $adminobj->findOne_by_username($username);
        $password = strtoupper(md5($password));
        if((!empty($auth))&&$auth['Password']==$password){
            return $auth;
        }else{
            return false;
        }
    }

    public function getauth(){
        return $this->auth;
    }

    public function logout(){
        unset($_SESSION['auth']);
        $this->auth = '';
    }
}