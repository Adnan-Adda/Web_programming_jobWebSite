<?php
 

session_start();
if(isset($_SESSION['username'])){
    header('location: index.php');
}

include_once('config.php');
$page_title="logga in";
include_once(TEMPLATE_PATH . 'header.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $is_Valid = escape_specialchars($_POST,true) && validate_email($_POST,'email');
    if($is_Valid){
        Db::connect();
        // check if user exists
        $query = "SELECT * FROM account WHERE email = ? AND password = ? AND group_type = ?";
        $param = array($_POST['email'], $_POST['password'],$_POST['account-type']);
        $user = Db::fetch_one($query,$param);
        //if user exists register sessions
        if ($user) {
            // set sessions according to user type
            if($_POST['account-type']=='c'){ // company
                $query = "SELECT company_id FROM company WHERE account_id = ?";
                $company = Db::fetch_one($query, array($user['account_id']));
                $_SESSION['company-id'] = $company['company_id'];
            }
            if($_POST['account-type']=='s'){ //student
                $query = "SELECT student_id FROM student WHERE account_id = ?";
                $student = Db::fetch_one($query, array($user['account_id']));
                $_SESSION['student-id'] = $student['student_id'];
            }
            // set sessions
            $_SESSION['username'] = $user['email'];
            $_SESSION['account-id']  = $user['account_id'];
            Db::close_connection();
            header("Location: index.php");
            exit();
        }else {
            set_alert("Fel Uppstått!!</br>Kontrollera inloggning uppgifter", 'error');
            Db::close_connection();
        }
    }
    else{
        set_alert("Skriv in giltig e-post adress", 'error');
    }
}

?>

<div class="membership login-page">
    <?= get_alert(); ?> <!--alert message on success/failure-->
    <h2>Logga in</h2>
    <form  class="needs-validation" novalidate action="login.php" method="POST"> <!--Use bootstrap client side validation-->
        <fieldset>
            <legend>Fyll in kontouppgifter </legend>
            <div class="mb-3 mt-3"> <!--Email-address-->
                <label for="email" class="form-label required">Email-address:</label>
                <input type="email" class="form-control" id="email" placeholder="skriv in email" name="email"  required>
                <div class="invalid-feedback">Fyll i korrekt email-address.</div>
            </div>
            <div class="mb-3"> <!--Lösenord-->
                <label for="pwd" class="form-label required">Lösenord:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Skriv in lösenord" name="password" required>
                <div class="invalid-feedback">Fyll i lösenord.</div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Logga in som:</legend>
            <div class="form-check"> <!--kontotyp-->
                <input type="radio" class="form-check-input" id="radio1" name="account-type" value="s"  checked required>
                <label class="form-check-label" for="radio1">Student</label>
            </div>
            <div class="form-check mt-2">
                <input type="radio" class="form-check-input" id="radio2" name="account-type" value="c" required>
                <label class="form-check-label" for="radio2">Företag</label>
            </div>
        </fieldset>
        <button type="submit" class="btn btn-primary">Skicka</button>
    </form>
</div>

<?php include_once(TEMPLATE_PATH . 'footer.php'); ?>
