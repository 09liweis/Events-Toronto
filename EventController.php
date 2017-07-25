<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'models/Database.php';
require 'models/GooglePlus.php';
require 'models/API.php';
require 'models/Event.php';

if ($_SERVER['HTTP_HOST'] == 'events-toronto-a09liweis.c9users.io') {
    $redirectURL = 'https://events-toronto-a09liweis.c9users.io/';
} else {
    $redirectURL = 'https://events-toronto.herokuapp.com/';
}

$event = new Event(Database::dbConnect());

header('Content-Type: application/json');

session_start();
$userid = $_SESSION['userid'];

if ($_GET['action'] == 'getEvents') {
    $date = $_GET['date'];
    if (isset($userid)) {
        $events = $event->getUserWithEvents($date, $userid);
    } else {
        $events = $event->getEvents($date);
    }
    echo json_encode($events);
}

if ($_GET['action'] == 'getUserEvents') {
    if (isset($userid)) {
        $events = $event->getUserEvents($userid);
        echo json_encode($events);
    }
}

if ($_GET['action'] == 'getEvent') {
    $eventid = $_GET['eventId'];
    $evt = $event->getEvent($eventid);
    echo json_encode($evt);
}

if ($_GET['action'] == 'updateEvents') {
    $api = new API();
    $data = $api->getData();
    $event->updateEvents($data);
}

if ($_GET['action'] == 'saveEvent') {
    if (isset($userid)) {
        $eventid = $_POST['eventId'];
        $res = $event->userEvent($userid, $eventid);
        echo json_encode($res);
    } else {
        echo json_encode(array('code' => 400, 'msg' => 'You need to log in'));
    }
}

if ($_GET['action'] == 'getDates') {
    $dates = $event->getDates();
    echo json_encode($dates);
}

if ($_GET['action'] == 'getGoogleEvents') {
    $googlePlus = new GooglePlus($redirectURL);
    $googleUser = $googlePlus->getProfile($_SESSION['access_token']);
    $loginUser = $user->checkAuth($googleUser['google_plus_id']);
}