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
    $students = $fetchTeamsByCourse->fetchTeamByStudent();
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

    <?php foreach ($students as $student): ?>
    <p><?php echo $student; ?></p>
    <?php endforeach; ?>

    <?php foreach ($students as $student) : ?>
        <p><?php echo $student ; ?></p>
    <?php endforeach; ?>

</body>

</html>