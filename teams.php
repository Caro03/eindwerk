<?php
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");

session_start();
$email = $_SESSION["user"];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
//$user->setId($_SESSION["id"]);
$PData = $user->allUserData();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $course = new Course();
    $courseData = $course->fetchCoursesById2();
}

if (isset($_GET['id'])) {
    $courseID = $_GET['id'];
    $fetchTeamsByCourse = new Team();
    $teams = $fetchTeamsByCourse->fetchTeamsByCourse();
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
    <title>PEERHOOD | Home</title>
</head>

<body>
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
        <h1 class="font-medium text-2xl my-10"><?php echo $courseData['coursename'] ?></h1>

        <?php foreach ($teams as $team) : ?>
            <a class="text-center outline-none block px-5 py-5 rounded-xl text-white bg-blue-400 hover:bg-blue-500" href="team.php?id=<?php echo $team['id'] ?>">
                <?php echo $team['teamname']; ?>
            </a>
            <br>
        <?php endforeach; ?>

        <a class="text-center outline-none block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" href="addteam.php?id=<?php echo $courseData['id'] ?>">Maak een nieuw team</a>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>