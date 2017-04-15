<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    
    public function register($user) {
        $username = $user['username'];
        $profileimage = $user['profileimage'];
        $google_plus_id = $user['google_plus_id'];
        $sql = 'INSERT INTO users (username, profileimage, google_plus_id) VALUES (:username, :profileimage, :google_plus_id)';
        try {
        
            $pdostmt = $this->db->prepare($sql);
            $pdostmt->bindValue(':username', $username, PDO::PARAM_STR);
            $pdostmt->bindValue(':profileimage', $profileimage, PDO::PARAM_STR);
            $pdostmt->bindValue(':google_plus_id', $google_plus_id, PDO::PARAM_STR);
            $pdostmt->execute();
            //print_r($this->db->errorInfo());
            return $this->db->lastInsertId();
        } catch( PDOEXception $e ) {
            echo $e->getMessage(); // display error
            exit();
        }
    }
    
    public function getUser($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':id', $id, PDO::PARAM_STR);
        $pdostmt->execute();
        $user = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    
    public function checkAuth($google_plus_id) {
        $sql = 'SELECT * FROM users WHERE google_plus_id = :google_plus_id';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':google_plus_id', $google_plus_id, PDO::PARAM_STR);
        $pdostmt->execute();
        $user = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}