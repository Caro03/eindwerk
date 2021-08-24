<?php

include_once(__DIR__ . "/classes/User.php");
$user = new User();

if (!empty($_POST)) {
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);
	if (!empty($email) && !empty($password)) {
		if ($user->checkLogin($email, $password)) {
			$user->setEmail($email);
			$idArray = $user->idFromSession($email);
			$id = $idArray['id'];
			$user->setId($id);

			// later aanpassen -> if checkbox is ticked use cookie 
			session_start();
			$_SESSION["user"] = $email;
			$_SESSION["id"] = $id;

			//redirect to index.php
			header("Location: index.php");
		} else {
			$error = "Wachtwoord en email komen niet overeen";
		}
	} else {
		$error = "Email en wachtwoord zijn verplicht";
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
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400&display=swap" rel="stylesheet">
	<title>Login | vindr</title>
</head>

<body>
	<div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">
		<img class="mt-10" src="images/logo.png">
		<div class="form form--login">
			<form action="" method="post">
				<h2 class="text-center font-medium text-2xl my-10">Login</h2>

				<?php if (isset($error)) : ?>
					<div class="mb-5 text-red-500 font-medium">
						<p>
							<?php echo $error; ?>
						</p>
					</div>
				<?php endif; ?>

				<div class="form__field">
					<input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" type="text" class="form-control" id="email" name="email" placeholder="Email">
				</div>

				<div class="form__field">
					<input class="ml-auto mr-auto outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4 md:w-72 lg:w-80" type="password" class="form-control" id="password" name="password" placeholder="Passwoord">
				</div>

				<div class="form__field">
					<input class="ml-auto mr-auto outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500 md:w-72 lg:w-80" type="submit" value="Login" class="btn btn--primary">
				</div>
			</form>
		</div>
		<div class="text-center">
			<a class="text-sm" href="register.php">Nog geen account?</a>
		</div>
	</div>
</body>

</html>