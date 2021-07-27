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
	<div class="block ml-auto mr-auto w-64">
		<img class="logo" src="images/logo-wit.svg">
		<div class="form form--login">
			<form action="" method="post">
				<h2 class="font-medium text-2xl my-10">Login</h2>

				<?php if (isset($error)) : ?>
					<div class="mb-5 text-red-500 font-medium">
						<p>
							<?php echo $error; ?>
						</p>
					</div>
				<?php endif; ?>

				<div class="form__field">
					<input class="outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" type="text" class="form-control" id="email" name="email" placeholder="Email">
				</div>

				<div class="form__field">
					<input class="outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" type="password" class="form-control" id="password" name="password" placeholder="Passwoord">
				</div>

				<div class="form__field">
					<input class="outline-none w-56 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Login" class="btn btn--primary">
				</div>
			</form>
		</div>
		<div class="-ml-10 text-center">
			<a class="text-sm" href="register.php">Nog geen account?</a>
		</div>
	</div>
</body>

</html>