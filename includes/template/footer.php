<!--sidfot-->
<footer class="main-footer"><hr/>
    <div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link"  href="index.php">Hem <span aria-hidden="true" class="fas fa-house"></span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">Om sidan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.php">Kontakta</a>
            </li>
        </ul>
        <div id="social-icons">
            <p>Följa oss på sociala media</p>
            <a href="#" title="facebook"><img class="site-logo" src="<?= IMG_PATH?>facebook.png" alt="facebook"></a>
            <a href="#" title="instagram"><img class="site-logo" src="<?= IMG_PATH?>instagram.png" alt="instagram"></a>
        </div>
    </div>

</footer>
<!--print current time-->
<div id="date"><span><?= date("l jS F Y") ?> </span></div>
</div>
<!--End div container -->

</body>
</html>