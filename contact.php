<?php
session_start();
include_once('config.php');
$page_title="kontakta";
include_once(TEMPLATE_PATH . 'header.php');
?>
    <main class="membership login-page">
        <h2>Kontakta oss</h2>
        <form>
            <div class="mb-3 mt-3">
                <label for="email" class="form-label">Email-address</label>
                <input type="email" id="email" class="form-control" />
            </div>
            <div class="mb-3 mt-3"> <!--ärande-->
                <label for="case" class="form-label">Ärende</label>
                <input type="text" class="form-control" id="case"/>
            </div>
            <div class="mb-3 mt-3"> <!--kontakt meddelande-->
                <label for="message" class="form-label">Meddelande</label>
                <textarea class="form-control" rows="6" id="message"></textarea>
            </div>
            <div class="mb-3 mt-3">
                <button type="submit" id="submit-ads" class="btn btn-primary" >skicka (ur funktion)</button>
            </div>
        </form>
    </main>
<?php

include_once(TEMPLATE_PATH . 'footer.php'); // include page header
?>
