<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller {
  public function index() {
    return [
      "status" => 200,
      "data" => []
    ];
  }


  public function import() {
    $events = array();
    $torontoAPIURL = "https://secure.toronto.ca/cc_sr_v1/data/edc_eventcal_APR?limit=1000";
    $response = file_get_contents($torontoAPIURL);
    $data = json_decode($response, true);
    foreach ($data as $rawEvent) {
      $calEvent = $rawEvent['calEvent'];

      $imageUrl = isset($calEvent['image']) ? $calEvent['image']['url'] : null;
      
      $event = array(
        "recId" => $calEvent['recId'],
        "name" => $calEvent['eventName'],
        "desc" => $calEvent['description'],
        "shortDesc" => isset($calEvent['shortDescription'])?$calEvent['shortDescription']:'',
        "category" =>$calEvent['categoryString'],
        "free" => $calEvent['freeEvent'],
        "orgName" => $calEvent['orgName'],
        "contactName" => $calEvent['contactName'],
        "cost" => isset($calEvent['cost'])?$calEvent['cost']:null,
        "imageUrl" => $imageUrl,
        "startDate" => isset($calEvent['startDate'])?$calEvent['startDate']:$calEvent['startDateTime'],
      );
      $events[] = $event;
    }
    return [
      "name" => "import",
      "status" => 200,
      "data" => $events
    ];
  }
}
