<?php

class Database {
    private static $host = 'sql9.freemysqlhosting.net';
    private static $dbname = 'sql9166232';
    private static $username = 'sql9166232';
    private static $password = 'M8YWGiFl6f';
    private static $db;
    
    public function __construct() {

    }
    
    public static function dbConnect() {
        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname;
        if (!isset(self::$db)) {
            try {
                self::$db = new PDO($dsn, self::$username, self::$password);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->setAttribute(PDO::FETCH_ASSOC, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return self::$db;
    }

}