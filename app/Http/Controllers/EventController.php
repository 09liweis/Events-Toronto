<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    public function import() {
        $api = 'http://app.toronto.ca/cc_sr_v1_app/data/edc_eventcal_APR?limit=1000';
        $apiDomain = 'https://secure.toronto.ca';
        $importEvents = json_decode(file_get_contents($api), true);
        
        foreach($importEvents as $rawEvent) {
            $e = $rawEvent['calEvent'];
            $recId = $e['recId'];
            $event = Event::where('rec_id', $recId)->first();
            if (!$event) {
                $event = new Event;    
            }
            $event->name = $e['eventName'];
            $event->description = $e['description'];
            $event->location = $e['locations'][0]['locationName'];
            if (isset($e['locations'][0]['address'])) {
                $event->address = $e['locations'][0]['address'];
                $event->lat = $e['locations'][0]['coords']['lat'];
                $event->lng = $e['locations'][0]['coords']['lng'];
            }
            
            $event->start_date = $e['startDate'];
            $event->end_date = $e['endDate'];
            
            $event->rec_id = $e['recId'];
            $event->reservations_required = $e['reservationsRequired'];
            $event->free = $e['freeEvent'];
            $event->website = '';
            if (isset($e['eventWebsite'])) {
                $event->website = $e['eventWebsite'];
            }
            
            if (isset($e['thumbImage']) && isset($e['thumbImage']['url'])) {
                $event->thumbnail = $apiDomain . $e['thumbImage']['url'];
            } else {
                $event->thumbnail = '';
            }
            
            if (isset($e['image'])) {
                $event->image = $apiDomain . $e['image']['url'];
            } else {
                $event->image = '';
            }
            
            $event->save();
        }
        return 'done';
    }
}
