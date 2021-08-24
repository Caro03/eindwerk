<?php

include_once(__DIR__ . "/Db.php");

class User
{
        protected $id;
        protected $firstname;
        protected $lastname;
        protected $email;
        protected $password;
        protected $role;
        protected $course_id;
        protected $updatePassword;
        protected $roleChoice;

        protected $choice;

        public function getId()
        {
                return $this->id;
        }

        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }


        public function setEmail($email)
        {

                if (empty($email)) {
                        throw new Exception("Email mag niet leeg zijn!");
                }
                $this->email = $email;

                return $this;
        }

        public function getEmail()
        {
                return $this->email;
        }

        public function endsWith($target)
        {
                $email = $this->getEmail();
                $length = strlen($target);
                if ($length == 0) {
                        return true;
                }

                return (substr($email, -$length) === $target);
        }

        public function validEmail()
        {
                $email = $this->getEmail();
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        return true;
                } else {
                        return false;
                }
        }


        public function availableEmail($email)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $statement->bindParam(":email", $email);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);

                if ($result == false) {
                        // Email available
                        return true;
                } else {
                        // Email not available
                        return false;
                }
        }

        public function setPassword($password)
        {

                if (empty($password)) {
                        throw new Exception("Wachtwoord mag niet leeg zijn!");
                }

                $options = ['cost' => 12];
                $password = password_hash($password, PASSWORD_DEFAULT, $options);

                $this->password = $password;

                return $this;
        }

        public function getPassword()
        {
                return $this->password;
        }

        public function save()
        {
                // connectie
                $conn = Db::getConnection();

                $statement = $conn->prepare("insert into users (firstname, lastname, email, password, role_id) values (:firstname, :lastname, :email, :password, :role)");

                // variabelen klaarzetten om te binden
                $firstname = $this->getFirstname();
                $lastname = $this->getLastname();
                $email = $this->getEmail();
                $password = $this->getPassword();
                $role = $this->getRole();

                // uitlezen wat er in de variabele zit en die zal op een veilige manier gekleefd worden
                $statement->bindParam(":firstname", $firstname);
                $statement->bindParam(":lastname", $lastname);
                $statement->bindParam(":email", $email);
                $statement->bindParam(":password", $password);
                $statement->bindParam(":role", $role);


                // als je geen execute doet dan wordt die query niet uitgevoerd
                $result = $statement->execute();
                return $result;
        }

        public function checkLogin($email, $password)
        {
                //db conn
                $conn = Db::getConnection();
                //insert query
                $statement = $conn->prepare("select * from users where email = :email");
                $statement->bindParam(":email", $email);

                //return result
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                if (empty($result)) {
                        return false;
                }
                $hash = $result["password"];
                if (password_verify($password, $hash)) {
                        return true;
                } else {
                        return false;
                }
        }

        public function idFromSession($email)
        {
                //db conn
                $conn = Db::getConnection();
                //insert query
                $statement = $conn->prepare("select id from users where email = :email");
                $statement->bindParam(":email", $email);

                //return result
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function allUserData()
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select * from users where id = {$_SESSION["id"]}");

                //$id = $this->getId();
                //$statement->bindParam(":id", $id);

                //return result
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function allData()
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select * from users");

                //$id = $this->getId();
                //$statement->bindParam(":id", $id);

                //return result
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function fetchRole()
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare('SELECT role_id FROM users WHERE id = :id');
                $id = $this->getId();
                $statement->bindParam(':id', $id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function userDataFromId($id, $course_id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select * from users inner join scores as score on users.id = score.user_id INNER join courses as course on score.course_id = course.id where users.id = :id and score.course_id = :courseid");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                $course_id = $this->getCourse_id();
                $statement->bindParam(":courseid", $course_id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function countUserData($id, $course_id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select count(*) from users inner join scores as score on users.id = score.user_id INNER join courses as course on score.course_id = course.id where users.id = :id and score.course_id = :courseid");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                $course_id = $this->getCourse_id();
                $statement->bindParam(":courseid", $course_id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function countQuestions($id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select count(*) from users inner join posts as post on users.id = post.user_id where users.id = :id");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                //$course_id = $this->getCourse_id();
                //$statement->bindParam(":courseid", $course_id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function countAnswers($id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select count(*) from users inner join comments as comment on users.id = comment.user_id where users.id = :id");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                //$course_id = $this->getCourse_id();
                //$statement->bindParam(":courseid", $course_id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function countCorrectAnswers($id, $course_id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select count(*) from users inner join answers as answer on users.id = answer.user_id inner join questions as question on answer.response = question.correct_answer where users.id = :id and question.course_id = :courseid");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                $course_id = $this->getCourse_id();
                $statement->bindParam(":courseid", $course_id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function countFalseAnswers($id, $course_id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select count(*) from users inner join answers as answer on users.id = answer.user_id inner join questions as question on answer.response = question.false_answer1 or answer.response = question.false_answer2 where users.id = :id and question.course_id = :courseid");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                $course_id = $this->getCourse_id();
                $statement->bindParam(":courseid", $course_id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function countNuttig($id)
        {
                $conn = Db::getConnection();
                $statement = $conn->prepare("select count(*) from users inner join comments as comment on users.id = comment.user_id where comment.likes = 1 and users.id = :id");

                $id = $this->getId();
                $statement->bindParam(":id", $id);

                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result;
        }

        public function getUpdatePassword()
        {
                return $this->updatePassword;
        }

        public function setUpdatePassword($updatePassword)
        {
                if (empty($updatePassword)) {
                        throw new Exception("Wachtwoord mag niet leeg zijn!");
                }

                $options = ['cost' => 12];
                $updatePassword = password_hash($updatePassword, PASSWORD_DEFAULT, $options);

                $this->updatePassword = $updatePassword;
                return $this;
        }

        public function changePassword()
        {
                //db conn
                $conn = Db::getConnection();
                //insert query
                $statement = $conn->prepare("update users set password = :updatePassword where id = {$_SESSION["id"]}");
                $updatePassword = $this->getUpdatePassword();

                $statement->bindParam(":updatePassword", $updatePassword);

                //return result
                $result = $statement->execute();
                return $result;
        }

        public function changeRole()
        {
                //db conn
                $conn = Db::getConnection();
                //insert query
                $statement = $conn->prepare("update users set roleChoice = :roleChoice where id = {$_SESSION["id"]}");
                $roleChoice = $this->getRoleChoice();

                $statement->bindParam(":roleChoice", $roleChoice);

                //return result
                $result = $statement->execute();
                return $result;
        }

        /**
         * Get the value of lastname
         */
        public function getLastname()
        {
                return $this->lastname;
        }

        /**
         * Set the value of lastname
         *
         * @return  self
         */
        public function setLastname($lastname)
        {
                $this->lastname = $lastname;

                return $this;
        }

        /**
         * Get the value of firstname
         */
        public function getFirstname()
        {
                return $this->firstname;
        }

        /**
         * Set the value of firstname
         *
         * @return  self
         */
        public function setFirstname($firstname)
        {
                $this->firstname = $firstname;

                return $this;
        }

        /**
         * Get the value of role
         */
        public function getRole()
        {
                return $this->role;
        }

        /**
         * Set the value of role
         *
         * @return  self
         */
        public function setRole($role)
        {
                $this->role = $role;

                return $this;
        }

        /**
         * Get the value of course_id
         */
        public function getCourse_id()
        {
                return $this->course_id;
        }

        /**
         * Set the value of course_id
         *
         * @return  self
         */
        public function setCourse_id($course_id)
        {
                $this->course_id = $course_id;

                return $this;
        }

        /**
         * Get the value of roleChoice
         */ 
        public function getRoleChoice()
        {
                return $this->roleChoice;
        }

        /**
         * Set the value of roleChoice
         *
         * @return  self
         */ 
        public function setRoleChoice($roleChoice)
        {
                $this->roleChoice = $roleChoice;

                return $this;
        }
}
