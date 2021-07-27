<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");
include_once(__DIR__ . "/classes/Question.php");

if (isset($_GET['id'])) {
    $courseID = $_GET['id'];
    $fetchTeamById = new Team();

    $fetchMembersByTeamId = new Team();
    $fetchMembersByTeamId->setCourseID($courseID);
    $members = $fetchMembersByTeamId->fetchStudentsByCourse($courseID);

    $fetchQuestion = new Question();
    $questionData = $fetchQuestion->fetchQuestionById();
    $printScore = $fetchQuestion->printScoreForTeacher();
    $countScore = $fetchQuestion->countScoreForTeacher();
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
    <div class="block ml-auto mr-auto w-64">
        <h1 class="font-medium text-2xl my-10">Alle studenten</h1>
        <div class="mb-5 space-y-2">
            <?php foreach ($members as $member) : ?>
                <li class="list-none"><?php echo $member['firstname'] . " " . $member['lastname'] ?></li>
            <?php endforeach; ?>
        </div>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>