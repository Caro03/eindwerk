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
            $good = "Jouw team is succecvol aangemaakt!";
            header("Location: index.php");
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
    <link rel="stylesheet" href="src/styles.css">
    <link rel="stylesheet" href="public/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>PEERHOOD</title>
</head>

<body>

    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">

        <h2 class="font-medium text-xl my-10">Nieuw team toevoegen</h2>

        <?php if (isset($error)) : ?>
            <div class="mb-5 text-red-500 font-medium">
                <p class="form_error">
                    <?php echo $error; ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (isset($good)) : ?>
            <div class="mb-5 text-green-500 font-medium">
                <p class="form_error">
                    <?php echo $good; ?>
                </p>
            </div>
        <?php endif; ?>

        <form class="form" action="" method="post">
            <input class="md:w-72 lg:w-80 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" type="text" name="teamname" placeholder="Naam van het team">
            <input class="md:w-72 lg:w-80 outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Maak nieuw team" name="submit">
        </form>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>