<?php
include_once(__DIR__ . "/classes/User.php");

//test
if ($role = 1) {
    $fetchPData = new User();
    $fetchPData->setUserID($userID);
    $PData = $fetchPData->fetchPData($userID);
} else {
}
//var_dump($PData);
$id = $PData["id"];

$user = new User();
if (isset($_POST['emailOpslaan'])) {
    if (!empty($_POST)) {
        $email = htmlspecialchars($_POST['email']);
        echo $_POST['email'];
        if (!empty($email)) {
            $result = $user->emailAvailable($email);
            if ($result == true) {
                echo "goed";
                $user->setEmail($email);
                $userID = $id;
                $user->setuserID($userID);
                var_dump($email, $userID);
                $result = $user->changeEmail($email, $userID);
                echo $result;
                if ($result == true) {
                    echo "query gelukt";
                    header('Location:index.php');
                } else if ($result == false) {
                    echo "query gefaald";
                } else {
                    echo "email al in gebruik";
                }
            } else {
                $error = "speciaal teken niet ondersteund";
            }
        } else {
            $error = "mag niet leeg zijn";
        }
    }
}

$user = new User();
if (isset($_POST['WWOpslaan'])) {
    if (!empty($_POST)) {
        $password = htmlspecialchars($_POST['password']);
        echo $_POST['password'];
        if (!empty($password)) {


            $user->setPassword($password);
            $userID = $id;
            $user->setuserID($userID);
            var_dump($password, $userID);
            $result = $user->changePass($password, $userID);
            echo $result;
            if ($result == true) {
                echo "query gelukt";
                header('Location:index.php');
            } else if ($result == false) {
                echo "query gefaald";
            }
        } else {
            $error = "speciaal teken niet ondersteund";
        }
    } else {
        $error = "mag niet leeg zijn";
    }
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
    <title>PEERHOOD | Profiel</title>
</head>

<body class="h-screen gradient">
    <div class="h-auto pb-20 mx-5 my-10 ml-auto mr-auto bg-white rounded-3xl w-60 sm:w-80 md:w-96">
        <div class="container">
            <h1 class="text-2xl text-center pt-14 mb-14 form_title md:text-2xl">Jouw profiel</h1>
            <div class="mb-2 text-center form_field">
                <span><?php echo $PData["firstname"] . ' ' . $PData["lastname"] ?></span>
            </div>
            <br>
            <?php if (isset($error)) : ?>
                <div class="mb-5 text-center form_error">
                    <p class="form_error">
                        <?php echo $error; ?>
                    </p>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <input class="block mb-5 ml-auto mr-auto bg-transparent border-b border-black w-52 form_field sm:w-64 md:w-72" value="<?php echo $PData["email"] ?>" name="email" />
                <input type="submit" value="Opslaan" name="emailOpslaan" class="block h-12 mb-10 ml-auto mr-auto text-white shadow-md w-52 sm:w-64 form_btn md:w-72 rounded-2xl">
            </form>
            <form action="" method="post">
                <input class="block mb-5 ml-auto mr-auto bg-transparent border-b border-black w-52 form_field sm:w-64 md:w-72" type="password" value="" name="password" placeholder="Wachtwoord" />
                <input type="submit" value="Opslaan" name="WWOpslaan" class="block h-12 mb-2 ml-auto mr-auto text-white shadow-md w-52 sm:w-64 form_btn md:w-72 rounded-2xl">
            </form>

            <div class="mt-5 text-center">
                <a class="form_field" href="logout.php">Uitloggen</a>
            </div>

        </div>
    </div>

</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>