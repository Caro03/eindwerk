<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Team.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
$userData = $user->allUserData();
$role = $user->fetchRole();

$team = new Team();

if (isset($_GET['id'])) {
    $courseID = $_GET['id'];
    //$course = new Course();
    //$courseData = $course->fetchCoursesById2();

    if (isset($_POST['submit'])) {
        if (!empty($_POST['teamname'])) {
            $teamname = $_POST['teamname'];

            $team->setCourseID($courseID);
            $team->setTeamname($teamname);
            $team->createTeam();
            $error = "Jouw team is succecvol aangemaakt!";
        } else {
            $error = "Naam van team mag niet leeg zijn";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursussen</title>
</head>

<body>

    <?php if (isset($error)) : ?>
        <div class="mb-5 text-center form_error">
            <p class="form_error">
                <?php echo $error; ?>
            </p>
        </div>
    <?php endif; ?>

    <form class="form" action="" method="post">
        <input class="block w-64 mb-2 ml-auto mr-auto bg-transparent border-b border-black form_field md:w-72" type="text" name="teamname" placeholder="Naam van het team">
        <br>
        <input class="block w-64 h-12 mb-2 ml-auto mr-auto text-white shadow-md form_btn md:w-72 rounded-2xl" type="submit" value="Maak nieuw team" name="submit">
    </form>

</body>

</html>