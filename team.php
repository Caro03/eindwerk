<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");

if (isset($_GET['id'])) {
    $teamID = $_GET['id'];
    $fetchTeamById = new Team();
    $fetchTeamById->setTeamID($teamID);
    $team = $fetchTeamById->fetchTeamById($teamID);

    $fetchMembersByTeamId = new Team();
    $fetchMembersByTeamId->setTeamID($teamID);
    $members = $fetchMembersByTeamId->fetchMembersByTeamId($teamID);
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
    <link rel="icon" href="images/p.png">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>peerhood</title>
</head>

<body>
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
        <h1 class="font-medium text-2xl my-10"><?php echo $team['teamname'] ?></h1>
        <div class="mb-10 space-y-2">
            <?php foreach ($members as $member) : ?>
                <li class="list-none"><?php echo $member['firstname'] . " " . $member['lastname'] ?></li>
            <?php endforeach; ?>
        </div>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>