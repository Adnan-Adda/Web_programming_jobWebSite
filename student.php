<?php
 

session_start();
if(isset($_SESSION['student-id'])){
    include_once('config.php');
    escape_specialchars($_POST);
    escape_specialchars($_GET);
    //check if GET, ads id are set
    $action = (isset($_GET['action']))? $_GET['action'] : '';
    $ad_id = (isset($_GET['ad-id']))?  intval($_GET['ad-id']) : 0;
    $url = 'location:student.php?action='; // url for redirections
    /*========================= START EDIT-ACCOUNT  =========================*/
    if($action=='edit-account'){ ?>
        <?php
        $page_title = "Redigera konto";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
        <div class="membership">
            <?= get_alert(); ?> <!--alert message on success/failure-->
            <h2>Redigera konto</h2>
            <form  class="needs-validation" novalidate action="student.php?action=update-account" method="POST">
                    <h3>Ändra kontouppgifter (valfri)</h3>
                    <div class="mb-3 mt-3"><!--Email-address-->
                        <label for="email" class="form-label">Email-address:</label>
                        <input type="email" class="form-control"
                               value="<?= $_SESSION['username']?>" id="email"
                               placeholder="skriv in email" name="email"  required>
                        <div class="invalid-feedback">Fyll i korrekt email-address.</div>
                    </div>

                    <h3>Ändra lösenord (valfri)</h3>
                    <div class="mb-3"><!--Ny Lösenord-->
                        <label for="pwd" class="form-label">Ny Lösenord:</label>
                        <input type="password" class="form-control" id="pwd"
                               placeholder="Skriv in ny lösenord"  name="password" >
                        <div class="invalid-feedback">Fyll i lösenord.</div>
                    </div>
                    <div class="mb-3"><!--Bekräfta lösenord-->
                        <label for="pwd-confirm" class="form-label">Bekräfta lösenord:</label>
                        <input type="password" class="form-control" id="pwd-confirm"
                               placeholder="bekräfta lösenord" name="passwordConf" >
                        <div class="invalid-feedback">Repetera lösenordet.</div>
                        <div class="invalid invalid-pwd hide ">Lösenord matchar ej</div> <!--show error if password not match-->
                    </div>
                <button type="submit" id="signup-submit"  class="btn btn-primary">Skicka</button>
                <div class="ms-2 mb-2 me-2 mt-3" > <!--Radera konto-->
                    <a id="del-account" href="student.php?action=del-account"><span aria-hidden="true" class="fa-solid fa-trash-can fa-lg text-red"></span> Radera konto</a>
                </div>
            </form>
        </div>
        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>
    <?php } /*========================= END EDIT-ACCOUNT  =========================*/

    /*========================= START UPDATE-ACCOUNT  =========================*/
    elseif($action=='update-account'){
        if($_SERVER['REQUEST_METHOD'] == 'POST' && validate_email($_POST,'email')){
            Db::connect();
            // check if the new email-address if any is already exists
            $query="SELECT COUNT(*) FROM account where email=? AND account_id != ?";
            $param=array($_POST['email'],$_SESSION['account-id']);
            $row_count = Db::fetch_one($query,$param);
            // if the new email-address is unique perform update
            if($row_count['COUNT(*)']==0 || $_POST['email']==$_SESSION['username']){
                // update email-address,password(database triggers when password is empty)
                $query = " UPDATE account SET email=?,password=? WHERE account_id=? ";
                $param = array($_POST['email'],$_POST['password'],$_SESSION['account-id']);
                Db::execute($query,$param);
                //update session
                $_SESSION['username'] = $_POST['email'];
                set_alert('Konto har uppdaterat','success');
            }else{
                set_alert('Email-adress"'. $_POST['email'] .'"redan registrerad','error');
            }
        }else{
            set_alert('Du skrev ogiltig e-post adress','error');
        }
        Db::close_connection();
        header("location:student.php?action=edit-account");
        /*========================= END UPDATE-ACCOUNT  =========================*/


        /*========================= START DELETE-ACCOUNT  =========================*/
    }elseif ($action=='del-account'){
        Db::connect();
        Db::execute("DELETE FROM account WHERE account_id=?",[$_SESSION['account-id']]);
        Db::close_connection();
        header('location: logout.php');

    }elseif ($action=='create-cv') { /*========================= START CREATE-CV  =========================*/
        Db::connect();
        // fetch cv data if any
        $query = "SELECT * FROM student WHERE student_id = ?";
        $student = Db::fetch_one($query, array($_SESSION['student-id']));
        Db::close_connection();
        ?>
        <?php
        $page_title = "SKapa cv";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
        <div class="membership">
            <?= get_alert(); ?> <!--alert message on success/failure-->
            <h2>Skapa/Redigera CV profil</h2>
            <form action="student.php?action=update-cv" method="POST">
                    <h3>Fyll i dina uppgifter </h3>
                    <div class="mb-3 mt-3"> <!--Email-address-->
                        <label for="email" class="form-label">Email-address</label>
                        <input type="email" class="form-control" name="cv-email"
                               value="<?=get_value($student,'cv_email')?>" id="email">
                    </div>

                    <div class="mb-3 mt-3"> <!--Namn-->
                        <label for="name" class="form-label">Namn</label>
                        <input type="text" class="form-control"  id="name" name="student_name"
                               value="<?=get_value($student,'student_name')?>">
                    </div>

                    <div class="mb-3 mt-3"> <!--phone-number-->
                        <label for="phone-num" class="form-label">phone-number</label>
                        <input type="text" class="form-control"  id="phone-num" name="phone-num"
                               value="<?=get_value($student,'phone_num')?>">
                    </div>

                    <div class="mb-3 mt-3"> <!--Sammanfattning-->
                        <label for="summary" class="form-label">Sammanfattning</label>
                        <textarea class="form-control" rows="5" id="summary"
                                  name="summary" ><?=get_value($student,'summary')?></textarea>
                    </div>

                    <div class="mb-3 mt-3"> <!--Utbildning-->
                        <label for="education" class="form-label">Utbildning</label>
                        <textarea class="form-control" rows="5" id="education"
                                  name="education"><?=get_value($student,'education')?></textarea>
                    </div>

                    <div class="mb-3 mt-3"><!--Färdigheter/kompetenser-->
                        <label for="skills" class="form-label">Färdigheter/kompetenser</label>
                        <textarea class="form-control" rows="5" id="skills"
                                  name="skills"><?=get_value($student,'skills')?></textarea>
                    </div>

                    <div class="mb-3 mt-3"> <!--Övrig-->
                        <label for="other" class="form-label">Övrig</label>
                        <textarea class="form-control" rows="5" id="other"
                                  name="other"><?=get_value($student,'other')?></textarea>
                    </div>

                <button type="submit" class="btn btn-primary">Spara</button>
            </form>
        </div>
        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>
    <?php } /*========================= END CREATE-CV  =========================*/

    /*========================= START update-CV  =========================*/
    elseif ($action=='update-cv'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            Db::connect();
            //update cv data in db
            $query = "UPDATE student SET student_name=?, phone_num=?,cv_email=?, education=?, skills=?, summary=?,other=? WHERE student_id=? ";
            $param = array($_POST['student_name'],$_POST['phone-num'],$_POST['cv-email'],$_POST['education'],
                $_POST['skills'],$_POST['summary'],$_POST['other'],$_SESSION['student-id']);
            Db::execute($query,$param);
            Db::close_connection();
            set_alert('Uppgifterna har uppdaterats','success');
        }
        header('location:student.php?action=create-cv');

    } /*========================= END update-CV  =========================*/

    /*========================= START MANAGE-APPLICATION  =========================*/
    elseif($action=='manage-apps'){
        Db::connect();
        // fetch all sent applications
        $applications = Db::fetch_all("SELECT title,ad_id,send_date FROM applications NATURAL JOIN ads where student_id=?",[$_SESSION['student-id']]);
        Db::close_connection();
     ?>
        <?php
        $page_title = "Ansökningar";
        include_once(TEMPLATE_PATH . 'header.php');
        ?>
        <main class="main-content account-page">
            <?= get_alert(); ?> <!--alert message on success/failure-->
            <section>
                <h3>Jobb du har sökt</h3><br>
                <?php
                // print all sent applications together with delete btn and send date.
                $item='<article>'.
                    '<h4><a href="ads.php?ad-id=%1$d">%2$s</a></h4>'.
                    '<p>%3$s</p>'.
                    '<a class="btn btn-danger me-2 mt-2" href="student.php?action=del-app&ad-id=%1$d">Ta bort ansökan</a>'.
                    '</article><hr/>';
                $data='';
                foreach ($applications as $row) {
                    $data.= sprintf($item,$row['ad_id'],$row['title'],$row['send_date']);
                }
                $div='<div class="alert mt-2" id="result-info">Finns ej</div>'; // inform if there is no applications
                echo (count($applications)==0)? $div:$data;
                ?>
            </section>
        </main>
        <?php
        include_once(TEMPLATE_PATH . "right_sidebar.php");
        include_once(TEMPLATE_PATH . 'footer.php');
        ?>

    <?php }elseif ($action=='send-cv'){ // update application table with new tuple
        if($_SERVER['REQUEST_METHOD']=='POST'){
            Db::connect();
            // first check if the application is already sent, if not perform update
            $query = "SELECT COUNT(*) FROM applications where student_id=:student_id AND ad_id=:ad_id";
            $param = array('student_id'=>$_SESSION['student-id'],'ad_id'=>$ad_id);
            $row_count=Db::fetch_one($query,$param);
            if($row_count['COUNT(*)']==0){
                $query="INSERT INTO applications(student_id,ad_id) VALUES(:student_id,:ad_id) ";
                Db::execute($query,$param);
            }
            set_alert('Ansökan har skickats','success');
            Db::close_connection();
        }
        header('location:ads.php?ad-id='.$ad_id);
    }elseif ($action=='del-app' && $ad_id !=0){ // remove application from db
        Db::connect();
        Db::execute("DELETE FROM applications WHERE ad_id=? AND student_id=?",[$ad_id,$_SESSION['student-id']]);
        Db::close_connection();
        set_alert('Ansökan har raderats','success');
        header($url.'manage-apps');
    }
    else{ /* DEFAULT  IF THERE ARE NO action */
        echo 'No action';
    }

}else{ /* DEFAULT IF THERE ARE NO SESSION */
    header('location: login.php');
    exit();
}
