<?php

session_start();
if(isset($_SESSION['company-id'])){
    include_once('config.php');
    escape_specialchars($_POST);
    escape_specialchars($_GET);
    $action = (isset($_GET['action']))? $_GET['action'] : '';
    $do = (isset($_GET['do']))? $_GET['do'] : '';
    $ad_id = (isset($_GET['ad-id']))?  intval($_GET['ad-id']) : 0;
    $url = 'location:company.php?action='; // url for redirections
    // query company info from database
    Db::connect();
    $query = "SELECT * FROM company WHERE company_id = ?";
    $company = Db::fetch_one($query, array($_SESSION['company-id']));
    Db::close_connection();

    /*========================= START EDIT-ACCOUNT  =========================*/
    if($action=='edit-account'){?>
        <?php
        $page_title = "Redigera konto";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
        <div class="membership">
            <?= get_alert()?> <!--alert message on success/failure-->
            <h2>Redigera konto</h2>
            <form  class="needs-validation" novalidate action="company.php?action=update-account"
                   method="POST" enctype="multipart/form-data" id="signup-form"> <!--Use bootstrap client side validation-->

                <h3>Ändra kontouppgifter (valfri)</h3>
                    <div class="mb-3 mt-3"> <!--company name -->
                        <label for="company-name" class="form-label">Företag namn:</label>
                        <input type="text" class="form-control" id="company-name"
                               value="<?=get_value($company,'company_name')?>" name="company_name" />
                    </div>
                    <div class="mb-3 mt-3"><!--Email-address-->
                        <label for="email" class="form-label">Email-address:</label>
                        <input type="email" class="form-control"
                               value="<?=get_value($_SESSION,'username')?>" id="email"
                               name="email"  required>
                        <!--show error email entered incorrect/empty-->
                        <div class="invalid-feedback">Fyll i korrekt email-address.</div>
                    </div>

                    <h3>Ändra lösenord (valfri)</h3>
                    <!--START PASSWORD AREA -->
                    <div class="mb-3">
                        <label for="pwd" class="form-label">Ny Lösenord:</label>
                        <input type="password" class="form-control" id="pwd"
                               placeholder="Skriv in ny lösenord"  name="password" >
                        <div class="invalid-feedback">Fyll i lösenord.</div>
                    </div>
                    <div class="mb-3"> <!--Confirm password-->
                        <label for="pwd-confirm" class="form-label">Bekräfta lösenord:</label>
                        <input type="password" class="form-control" id="pwd-confirm"
                               placeholder="bekräfta lösenord" name="passwordConf" >
                        <div class="invalid-feedback">Repetera lösenordet.</div>
                        <div class="invalid invalid-pwd hide ">Lösenord matchar ej</div> <!--show error if password not match-->
                    </div>
                    <!--END PASSWORD AREA-->

                    <h3>Ändra logo (valfri)</h3>
                    <div class="account-logo-area mb-3 mt-3 "> <!--START LOGO AREA-->
                        <label for="logo-upload" class="form-label">Företagslogo:</label><br/>
                        <input type="file" class="form-control " id="logo-upload"
                               name="fileToUpload" accept=".jpg, .jpeg, .png , .svg" hidden /> <!--logo upload input-->
                        <button type="button" class="btn btn-primary me-2"
                                id="btn-logo-upload" aria-label="">Ladda upp filen</button> <!--btn onclick click input file-->
                        <button type="button" id="del-file" class="btn btn-danger hide" aria-label="Rensa filen">
                            Rensa <span aria-hidden="true" class="fa-solid fa-xmark"></span></button>
                        <div id="file-info"></div> <!--show file info/error on upload-->
                        <div class="account-img mb-3 mt-3">
                            <?php
                            $format='<img src="%1$s" alt="logo" class="company-logo" id="%2$s"/>';
                            if(!empty($company['logo'])){
                                // how logo image if exists
                                echo sprintf($format,LOGO_UPLOAD_PATH.$company['logo'],$company['logo'])
                                ;}
                            ?>
                        </div>
                    </div> <!--END LOGO AREA-->
                <button type="submit" id="signup-submit"  class="btn btn-primary">Spara</button>
                <div class="ms-2 mb-2 me-2 mt-3" > <!--Delete account-->
                    <a id="del-account" href="company.php?action=del-account"><span aria-hidden="true" class="fa-solid fa-trash-can fa-lg text-red"></span> Radera konto</a>
                </div>
            </form>
        </div>
        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>

    <?php }elseif ($action=='update-account'){ /*========================= START UPDATE-ACCOUNT  =========================*/
        // check server method and validate email
        if($_SERVER['REQUEST_METHOD']=='POST' && validate_email($_POST,'email'))
        {
            Db::connect();
            // check if the new email-address if any is already exists
            $query="SELECT COUNT(*) FROM account where email=? AND account_id != ?";
            $param=array($_POST['email'],$_SESSION['account-id']);
            $row_count = Db::fetch_one($query,$param);
            // perform db update if new email not exists in db
            if($row_count['COUNT(*)']==0 || $_POST['email']==$_SESSION['username']){
                $query = " UPDATE account SET email=?,password=? WHERE account_id=?";
                $param = array($_POST['email'],$_POST['password'],$company['account_id']);
                Db::execute($query,$param);
                $_SESSION['username'] = $_POST['email']; // update session
                // if logo have been changed then upload the new logo otherwise used old one
                $new_logo = upload_img()?: $company['logo'];
                clearstatcache();
                // delete old logo from server
                if(is_file(LOGO_UPLOAD_PATH . $company['logo']) && $new_logo != $company['logo']){
                    unlink(LOGO_UPLOAD_PATH . $company['logo']);
                }
                $query = " UPDATE company SET company_name=?, logo=? WHERE company_id=?";
                $param = array($_POST['company_name'],$new_logo,$company['company_id']);
                Db::execute($query,$param);
                set_alert('Konto har uppdaterat','success');
            }else{
                set_alert('Email-adress"'. $_POST['email'] .'"redan registrerad','error');
            }
        }else{
            set_alert('Du skrev ogiltig e-post adress','error');
        }
        Db::close_connection();
        header($url.'edit-account'); // redirect back to edit page
        /*========================= END UPDATE-ACCOUNT  =========================*/

    } elseif ($action=='del-logo'){ /*========================= START DEL-LOGO  =========================*/
        if($_GET['logo-id']==$company['logo']){
            Db::connect();
            // delete logo from database and from server
            Db::execute("UPDATE company SET logo=NULL WHERE company_id=?", array($company['company_id']));
            unlink(LOGO_UPLOAD_PATH . $company['logo']);
            Db::close_connection();
        }

    }elseif ($action=='del-account'){ /*========================= START DELETE-ACCOUNT  =========================*/
        Db::connect();
        // Delete account, on delete cascade is used in db
        Db::execute("DELETE FROM account WHERE account_id=?",[$_SESSION['account-id']]);
        Db::close_connection();
        header('location: logout.php');

    } elseif ($action=='create-ads'){ /*========================= START CREATE/edit-ADS  =========================*/
        $page_title = "Skapa annons";
        include_once(TEMPLATE_PATH . 'header.php'); // include page header
        $ads_data = array();
        if($ad_id !=0){ // fill input with ad's data when the request is coming from edit
            Db::connect();
            $ads_data= Db::fetch_one("SELECT * FROM ads WHERE ad_id=? AND company_id=?",
                array($ad_id,$company['company_id']));
            Db::close_connection();
        }
        ?>
        <?php
        $page_title = "Skapa/redigera annons";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
            <!--start create/edit ad-->
        <div class="membership create-ads-page" >
            <form  action="company.php?action=update-ads&ad-id=<?=$ad_id;?>" method="POST">
                <h2>Skapa/Redigera annons</h2>
                    <h3>Jobbtitel, adress och yrkesområde </h3>
                    <!--START ADS HEADER AREA -->
                    <div class="mb-3 mt-3"> <!--TITLE-->
                        <label for="ads-title" class="form-label">Jobb titel</label>
                        <input type="text" class="form-control" id="ads-title"
                               name="title" value=" <?=get_value($ads_data,'title')?>"/>
                    </div>

                    <div class="mb-3 mt-3"> <!--JOB AREA-->
                        <label for="job-area" class="form-label">Yrkesområde</label>
                        <select class="form-select" name="job_area" id="job-area">
                            <?php
                            $value = get_value($ads_data,"job_area");
                            if(empty($value)){
                                echo '<option>välj yrkesområde</option>';
                            }else{ // if request is coming from edit
                               echo sprintf('<option value="%1$s" selected>%1$s</option>',$value);
                            }
                            echo get_select_options($job_options);
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 mt-3"> <!--PROVINCE AND CITY-->
                        <label for="city" class="form-label me-2">Stad</label>
                        <input type="text" class="form-control me-2" id="city"
                               name="city" value="<?=get_value($ads_data,'city')?>" />

                        <label for="province" class="form-label me-2">Län </label>
                        <select class="form-select" name="province" id="province">
                            <?php
                            $value = get_value($ads_data,"province");
                            if(empty($value)){
                                echo '<option>välj Län</option>';
                            }else{ // if request is coming from edit
                                echo sprintf('<option value="%1$s" selected>%1$s</option>',$value);
                            }
                            echo get_select_options($provinces);
                            ?>
                        </select>
                    </div> <!--END ADS HEADER AREA -->

                    <div class="mb-3 mt-3"> <!--START DATE AREA -->
                        <label for="end-date" class="form-label">Slutdatum</label>
                        <input type="date" class="form-control" id="end-date"
                               name="end_date"
                               value="<?= (get_value($ads_data,'end_date'))?: "2022-07-22" ?>" />
                    </div> <!--END DATE AREA -->

                    <h3>Jobb beskrivning</h3>
                    <div class="mb-3 mt-3"> <!--START JOB DESCRIPTION AREA-->
                        <label for="job_duration" class="form-label">Om anställning</label>
                        <textarea class="form-control"
                                  rows="3"
                                  id="job_duration"
                                  name="job_duration"
                                  placeholder="Omfattning/Varaktighet/Anställningsform"><?= get_value($ads_data,'job_duration') ?></textarea>
                    </div>

                    <div class="mb-3 mt-3"> <!--START JOB DESCRIPTION AREA-->
                        <label for="qualifications" class="form-label">Kvalifikationer</label>
                        <textarea class="form-control" rows="6"
                                  id="qualifications"
                                  name="qualifications"><?= get_value($ads_data,'qualifications') ?></textarea>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="post" class="form-label">Om jobbet</label>
                        <textarea class="form-control" rows="12" id="post" name="post"><?= get_value($ads_data,'post') ?></textarea>
                    </div> <!--END JOB DESCRIPTION AREA-->

                <div class="mb-3 mt-3">
                    <button type="submit" id="submit-ads" class="btn btn-primary" >Spara</button>
                </div>
            </form>
        </div>
        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>
    <?php /*========================= END CREATE/edit-ADS  =========================*/ }

    elseif ($action=='update-ads'){ /*========================= START UPDATE-ADS  =========================*/
        Db::connect();
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if($ad_id == 0){ // indicate new ad is created
                // insert ad data into db
                $query = "INSERT INTO ads(company_id,title,job_area,city,province,post,qualifications,end_date,job_duration)"
                    . "VALUES(:company_id,:title,:job_area,:city,:province,:post,:qualifications,:end_date,:job_duration)";
                $param=array('company_id'=> $company['company_id'],'title'=>$_POST['title'],'job_area'=>$_POST['job_area'],
                    'city'=>$_POST['city'],'province'=>$_POST['province'],'post'=>$_POST['post'],'qualifications'=>$_POST['qualifications'],
                    'end_date'=>$_POST['end_date'],'job_duration'=>$_POST['job_duration']);
                set_alert('Ny annons med titel "'.$_POST['title'].'" har skapats','success');
            }else{ // indicate old ad is edited
                // update ad data
                $query = "UPDATE ads SET is_published=?,title=?,job_area=?,city=?,province=?,post=?,qualifications=?,end_date=?"
                    .",job_duration=? where ad_id=? AND company_id=?";
                $param=array(false,$_POST['title'],$_POST['job_area'],$_POST['city'],$_POST['province'],$_POST['post'],
                    $_POST['qualifications'],$_POST['end_date'], $_POST['job_duration'],$ad_id ,$company['company_id']);
                set_alert('Annons med id "'.$ad_id.'" har redigerats','success');
            }
            Db::execute($query,$param);
            Db::close_connection();
        }
        header($url.'manage-ads');

    } /*========================= END UPDATE-ADS  =========================*/

    /*========================= START manage-ads[publish,delete,edit,view,unpublish ads]  =========================*/
    elseif ($action=='manage-ads'){
        Db::connect();
        // fetch all ads from db
        $all_ads = Db::fetch_all("SELECT * FROM ads WHERE company_id=?",array($company['company_id']));
        Db::close_connection();
    ?>
        <?php
        $page_title = "Hantera annonser";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
        <main class="main-content account-page">
            <?= get_alert() ?>
            <section>
                <h3 >Hantera annonser</h3><br>
                <strong>Annonser du har inte publicerad:</strong>
                <ul class="list-group apps-list mt-2"> <!--not published ads-->
                    <?= (get_company_ads($all_ads))?:'<li class="list-group-item bg-light">Finns ej</li>' ?>
                </ul>
                <br/>
                <strong>Annonser du har publicerad</strong>
                <ul class="list-group apps-list mt-2"> <!--published ads-->
                    <?= (get_company_ads($all_ads,true))?:'<li class="list-group-item bg-light">Finns ej</li>' ?>
                </ul>
            </section>
        </main>
        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>
<?php } elseif ($action=='del-ads'){ //delete ads from db
        Db::connect();
        Db::execute("DELETE FROM ads where ad_id=? AND company_id=?",
            array($ad_id,$company['company_id']));
        Db::close_connection();
        header($url.'manage-ads');
    }elseif ($action=='publish-ads'){ // update ad in db and set it to be published
        Db::connect();
        Db::execute("UPDATE ads SET is_published=true, publish_date=? where ad_id=? AND company_id=?",
            array(date("Y-m-d"),$ad_id,$company['company_id']));
        Db::close_connection();
        header($url.'manage-ads');

    }elseif ($action=='unpublish-ads'){ // update ad in db and set it to be unpublished
        Db::connect();
        Db::execute("UPDATE ads SET is_published=false WHERE ad_id=? AND company_id=?",
            array($ad_id,$company['company_id']));
        Db::close_connection();
        header($url.'manage-ads');
    } /*========================= END manage-ads  =========================*/

    /*========================= START MANAGE-APPLICATIONS received from students =========================*/
    elseif($action=='manage-apps'){
        Db::connect();
        // the request here is to delete applications
        if($_SERVER['REQUEST_METHOD']=='POST'){
            // set application as rejected
            $query="DELETE FROM applications where student_id=? AND ad_id=?";
            foreach($_POST as $student_id){
                Db::execute($query,[$student_id, $_POST['ad-id']]);
            }
            set_alert('Valda profiler har raderats', 'success');
        }
        ?>
        <?php
        $page_title = "Hantera Ansökningar";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
        <main class="main-content account-page">
            <?= get_alert() ?>
            <section>
                <h3>Ansökningar</h3><br/>
                <div class="list-group">
                <?php
                if($do =='show-apps'){ // fetch and print all applications for chosen ad

                    $query="SELECT student_id FROM applications NATURAL JOIN ads where ad_id=? AND is_rejected=false ";
                    $apps = Db::fetch_all($query,[$ad_id]);
                    $anchor='<a  class="ms-2 cv-btn" href="cv.php?student-id=%1$d">profil id#%1$d</a>';
                    $checkBox='<input class="form-check-input me-2 " type="checkbox" title="Id %1$d" name="%1$d" value="%1$d" aria-label="cv-profil">';
                    $item = '<div class="list-group-item">'. $checkBox.$anchor.'</div>';
                    // form to delete all selected profiles
                    echo '<form method="POST" action="company.php?action=manage-apps">';
                    foreach ($apps as $row){
                        echo sprintf($item,$row['student_id']);
                    }
                    echo '<input type="hidden" name="ad-id" value="'.$ad_id.'" />';
                    echo '<button type="submit" id="del-apps-submit" class="btn btn-danger mt-2">Radera valda kryssruta</button></form>';
                }else{ // fetch and print each ad with number of applications received and ad id
                    $query="SELECT ad_id,title, COUNT(student_id) FROM applications NATURAL JOIN ads".
                            " where company_id=? AND is_rejected=false GROUP by ad_id";
                    $apps = Db::fetch_all($query,[$company['company_id']]);
                    $anchor='<div class="list-group-item"><h4>%2$s</h4>';
                    $title='<span class="d-block">Annons ID: #%1$d</span>';
                    $count='<span class="d-block"><a href="company.php?action=manage-apps&do=show-apps&ad-id=%1$d">Ansökningar (%3$d)</a></span></div>';
                    $item=$anchor.$title.$count;
                    foreach ($apps as $row){
                        echo sprintf($item,$row['ad_id'],$row['title'],$row['COUNT(student_id)']);
                    }
                }
                if(count($apps)==0){
                    echo '<div class="list-group-item bg-light">Finns ej</div>';
                }
                Db::close_connection();
                ?>
                </div>
            </section>
        </main>

        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>
    <?php } else{ /* DEFAULT  IF THERE ARE NO action */
        echo 'No action';
    }

} else{ /* DEFAULT IF THERE ARE NO SESSION */
    header('location: index.php');
    exit();
}
?>
