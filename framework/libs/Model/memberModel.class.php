<?php
/**
 * Created by PhpStorm.
 * User: ofyoo
 * Date: 2016/05/20
 * Time: 17:25
 */
class memberModel{
    public $_table = 'u_member';
    public function getinfo(){
        $sql = "SELECT p.ID,Member,Sex,Birthday,Age,Mobile,Card,CardType,AvailablePoint FROM u_member p INNER JOIN u_card s
                ON p.CardID = s.ID";
        return DB::findAll($sql);
    }
    public function page(){
        $num = 10;

        $num = isset($_POST['num'])?intval($_POST['num']):$num;

        if(isset($_POST['num'])){
            $_SESSION['num'] = $_POST['num'];
        }

        $nums['nums'] = isset($_SESSION['num'])?intval($_SESSION['num']):$num;

        $page = isset($_GET['page'])?intval($_GET['page']):1;

        $sql = "SELECT ID FROM u_member";

        $rows = DB::num_row($sql);

        $pages = ceil($rows/$nums['nums']);

        if($page > $pages){
            VIEW::display('template/tpl/test/404.html');
            exit;
        }

        $start = ($page-1)*$nums['nums'];

        $url = '/lists/';

        $nav['nav'] = $this->getPageHtml($page, $pages, $url);

        $sql = "SELECT p.ID,Member,Sex,Birthday,Age,Mobile,Card,CardType,AvailablePoint FROM u_member p INNER JOIN u_card s
                ON p.CardID = s.ID ORDER BY CardID LIMIT {$start},{$nums['nums']}";
        
        $lists = DB::findAll($sql);
        VIEW::assign($nav);
        VIEW::assign($nums);
        return $lists;
    }
    public function getPageHtml($page, $pages, $url){
        //最多显示多少个页码
        $_pageNum = 5;
        //当前页面小于1 则为1
        $page = $page<1?1:$page;
        //当前页大于总页数 则为总页数
        $page = $page > $pages ? $pages : $page;
        //页数小当前页 则为当前页
        $pages = $pages < $page ? $page : $pages;

        //计算开始页
        $_start = $page - floor($_pageNum/2);
        $_start = $_start<1 ? 1 : $_start;
        //计算结束页
        $_end = $page + floor($_pageNum/2);
        $_end = $_end>$pages? $pages : $_end;

        //当前显示的页码个数不够最大页码数，在进行左右调整
        $_curPageNum = $_end-$_start+1;
        //左调整
        if($_curPageNum<$_pageNum && $_start>1){
            $_start = $_start - ($_pageNum-$_curPageNum);
            $_start = $_start<1 ? 1 : $_start;
            $_curPageNum = $_end-$_start+1;
        }
        //右边调整
        if($_curPageNum<$_pageNum && $_end<$pages){
            $_end = $_end + ($_pageNum-$_curPageNum);
            $_end = $_end>$pages? $pages : $_end;
        }

        $_pageHtml = '<ul class="pagination">';
        if($_start == 1){
            $_pageHtml .= '<li><a title="第一页">首页</a></li>';
        }else{
            $_pageHtml .= '<li><a  title="第一页" href="'.$url.'1.html">首页</a></li>';
        }
        if($page == 2 || $page == 3){
            $_pageHtml = '<ul class="pagination"><li><a  title="第一页" href="'.$url.'1.html">首页</a></li>';
        }
        if($page>1){
            $_pageHtml .= '<li><a  title="上一页" href="'.$url.($page-1).'.html">«</a></li>';
        }
        for ($i = $_start; $i <= $_end; $i++) {
            if($i == $page){
                $_pageHtml .= '<li class="active"><a>'.$i.'</a></li>';
            }else{
                $_pageHtml .= '<li><a href="'.$url.$i.'.html">'.$i.'</a></li>';
            }
        }
        if($page<$_end){
            $_pageHtml .= '<li><a  title="下一页" href="'.$url.($page+1).'.html">»</a></li>';
        }
        if($_end == $pages){
            $_pageHtml .= '<li><a title="最后一页">尾页</a></li>';
        }else{
            $_pageHtml .= '<li><a  title="最后一页" href="'.$url.$pages.'.html">尾页</a></li>';
        }
        $_pageHtml .= '</ul>';
        return $_pageHtml;
    }
}