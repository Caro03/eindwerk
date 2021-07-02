<?php
include_once(__DIR__ . "/classes/User.php");

if(!empty($_POST)) {

    try {
        $user = new User();
        $user->setEmail(htmlspecialchars($_POST['email']));
        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setPassword($_POST['password']);
        $user->setRole($_POST['role']);

        if($_POST['password'] != $_POST['verifyPassword']) {
            $error = "Wachtwoord klopt niet!";
        }

        if ($user->endsWith("@student.thomasmore.be")) {
        } else {
            $error = "Gebruik email van Thomasmore!";
        }

        if ( $user->availableEmail($user->getEmail()) ) {
            // Email ready to use
            if ( $user->validEmail()){
                // valid email
            } else {
                $error = "Ongeldig email!";
            }
        } 
        else {
            $error = "Email is al in gebruik!";
        }


    } catch (\Throwable $th) {
        $error = $th->getMessage();
    }


    if(!isset($error)) {
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="build/tailwind.css">
    <title>Register</title>
</head>

<body class="h-screen gradient">
    <div class="h-auto pb-10 mx-5 my-10 ml-auto mr-auto bg-white rounded-3xl w-60 sm:w-80 md:w-96">
        <img class="pt-5 mb-2 ml-auto mr-auto logo" src="./images/logo-slogan.png">
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                <h2 class="mb-10 text-xl text-center form_title md:text-2xl">Registreren</h2>

                <?php if (isset($error)) : ?>
                    <div class="mb-5 text-center form_error">
                        <p class="form_error">
                            <?php echo $error; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="form_field">
                    <input class="block mb-8 ml-auto mr-auto bg-transparent border-b border-black w-52 sm:w-64 form_field md:w-72" placeholder="First name" type="text" name="firstname" id="firstname">
                </div>

                <div class="form_field">
                    <input class="block mb-8 ml-auto mr-auto bg-transparent border-b border-black w-52 sm:w-64 form_field md:w-72" placeholder="Last name" type="text" name="lastname" id="lastname">
                </div>

                <div class="form_field">
                    <input class="block mb-8 ml-auto mr-auto bg-transparent border-b border-black w-52 sm:w-64 form_field md:w-72" placeholder="Email" type="text" name="email" id="email">
                </div>

                <div class="form_field">
                    <input class="block mb-8 ml-auto mr-auto bg-transparent border-b border-black w-52 sm:w-64 form_field md:w-72" placeholder="Password" type="password" name="password" id="password">
                </div>

                <div class="form_field">
                    <input class="block mb-8 ml-auto mr-auto bg-transparent border-b border-black w-52 sm:w-64 form_field md:w-72" placeholder="Verify password" type="password" name="verifyPassword" id="verifyPassword">
                </div>

                <div class="block mb-8 ml-auto mr-auto w-52 sm:w-64 radio-item form_field md:w-72">
                    <label class="mr-2" for="role">Ik ben een...</label>
                    <input type="radio" id="student" name="role" value="2">
                    <label class="mr-2" for="student">Student</label>
                    <input type="radio" id="docent" name="role" value="1">
                    <label for="docent">Docent</label>
                </div>

                <div class="form_button">
                    <input class="block h-12 mb-2 ml-auto mr-auto text-white shadow-md w-52 sm:w-64 form_btn md:w-72 rounded-2xl" type="submit" value="Registreren" name="register" id="register">
                </div>

            </form>
            <div class="text-sm text-center">
                <a class="form_register" href="login.php">Al een account? Log je hier in</a>
            </div>
        </div>
    </div>
</body>

</html>