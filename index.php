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
    header('Location:' . $redirectURL);
}

if(isset($_SESSION['access_token']) && $_SESSION['access_token']){
    $googleUser = $googlePlus->getProfile($_SESSION['access_token']);
    $loginUser = $user->checkAuth($googleUser['google_plus_id']);

    if ($loginUser != false) {
        $_SESSION['username'] = $loginUser['username'];
        $_SESSION['userid'] = $loginUser['id'];
    } else {
        $userid = $user->register($googleUser);
        $loginUser = $user->getUser($userid);
        $_SESSION['username'] = $loginUser['username'];
        $_SESSION['userid'] = $loginUser['id'];
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="">
            <nav>
                <div class="nav-wrapper">
                    <a href="#" class="brand-logo">Toronto Events</a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down">
                            <?php if(isset($authUrl)) {?>
                            <li><a href="<?=$authUrl?>">Login with Google+</a>
                            <?php } else { ?>
                            <li><a><?=$_SESSION["username"]?></a></li>
                            <li><a href="?logout">Logout</a></li>
                            <?php } ?>
                    </ul>
                </div>
            </nav>
            <div>
                <p>
                    <input type="checkbox" class="filled-in" id="free" />
                    <label for="free">Free Event</label>
                </p>
                <div id="datepicker"></div>
                <input id="date" type="hidden" value="<?=date('Y-m-d')?>" />
            </div>
            <div class="row">
                <div class="col s12 m3">
                    <ul class="tabs">
                        <li class="tab col s6"><a class="active" href="#events">List</a></li>
                        <li class="tab col s6"><a id="map-tab" href="#map">Map</a></li>
                    </ul>
                </div>
                <div id="events" class="col s12 row"></div>
                <div id="map" class="col s12"></div>
            </div>
        </div>
        
        
        <div id="detail" class="modal">
            <div class="modal-content">
                <div class="row">
                    <div class="col s12 m4 l4" id="image">
                        <img src="images/events.jpg" class="responsive-img" />
                    </div>
                    <div class="col s12 m8 l8">
                        <h4 id="event_name"></h4>
                        <p id="event_description"></p>
                    </div>
                </div>
                <div class="row" id="detailMap">
                    
                </div>
            </div>
            <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Agree</a>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCCUfAQlAr-YR9De_ONa1reKPLA2xWuWm8&library=place"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
    <script src="https://npmcdn.com/isotope-layout@3.0/dist/isotope.pkgd.min.js"></script>
    <script type="text/javascript" src="index.js"></script>
</html>