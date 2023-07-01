<?php

session_start();
require_once("config.php");
$page_title = "startsida";
include_once(TEMPLATE_PATH . "header.php");
Db::connect();
escape_specialchars($_GET);
escape_specialchars($_POST);
$action=(isset($_GET['action']))? $_GET['action'] : ''; // get action if any, default empty
$page_number = (isset($_GET['page']))? intval($_GET['page']) : 1; // get page number, default 1
$filter=''; // store the selected options from filter-form
$rows_per_page = 5; // number of ads shown for each page
// indicate from which row database will fetch result, first page start from 0 to rows_per_page
$initial_page = ($page_number-1) * $rows_per_page;
$limit = $initial_page.","."$rows_per_page"; // Limit used in db query to fetch result for each page
$total_rows = 0; // number of total affected rows in db

// If filter-form was submitted
if($_SERVER['REQUEST_METHOD']=='POST' && $action=='filter'){
    // store temporarily form inputs values in session
    $_SESSION['province']=$_POST['province'];
    $_SESSION['job_area']=$_POST['job_area'];
}
// If search-form was submitted
if($_SERVER['REQUEST_METHOD']=='POST' && $action=='search'){
    // store temporarily form inputs values in current session
    $_SESSION['keyword'] = $_POST['keyword'];
}
// process received data
if($action == 'filter'){
    $params =  array($_SESSION['province'], $_SESSION['job_area']);
    // if both province and category are not empty
    if(!empty($_SESSION['province']) && !empty($_SESSION['job_area'])){
        // query the total ads found
        $counter = Db::fetch_one("SELECT COUNT(*) FROM ads WHERE is_published=true AND province=? AND job_area=?",$params);
        $total_rows = $counter['COUNT(*)'];
        $query = "SELECT * FROM ads NATURAL JOIN company WHERE is_published=true AND province=? AND job_area=? LIMIT ".$limit;

    }else{ // if either province or category not empty
        $counter= Db::fetch_one("SELECT COUNT(*) FROM ads WHERE is_published=true AND province=? OR job_area=?",$params);
        $total_rows = $counter['COUNT(*)'];
        $query = "SELECT * FROM ads NATURAL JOIN company WHERE is_published=true  AND province=? OR job_area=? LIMIT ".$limit;
    }
    // fetch and store result
    $ads_data = Db::fetch_all($query , $params);
    // store the selected options to be printed later
    $filter='<span>' .$_SESSION['province'] .'>' . $_SESSION['job_area'] .'</span><br/>';

}elseif($action=='search'){ // search for substring in ads titles
    $str = '"%'.$_SESSION['keyword'].'%"';
    $counter= Db::fetch_one("SELECT COUNT(*) FROM ads WHERE is_published=true AND title LIKE ".$str);
    $total_rows = $counter['COUNT(*)'];
    $query = "SELECT * FROM ads NATURAL JOIN company WHERE is_published=true AND title LIKE ".$str." LIMIT ".$limit;
    $ads_data = Db::fetch_all($query);

}else{ // if no data was submitted or no actions, fetch ads to be printed in homepage
    // release resources if any
    unset($_SESSION['province']);
    unset($_SESSION['job_area']);
    unset($_SESSION['keyword']);
    $counter = Db::fetch_one("SELECT COUNT(*) FROM ads WHERE is_published=true");
    $total_rows = $counter['COUNT(*)'];
    $ads_data = Db::fetch_all("SELECT * FROM ads NATURAL JOIN company WHERE is_published=true LIMIT ".$limit);
}

?>

<main class="main-content">
    <section>
        <h2 class="mb-4">Antal lediga jobb(<?= $total_rows ?>)</h2>
        <!--include Search and filter forms-->
        <?php include( TEMPLATE_PATH . "search.php"); ?>
        <hr/>
    <?php
    // alert if no result was found
    $div='<div class="alert mt-2" id="result-info">Inga resultat. <a href="index.php">Gå till startsida</a></div>';
    echo ($total_rows==0)? $filter.$div:$filter;
    echo get_published_ads($ads_data); // print ads
    ?>
</section>
</main>
<!--pagination nav-->
<nav aria-label="Page navigation" class="page-navigation">
    <ul class="pagination">
        <?php
        $total_pages = ceil($total_rows / $rows_per_page); // total pages
        $list_items=''; // stor pages number in list
        // set url part
        if($action=='filter'){
            $href='index.php?action=filter&page=';
        }elseif ($action=='search'){
            $href='index.php?action=search&page=';
        }else{
            $href='index.php?page=';
        }
        // add action and page number in url
        $pageURL = '<li class="page-item"><a class="page-link %1$s" href="%2$s%3$d" id="%3$d">%3$d</a></li>';
        if($page_number>=2){ // add previous page symbol
            $prev_page ='<li class="page-item"><a class="page-link" href="%1$s%2$d" aria-label="Previous">
                       <span aria-label="förgående sida" class="fa-solid fa-angles-left"></span></a></li>';
            $list_items .= sprintf($prev_page,$href,($page_number-1));
        }
        for ($num=1; $num<=$total_pages; $num++) { // add page url to $list_items
            if ($num == $page_number) {
                $list_items .= sprintf($pageURL,'active',$href,$num);
            }else{
                $list_items .= sprintf($pageURL,'',$href,$num);
            }
        }
        if($page_number < $total_pages){ // add next page symbol
            $next_page ='<li class="page-item"><a class="page-link" href="%1$s%2$d" aria-label="Next">
                       <span aria-label="nästa sida" class="fa-solid fa-angles-right"></span></a></li>';
            $list_items .= sprintf($next_page,$href,($page_number+1));
        }
        echo $list_items; // print list items
        ?>
    </ul>
</nav>

<?php
include_once TEMPLATE_PATH . "right_sidebar.php";
include TEMPLATE_PATH . "footer.php";
?>
