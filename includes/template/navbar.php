<!--navigering-->
<nav class="navbar">
    <!--logo-area-->
    <ul class="nav">
        <li class="nav-item">
            <a class="navbar-brand" href="index.php">
                <img alt="website logo" src="<?=IMG_PATH;?>logo2.svg" class="site-logo text-black"><span class="site-name">Student Jobb</span></a>
        </li>
    </ul>

    <!--meny area-->
    <ul class="nav nav-menu">
        <li class="nav-item">
            <a class="nav-link"  href="index.php">Hem <span aria-hidden="true" class="fas fa-house"></span></a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-bs-toggle="dropdown" href="#">Meny <span aria-hidden="true" class="fa-solid fa-align-justify"></span></a>
            <ul class="dropdown-menu dropdown-menu-end menu-list">
                <li class="nav-item signin-small-sc not-logged">
                    <a class="dropdown-item nav-link" href="login.php">Logga in <span aria-hidden="true" class="fa-solid fa-right-to-bracket"></span> </a>
                </li>
                <li class="nav-item signin-small-sc not-logged">
                    <a class="dropdown-item nav-link" href="register.php">Registrera <span aria-hidden="true" class="fa-solid fa-user-plus"></span></a>
                </li>
                <li><a class="dropdown-item nav-link" href="contact.php">Kontakt <span aria-hidden="true" class="fa-solid fa-address-card"></span></a></li>
                <li><a class="dropdown-item nav-link" href="about.php">Om sidan</a></li>
            </ul>
        </li>

        <!--logged in as student-->
        <?php
        if (isset($_SESSION['student-id'])){?>
            <li class="nav-item dropdown logged-in">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">Inloggad <span aria-hidden="true" class="fa-solid fa-screwdriver-wrench"></span></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item nav-link" href="student.php?action=edit-account">Redigera konto</a></li>
                    <li><a class="dropdown-item nav-link" href="student.php?action=create-cv">Skapa CV</a></li>
                    <li><a class="dropdown-item nav-link" href="cv.php?student-id=<?=$_SESSION['student-id']?>">Visa CV</a></li>
                    <li><a class="dropdown-item nav-link" href="student.php?action=manage-apps">Dina ansökningar</a></li>
                    <li><a class="dropdown-item nav-link" href="logout.php">Logga ut <span aria-hidden="true" class="fa-solid fa-arrow-up-right-from-square"></span></a></li>
                </ul>
            </li>

            <!--logged in as company-->
            <?php
        }elseif(isset($_SESSION['company-id'])){?>
            <li class="nav-item dropdown logged-in">
                <a class="nav-link" data-bs-toggle="dropdown" href="company.php?action=account">Inloggad <span aria-hidden="true" class="fa-solid fa-screwdriver-wrench"></span></a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item nav-link" href="company.php?action=edit-account">Redigera konto</a></li>
                    <li><a class="dropdown-item nav-link" href="company.php?action=create-ads">Skapa annons</a></li>
                    <li><a class="dropdown-item nav-link" href="company.php?action=manage-ads">Hantera annonser</a></li>
                    <li><a class="dropdown-item nav-link" href="company.php?action=manage-apps">Hantera ansökningar</a></li>
                    <li><a class="dropdown-item nav-link" href="logout.php">Logga ut <span aria-hidden="true" class="fa-solid fa-arrow-up-right-from-square"></span></a></li>
                </ul>
            </li>

            <?php
        }else { ?>
            <!--Account area-->
            <li class="nav-item signin-big-sc not-logged">  <!--Not logged in-->
                <ul class="nav justify-content-end ">
                    <li class="nav-item ">
                        <a class="nav-link" href="login.php">Logga in <span aria-hidden="true" class="fa-solid fa-right-to-bracket"></span></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="register.php">Registrera <span aria-hidden="true" class="fa-solid fa-user-plus"></span></a>
                    </li>
                </ul>
            </li>

        <?php } ?>
    </ul>
</nav>
