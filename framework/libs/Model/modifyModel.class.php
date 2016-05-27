<?php

/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016-05-23
 * Time: 17:30
 */
class modifyModel{
    public function modify(){
        M('auth')->checkloging();
        $member = $_POST['Member'];
        $sex = $_POST['Sex'];
        $mobile = $_POST['Mobile'];
        $id = $_POST['id'];
        $sql = "UPDATE u_member SET Member = '{$member}',
                            Sex = '{$sex}',
                            Mobile = '{$mobile}' WHERE ID = '{$id}'";
        $res['res'] = DB::query($sql);
        $row['row'] = DB::aff_row();
        VIEW::assign($res);
        VIEW::assign($row);
    }
}