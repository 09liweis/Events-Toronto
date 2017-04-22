<?php

class Event {
    private $db;
    private $apiDomain = 'http://app.toronto.ca';

    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getEvents() {
        $sql = 'SELECT * FROM events ORDER BY startDate ASC';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->execute();
        $events = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }
    
    public function getEvent($id) {
        $sql = 'SELECT * FROM events WHERE id = :id';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':id', $id, PDO::PARAM_INT);
        $pdostmt->execute();
        $event = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $event;
    }
    
    public function getUserEvents($userid) {
        $sql = 'SELECT e.id, e.name, e.lat, e.lng, e.thumbImage, e.freeEvent, e.startDate, e.endDate, ue.user_id FROM events e LEFT JOIN user_events ue ON e.id = ue.event_id AND ue.user_id = :userid';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':userid', $userid, PDO::PARAM_STR);
        $pdostmt->execute();
        $events = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }
    
    public function getDates() {
        $sql = 'SELECT DISTINCT(startDate) FROM events ORDER BY startDate ASC ';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->execute();
        $dates = $pdostmt->fetchAll(PDO::FETCH_COLUMN);
        return $dates;
    }
    
    public function checkEvent($userid, $eventid) {
        $sql = 'SELECT COUNT(*) FROM user_events WHERE user_id = :userid AND event_id = :eventid';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':userid', $userid, PDO::PARAM_STR);
        $pdostmt->bindValue(':eventid', $eventid, PDO::PARAM_STR);
        $pdostmt->execute();
        $result = $pdostmt->fetch(PDO::FETCH_COLUMN);
        return $result;
    }
    
    public function userEvent($userid, $eventid) {
        $count = $this->checkEvent($userid, $eventid);
        if ($count >= 1) {
            $sql = 'DELETE FROM user_events WHERE user_id = :userid AND event_id = :eventid';
            $pdostmt = $this->db->prepare($sql);
            $pdostmt->bindValue(':userid', $userid, PDO::PARAM_STR);
            $pdostmt->bindValue(':eventid', $eventid, PDO::PARAM_STR);
            $pdostmt->execute();
            return array('code' => 200, 'msg' => 'success', 'status' => 'delete');
        } else {
            $sql = 'INSERT INTO user_events (user_id, event_id) VALUES (:userid, :eventid)';
            $pdostmt = $this->db->prepare($sql);
            $pdostmt->bindValue(':userid', $userid, PDO::PARAM_STR);
            $pdostmt->bindValue(':eventid', $eventid, PDO::PARAM_STR);
            $pdostmt->execute();
            return array('code' => 200, 'msg' => 'success', 'status' => 'save');
        }
    }
    
    // public function getOfficesByCountry($country) {
    //     $sql = 'SELECT * FROM offices WHERE country = :country';
    //     $pdostmt = $this->db->prepare($sql);
    //     $pdostmt->bindValue(':country', $country, PDO::PARAM_STR);
    //     $pdostmt->execute();
    //     $offices = $pdostmt->fetchAll();
    //     return $offices;
    // }
    
    public function updateEvents($events) {
        $this->clearEvents();
        $this->insertEvents($events);
    }
    
    public function clearEvents() {
        $sql = 'truncate events';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->execute();
    }
    
    public function insertEvents($events) {
        foreach ($events as $event) {
            $this->insertEvent($event);
        }
    }
    
    public function insertEvent($event) {
        
        $calEvent = $event['calEvent'];
        $name = $calEvent['eventName'];
        $description = $calEvent['description'];
        
        $location = $calEvent['locations'][0]['locationName'];
        $address = $calEvent['locations'][0]['address'];
        $lat = $calEvent['locations'][0]['coords']['lat'];
        $lng = $calEvent['locations'][0]['coords']['lng'];
        
        $startDate = $calEvent['startDate'];
        $endDate = $calEvent['endDate'];
        
        $recId = $calEvent['recId'];
        $reservationsRequired = $calEvent['reservationsRequired'];
        $freeEvent = $calEvent['freeEvent'];
        
        if (isset($calEvent['thumbImage']) && isset($calEvent['thumbImage']['url'])) {
            $thumbImage = $this->apiDomain . $calEvent['thumbImage']['url'];
        } else {
            $thumbImage = '';
        }
        
        if (isset($calEvent['image'])) {
            $image = $this->apiDomain . $calEvent['image']['url'];
        } else {
            $image = '';
        }
        
        $sql = 'INSERT INTO events (name, 
                                    address, 
                                    location, 
                                    lat, 
                                    lng, 
                                    description, 
                                    startDate, 
                                    endDate,
                                    thumbImage, 
                                    image,
                                    recId,
                                    reservationsRequired,
                                    freeEvent
                                    ) 
                                    VALUES (
                                        :name, 
                                        :address, 
                                        :location, 
                                        :lat, 
                                        :lng, 
                                        :description, 
                                        :startDate, 
                                        :endDate,
                                        :thumbImage, 
                                        :image,
                                        :recId,
                                        :reservationsRequired,
                                        :freeEvent
                                        )';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':name', $name, PDO::PARAM_STR);
        $pdostmt->bindValue(':address', $address, PDO::PARAM_STR);
        $pdostmt->bindValue(':location', $location, PDO::PARAM_STR);
        $pdostmt->bindValue(':lat', $lat, PDO::PARAM_STR);
        $pdostmt->bindValue(':lng', $lng, PDO::PARAM_STR);
        $pdostmt->bindValue(':description', $description, PDO::PARAM_STR);
        $pdostmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        $pdostmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        $pdostmt->bindValue(':thumbImage', $thumbImage, PDO::PARAM_STR);
        $pdostmt->bindValue(':image', $image, PDO::PARAM_STR);
        $pdostmt->bindValue(':recId', $recId, PDO::PARAM_STR);
        $pdostmt->bindValue(':reservationsRequired', $reservationsRequired, PDO::PARAM_STR);
        $pdostmt->bindValue(':freeEvent', $freeEvent, PDO::PARAM_STR);
        $pdostmt->execute();
    }
}