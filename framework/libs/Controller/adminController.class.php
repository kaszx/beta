<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/20
 * Time: 14:49
 */
class adminController{
    public function index(){
        VIEW::display('template/tpl/admin/login.html');
    }
    public function login(){
        if($_POST){
            $this->checklogin();
        }else{
            VIEW::display('template/tpl/admin/login.html');
        }
    }
    private function checklogin(){
        $authobj = M('auth');
        $err = M('error');
        if($authobj->loginsubmit()){
            $err->showmessage('登录成功！','main.html');
        }else{
            $err->showmessage('登录失败！','login.html');
        }
    }
    public function loginout(){
        unset($_SESSION['auth']);
        $err = M('error');
        $err->showmessage('退出成功','/login.html');
    }
    public function main(){
        $authobj = M('auth');
        $auth = $authobj->getauth();
        VIEW::assign($auth);
        VIEW::display('template/tpl/admin/index.html');
    }
    public function welcome(){
        $welobj = M('welcome');
        $info = $welobj->info();
        $adminobj = M('admin');
        $adminobj->userinfo = $_SESSION['auth'];
        $info = $info + $adminobj->get_login_info();
        VIEW::assign($info);
        VIEW::display('template/tpl/admin/welcome.html');
    }
    public function member(){
        $authobj = M('auth');
        $auth = $authobj->getauth();
        $memberobj = M('member');
        $list['list'] = $memberobj->page();
        VIEW::assign($list);
        VIEW::assign($auth);
        VIEW::display('template/tpl/admin/member.html');
    }
    public function edit(){
        $editobj = M('edit');
        $editobj->edit();
        VIEW::display('template/tpl/index/edit.html');
    }
    public function modify(){
        $modifyobj = M('modify');
        $modifyobj->modify();
        VIEW::display('template/tpl/index/modify.html');
    }
    public function sell(){
        VIEW::display('template/tpl/index/sell.html');
    }
    public function article_list(){
        VIEW::display('template/tpl/admin/article-list.html');
    }
    public function member_list(){
        $authobj = M('auth');
        $auth = $authobj->getauth();
        $memberobj = M('member');
        $list['list'] = $memberobj->page();
        VIEW::assign($list);
        VIEW::assign($auth);
        VIEW::display('template/tpl/admin/member-list.html');
    }
    public function admin_list(){
        $adminobj = M('admin');
        $list = $adminobj->admin_list();
        VIEW::assign($list);
        VIEW::display('template/tpl/admin/admin-list.html');
    }
    public function admin_add(){
        $adminobj = M('admin');
        $list = $adminobj->admin_table();
        VIEW::assign($list);
        VIEW::display('template/tpl/admin/admin-add.html');
    }
    public function admin_submit(){
        $adminobj = M('admin');
        $check['check'] = $adminobj->admin_add();
        VIEW::assign($check);
        VIEW::display('template/tpl/admin/admin-submit.html');
    }
    public function test(){
    }
}