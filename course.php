<?php
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Course.php");

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
    <h2>Cursuscode: <?php echo $courseData['code'] ?></h2>

    <a href="teams.php?id=<?php echo $courseData['id'] ?>">Bekijk teams</a>
    <a href="students.php?id=<?php echo $courseData['id'] ?>">Bekijk alle studenten</a>

</body>

</html>