<?php

/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016-05-23
 * Time: 17:07
 */
class editModel{
    public function edit(){
        M('auth')->checkloging();
        @$id['id'] = $_POST['ID'];
        if (empty($id)){
            echo "<script> alert('没有选择会员');parent.location.href='/lists.html'; </script>";
        }
        $sql = "SELECT Member,Sex,Mobile FROM u_member WHERE ID = '{$id['id']}'";
        $res = DB::findOne($sql);
        VIEW::assign($res);
        VIEW::assign($id);
    }
}