<?php

include_once(__DIR__ . "/Db.php");

class Team
{
    private $teamID;
    private $courseID;
    private $teamname;
    private $studentID;

    public function getTeamID()
    {
        return $this->teamID;
    }

    public function setTeamID($teamID)
    {
        $this->teamID = $teamID;

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

    public function getTeamname()
    {
        return $this->teamname;
    }

    public function setTeamname($teamname)
    {

        if (empty($teamname)) {
            throw new Exception("Team naam mag niet leeg zijn!");
        }

        $this->teamname = $teamname;

        return $this;
    }

    public function getStudentID()
    {
        return $this->studentID;
    }

    public function setStudentID($studentID)
    {
        $this->studentID = $studentID;

        return $this;
    }

    public function fetchTeamsByCourse()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM teams WHERE course_id = {$_GET["id"]}");

        //$courseID = $this->getCourseID();
        //$statement->bindParam(":courseID", $courseID);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchCourseByID($courseID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM courses WHERE course_id = :courseID");
        $statement->bindParam(":courseID", $courseID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchTeamById($teamID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM teams WHERE id = :teamID");
        $statement->bindParam(":teamID", $teamID);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchTeamByStudent()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT firstname, lastname FROM students INNER JOIN users ON students.student_id = users.id WHERE course_id = {$_GET["id"]}");
        
        //$id = $this->getCourseID();
        //$statement->bindParam(":courseID", $id);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchMembersByTeamId($teamID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT firstname, lastname FROM members INNER JOIN users ON members.student_id = users.id WHERE team_id = :teamID");
        $statement->bindParam(":teamID", $teamID);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function createTeam()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO teams (course_id, teamname) VALUES({$_GET['id']}, :teamname)");

        //$courseID = $this->getCourseID();
        $teamname = $this->getTeamname();
        //$statement->bindParam(":courseID", $courseID);
        $statement->bindParam(":teamname", $teamname);

        $result = $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchStudentsByCourse($courseID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM students INNER JOIN users ON students.student_id = users.id WHERE course_id = :courseID");
        $statement->bindParam(":courseID", $courseID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function addStudents($studentID, $teamID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO members (student_id, team_id) VALUES(:studentID, :teamID)");
        $statement->bindParam(":studentID", $studentID);
        $statement->bindParam(":teamID", $teamID);
        $result = $statement->execute();
        return $result;
    }
    public function fetchStudentsGroups($courseID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT team_id FROM members INNER JOIN teams ON members.team_id = teams.id WHERE course_id = :courseID");
        $statement->bindParam(":courseID", $courseID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchAvailableGroups($courseID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT id FROM teams WHERE course_id = :courseID");
        $statement->bindParam(":courseID", $courseID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchCourseForUser($studentID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users AS user INNER JOIN members AS member ON user.id = member.student_id INNER JOIN teams AS team ON team.id = member.team_id INNER JOIN courses AS course ON course.id = team.course_id WHERE user.id = :userID");
        $statement->bindParam(":userID", $studentID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchTeamByCourseForUser($studentID, $courseID)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM users AS user INNER JOIN members AS member ON user.id = member.student_id INNER JOIN teams AS team ON team.id = member.team_id INNER JOIN courses AS course ON course.id = team.course_id WHERE user.id = :userID AND team.course_id = :courseID");
        $statement->bindParam(":userID", $studentID);
        $statement->bindParam(":courseID", $courseID);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
