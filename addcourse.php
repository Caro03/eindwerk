<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Team.php");

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
$user->setId($_SESSION["id"]);
$userData = $user->allUserData();
$role = $user->fetchRole();

$course = new Course();
//$courseData = $course->checkCode($code, $coursename);

if ($userData['role_id'] == 'docent') {
    if (!empty($_POST)) {
        $code = substr(md5(uniqid(mt_rand(), true)), 0, 6);

        try {
            $course->setId($_SESSION["id"]);
            $course->setCoursename(htmlspecialchars($_POST['coursename']));
            $course->setCode($code);
            $course->setCategory($_POST['category']);
        } catch (\Throwable $th) {
            $error = $th->getMessage();
        }


        if (!isset($error)) {
            // methode
            $course->createCourse();
            $good = "De cursus is succesvol opgeslagen! Ga naar de cursus om de code te bekijken";
            header("Location: index.php");
        }
    }
}

if ($userData['role_id'] == 'student') {
    if (!empty($_POST)) {
        $code = htmlspecialchars($_POST['CheckCode']);

        if (!empty($code)) {
            if ($course->checkCode($code)) {
                $course->setCode($code);
                $idArray = $course->idFromSession();
                $courseID = $idArray['id'];
                $course->setCourseID($courseID);
                //$courses = $course->fetchCoursesById();
                $good = "Je bent succesvol toegevoegd aan deze cursus";
                $course->saveStudent();
                header("Location: index.php");

                $team = new Team();
                $r = $team->fetchAvailableGroups($courseID);
                shuffle($r);
                $newR = $r[0];
                $newRString = implode(" ", $newR);
                $rnew = (int)$newRString;

                //Add student to groups
                $newly = new Team();
                $newly->setTeamID($newR);
                //$newly->setStudentID($userID);
                $n = $newly->addStudents($rnew);
            } else {
                $error = "Code is niet correct";
            }
        } else {
            $error = "Code invullen is verplicht";
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
    <title>Cursussen</title>
</head>

<body>
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
        <?php if ($userData['role_id'] == 'docent') { ?>
            <h2 class="font-medium text-2xl mt-10 mb-5">Nieuwe cursus</h2>
            <h2 class="font-medium text-2xl mb-10 mb-5">Welk vak geef jij?</h2>

        <?php } else { ?>
            <h2 class="font-medium text-2xl mt-10 mb-5">Nieuwe cursus</h2>
            <h2 class="font-medium text-2xl mb-10">Vul de cursuscode in</h2>

        <?php } ?>

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

        <?php if ($userData['role_id'] == 'docent') { ?>

            <form class="form" action="" method="post">
                <input class="md:w-72 lg:w-80 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" type="text" name="coursename" placeholder="Naam van jouw vak">

                <div class="pb-5 pt-5">
                    <label for="cars">Welke categorie past bij jouw vak?</label>
                </div>
                <select name="category" class="md:w-72 lg:w-80 outline-none w-56 block px-5 py-5 rounded-xl border-black border-2 mb-10" id="choiceselect">
                    <option value="design">Design</option>
                    <option value="marketing">Marketing</option>
                    <option value="programmeren">Programmeren</option>
                    <option value="taal">Taal</option>
                </select>

                <input class="md:w-72 lg:w-80 outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Creeër cursus" name="submit">
            </form>

        <?php } else { ?>

            <form class="form" action="" method="post">
                <input class="md:w-72 lg:w-80 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" type="text" name="CheckCode" placeholder="Code">
                <input class="md:w-72 lg:w-80 outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Controleer" name="controleer">
            </form>
        <?php } ?>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>