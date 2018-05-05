<?php



class API {
    const URL = 'http://app.toronto.ca/cc_sr_v1_app/data/edc_eventcal_APR?limit=1000';
    
    public function getData() {
        $data = json_decode(file_get_contents(self::URL), true);
        return $data;
    }
}