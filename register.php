<?php
 

session_start();
if(isset($_SESSION['username'])){
    header('location: index.php');
}

include_once('config.php');
$page_title="skapa konto";
include_once(TEMPLATE_PATH . 'header.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $is_Valid = escape_specialchars($_POST, false) && validate_email($_POST,'email');
    if($is_Valid){
        Db::connect();
        // check if email already exists
        $query_select = "SELECT COUNT(*),account_id FROM account WHERE email = ?";
        $param_select = array($_POST['email']);
        $rows_count = Db::fetch_one($query_select, $param_select);
        // if email not exists in db process registration
        if($rows_count['COUNT(*)']==0){
            // add user to db, triggers are used in db to update foreign key in student/company table
            $query = "INSERT INTO account(email,password,group_type) VALUES(:email,:password,:group_type)";
            $param = array('email'=> $_POST['email'], 'password'=> $_POST['password'], 'group_type'=> $_POST['account-type']);
            Db::execute($query,$param);
            // if user is company add logo and name to db
            if(isset($_POST['company_name'])){
                $query = " UPDATE company NATURAL JOIN account SET company_name=?, logo=? WHERE email=?";
                $param = array($_POST['company_name'],upload_img(),$_POST['email']);
                Db::execute($query,$param);
            }
            Db::close_connection();
            header("Location: login.php");
            exit();
        }else{
            set_alert("Email adress redan registrerad", 'error');
            Db::close_connection();}
    } else{
        set_alert("Skriv in giltig e-post adress", 'error');
    }
}
?>

<div class="membership login-page">
    <?= get_alert(); ?> <!--alert message on success/failure-->
    <h2>Skapa konto</h2>
    <!--Use bootstrap client side validation-->
    <form  class="needs-validation" novalidate action="register.php" enctype="multipart/form-data" method="POST">
        <fieldset>
            <legend>Fyll in kontouppgifter </legend>
            <div class="mb-3 mt-3"> <!--Email-address-->
                <label for="email" class="form-label required">Email-address:</label>
                <input type="email" class="form-control" value="<?=get_value($_POST,'email')?>"
                       id="email" placeholder="skriv in email" name="email"  required>
                <div class="invalid-feedback">Fyll i korrekt email-address.</div>
            </div>
            <div class="mb-3"> <!--Lösenord-->
                <label for="pwd" class="form-label required">Lösenord:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Skriv in lösenord" name="password" required>
                <div class="invalid-feedback">Fyll i lösenord.</div>
            </div>
            <div class="mb-3"> <!--Bekräfta lösenord-->
                <label for="pwd-confirm" class="form-label required">Bekräfta lösenord:</label>
                <input type="password" class="form-control" id="pwd-confirm" placeholder="bekräfta lösenord" name="passwordConf" required>
                <div class="invalid-feedback">Repetera lösenordet.</div>
                <div class="invalid invalid-pwd hide ">Lösenord matchar ej</div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Välj konto typ</legend>
            <div class="form-check"> <!--kontotyp-->
                <input type="radio" class="form-check-input" id="radio1" name="account-type" value="s"  required>
                <label class="form-check-label" for="radio1">Student</label>
            </div>
            <div class="form-check mt-2">
                <input type="radio" class="form-check-input" id="radio2" name="account-type" value="c" required>
                <label class="form-check-label" for="radio2">Företag</label>
            </div>
        </fieldset>

        <div class="company-info hide">
            <fieldset>
                <legend>Företag uppgifter </legend>
                <div class="mb-3 mt-3"> <!--Företag namn-->
                    <label for="company-name" class="form-label required">Företag namn:</label>
                    <input type="text" class="form-control" id="company-name" placeholder="skriv in namn"
                           name="company_name" required>
                    <div class="invalid-feedback">Fältet kan ej lämnas tomt.</div>
                </div>
                <div class="mb-3 mt-3 "> <!--START LOGO AREA-->
                    <label for="logo-upload" class="form-label">Företagslogo:</label><br/>
                    <!--logo upload input used with button to show uploaded file name-->
                    <input type="file" class="form-control " id="logo-upload" name="fileToUpload" accept=".jpg, .jpeg, .png , .svg" hidden />
                    <button type="button" class="btn btn-primary me-2" id="btn-logo-upload" aria-label="upload logo">Ladda upp filen</button>
                    <button type="button" id="del-file" class="btn btn-danger hide"
                            aria-label="Rensa filen">Rensa <span aria-hidden="true" class="fa-solid fa-xmark"></span></button>
                    <div id="file-info"></div>
                </div>
            </fieldset>
        </div>
        <button type="submit" id="signup-submit"  class="btn btn-primary">Skicka</button>
    </form>
</div>

<?php include_once(TEMPLATE_PATH . 'footer.php'); ?>
