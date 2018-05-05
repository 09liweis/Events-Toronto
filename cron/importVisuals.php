<?php
require '../models/Database.php';
require '../models/Visual.php';

$visual = new Visual(Database::dbConnect());
$url = 'http://whatiwatched-samliweisen.rhcloud.com/visual/visuals_json';
$data = json_decode(file_get_contents($url), true);
$visual->insert($data);