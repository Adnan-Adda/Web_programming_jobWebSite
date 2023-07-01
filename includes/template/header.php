<!DOCTYPE html>
<html lang="sv">
<head>
    <title><?= $site_title . $divider . $page_title; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= CSS_PATH; ?>all.min.css" > <!--fontawesome css file-->
    <link rel="stylesheet" href="<?= CSS_PATH; ?>bootstrap.min.css" > <!--bootstrap css file-->
    <link rel="stylesheet" href="<?= CSS_PATH; ?>custom.css" > <!--my local css file-->
    <script src="<?= JS_PATH; ?>bootstrap.bundle.min.js"></script> <!--bootstrap js file-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?= JS_PATH; ?>script.js"></script>  <!--my local js file-->
</head>
<body>
<!--Start div container -->
<div class="main-container">
    <header class="main-header" id="main-header">
        <?php include_once(TEMPLATE_PATH . 'navbar.php'); ?>
    </header>

