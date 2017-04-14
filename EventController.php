<?php

require 'models/Database.php';
require 'models/API.php';
require 'models/Event.php';

$event = new Event(Database::dbConnect());

header('Content-Type: application/json');

if ($_GET['action'] == 'getEvents') {
    $events = $event->getEvents();
    echo json_encode($events);
}

if ($_GET['action'] == 'updateEvents') {
    $api = new API();
    $data = $api->getData();
    $event->updateEvents($data);
}

if ($_GET['action'] == 'getDates') {
    $dates = $event->getDates();
    echo json_encode($dates);
}