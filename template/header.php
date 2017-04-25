<?php
require_once 'models/IPGeolocation.php';
$currentLocation = IPLocation::getLocation();

session_start();

if(isset($_REQUEST['logout'])) {
    session_unset();
    session_destroy();
    header('Location:' . $redirectURL);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Events in Toronto</title>
        <link rel="icon" type="image/png" href="favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="../style.css" type="text/css" />
    </head>
    <body>
        <nav>
            <div class="nav-wrapper">
                <a href="/" class="brand-logo">Toronto Events</a>
                <ul id="nav-mobile" class="right">
                    <li><a href="/">All Events</a></li>    
                    <?php if(isset($authUrl)) {?>
                    <li><a href="<?=$authUrl?>">Login with Google+</a>
                    <?php } else { ?>
                    <li><a href="user.php"><?=$_SESSION["username"]?></a></li>
                    <li><a href="/?logout=true">Logout</a></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>