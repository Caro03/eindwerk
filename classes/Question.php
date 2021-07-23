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

    public function saveQuestion($course_id, $vraag, $correctantwoord, $foutantwoord1, $foutantwoord2)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into questions (course_id, question, correct_answer, false_answer1, false_answer2) values (:courseID, :question, :correct_answer, :false_answer1, :false_answer2)");
        $statement->bindParam(":courseID", $course_id);
        $statement->bindParam(":question", $vraag);
        $statement->bindParam(":correct_answer", $correctantwoord);
        $statement->bindParam(":false_answer1", $foutantwoord1);
        $statement->bindParam(":false_answer2", $foutantwoord2);
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
        $statement = $conn->prepare("INSERT INTO answers (question_id, user_id, response) VALUES (:question_id, {$_SESSION["id"]}, :response)");
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
        $statement = $conn->prepare("SELECT * from answers as answer INNER JOIN questions as question on answer.question_id = {$_GET["id"]} where answer.response = question.correct_answer AND answer.user_id = {$_SESSION["id"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    public function saveScore()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into scores (user_id, course_id, value) values ({$_SESSION["id"]}, {$_GET["id"]}, 10)");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return false;
        }
    }

    public function printScore() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * from scores where user_id = {$_SESSION["id"]} and course_id = {$_GET["teamid"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countScore() {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT count(*) from scores where user_id = {$_SESSION["id"]} and course_id = {$_GET["teamid"]}");
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
}
