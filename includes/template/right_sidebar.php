<!--right sidebar-->
<?php
$filename = basename($_SERVER['PHP_SELF']);
if($filename!='index.php' && isset($_SESSION['company-id'])){
    // if logged in as company show options in right sidebar
    ?>
    <aside class="right-sidebar account-page">
        <h3>Kontomenyn</h3>
        <ul class="list-group">
            <li class="list-group-item list-group-item-action">
                <a class=" nav-link" href="company.php?action=edit-account">Redigera konto</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class="nav-link" href="company.php?action=create-ads">Skapa annons</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class=" nav-link" href="company.php?action=manage-ads">Hantera annonser</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class=" nav-link" href="company.php?action=manage-apps">Hantera ansökningar</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class="nav-link" href="logout.php">Logga ut</a>
            </li>
        </ul>

    </aside>
<?php }elseif ($filename!='index.php' && isset($_SESSION['student-id'])){
    // if logged in as student show right sidebar
    ?>
    <aside class="right-sidebar">
        <ul class="list-group">
            <li class="list-group-item list-group-item-action">
            <a class="nav-link" href="student.php?action=edit-account">Ändra konto uppgifter</a>
            </li>
            <li class="list-group-item list-group-item-action">
            <a class=" nav-link" href="student.php?action=create-cv">Skapa CV</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class=" nav-link" href="cv.php?student-id=<?=$_SESSION['student-id']?>">Visa CV</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class=" nav-link" href="student.php?action=manage-apps">Dina ansökningar</a>
            </li>
            <li class="list-group-item list-group-item-action">
                <a class="nav-link" href="logout.php">Logga ut</a>
            </li>
        </ul>
    </aside>
<?php } else{
    // // if current page is index.php show search form
    ?>
    <aside class="right-sidebar" id="right-sidebar">

    </aside>
<?php }?>