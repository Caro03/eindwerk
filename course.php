<?php
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");
include_once(__DIR__ . "/classes/Question.php");

session_start();
$email = $_SESSION["user"];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
//$user->setId($_SESSION["id"]);
$PData = $user->allUserData();

$getUserCourses = new Team();
//$getUserCourses->setStudentID($userID);
$userCourses = $getUserCourses->fetchCourseForUser();

//$fetchQuestion = new Question();
//$fetchQuestion->setCourse_id($course_id);
//$q = $fetchQuestion->fetchLatestQuizByTeam($course_id);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $course = new Course();
    $courseData = $course->fetchCoursesById2();
}

if (isset($_GET['teamid'])) {
    $courseID = $_GET['teamid'];
    $fetchTeam = new Team();
    //$fetchTeam->setStudentID($userID);
    $fetchTeam->setCourseID($courseID);
    $team = $fetchTeam->fetchTeamByCourseForUser($courseID);

    $fetchQuestion = new Question();
    $questionData = $fetchQuestion->fetchQuestionById();
    $printScore = $fetchQuestion->printScore();
    $countScore = $fetchQuestion->countScore();
    $teamScores = $fetchQuestion->printTeamScore();
    $countTeamScores = $fetchQuestion->countTeamScore();
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
    <?php if ($PData['role_id'] == 'docent') { ?>
        <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
            <h1 class="font-medium text-2xl mt-10 mb-2"><?php echo $courseData['coursename'] ?></h1>
            <h2 class="font-medium text-xl mb-10">Cursuscode: <?php echo $courseData['code'] ?></h2>

            <a class="block text-center px-5 py-5 rounded-xl text-white bg-green-400 mb-4 hover:bg-green-500" href="question.php?id=<?php echo $courseData['id'] ?>">Nieuwe vraag uploaden</a>
            <a class="block text-center px-5 py-5 rounded-xl text-white bg-blue-400 mb-4 hover:bg-blue-500" href="teams.php?id=<?php echo $courseData['id'] ?>">Bekijk teams</a>
            <a class="block text-center px-5 py-5 rounded-xl text-white bg-purple-400 hover:bg-purple-500" href="students.php?id=<?php echo $courseData['id'] ?>">Bekijk alle studenten</a>
        </div>

    <?php } else { ?>
        <div class="block ml-auto mr-auto w-64">
            <h1 class="font-medium text-2xl my-10"><?php echo $team['teamname'] ?></h1>

            <?php foreach ($countTeamScores as $countTeam) : ?>
                <h2 class="mb-2">Team score: <span class="font-medium"> <?php echo $countTeam * $teamScores['value']; ?></span></h2>
            <?php endforeach; ?>

            <?php foreach ($countScore as $score) : ?>
                <h2 class="mb-10">Jouw persoonlijke score: <span class="font-medium"> <?php echo $score * $printScore['value']; ?></span></h2>
            <?php endforeach; ?>

            <a class="ml-auto mr-auto block text-center px-5 py-5 rounded-xl text-white bg-green-400 hover:bg-green-500" href="question.php?id=<?php echo $team['course_id'] ?>&team=<?php echo $team['team_id'] ?>">Nieuwe vraag beantwoorden</a>
        <?php } ?>
        </div>

</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>