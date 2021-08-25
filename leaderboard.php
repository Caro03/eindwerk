<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");
include_once(__DIR__ . "/classes/Forum.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Question.php");

session_start();
$email = $_SESSION["user"];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$scores = new Question();
$countAnswers = $scores->countAnswersLeaderboard();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles.css">
    <link rel="stylesheet" href="public/styles.css">
    <link rel="icon" href="images/p.png">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>peerhood</title>
</head>

<body>
    <div class="block ml-auto mr-auto md:w-72 lg:w-96 w-64">
        <h2 class="font-medium text-center text-2xl my-10">Leaderboard</h2>

        <div class="rounded-lg space-y-4 bg-green-400 text-white">

            <?php foreach ($countAnswers as $answer) : ?>

                <li class="flex flex-row justify-between px-10 lg:px-24 py-2 text-lg">
                    <div><?php echo $answer['teamname'] ?></div>
                    <div class="font-medium"><?php echo $answer['(select count(*) FROM scores where (scores.team_id = teams.id))'] * 10; ?></div>
                </li>

                <hr>
            <?php endforeach; ?>
        </div>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>