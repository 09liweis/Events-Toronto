<?php

class User {
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function register($user) {
        $username = $user['username'];
        $profileimage = $user['profleimage'];
        $google_plus_id = $user['google_plus_id'];
        $sql = 'INSERT INTO users ( username, profileimage, google_plus_id) VALUES (:username, :profileimage, :google_plus_id)';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':username', $username, PDO::PARAM_STR);
        $pdostmt->bindValue(':profileimage', $profileimage, PDO::PARAM_STR);
        $pdostmt->bindValue(':google_plus_id', $google_plus_id, PDO::PARAM_STR);
        $pdostmt->execute();
        var_dump($sql);
        return $this->db->lastInsertId('google_plus_id');
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