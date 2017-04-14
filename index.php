<?php

require_once __DIR__ .'/vendor/autoload.php';
require_once 'models/GooglePlus.php';
require_once 'models/Database.php';
require_once 'models/User.php';

if ($_SERVER['HTTP_HOST'] == 'xml-finalproject-a09liweis.c9users.io') {
    $redirectURL = 'https://xml-finalproject-a09liweis.c9users.io/';
} else {
    $redirectURL = 'https://xml-finalproject.herokuapp.com/';
}

session_start();
$user = new User(Database::dbConnect());
$googlePlus = new GooglePlus($redirectURL);

if(isset($_REQUEST['logout'])) {
    session_unset();
    session_destroy();
    header('Location:' . $redirectURL);
}

if(isset($_GET['code'])) {
    $accessToken = $googlePlus->getAccessToken($_GET['code']);
    $_SESSION['access_token'] = $accessToken;
    //header('Location:' . $redirectURL);
}

if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
    $googleUser = $googlePlus->getProfile($_SESSION['access_token']);
    $loginUser = $user->checkAuth($googleUser['google_plus_id']);
    if ($loginUser) {
        $_SESSION['username'] = $loginUser['username'];
    } else {
        $userGoogleId = $user->register($googleUser);
        $loginUser = $user->checkAuth($userGoogleId);
        $_SESSION['username'] = $loginUser['username'];
    }
} else {
    $authUrl = $googlePlus->getAuthUrl();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Events in Toronto</title>
        <link rel="icon" type="image/png" href="favicon.png">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="">
            <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
                <header class="mdl-layout__header">
                    <div class="mdl-layout__header-row">
                        <!-- Title -->
                        <span class="mdl-layout-title">Title</span>
                        <!-- Add spacer, to align navigation to the right -->
                        <div class="mdl-layout-spacer"></div>
                        <!-- Navigation. We hide it in small screens. -->
                        <nav class="mdl-navigation mdl-layout--large-screen-only">
                            <?php if(isset($authUrl)) {?>
                            <a class="mdl-navigation__link" href="<?=$authUrl?>">Login with Google+</a>
                            <?php } else { ?>
                            <a class="mdl-navigation__link"><?=$_SESSION["username"]?></a>
                            <a class="mdl-navigation__link" href="?logout">Logout</a>
                            <?php } ?>
                        </nav>
                    </div>
                </header>
                <div class="mdl-layout__drawer">
                    <span class="mdl-layout-title">Title</span>
                    <nav class="mdl-navigation">
                        <?php if(isset($authUrl)) {?>
                        <a class="mdl-navigation__link" href="<?=$authUrl?>">Login with Google+</a>
                        <?php } else { ?>
                        <a class="mdl-navigation__link"><?=$_SESSION["username"]?></a>
                        <a class="mdl-navigation__link" href="?logout">Logout</a>
                        <?php } ?>
                    </nav>
                </div>
                <main class="mdl-layout__content">
                    <div class="page-content wrapper">
                        <div>
                            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="free">
                                <input type="checkbox" id="free" class="mdl-checkbox__input">
                                <span class="mdl-checkbox__label">Free Events</span>
                            </label>
                            <div id="datepicker"></div>
                            <input id="date" type="hidden" value="<?=date('Y-m-d')?>" />
                        </div>
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                            <div class="mdl-tabs__tab-bar">
                                <a href="#list" class="mdl-tabs__tab is-active">List View</a>
                                <a href="#map" class="mdl-tabs__tab" id="map-tab">Map View</a>
                            </div>
                            <div class="mdl-tabs__panel is-active" id="list">
                                <div class="mdl-grid" id="events">
                                    
                                </div>
                            </div>
                            <div class="mdl-tabs__panel" id="map">
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCUfAQlAr-YR9De_ONa1reKPLA2xWuWm8&library=place"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="index.js"></script>
</html>