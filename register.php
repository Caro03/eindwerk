<?php
include_once(__DIR__ . "/classes/Db.php");
include_once(__DIR__ . "/classes/User.php");
session_start();
session_destroy();

// if register form is submitted and not empty
if (!empty($_POST['register'])) {
    if (count(array_filter($_POST)) == count($_POST)) {
        // check if email is filled out
        if (!empty($_POST['email'])) {
            // check for thomas more email
            $verifyEmail = new User();
            $email = $_POST['email'];
            $verifyEmail->setEmail($email);
            $resultEmail = $verifyEmail->validateEmail($email);

            // if thomas more email = ok
            if ($resultEmail == 1) {
                // check if email is not taken
                $emailAvailable = new User();
                $emailAvailable->setEmail($email);
                $available = $emailAvailable->emailAvailable($email);
                if ($available == 1) {
                    // check if password and verifypassword are the same
                    if (!empty($_POST['password']) && $_POST['password'] === $_POST['verifyPassword']) {
                        //check if password length is ok
                        $verifyPassword = new User();
                        $password = $_POST['password'];
                        $verifyPassword->setPassword($password);
                        $resultPassword = $verifyPassword->validatePassword($password);

                        if ($resultPassword == 1) {
                            // register the user
                            $user = new User();
                            $firstname = $_POST['firstname'];
                            $lastname = $_POST['lastname'];
                            $role = $_POST['role'];
                            $email = $_POST['email'];
                            $password = $_POST['password'];
                            $user->setFirstname($firstname);
                            $user->setLastname($lastname);
                            $user->setRole($role);
                            $user->setEmail($email);
                            $user->setPassword($password);
                            $register = $user->register($email, $password, $firstname, $lastname, $role);

                            session_start();
                            $userID = $user->fetchUserID($email);
                            $_SESSION['user'] = $userID;
                            header("Location: index.php");
                        } else {
                            $error = "Password too short.";
                            $alert = 5;
                        }
                    } else {
                        $error = "Password doesn't match.";
                        $alert = 4;
                    }
                } else {
                    $error = "Email taken.";
                    $alert = 3;
                }
            } else {
                $error = "Only Thomas More emails please.";
                $alert = 2;
            }
        }
    } else {
        $error = "Fill all fields out please.";
        $alert = 1;
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