<?php

/**
 * Created by PhpStorm.
 * User: Kaszx
 * Date: 2016-05-29
 * Time: 11:37
 */
class imageModel{
    public function image(){
        $width=80;
        $height=42;
        $image=imagecreatetruecolor($width,$height);
        $white=imagecolorallocate($image,255,255,255);
        $black=imagecolorallocate($image,0,0,0);
        imagefilledrectangle($image,1,1,$width-2,$height-2,$white);
        $type=1;
        $length=4;
        $chars=$this->random_str($type,$length);
        $_SESSION['veryfi']=$chars;
        $fontfiles= array(
            "verdana.ttf",
            "verdanab.ttf",
            "verdanai.ttf",
            "verdanaz.ttf"
        );
        for ($i=0;$i<$length;$i++){
            $size=mt_rand(14,18);
            $angle=mt_rand(-15,15);
            $x=5+$i*$size;
            $y=mt_rand(20,40);
            $color=imagecolorallocate($image,mt_rand(50,90),mt_rand(80,200),mt_rand(90,180));
            $fontfile="./fonts/".$fontfiles[mt_rand(0,count($fontfiles)-1)];
            $text=substr($chars,$i,1);
            imagettftext($image,$size,$angle,$x,$y,$color,$fontfile,$text);
        }
        return imagegif($image);
    }
    public function random_str($type=1,$length=4){
        if($type==1){
            $chars=join("",range(0,9));
        }elseif ($type==2){
            $chars=join("",array_merge(range("a","z"),range("A","Z")));
        }elseif ($type==3){
            $chars=join("",array_merge(range("a","z"),range("A","Z"),range(0,9)));
        }
        if($length>strlen($chars)){
            exit("字符串长度不够");
        }
        $chars=str_shuffle($chars);
        return substr($chars,0,$length);
    }
    public function test(){
        $im = imagecreate(300,65); //创建画布
        $colo = imagecolorallocate($im,225,230,65); //定义背景颜色
        //imagefill($im,0,0,$colo);//填充颜色
        header('content-type:image/gif'); //
        return imagegif($im);
    }
}