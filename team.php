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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="build/tailwind.css">
    <title>PEERHOOD</title>
</head>

<body>

    <h1 class="mb-10 text-2xl text-center form_title md:text-2xl"><?php echo $team['teamname'] ?></h1>
    <div class="mb-5">
        <?php foreach ($members as $member) : ?>
            <li class="py-2 text-center list-none form_field"><?php echo $member['firstname'] . " " . $member['lastname'] ?></li>
        <?php endforeach; ?>
    </div>
    <a href="editteam.php?id=<?php echo $team['id'] ?>" class="">
        Voeg studenten toe
    </a>
</body>

</html>