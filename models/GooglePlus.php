<?php

define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR, 'email')
));

class GooglePlus {
    const CLIENT_ID = '433300294368-sp6f150psm0akdkomhn80laqqmd5fukh.apps.googleusercontent.com';
    const CLIENT_SECRET = '1QwJraMuCR0FG8mexKoLXhl-';
    private $client;
    
    public function __construct($redirectURL) {
        
        $this->client = new Google_Client();
        $this->client->setClientId(self::CLIENT_ID);
        $this->client->setClientSecret(self::CLIENT_SECRET);
        $this->client->setRedirectURi($redirectURL);
        $this->client->setScopes(SCOPES);
    }
    
    public function getProfile($accessToken) {
        $this->client->setAccessToken($accessToken);
        
        $plus = new Google_Service_Plus($this->client);
        $me = $plus->people->get('me');
        $username = $me->displayName;
        $profileimage = $me->image->url;
        $google_plus_id = $me->id;
        return array(   'username' => $username, 
                        'profileimage' => $profileimage,
                        'google_plus_id' => $google_plus_id);
    }
    
    public function getAccessToken($code) {
        $this->client->authenticate($code);
        $accessToken = $this->client->getAccessToken();
        return $accessToken;
    }
    
    public function getAuthUrl() {
        return $this->client->createAuthUrl();
    }
    
    public function getCalendar() {
        $calendar = new Google_Service_Calendar($this->client);
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => date('c'),
        );
        $results = $calendar->events->listEvents($calendarId, $optParams);
        var_dump($results->getItems());die;
        return $results;
    }
}