<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller {
  public function index() {
    return [
      "status" => 1,
      "data" => []
    ];
  }


  public function import() {
    $events = array();
    $apiUrl = "https://secure.toronto.ca/cc_sr_v1/data/edc_eventcal_APR?limit=5";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    foreach ($data as $rawEvent) {
      $calEvent = $rawEvent['calEvent'];
      $event = array(
        "recId" => $calEvent['recId'],
        "name" => $calEvent['eventName'],
        "category" =>$calEvent['categoryString'],
        "free" => $calEvent['freeEvent'],
      );
      $events[] = $event;
    }
    return [
      "name" => "import",
      "status" => 1,
      "data" => $events
    ];
  }
}
