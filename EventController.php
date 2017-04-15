<?php

require 'models/Database.php';
require 'models/API.php';
require 'models/Event.php';

$event = new Event(Database::dbConnect());

header('Content-Type: application/json');

session_start();
$userid = $_SESSION['userid'];

if ($_GET['action'] == 'getEvents') {
    if (isset($userid)) {
        //$events = $event->getUserEvents($userid);
        $events = $event->getEvents();
    } else {
        $events = $event->getEvents();
    }
    echo json_encode($events);
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
    }
}

if ($_GET['action'] == 'getDates') {
    $dates = $event->getDates();
    echo json_encode($dates);
}