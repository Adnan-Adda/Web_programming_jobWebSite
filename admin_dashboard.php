<?php
session_start();
include_once('config.php');
if(isset($_SESSION['group_type']) && $_SESSION['group_type']=='a'){
    $action = (isset($_GET['action']))? $_GET['action'] : '';
    $id = (isset($_GET['id']))?  intval($_GET['id']) : 0; //account-id/ad-id
    $company_account=''; // to store all company accounts in table
    $student_account=''; // to store all student accounts in table
    $all_ads='';
    Db::connect();
    if($action=='del-account'){ // delete specified account
        Db::execute("DELETE FROM account WHERE account_id=?",array($id));
    }if($action=='del-ad'){ // delete specified ad
        Db::execute("DELETE FROM ads WHERE ad_id=?",array($id));
    }
    // fetch all accounts and set them in tables
    $accounts = Db::fetch_all("SELECT email,group_type,account_id FROM account");
    $ads = Db::fetch_all("SELECT title,ad_id,company_id,account_id FROM ads NATURAL JOIN company where is_published=true");
    Db::close_connection();
    // cell in the table
    $account_row='<tr id="%1$d"><td>%1$d</td><td>%2$s</td><td><a href="admin_dashboard.php?action=del-account&id=%1$d">Ta bort</a></td></tr>';
    foreach ($accounts as $account){
        if($account['group_type']=='c'){
            $company_account .= sprintf($account_row,$account['account_id'],$account['email']);
        }
        if($account['group_type']=='s'){
            $student_account .= sprintf($account_row,$account['account_id'],$account['email']);
        }
    }
    // fetch all published ads adn set them in table
    // 1=>title, 2=>ad_id, 3=>company_id, 4=>account_id
    $ad_row ='<tr><td>%1$s</td><td>%2$d</td><td>%3$d</td><td><a class="text-decoration-none" href="#%4$d">%4$d</a>'.
        '</td><td><a href="admin_dashboard.php?action=del-ad&id=%2$d">Ta bort</a></td></tr>';
    foreach ($ads as $ad){
        $all_ads .=sprintf($ad_row,$ad['title'],$ad['ad_id'],$ad['company_id'],$ad['account_id']);
    }
    ?>
    <?php
    $page_title='dashboard';
    include_once(TEMPLATE_PATH . 'header.php');
    ?>
    <main class="main-content account-page dashboard">
        <section> <!--All company account table-->
            <h3>Företags konton</h3>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Konto ID</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ta bort</th>
                </tr>
                </thead>

                <?php
                echo $company_account;
                ?>
            </table>
        </section>

        <section> <!--All Student account table-->
            <h3>Student konton</h3>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Konto ID</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ta bort</th>
                </tr>
                </thead>
            <?php echo $student_account?>
            </table>
        </section>

        <section> <!--All ads table-->
            <h3>Alla publicerad annonser</h3>
            <table class="table table-hover" >
                <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Annons ID</th>
                    <th scope="col">Company ID</th>
                    <th scope="col">Account ID</th>
                    <th scope="col">Ta bort</th>
                </tr>
                </thead>
                <?php echo $all_ads ?>
            </table>
        </section>
    </main>
    <?php include_once(TEMPLATE_PATH . 'footer.php'); ?>
    <?php

}else{
    //login page
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $is_Valid = escape_specialchars($_POST,true) && validate_email($_POST,'email');
        if($is_Valid){
            Db::connect();
            // check if user exists
            $query = "SELECT * FROM account WHERE email = ? AND password = ? AND group_type='a'";
            $param = array($_POST['email'], $_POST['password']);
            $user = Db::fetch_one($query,$param);
            //if user exists register sessions
            if ($user) {
                // set sessions
                $_SESSION['group_type'] = $user['group_type'];
                $_SESSION['account-id']  = $user['account_id'];
                Db::close_connection();
                header("Location: admin_dashboard.php");
                exit();
            }else {
                set_alert("Fel Uppstått!!</br>Kontrollera inloggning uppgifter", 'error');
                Db::close_connection();
            }
        }
        else{
            set_alert("Skriv in giltig e-post adress", 'error');
        }
    }?>
    <?php
    $page_title="dashboard login";
    include_once(TEMPLATE_PATH . 'header.php'); // include page header
    ?>
    <div class="main-content membership login-page account-page dashboard">
        <?= get_alert(); ?> <!--alert message on success/failure-->
        <h2>Logga in som Admin</h2>
        <form  class="needs-validation" novalidate action="admin_dashboard.php" method="POST">
            <div class="mb-3 mt-3">
                <label for="email" class="form-label required">Email-address:</label>
                <input type="email" class="form-control" id="email" placeholder="skriv in email" name="email"  required>
                <div class="invalid-feedback">Fyll i korrekt email-address.</div>
            </div>
            <div class="mb-3">
                <label for="pwd" class="form-label required">Lösenord:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Skriv in lösenord" name="password" required>
                <div class="invalid-feedback">Fyll i lösenord.</div>
            </div>
            <button type="submit" class="btn btn-primary">Skicka</button>
        </form>
    </div>

    <?php include_once(TEMPLATE_PATH . 'footer.php'); ?>
    <?php } ?>
