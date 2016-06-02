<?php

/**
 * Created by PhpStorm.
 * User: Kaszx
 * Date: 2016-05-28
 * Time: 8:33
 */
class welcomeModel{
    public function info(){
        $_SERVER['OS'] = PHP_OS;
        $_SERVER['t'] = ini_get("max_execution_time");
        $_SERVER['date'] = date("Y-m-d H:i:s");
        $_SERVER = $_SERVER + $this->sys_linux();
        return $_SERVER;
    }
    public function sys_linux() {
        // CPU
        if (false === ($str = @file("/proc/cpuinfo"))) return false;
        $str = implode("", $str);
        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
        @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
        @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);
        if (false !== is_array($model[1]))    {
            $res['cpu']['num'] = sizeof($model[1]);
            $res['cpu']['num_text'] = str_replace(array(1,2,4,8,16), array('单','双','四','八','十六'), $res['cpu']['num']).'核';
            /*
            for($i = 0; $i < $res['cpu']['num']; $i++) {
                $res['cpu']['model'][] = $model[1][$i].'&nbsp;('.$mhz[1][$i].')';
                $res['cpu']['mhz'][] = $mhz[1][$i];
                $res['cpu']['cache'][] = $cache[1][$i];
                $res['cpu']['bogomips'][] = $bogomips[1][$i];
            }*/
            $x1 = ($res['cpu']['num']==1) ? '' : ' ×'.$res['cpu']['num'];
            $mhz[1][0] = ' | 频率:'.$mhz[1][0];
            $cache[1][0] = ' | 二级缓存:'.$cache[1][0];
            $bogomips[1][0] = ' | Bogomips:'.$bogomips[1][0];
            $res['cpu']['model'][] = $model[1][0];//.$mhz[1][0].$cache[1][0].$bogomips[1][0].$x1;
            if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
            if (false !== @is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
            if (false !== @is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
            if (false !== @is_array($res['cpu']['bogomips'])) $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']);
        }
        // NETWORK
        // UPTIME
        if (false === ($str = @file("/proc/uptime"))) return false;
        $str = explode(' ', implode("", $str));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) $res['uptime'] = $days."天";
        if ($hours !== 0) $res['uptime'] .= $hours."小时";
        $res['uptime'] .= $min."分钟";
        // MEMORY
        if(false === ($str = @file("/proc/meminfo"))) return false;
        $str = implode("", $str);
        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
        preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);
        $res['mem_total'] = round($buf[1][0]/1024, 2);
        $res['mem_free'] = round($buf[2][0]/1024, 2);
        $res['mem_buffers'] = round($buffers[1][0]/1024, 2);
        $res['mem_cached'] = round($buf[3][0]/1024, 2);
        $res['mem_used'] = $res['mem_total']-$res['mem_free'];
        $res['mem_percent'] = (floatval($res['mem_total'])!=0)?round($res['mem_used']/$res['mem_total']*100,2):0;
        $res['mem_real_used'] = $res['mem_total'] - $res['mem_free'] - $res['mem_cached'] - $res['mem_buffers']; //真实内存使用
        $res['mem_real_free'] = $res['mem_total'] - $res['mem_real_used']; //真实空闲
        $res['mem_real_percent'] = (floatval($res['mem_total'])!=0)?round($res['mem_real_used']/$res['mem_total']*100,2):0; //真实内存使用率
        $res['mem_cached_percent'] = (floatval($res['mem_cached'])!=0)?round($res['mem_cached']/$res['mem_total']*100,2):0; //Cached内存使用率
        $res['swap_total'] = round($buf[4][0]/1024, 2);
        $res['swap_free'] = round($buf[5][0]/1024, 2);
        $res['swap_used'] = round($res['swap_total']-$res['swap_free'], 2);
        $res['swap_percent'] = (floatval($res['swap_total'])!=0)?round($res['swap_used']/$res['swap_total']*100,2):0;
        // LOAD AVG
        if (false === ($str = @file("/proc/loadavg"))) return false;
        $str = explode(' ', implode("", $str));
        $str = array_chunk($str, 4);
        $res['load_avg'] = implode(' ', $str[0]);
        return $res;
    }
}

?>
