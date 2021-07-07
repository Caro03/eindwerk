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
    <title>PEERHOOD | Home</title>
</head>

<body>
    <h1><?php echo $courseData['coursename'] ?></h1>

    <?php foreach ($teams as $team) : ?>
        <a href="team.php?id=<?php echo $team['id'] ?>">
            <?php echo $team['teamname']; ?>
        </a>
        <br>
    <?php endforeach; ?>

    <a href="addteam.php?id=<?php echo $courseData['id'] ?>">Maak een nieuw team</a>

</body>

</html>