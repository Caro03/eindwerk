<?php

include_once(__DIR__ . "/Db.php");

class Course
{

    private $id;
    private $courseID;
    private $coursename;
    private $code;
    private $category;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getCourseID()
    {
        return $this->courseID;
    }

    public function setCourseID($courseID)
    {
        $this->courseID = $courseID;

        return $this;
    }

    public function getCoursename()
    {
        return $this->coursename;
    }

    public function setCoursename($coursename)
    {
        if (empty($coursename)) {
            throw new Exception("Cursusnaam mag niet leeg zijn!");
        }

        $this->coursename = $coursename;

        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function createCourse()
    {
        //db conn
        $conn = Db::getConnection();
        //insert query
        $statement = $conn->prepare("INSERT INTO courses (admin_id, coursename, code, category) VALUES(:id , :coursename , :code, :category)");

        $id = $this->getId();
        $coursename = $this->getCoursename();
        $code = $this->getCode();
        $category = $this->getCategory();

        $statement->bindParam(":id", $id);
        $statement->bindParam(":coursename", $coursename);
        $statement->bindParam(":code", $code);
        $statement->bindParam(":category", $category);

        //return result
        $result = $statement->execute();
        return $result;
    }

    public function checkCode($code)
    {
        //db conn
        $conn = Db::getConnection();
        //insert query
        $statement = $conn->prepare("SELECT * FROM courses WHERE code = :code");

        $statement->bindParam(":code", $code);

        $statement->execute();
        //return result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        if ($code == $_POST['CheckCode']) {
            return true;
        }
        else {
            return false;
        }
    }

    public function idFromSession()
    {
        //db conn
        $conn = Db::getConnection();
        //insert query
        $statement = $conn->prepare("select id from courses where code = :code");

        $code = $this->getCode();
        $statement->bindParam(":code", $code);

        //return result
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveStudent() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT into students (student_id, course_id) VALUES ({$_SESSION['id']}, :courseID)");

        $courseID = $this->getCourseID();
        $statement->bindParam(":courseID", $courseID);

        $result = $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result; 
    }

    public function allCourseData()
    {
        //db conn
        $conn = Db::getConnection();
        //insert query
        $statement = $conn->prepare("select code from courses");

        //return result
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchCoursesByAdmin()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM courses WHERE admin_id = {$_SESSION["id"]}");

        //$id = $this->getId();
        //$statement->bindParam(":id", $id);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchCoursesById()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM courses WHERE id = :courseID");

        $id = $this->getId();
        $statement->bindParam(":courseID", $id);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchCoursesById2()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM courses WHERE id = {$_GET["id"]}");

        $id = $this->getId();
        $statement->bindParam(":courseID", $id);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}
