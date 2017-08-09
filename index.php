<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'models/GooglePlus.php';
require_once 'models/Database.php';
require_once 'models/User.php';

if ($_SERVER['HTTP_HOST'] == 'events-toronto-a09liweis.c9users.io') {
    $redirectURL = 'https://events-toronto-a09liweis.c9users.io/';
} else {
    $redirectURL = 'https://events-toronto.herokuapp.com/';
}

session_start();
//when there is google auth error, use destroy to reset session
//session_destroy();
$user = new User(Database::dbConnect());
$googlePlus = new GooglePlus($redirectURL);

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

include 'template/header.php';

?>
<div ng-view></div>
<?php
$scripts = array(
    //'https://maps.googleapis.com/maps/api/js?key=AIzaSyCCUfAQlAr-YR9De_ONa1reKPLA2xWuWm8',
    'https://code.jquery.com/jquery-3.2.1.min.js',
    'https://code.jquery.com/ui/1.12.1/jquery-ui.js',
    'https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js',
    'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.5/angular.js',
    'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-route.js',
    '../js/app.js',
);
include 'template/footer.php';
?>