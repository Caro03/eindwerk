<?php

include_once(__DIR__ . "/Db.php");

class Question
{
    private $onderwerp;
    private $vraag;
    private $correctantwoord;
    private $foutantwoord1;
    private $foutantwoord2;
    private $course_id;
    private $question_id;
    private $solution_id;
    private $answer;
    private $user_id;
    private $id;
    private $deadline;

    public function getOnderwerp()
    {
        return $this->onderwerp;
    }

    public function setOnderwerp($onderwerp)
    {

        if (empty($onderwerp)) {
            throw new Exception("Het onderwerp mag niet leeg zijn!");
        }

        $this->onderwerp = $onderwerp;

        return $this;
    }

    public function getVraag()
    {
        return $this->vraag;
    }

    public function setVraag($vraag)
    {

        if (empty($vraag)) {
            throw new Exception("De vraag mag niet leeg zijn!");
        }

        $this->vraag = $vraag;

        return $this;
    }

    public function getFoutantwoord1()
    {

        return $this->foutantwoord1;
    }

    public function setFoutantwoord1($foutantwoord1)
    {

        if (empty($foutantwoord1)) {
            throw new Exception("Het antwoord mag niet leeg zijn!");
        }

        $this->foutantwoord1 = $foutantwoord1;

        return $this;
    }

    public function getFoutantwoord2()
    {
        return $this->foutantwoord2;
    }

    public function setFoutantwoord2($foutantwoord2)
    {

        if (empty($foutantwoord2)) {
            throw new Exception("Het foutantwoord mag niet leeg zijn!");
        }

        $this->foutantwoord2 = $foutantwoord2;

        return $this;
    }

    public function saveQuestion($course_id, $vraag, $correctantwoord, $foutantwoord1, $foutantwoord2, $deadline)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into questions (course_id, question, correct_answer, false_answer1, false_answer2, deadline) values (:courseID, :question, :correct_answer, :false_answer1, :false_answer2, :deadline)");
        $statement->bindParam(":courseID", $course_id);
        $statement->bindParam(":question", $vraag);
        $statement->bindParam(":correct_answer", $correctantwoord);
        $statement->bindParam(":false_answer1", $foutantwoord1);
        $statement->bindParam(":false_answer2", $foutantwoord2);
        $statement->bindParam(":deadline", $deadline);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getCourse_id()
    {
        return $this->course_id;
    }

    public function setCourse_id($course_id)
    {
        $this->course_id = $course_id;

        return $this;
    }

    public function getSolution_id()
    {
        return $this->solution_id;
    }

    public function setSolution_id($solution_id)
    {
        $this->solution_id = $solution_id;

        return $this;
    }

    public function fetchQuestionById()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM questions");

        $id = $this->getCourse_id();
        $statement->bindParam(":courseID", $id);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchLatestQuizByTeam($course_id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM questions WHERE questions.course_id = :courseID ORDER BY questions.id DESC LIMIT 1");
        $statement->bindParam(":courseID", $course_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchQuestions($course_id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT correct_answer, false_answer1, false_answer2 FROM questions WHERE questions.course_id = :courseID ORDER BY questions.id DESC LIMIT 1");
        $statement->bindParam(":courseID", $course_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function submitAnswer($question_id, $answer)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO answers (question_id, user_id, course_id, team_id, response) VALUES (:question_id, {$_SESSION["id"]}, {$_GET["id"]}, {$_GET["team"]}, :response)");
        $statement->bindParam(":question_id", $question_id);
        //$statement->bindParam(":userID", $user_id);
        $statement->bindParam(":response", $answer);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function checkIfAnswered($question_id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT id FROM answers WHERE user_id = {$_SESSION["id"]} AND question_id = :questionID");
        //$statement->bindParam(":userID", $user_id);
        $statement->bindParam(":questionID", $question_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkAnswer()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from answers as answer INNER JOIN questions as question on answer.question_id = question.id where answer.user_id = {$_SESSION["id"]} order by answer.id desc");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveScore()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into scores (user_id, course_id, team_id, value) values ({$_SESSION["id"]}, {$_GET["id"]}, {$_GET["team"]}, 10)");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }
    }

    public function printScore()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from scores where user_id = {$_SESSION["id"]} and course_id = {$_GET["teamid"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countScore()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT count(*) from scores where user_id = {$_SESSION["id"]} and course_id = {$_GET["teamid"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function printTeamScore()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from scores where team_id = {$_GET["team"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countTeamScore()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT count(*) from scores where team_id = {$_GET["team"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function printScoreTeacher()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT scores.value, user.firstname, user.lastname from scores INNER join users as user where scores.user_id = user.id and scores.course_id = {$_GET["id"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countScoreTeacher()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT count(*) from scores inner join users as user where scores.user_id = user.id and scores.course_id = {$_GET["id"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countAnswersLeaderboard()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select teams.id, teams.teamname, (select count(*) FROM scores where (scores.team_id = teams.id)) FROM teams order by (select count(*) FROM scores where (scores.team_id = teams.id)) DESC limit 10");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function printScoreStudent($course_id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from scores inner join users as user where user_id = {$_GET["id"]} and course_id = :courseid");

        $course_id = $this->getCourse_id();
        $statement->bindParam(":courseid", $course_id);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function printScoreTeam()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from scores inner join teams as team on scores.team_id = team.id");

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countScoreStudent($id)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select count(*) from users inner join scores as score on users.id = score.user_id INNER join courses as course on score.course_id = course.id where users.id = :id");

        $id = $this->getId();
        $statement->bindParam(":id", $id);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function printScoreForTeacher()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from users as user inner join scores as score on user.id = score.user_id where {$_GET["id"]} = score.course_id");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countScoreForTeacher()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT count(*) from scores where course_id = {$_GET["id"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Get the value of correctantwoord
     */
    public function getCorrectantwoord()
    {
        return $this->correctantwoord;
    }

    /**
     * Set the value of correctantwoord
     */
    public function setCorrectantwoord($correctantwoord)
    {
        if (empty($correctantwoord)) {
            throw new Exception("Het antwoord mag niet leeg zijn!");
        }

        $this->correctantwoord = $correctantwoord;

        return $this;
    }

    /**
     * Get the value of answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set the value of answer
     */
    public function setAnswer($answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get the value of question_id
     */
    public function getQuestionId()
    {
        return $this->question_id;
    }

    /**
     * Set the value of question_id
     */
    public function setQuestionId($question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     */
    public function setUserId($user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of deadline
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set the value of deadline
     *
     * @return  self
     */
    public function setDeadline($deadline)
    {
        if (empty($deadline)) {
            throw new Exception("De deadline mag niet leeg zijn!");
        }

        $this->deadline = $deadline;

        return $this;
    }
}
