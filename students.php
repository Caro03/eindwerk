<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");

if (isset($_GET['id'])) {
    $courseID = $_GET['id'];
    $fetchTeamById = new Team();

    $fetchMembersByTeamId = new Team();
    $fetchMembersByTeamId->setCourseID($courseID);
    $members = $fetchMembersByTeamId->fetchStudentsByCourse($courseID);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="build/tailwind.css">
    <title>PEERHOOD</title>
</head>

<body>

    <div class="mb-5">
        <?php foreach ($members as $member) : ?>
            <li class="py-2 text-center list-none form_field"><?php echo $member['firstname'] . " " . $member['lastname'] ?></li>
        <?php endforeach; ?>
    </div>
</body>

</html>