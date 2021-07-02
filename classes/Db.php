<?php

class Db {
            
    private static $conn;

    public static function connect() {
        include_once(__DIR__ . "/../settings/settings.php");
    
        if(self::$conn === null) {
            try {
                self::$conn = new PDO('mysql:host=' . SETTINGS['db']['host'] . ';dbname=' . SETTINGS['db']['dbname'], SETTINGS['db']['user'], SETTINGS['db']['password']);
            } catch (PDOException $th) {
                $error = $th->getMessage();
            }
            return self::$conn;
        }else {
            return self::$conn;
        }
        
    }  
}