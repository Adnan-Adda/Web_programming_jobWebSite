<?php

session_start();
include_once('config.php');
$page_title="annons";
include_once(TEMPLATE_PATH . 'header.php');
escape_specialchars($_GET);
$ad_id = (isset($_GET['ad-id']))? intval($_GET['ad-id']) : 0;
$action = (isset($_GET['action']))? $_GET['action'] : '';
$ad_data = array();

// if user is company fetch all it's published and not published ads
if($action=='view-ad'){
    Db::connect();
    $ad_data=Db::fetch_one("SELECT * FROM ads NATURAL JOIN company WHERE ad_id=? AND company_id=?", [$ad_id,$_SESSION['company-id']]);
    Db::close_connection();
}elseif($ad_id != 0){ // if ad_id is not 0 fetch ad with ad_id from db
    Db::connect();
    $ad_data=Db::fetch_one("SELECT * FROM ads NATURAL JOIN company WHERE is_published=true AND ad_id=?", [$ad_id]);
    Db::close_connection();
}else{
    header("Location: index.php");
    exit();
}
if(!isset($ad_data['ad_id'])){ // if there are no request with ad_id print error
    echo 'ERROR';
    exit();
}
?>
    <main class="main-content">
        <?= get_alert(); ?> <!--alert message on success/failure-->
        <section class="ads-page">
            <h3 class="mt-3"><?= $ad_data['title']?></h3>
            <div class="header">
                <div><img src="<?= LOGO_UPLOAD_PATH.$ad_data['logo']?>" alt="company logo" class="company-logo"></div>
                <p><strong><?= $ad_data['company_name']?></strong></p>

                <p>
                    Plats: <span class="fw-light"><?= $ad_data['province'] . '/' .$ad_data['city']?></span><br/>
                    Publicerad: <span class="fw-light"><?= $ad_data['publish_date']?></span><br/>
                </p>
                <p id="job-info" class="fw-light preserve-format"><?= $ad_data['job_duration']?></p>
            </div>
            <hr/>
            <div class="qualification">
                <h4><strong>Kvalifikationer</strong><br/></h4>
                <p class="preserve-format"><?= $ad_data['qualifications']?></p>
            </div>
            <hr/>
            <div class="border-bottom">
                <h4><strong>Om Jobbet</strong></h4>
                <p class="preserve-format"><?= $ad_data['post']?></p><br/>
            </div>
        </section>
    </main>
    <aside class="right-sidebar send-cv">
        <div class="card mt-2 me-2 ms-2">
            <div class="card-header">
                <span class="fw-semibold">Sök senast: </span><span class="fw-light"><?= $ad_data['end_date']?></span><br/>
            </div>
            <div class="card-body"> <!--Form to send application to the company-->
                <p>Annons ID#<?= $ad_data['ad_id']?></p>
                <p class="card-text"><strong>NOTERA!! </strong>När du klicka 'Ansök' din cv skickas till arbetsgivaren</p>
                <form class="" action="student.php?action=send-cv&ad-id=<?= $ad_data['ad_id']?>" method="POST">
                    <button type="submit" class="btn btn-primary">Skicka ansöka</button>
                </form>
            </div>
        </div>
    </aside>
<?php include_once(TEMPLATE_PATH . 'footer.php'); ?>
