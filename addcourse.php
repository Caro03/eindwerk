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
            $error = "De cursus is succesvol opgeslagen! Ga naar de cursus om de code te bekijken";
        }
    }
}

if (!empty($_POST)) {
    $code = htmlspecialchars($_POST['CheckCode']);

    if (!empty($code)) {
        if ($course->checkCode($code)) {
            $course->setCode($code);
            $idArray = $course->idFromSession();
            $courseID = $idArray['id'];
            $course->setCourseID($courseID);
            //$courses = $course->fetchCoursesById();
            $error = "Je bent succesvol toegevoegd aan deze cursus";
            $course->saveStudent();

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

    <?php if ($userData['role_id'] == 'docent') { ?>
        <div class="px-5 py-5 mb-10 ml-auto mr-auto gradient rounded-b-xl">
            <h2 class="text-2xl text-center text-white form_title">Welk vak geef jij?</h2>
        </div>
        <h2 class="text-2xl text-center mb-14 form_title md:text-2xl">Nieuwe cursus</h2>

    <?php } else { ?>
        <div class="px-5 py-5 mb-10 ml-auto mr-auto gradient rounded-b-xl">
            <h2 class="text-2xl text-center text-white form_title">Welk vak volg jij?</h2>
        </div>

        <h2 class="text-2xl text-center mb-14 form_title md:text-2xl">Nieuwe cursus</h2>

    <?php } ?>

    <?php if (isset($error)) : ?>
        <div class="mb-5 text-center form_error">
            <p class="form_error">
                <?php echo $error; ?>
            </p>
        </div>
    <?php endif; ?>

    <?php if ($userData['role_id'] == 'docent') { ?>

        <form class="form" action="" method="post">
            <input class="block w-64 mb-2 ml-auto mr-auto bg-transparent border-b border-black form_field md:w-72" type="text" name="coursename" placeholder="Naam van jouw vak">
            <br>

            <label for="cars">Welke catogorie past bij uw vak?</label>
            <select name="category" class="form-control" id="choiceselect">
                <option value="design">Design</option>
                <option value="marketing">Marketing</option>
                <option value="programmeren">Programmeren</option>
                <option value="taal">Taal</option>
            </select>

            <input class="block w-64 h-12 mb-2 ml-auto mr-auto text-white shadow-md form_btn md:w-72 rounded-2xl" type="submit" value="Creeër cursus" name="submit">
        </form>

    <?php } else { ?>

        <form class="form" action="" method="post">
            <input class="block w-64 mb-2 ml-auto mr-auto bg-transparent border-b border-black form_field md:w-72" type="text" name="CheckCode" placeholder="Code">
            <br>
            <input class="block w-64 h-12 mb-2 ml-auto mr-auto text-white shadow-md form_btn md:w-72 rounded-2xl" type="submit" value="Controleer" name="controleer">
        </form>
    <?php } ?>

</body>

</html>