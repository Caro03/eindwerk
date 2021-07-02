<?php

include_once(__DIR__ . "/Db.php");

class User {
        private $userID;
        private $email;
        private $password;
        private $firstname;
        private $lastname;
        private $role;

        public function getUserID()
        {
                return $this->userID;
        }

        public function setUserID($userID)
        {
                $this->userID = $userID;

                return $this;
        }

        public function setEmail($email) {

                if(empty($email)) {
                        throw new Exception("Email mag niet leeg zijn!");
                }
                $this->email = $email;

                return $this;
        }

        public function getEmail() {
                return $this->email;
        }

        public function setPassword($password) {

            if(empty($password)) {
                    throw new Exception("Wachtwoord mag niet leeg zijn!");
            }

            return $this;
        }

        public function getPassword() {
            return $this->password;
        }

        public function setFirstname($firstname) {
            $this->firstname = $firstname;

            return $this;
        }

        public function getFirstname(){
            return $this->firstname;
        }

        public function setLastname($lastname) {
                $this->lastname = $lastname;
    
                return $this;
        }
    
        public function getLastname(){
                return $this->lastname;
        }

        public function setRole($role){
                $this->role = $role;

                return $this;
        }

        public function getRole() {
                return $this->role;
        }


        public function validateEmail($email)
        {
            // Remove all illegal characters from email
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
            // validate e-mail + check for Thomas More email address
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('|@student.thomasmore.be$|', $email)) {
                return true;
            } else {
                echo false;
            }
        }

        public function emailAvailable($email)
        {
            $pdo = Db::connect();
            $stmt = $pdo->prepare("SELECT COUNT(id) FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetchColumn();
    
            if ($result > 0) {
                return false;
            } else {
                return true;
            }
        }

        public function validatePassword($password)
        {
            $length = strlen($password);
    
            if ($length < 5) {
                return false;
            } else {
                return true;
            }
        }

        public function register($email, $password, $firstname, $lastname, $role)
        {
            $pdo = Db::connect();
            $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, role_id) VALUES (:firstname, :lastname, :email, :password, :role)");
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $result = $stmt->execute();
            return $result;
        }

        public function fetchUserID($email)
        {
            $pdo = Db::connect();
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            return $result;
        }

        public function checkLogin($email, $password) {
            //db conn
            $conn = Db::connect();
            //insert query
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindParam(":email", $email);
    
            //return result
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            if(empty($result)) {
                return false;
            }
            $hash = $result["password"];
            if(password_verify($password, $hash)) {
                return true;
            }else {
                return false;
            }
        }

        public function userIDFromSession($email) {
            //db conn
            $conn = Db::connect();
            //insert query
            $statement = $conn->prepare("select id from users where email = :email");
            $statement->bindParam(":email", $email);
    
            //return result
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public function fetchRole($userID){
            $conn = Db::connect();
            $statement = $conn->prepare('SELECT role_id FROM users WHERE id = :userID');
            $statement->bindParam(':userID', $userID);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_COLUMN);
            return $result;
        }
  
        public function allUserData()
        {
            //db conn
            $conn = Db::connect();
            //insert query
            $statement = $conn->prepare("select * from users where id = :userID");
            $userID = $this->getUserID();
            $statement->bindParam(':userID', $userID);

            //return result
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            return $result;
        }
        public function fetchPData($userID){
            $conn = Db::connect();
            $statement = $conn->prepare('SELECT * FROM users WHERE id = :userID');
            $statement->bindParam(':userID', $userID);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        public function changeEmail($email, $userID){
            $conn = Db::connect();
            $statement = $conn->prepare("UPDATE `users` SET `email` = :email WHERE `id` = :userID");
            $statement->bindParam(':userID', $userID);
            $statement->bindParam(':email', $email);
            $result = $statement->execute();
        return $result;
        }

        public function changePass($password, $userID){
            $conn = Db::connect();
            $statement = $conn->prepare("UPDATE `users` SET `password` = :password WHERE `id` = :userID");
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $statement->bindParam(':userID', $userID);
            $statement->bindParam(':password', $password);
            $result = $statement->execute();
        return $result;
        }
}