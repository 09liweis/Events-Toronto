<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    public function import() {
        $api = 'http://app.toronto.ca/cc_sr_v1_app/data/edc_eventcal_APR?limit=1000';
        $importEvents = json_decode(file_get_contents($api), true);
        return $importEvents;
    }
}
