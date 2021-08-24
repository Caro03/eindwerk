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
    <link rel="stylesheet" href="src/styles.css">
    <link rel="stylesheet" href="public/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>PEERHOOD | Home</title>
</head>

<body>
    <div class="block ml-auto mr-auto w-full ">
        <div class="flex items-baseline justify-center my-10 space-x-5 lg:space-x-20 md:space-x-18">
            <h1 class="font-medium text-2xl">Hey <?php echo $PData['firstname'] ?></h1>
            <div class="">
                <a class="" href="addcourse.php">Cursus toevoegen</a>
            </div>
        </div>

        <?php foreach ($courseData as $data) : ?>
            <div class="max-w-sm mx-auto my-4">
                <a class="block text-center w-full px-10 py-5 rounded-xl <?php if ($data['category'] == 'taal') { ?>bg-blue-500 text-white hover:bg-blue-600<?php } ?> <?php if ($data['category'] == 'programmeren') { ?>bg-green-500 text-white hover:bg-green-600<?php } ?> <?php if ($data['category'] == 'design') { ?>bg-pink-500 text-white hover:bg-pink-600<?php } ?> <?php if ($data['category'] == 'marketing') { ?>bg-yellow-500 text-white hover:yellow-600<?php } ?>" href="course.php?id=<?php echo $data['id'] ?>">
                    <?php echo $data['coursename']; ?>
                </a>
            </div>
        <?php endforeach; ?>

        <?php foreach ($userCourses as $userCourse) : ?>
            <div class="max-w-sm mx-auto my-4">
                <a class="block text-center w-full px-10 py-5 rounded-xl  <?php if ($userCourse['category'] == 'taal') { ?>bg-blue-500 text-white hover:bg-blue-600<?php } ?> <?php if ($userCourse['category'] == 'programmeren') { ?>bg-green-500 text-white hover:bg-green-600<?php } ?> <?php if ($userCourse['category'] == 'design') { ?>bg-pink-500 text-white hover:bg-pink-600<?php } ?> <?php if ($userCourse['category'] == 'marketing') { ?>bg-yellow-500 text-white hover:yellow-600<?php } ?>" href="course.php?teamid=<?php echo $userCourse['course_id'] ?>&team=<?php echo $userCourse['team_id'] ?>">
                    <?php echo $userCourse['coursename']; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>