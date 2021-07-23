<?php
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Question.php");

session_start();
$email = $_SESSION["user"];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
$PData = $user->allUserData();

if (isset($_GET['teamid'])){
    $courseID = $_GET['teamid'];
    $fetchTeam = new Team();
    //$fetchTeam->setStudentID($userID);
    $fetchTeam->setCourseID($courseID);
    $team = $fetchTeam->fetchTeamByCourseForUser($courseID);
}

if ($PData['role_id'] == 'student') {
    $course_id = $_GET['id'];
    $fetchQuestion = new Question();
    $fetchQuestion->setCourse_id($course_id);
    $q = $fetchQuestion->fetchLatestQuizByTeam($course_id);

    $question_id = $q['id'];
    $checkAnswer = new Question();
    $checkAnswer->setQuestionId($question_id);
    //$checkAnswer->setUserId($userID);
    $c = $checkAnswer->checkIfAnswered($question_id);

    $fetchAnswers = new Question();
    $fetchAnswers->setCourse_id($course_id);
    $a = $fetchAnswers->fetchQuestions($course_id);
    shuffle($a);

    if (isset($_POST['indienen'])) {
        $submitAnswer = new Question();
        $question_id = $q['id'];
        $answer = $_POST['answer'];

        $submitAnswer->setAnswer($answer);
        $submitAnswer->setQuestionId($question_id);
        $submit = $submitAnswer->submitAnswer($question_id, $answer);
        header("Location: succes.php");

        if ($submitAnswer->checkAnswer()) {
            $submitAnswer->saveScore();
        }
    }
}

if (!empty($_POST['submitQuestion'])) {
    $question = new Question();

    try {
        $vraag = $_POST['vraag'];
        $correctantwoord = $_POST['correctantwoord'];
        $foutantwoord1 = $_POST['foutantwoord1'];
        $foutantwoord2 = $_POST['foutantwoord2'];
        $course_id = $_GET['id'];

        $question->setCourse_id($course_id);
        $question->setVraag($vraag);
        $question->setCorrectantwoord($correctantwoord);
        $question->setFoutantwoord1($foutantwoord1);
        $question->setFoutantwoord2($foutantwoord2);

        // methode
        $question->saveQuestion($course_id, $vraag, $correctantwoord, $foutantwoord1, $foutantwoord2);
        $error = "Jouw vraag is succesvol opgeslagen";
    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="build/tailwind.css">
    <title>PEERHOOD | Quiz</title>
</head>

<body>

    <?php if ($PData['role_id'] == 'docent') { ?>
        <h2 class="mb-5 text-2xl text-center form_title md:text-2xl">Meerkeuzevraag maken</h2>
        <form action="" method="POST">

            <?php if (isset($error)) : ?>
                <div class="mb-5 text-center form_error">
                    <p>
                        <?php echo $error; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div>
                <input type="text" name="vraag" id="vraag" placeholder="Wat is de vraag?">
            </div>
            <div>
                <input type="text" name="correctantwoord" id="correctantwoord" placeholder="Correct antwoord">
            </div>
            <div>
                <input type="text" name="foutantwoord1" id="foutantwoord1" placeholder="Fout antwoord 1">
            </div>
            <div>
                <input type="text" name="foutantwoord2" id="foutantwoord2" placeholder="Fout antwoord 2">
            </div>
            <div>
                <input type="submit" name="submitQuestion" value="Post quiz">
            </div>
        </form>

    <?php } ?>

    <?php if ($PData['role_id'] == 'student') { ?>
        <?php if ($c == false) { ?>
            <h2 class="mb-5 text-center"><?php echo $q['question'] ?></h2>

            <form action="" method="POST">
                <div class="mb-10 space-y-2 text-center">
                    <input type="radio" id="answer1" name="answer" value="<?php echo $randomA = $a[0];  ?>">
                    <label for="answer1"><?php echo $randomA = $a[0]; ?></label><br>
                    <input type="radio" id="answer2" name="answer" value="<?php echo $randomA = $a[1];  ?>">
                    <label for="answer2"><?php echo $randomA = $a[1]; ?></label><br>
                    <input type="radio" id="answer3" name="answer" value="<?php echo $randomA = $a[2];  ?>">
                    <label for="answer3"><?php echo $randomA = $a[2]; ?></label><br>
                </div>
                <div>
                    <input type="submit" name="indienen" value="Indienen">
            </form>
        <?php } ?>
    <?php } ?>

</body>

</html>