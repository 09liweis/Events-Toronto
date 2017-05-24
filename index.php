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
        <div class="row">
            <div class="col right s12 m4">
                <p>
                    <input type="checkbox" class="filled-in" id="free" />
                    <label for="free">Free Event</label>
                </p>
                <p>
                    <input type="checkbox" class="filled-in" id="long-run" />
                    <label for="long-run">Hide long-running events</label>
                </p>
                <div id="datepicker"></div>
                <input id="date" type="hidden" value="<?=date('Y-m-d')?>" />
            </div>
            <div id="events" class="col s12 m8">
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
                <div class="row" id="detailMapContainer">
                    <div id="detailMap"></div>
                    <div id="route"></div>
                </div>
            </div>
        </div>
<?php
include 'template/footer.php';
?>