<?php
include_once(__DIR__ . "/classes/User.php");

if (!empty($_POST)) {

    try {
        $user = new User();
        $user->setEmail(htmlspecialchars($_POST['email']));
        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setPassword($_POST['password']);
        $user->setRole($_POST['role']);

        if ($_POST['password'] != $_POST['verifyPassword']) {
            $error = "Wachtwoord klopt niet!";
        }

        if ($user->endsWith("thomasmore.be")) {
        } else {
            $error = "Gebruik email van Thomasmore!";
        }

        if ($user->availableEmail($user->getEmail())) {
            // Email ready to use
            if ($user->validEmail()) {
                // valid email
            } else {
                $error = "Ongeldig email!";
            }
        } else {
            $error = "Email is al in gebruik!";
        }
    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }


    if (!isset($error)) {
        // methode
        $user->save();

        //$succes = "user saved";
        header('Location: login.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/styles.css">
    <link rel="stylesheet" href="public/styles.css">
    <link rel="icon" href="images/p.png">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
    <title>peerhood</title>
</head>

<body>
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
        <img class="mt-10" src="./images/logo.png">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <h2 class="text-center font-medium text-2xl my-10">Registreren</h2>

            <?php if (isset($error)) : ?>
                <div class="mb-5 text-red-500 font-medium">
                    <p class="form_error">
                        <?php echo $error; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="First name" type="text" name="firstname" id="firstname">
            </div>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Last name" type="text" name="lastname" id="lastname">
            </div>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Email" type="text" name="email" id="email">
            </div>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Password" type="password" name="password" id="password">
            </div>

            <div class="form_field">
                <input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" placeholder="Verify password" type="password" name="verifyPassword" id="verifyPassword">
            </div>

            <select name="role" class="ml-auto mr-auto outline-none w-56 block px-5 py-5 rounded-xl border-black border-2 mb-10 md:w-72 lg:w-80" id="choiceselect">
                <option value="student">Ik ben een student</option>
                <option value="docent">Ik ben een docent</option>
            </select>

            <div class="form_button">
                <input class="ml-auto mr-auto outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500 md:w-72 lg:w-80" type="submit" value="Registreren" name="register" id="register">
            </div>

        </form>
        <div class="text-center mb-10">
            <a class="text-sm hover:underline hover:text-yellow-500" href="login.php">Al een account? Log je hier in</a>
        </div>
    </div>
</body>

</html>