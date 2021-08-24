<?php
include_once(__DIR__ . "/classes/User.php");

session_start();
$email = $_SESSION["user"];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
$PData = $user->allUserData();

if (!empty($_POST['password']) && !empty($_POST['newPassword'])) {
    if ($user->checkLogin($_SESSION['user'], $_POST['password'])) {
        if ($_POST['newPassword'] == $_POST['newPasswordConfirmation']) {
            //current password matches -> update password
            $user->setUpdatePassword($_POST['newPassword']);
            $user->changePassword();
            $good = "Nieuw wachtwoord is opgeslagen";
        } else {
            $error = "wachtwoord komt niet overeen";
        }
    } else {
        $error = "wachtwoord klopt niet";
    }
}

if (!empty($_POST['roleChoice'])) {
    $user->setRoleChoice($_POST['role']);
    $user->changeRole();
    $good = "Jouw voorkeur voor het forum is opgeslagen";
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
    <div class="block ml-auto mr-auto md:w-72 lg:w-80 w-64">

        <h2 class="font-medium text-2xl my-10">Profiel</h2>

        <h2 class="mb-2"><?php echo $PData['firstname'] . ' ' . $PData['lastname']; ?></h2>
        <p class="mb-5"><?php echo $PData['email']; ?></p>

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

        <form action="" method="post">
            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Huidig wachtwoord" type="password" name="password" id="firstname">
            </div>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Nieuw wachtwoord" type="password" name="newPassword" id="lastname">
            </div>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Bevestig nieuw wachtwoord" type="password" name="newPasswordConfirmation" id="email">
            </div>

            <div class="form_button">
                <input class="mb-10 ml-auto mr-auto outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500 md:w-72 lg:w-80" type="submit" value="Opslaan" name="profile" id="register">
            </div>
        </form>

        <?php if ($PData['role_id'] == 'student') { ?>
            <form action="" method="post">
                <p class="mb-2">Hoe wil je berichten plaatsen op het forum?</p>
                <select name="role" class="ml-auto mr-auto outline-none w-56 block px-5 py-5 rounded-xl border-black border-2 mb-5 md:w-72 lg:w-80" id="choiceselect">
                    <option <?php if ($PData['roleChoice'] == 0) {
                                echo "selected";
                            } ?> value="0">Met mijn eigen naam</option>
                    <option <?php if ($PData['roleChoice'] == 1) {
                                echo "selected";
                            } ?> value="1">Anoniem</option>
                </select>

                <div class="form_button">
                    <input class="mb-10 ml-auto mr-auto outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500 md:w-72 lg:w-80" type="submit" value="Opslaan" name="roleChoice" id="register">
                </div>
            </form>
        <?php } ?>

        <a class="ml-auto mr-auto text-center outline-none w-56 block px-5 py-5 rounded-xl text-white bg-blue-400 mb-4 hover:bg-blue-500 md:w-72 lg:w-80" href="logout.php">Uitloggen</a>

    </div>

</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>