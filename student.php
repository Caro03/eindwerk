<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");
include_once(__DIR__ . "/classes/Question.php");
include_once(__DIR__ . "/classes/User.php");

if (isset($_GET['id']) && $_GET['course']) {
    $id = $_GET['id'];
    $course_id = $_GET['course'];
    $user = new User();
    $user->setId($id);
    $user->setCourse_id($course_id);
    $userData = $user->userDataFromId($id, $course_id);
    $countScore = $user->countUserData($id, $course_id);
    $countQuestions = $user->countQuestions($id);
    $countAnswers = $user->countAnswers($id);
    $correctAnswer = $user->countCorrectAnswers($id, $course_id);
    $falseAnswer = $user->countFalseAnswers($id, $course_id);
    $countNuttig = $user->countNuttig($id);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles.css">
    <link rel="stylesheet" href="public/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>PEERHOOD</title>
</head>

<body>
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
        <div class="mb-5 space-y-4">
            <?php foreach ($countScore as $score) : ?>
                <h1 class="font-medium text-2xl my-10"><?php echo $userData['firstname'] . " " . $userData['lastname']; ?></h1>
                <div class="space-y-2 bg-gray-200 my-5 rounded-xl px-5 py-5">
                    <p class="font-medium text-lg">Info over quiz</p>
                    <p>Score voor dit vak: <span class="font-medium"><?php echo $score * $userData['value']; ?></span></p>
                <?php endforeach; ?>
                <p>Aantal juiste antwoorden: <span class="font-medium"><?php echo $correctAnswer['count(*)']; ?></span></p>
                <p>Aantal foute antwoorden: <span class="font-medium"><?php echo $falseAnswer['count(*)']; ?></span></p>
                </div>
                <div class="space-y-2 bg-gray-200 my-5 rounded-xl px-5 py-5">
                    <p class="font-medium text-lg">Info over forum</p>
                    <p>Aantal vragen gesteld: <span class="font-medium"><?php echo $countQuestions['count(*)']; ?></span></p>
                    <p>Aantal vragen beantwoord: <span class="font-medium"><?php echo $countAnswers['count(*)']; ?></span></p>
                    <p>Aantal nuttige antwoorden gegeven: <span class="font-medium"><?php echo $countNuttig['count(*)']; ?></span></p>
                </div>
        </div>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>