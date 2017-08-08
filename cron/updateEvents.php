<?php
require '../models/Database.php';
require '../models/API.php';
require '../models/Event.php';

$event = new Event(Database::dbConnect());
$api = new API();
$data = $api->getData();
$event->updateEvents($data);