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

$course = new Course();
$courseData = $course->fetchCoursesByAdmin();

$getUserCourses = new Team();
//$getUserCourses->setStudentID($userID);
$userCourses = $getUserCourses->fetchCourseForUser();

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
    <h1>Hey <?php echo $PData['firstname'] ?></h1>
    <a href="addcourse.php">Cursus toevoegen</a>

    <?php foreach ($courseData as $data) : ?>
        <a href="course.php?id=<?php echo $data['id'] ?>">
            <?php echo $data['coursename']; ?>
        </a>
        <br>
    <?php endforeach; ?>

    <?php foreach ($userCourses as $userCourse) : ?>
        <a href="course.php?teamid=<?php echo $userCourse['course_id'] ?>">
            <?php echo $userCourse['coursename']; ?>
        </a>
        <br>
    <?php endforeach; ?>

</body>

</html>