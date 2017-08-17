<?php
class Category {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function insert($name) {
        $sql = 'INSERT INTO categories (name) VALUES (:name)';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':name', $name, PDO::PARAM_STR);
        $pdostmt->execute();
        return $pdostmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getByName($name) {
        $sql = 'SELECT * FROM categories WHERE name = :name';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':name', $name, PDO::PARAM_STR);
        $pdostmt->execute();
        $category = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $category;
    }
    
    public function getById($id) {
        $sql = 'SELECT * FROM categories WHERE id = :id';
        $pdostmt = $this->db->prepare($sql);
        $pdostmt->bindValue(':id', $id, PDO::PARAM_STR);
        $pdostmt->execute();
        $category = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $category;
    }
}