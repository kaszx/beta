<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/20
 * Time: 14:49
 */
class adminController{
    public function index(){
        if(isset($_SESSION['auth'])){
            header("Location: /main.html");
        }
        if (!isset($_SESSION['username'])){
            $_SESSION['status'] = false;
        }else{
            $_SESSION['status'] = true;
        }
        VIEW::assign($_SESSION);
        VIEW::display('template/tpl/admin/login.html');
    }
    public function login(){
        if($_POST){
            $this->checklogin();
        }else{
            if (!isset($_SESSION['username'])){
                $_SESSION['status'] = false;
            }else{
                $_SESSION['status'] = true;
            }
            VIEW::assign($_SESSION);
            VIEW::display('template/tpl/admin/login.html');
        }
    }
    private function checklogin(){
        $authobj = M('auth');
        $err = M('error');
        if ($_POST['veryfi'] !== $_SESSION['veryfi']){
            $_SESSION['username'] = $_POST['username'];
            $err->showmessage('验证码错误！请重试','login.html');
        }
        if($authobj->loginsubmit()){
            $_SESSION['username'] = $_POST['username'];
            $err->showmessage('登录成功！','main.html');
        }else{
            $_SESSION['username'] = $_POST['username'];
            $err->showmessage('登录失败！','login.html');
        }
    }
    public function loginout(){
        $err = M('error');
        if(empty($_SESSION['auth'])){
            $err->showmessage('当前没有登录','/login.html');
        }else{
            unset($_SESSION['auth']);
            $err->showmessage('退出成功','/login.html');
        }
    }
    public function main(){
        $authobj = M('auth');
        $auth = $authobj->getauth();
        VIEW::assign($auth);
        VIEW::display('template/tpl/admin/index.html');
    }
    public function welcome(){
        $authobj = M('auth');
        $welobj = M('welcome');
        $info = $welobj->info();
        $adminobj = M('admin');
        $adminobj->userinfo = $_SESSION['auth'];
        $info = $info + $adminobj->get_login_info();
        VIEW::assign($info);
        VIEW::display('template/tpl/admin/welcome.html');
    }
    public function article_list(){
        $authobj = M('auth');
        VIEW::display('template/tpl/admin/article-list.html');
    }
    public function member_list(){
        $authobj = M('auth');
        $memberobj = M('member');
        $list = $memberobj->page();
        $num = $memberobj->num();
        VIEW::assign($num);
        VIEW::assign($list);
        VIEW::display('template/tpl/admin/member-list.html');
    }
    public function member_show(){
        $memberobj = M('member');
        $res = $memberobj->member_view();
        VIEW::assign($res);
        VIEW::display('template/tpl/admin/member-show.html');
    }
    public function member_search(){
        $memobj = M('member');
        $res = $memobj->member_search();
        VIEW::assign($res);
        VIEW::display('template/tpl/admin/member-search.html');
    }
    public function admin_list(){
        $authobj = M('auth');
        $adminobj = M('admin');
        $list = $adminobj->admin_list();
        VIEW::assign($list);
        VIEW::display('template/tpl/admin/admin-list.html');
    }
    public function admin_add(){
        $authobj = M('auth');
        $adminobj = M('admin');
        if(isset($_GET['id'])){
            $res = $adminobj->get_admin($_GET['id']);
            $res += array('status' => 'edit');
            VIEW::assign($res);
        }else{
            $res = array(
                'ID' => false,
                'Personnel' => false,
                'LoginName' => false,
                'Department' => false,
                'Sex' => false,
                'Birthday' => false,
                'Mobile' => false,
                'Closed' => false,
                'CardNo' => false,
                'Remark' => false,
                'WorkName' => false,
                'QueryName' => false
            );
            $res += array('status' => 'add');
            VIEW::assign($res);
        }
        $list = $adminobj->admin_table();
        VIEW::assign($list);
        VIEW::display('template/tpl/admin/admin-add.html');
    }
    public function admin_submit(){
        $authobj = M('auth');
        $adminobj = M('admin');
        if ($_POST['status'] == 'edit'){
            $check['check'] = $adminobj->admin_edit($_POST['id']);
        }else{
            $check['check'] = $adminobj->admin_add();
        }
        VIEW::assign($check);
        VIEW::display('template/tpl/admin/admin-submit.html');
    }
    public function admin_search(){
        $authobj = M('auth');
        $adminobj = M('admin');
        $res = $adminobj->search();
        VIEW::assign($res);
        VIEW::display('template/tpl/admin/admin-search.html');
    }
    public function v_image(){
        header("content-type:image/gif");
        $imageobj = M('image');
        $imageobj->image();
    }
}


