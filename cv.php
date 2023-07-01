<?php

session_start();
if(!isset($_SESSION['username'])){
    header('location: index.php');
}
include_once('config.php'); // include configuration file
$page_title="student cv";
include_once(TEMPLATE_PATH . 'header.php'); // include page header
escape_specialchars($_GET); //escape html special characters
//check if ad_id are set
$userid = (isset($_GET['student-id']))? intval($_GET['student-id']) : 0;
$user_info = array(); // stor fetched user cv from db

if($userid != 0){ // fetch cv profile from db
    Db::connect();
    $query = "SELECT * FROM student WHERE student_id = ?";
    $user_info = Db::fetch_one($query,[$userid]);
    Db::close_connection();
} else{
    header("Location: index.php");
    exit();
}
?>

<main class="main-content account-page">
    <div class="cv-profile">
        <h2>CV#<?=$userid?></h2>
        <hr/>
        <address>
            <?=get_value($user_info,'student_name')?><br/>
            E-post: <?=get_value($user_info,'cv_email')?> <br/>
            Mobil: <?=get_value($user_info,'phone_num')?> <br/>
        </address>
        <div>
            <h3>Sammanfattning</h3>
            <hr/>
            <p class="preserve-format"><?=get_value($user_info,'summary')?></p>
        </div>

        <div>
            <h3>Färdigheter/kompetenser</h3>
            <hr/>
            <p class="preserve-format"><?=get_value($user_info,'skills') ?></p>
        </div>

        <div>
            <h3>Utbildning</h3>
            <hr/>
            <p class="preserve-format"><?=get_value($user_info,'education')?></p>
        </div>

        <div>
            <h3>Övrig</h3>
            <hr/>
            <p class="preserve-format"><?=get_value($user_info,'other')?></p>
        </div>
    </div>
</main>


<?php include_once(TEMPLATE_PATH . 'footer.php'); ?>
