<?php

require 'Category.php';

class Event {
    private $db;
    private $apiDomain = 'https://secure.toronto.ca';

    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getEvents($date) {
        //$sql = 'SELECT * FROM events WHERE startDate <= :date AND endDate >= :date ORDER BY startDate ASC';
        $sql = 'SELECT * FROM events WHERE startDate = :date ORDER BY startDate ASC';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':date', $date, PDO::PARAM_STR);
        $pdostmt->execute();
        $events = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($events as &$event) {
            $event['categories'] = $this->getCategoriesByEventId($event[id]);
        }
        return $events;
    }
    
    public function getCategoriesByEventId($eventId) {
        $sql = 'SELECT c.id AS id, c.name AS name FROM categories AS c JOIN event_category AS ec ON c.id = ec.category_id WHERE ec.event_id = :eventId';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':eventId', $eventId, PDO::PARAM_STR);
        $pdostmt->execute();
        $categories = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }
    
    public function getEvent($id) {
        $sql = 'SELECT * FROM events WHERE id = :id';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':id', $id, PDO::PARAM_INT);
        $pdostmt->execute();
        $event = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $event;
    }
    
    public function getEventRecId($id) {
        $sql = 'SELECT id FROM events WHERE recId = :id';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':id', $id, PDO::PARAM_INT);
        $pdostmt->execute();
        $eventId = $pdostmt->fetch(PDO::FETCH_COLUMN);
        return $eventId;
    }
    
    public function getUserWithEvents($date, $userid) {
        $sql = 'SELECT e.id, e.name, e.lat, e.lng, e.thumbImage, e.freeEvent, e.startDate, e.endDate, ue.user_id FROM events e LEFT JOIN user_events ue ON e.id = ue.event_id AND ue.user_id = :userid WHERE startDate = :date';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':userid', $userid, PDO::PARAM_STR);
        $pdostmt->bindValue(':date', $date, PDO::PARAM_STR);
        $pdostmt->execute();
        $events = $pdostmt->fetchAll(PDO::FETCH_ASSOC);
        return $events;
    }
    
    public function getUserEvents($userid) {
        $sql = 'SELECT e.id, e.name, e.lat, e.lng, e.thumbImage, e.freeEvent, e.startDate, e.endDate, ue.user_id FROM events e JOIN user_events ue ON e.id = ue.event_id AND ue.user_id = :userid';
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
    
    public function checkEventCategory($eventId, $categoryId) {
        $sql = 'SELECT COUNT(*) FROM event_category WHERE category_id = :categoryId AND event_id = :eventId';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':categoryId', $categoryId, PDO::PARAM_STR);
        $pdostmt->bindValue(':eventId', $eventId, PDO::PARAM_STR);
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
            return array('code' => 200, 'msg' => 'success', 'status' => 'save', 'user_id' => $userid);
        }
    }
    
    public function updateEventCategory($eventId, $categoryId) {
        $count = $this->checkEventCategory($eventId, $categoryId);
        if ($count == 0) {
            $this->insertEventCategory($eventId, $categoryId);
        }
    }
    
    public function insertEventCategory($eventId, $categoryId) {
        $sql = 'INSERT INTO event_category (category_id, event_id) VALUES (:categoryId, :eventId)';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':categoryId', $categoryId, PDO::PARAM_STR);
        $pdostmt->bindValue(':eventId', $eventId, PDO::PARAM_STR);
        $pdostmt->execute();
    }
    
    public function updateEvents($events) {
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
        $website = '';
        if (isset($calEvent['eventWebsite'])) {
            $website = $calEvent['eventWebsite'];
        }
        
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
        
        $categories = $calEvent['category'];
        
        $eventId = $this->getEventRecId($recId);
        if (!$eventId) {
            $sql = 'INSERT INTO events (name, 
                                        address, 
                                        location, 
                                        lat, 
                                        lng, 
                                        description, 
                                        website, 
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
                                            :website,
                                            :startDate, 
                                            :endDate,
                                            :thumbImage, 
                                            :image,
                                            :recId,
                                            :reservationsRequired,
                                            :freeEvent
                                            )';
        } else {
            $sql = 'UPDATE events SET 
                                    name = :name, 
                                    address = :address, 
                                    location = :location, 
                                    lat = :lat, 
                                    lng = :lng, 
                                    description = :description, 
                                    website = :website, 
                                    startDate = :startDate, 
                                    endDate = :endDate,
                                    thumbImage = :thumbImage, 
                                    image = :image,
                                    reservationsRequired = :reservationsRequired,
                                    freeEvent = :freeEvent
                                    WHERE recId = :recId
                                    ';
        }
        
        
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':name', $name, PDO::PARAM_STR);
        $pdostmt->bindValue(':address', $address, PDO::PARAM_STR);
        $pdostmt->bindValue(':location', $location, PDO::PARAM_STR);
        $pdostmt->bindValue(':lat', $lat, PDO::PARAM_STR);
        $pdostmt->bindValue(':lng', $lng, PDO::PARAM_STR);
        $pdostmt->bindValue(':description', $description, PDO::PARAM_STR);
        $pdostmt->bindValue(':website', $website, PDO::PARAM_STR);
        $pdostmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
        $pdostmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
        $pdostmt->bindValue(':thumbImage', $thumbImage, PDO::PARAM_STR);
        $pdostmt->bindValue(':image', $image, PDO::PARAM_STR);
        $pdostmt->bindValue(':recId', $recId, PDO::PARAM_STR);
        $pdostmt->bindValue(':reservationsRequired', $reservationsRequired, PDO::PARAM_STR);
        $pdostmt->bindValue(':freeEvent', $freeEvent, PDO::PARAM_STR);
        $pdostmt->execute();
        
        if (!$eventId) {
            $eventId = $this->getEventRecId($recId);
        }
        
        foreach ($categories as $c) {
            $cname = $c['name'];
            $cmodel = new Category($this->db);
            $cexist = $cmodel->getByName($cname);
            $cId = $cexist['id'];
            
            if (!$cexist) {
                $category = $cmodel->insert($cname);
                $cId = $category['id'];
            }
            
            $this->updateEventCategory($eventId, $cId);
        }
    }
}