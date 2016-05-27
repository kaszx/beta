<?php

/**
 * Created by PhpStorm.
 * User: Kaszx
 * Date: 2016-05-24
 * Time: 21:15
 */
class errorModel{
    public function showmessage($mes,$url){
        echo "<script> alert('".$mes."');parent.location.href='".$url."'; </script>";
    }
}