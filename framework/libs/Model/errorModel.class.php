<?php

/**
 * Created by PhpStorm.
 * User: Kaszx
 * Date: 2016-05-24
 * Time: 21:15
 */
class errorModel{
    public function showmessage($mes,$url){
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        echo "<script> alert('".$mes."');parent.location.href='".$url."'; </script>";
    }
}