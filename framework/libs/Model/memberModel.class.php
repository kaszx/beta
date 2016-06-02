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
        $arr = array(
            't1.ID',
            't1.CreateDate',
            'Member',
            'Sex',
            'Birthday',
            'Age',
            'Mobile',
            'Card',
            'CardType',
            'AvailablePoint',
            't1.Remark'
        );
        $table = "u_member AS t1 INNER JOIN u_card AS t2 ON t1.CardID = t2.ID";
        $res['num'] = DB::num_row($table,$arr);
        $res['list'] = DB::findAll($table,$arr);

        return $res;
    }
    public function num(){
        $table = $this->_table;
        $arr = array( 'ID');
        $res['num'] = DB::num_row($table,$arr);
        return $res;
    }
    public function page(){
        $num = 10;
        
        $num = isset($_POST['num'])?intval($_POST['num']):$num;

        if(isset($_POST['num'])){
            $_SESSION['num'] = $_POST['num'];
        }

        $nums['nums'] = isset($_SESSION['num'])?intval($_SESSION['num']):$num;

        $page = isset($_GET['page'])?intval($_GET['page']):1;

        $arr1 = array(
            'ID'
        );

        $rows = DB::num_row($this->_table,$arr1);

        $pages = ceil($rows/$nums['nums']);

        if($page > $pages){
            VIEW::display('template/tpl/test/404.html');
            exit;
        }

        $start = ($page-1)*$nums['nums'];

        $url = '/member_list/';

        $nav['nav'] = $this->getPageHtml($page, $pages, $url);

        $arr2 = array(
            't1.ID',
            'Member',
            'Sex',
            'Birthday',
            'Age',
            'Mobile',
            'Card',
            'CardType',
            'AvailablePoint',
            't1.Remark',
            'StateID'
        );

        $table = "u_member AS t1 INNER JOIN u_card AS t2 ON t1.CardID = t2.ID ORDER BY CardID LIMIT {$start},{$nums['nums']}";
        
        $res['list'] = DB::findAll($table,$arr2);
        $res['nav'] = $nav;
        $res['nums'] = $nums;
        return $res;
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

        if ($_start == 1) {
            $_pageHtml .= '<li><a class="no" title="第一页">首页</a></li>';
        } else {
            $_pageHtml .= '<li><a  title="第一页" href="' . $url . '1.html">首页</a></li>';
        }
        if ($page == 2 || $page == 3) {
            $_pageHtml = '<ul class="pagination"><li><a  title="第一页" href="' . $url . '1.html">首页</a></li>';
        }
        if ($page > 1) {
            $_pageHtml .= '<li><a  title="上一页" href="' . $url . ($page - 1) . '.html">«</a></li>';
        }
        for ($i = $_start; $i <= $_end; $i++) {
            if ($i == $page) {
                $_pageHtml .= '<li><a class="active">' . $i . '</a></li>';
            } else {
                $_pageHtml .= '<li><a href="' . $url . $i . '.html">' . $i . '</a></li>';
            }
        }
        if ($page < $_end) {
            $_pageHtml .= '<li><a  title="下一页" href="' . $url . ($page + 1) . '.html">»</a></li>';
        }
        if ($_end == $pages) {
            $_pageHtml .= '<li><a class="no" title="最后一页">尾页</a></li>';
        } else {
            $_pageHtml .= '<li><a  title="最后一页" href="' . $url . $pages . '.html">尾页</a></li>';
        }
        $_pageHtml .= '</ul>';
        return $_pageHtml;
    }
    public function member_search(){
        $_POST['datemax'] = @!isset($_POST['datemax'])?@$_SESSION['date_start']:$_POST['datemax'];
        $_SESSION['date_start'] = @$_POST['datemax'] == ''?'1900-01-01':$_POST['datemax'];
        $_POST['datemin'] = @!isset($_POST['datemin'])?@$_SESSION['date_end']:$_POST['datemin'];
        $_SESSION['date_end'] = @$_POST['datemin'] == ''?'9999-12-31': $_POST['datemin'];
        $_POST['content'] = @!isset($_POST['content'])?@$_SESSION['content']:$_POST['content'];
        $_SESSION['content'] = @$_POST['content'] == ''?'%':$_POST['content'];
        $arr = array(
            't1.ID',
            't1.Member',
            't1.Sex',
            't1.Birthday',
            'Mobile',
            'stateID',
            't1.Remark',
            'AvailablePoint',
            'Age',
            't2.CardType',
            't2.Card',
            'StateID'
        );
        $where = "t1.Birthday BETWEEN '{$_SESSION['date_start']}' AND '{$_SESSION['date_end']}' AND (t1.Member LIKE '%{$_SESSION['content']}%' OR t1.Mobile LIKE '%{$_SESSION['content']}%' OR t2.Card LIKE '%{$_SESSION['content']}%')";

        
        $table = 'u_member AS t1 INNER JOIN u_card AS t2 ON t1.CardID = t2.ID';
        //$res['list'] = DB::findAll($table,$arr,$where);
        //$res['num'] = DB::num_row($table,$arr,$where);
        //return $res;

        $num = 10;

        $num = isset($_POST['num'])?intval($_POST['num']):$num;

        if(isset($_POST['num'])){
            $_SESSION['num'] = $_POST['num'];
        }

        $nums['nums'] = isset($_SESSION['num'])?intval($_SESSION['num']):$num;

        $page = isset($_GET['page'])?intval($_GET['page']):1;

        $rows = DB::num_row($table,$arr,$where);
        if ($rows === 0){
            $where = "t1.Member LIKE '%{$_SESSION['content']}%' OR t1.Mobile LIKE '%{$_SESSION['content']}%' OR t2.Card LIKE '%{$_SESSION['content']}%'";
            $rows = DB::num_row($table,$arr,$where);
            if ($rows ===0){
                $pages = 0;
                $url = '';
                $nav['nav'] = $this->getPageHtml($page, $pages, $url);
                $res['status'] = false;
                $res['nav'] = $nav;
                $res['nums'] = $nums;
                return $res;
            }
        }
        $res['num'] = $rows;
        $pages = ceil($rows/$nums['nums']);

        if($page > $pages){
            VIEW::display('template/tpl/test/404.html');
            exit;
        }

        $start = ($page-1)*$nums['nums'];

        $url = '/member_search/';
        $nav['nav'] = $this->getPageHtml($page, $pages, $url);


        $where = "t1.Birthday BETWEEN '{$_SESSION['date_start']}' AND '{$_SESSION['date_end']}' AND (t1.Member LIKE '%{$_SESSION['content']}%' OR t1.Mobile LIKE '%{$_SESSION['content']}%' OR t2.Card LIKE '%{$_SESSION['content']}%')  LIMIT {$start},{$nums['nums']}";
        $res['list'] = DB::findAll($table,$arr,$where);
        if ($res['list'] === false||(!isset($_POST['datemax'])&&(!isset($_POST['datemin'])))){
            $where = "t1.Member LIKE '%{$_SESSION['content']}%' OR t1.Mobile LIKE '%{$_SESSION['content']}%' OR t2.Card LIKE '%{$_SESSION['content']}%'  LIMIT {$start},{$nums['nums']}";
            $res['list'] = DB::findAll($table,$arr,$where);
        }
        $res['status'] = true;
        $res['nav'] = $nav;
        $res['nums'] = $nums;
        return $res;
    }
    public function member_view(){
        $id = $_REQUEST['id'];
        $arr = array(
            't1.ID',
            't1.CreateDate',
            'Member',
            'Sex',
            'Birthday',
            'Age',
            'Mobile',
            'Card',
            'CardType',
            'AvailablePoint',
            'Account',
            'times',
            'StartDate',
            'EndDate',
            't1.Remark',
            'State',
            'Point',
            'IntroducerPoint'
        );
        $table = "u_member AS t1 INNER JOIN u_card AS t2 ON t1.CardID = t2.ID";
        $where = "t1.ID = '{$id}'";
        $res = DB::findOne($table,$arr,$where);
        return $res;
    }
}