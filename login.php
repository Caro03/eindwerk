<?php

include_once(__DIR__ . "/classes/User.php");
	$user = new User();

	if(!empty($_POST)) {
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['password']);
		if(!empty($email) && !empty($password)){
			if($user->checkLogin($email, $password)){
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
			}else{
				$error = "Wachtwoord en email komen niet overeen";
			}
		}else {
			$error = "Email en wachtwoord zijn verplicht";
		}
	}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>
    <title>Login | vindr</title>
</head>
<body>
<img class="logo" src="images/logo-wit.svg">
<div class="container">
<img class="pijlfront" src="images/pijl-oranje.svg">
		<div class="form form--login">
			<form action="" method="post">
				<h2 class="form__title">Login</h2>

				<?php if(isset($error)) : ?>
				<div class="form__error">
					<p>
						<?php echo $error; ?>
					</p>
				</div>
				<?php endif; ?>

                <div class="form__field">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
				</div>

                <div class="form__field">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Passwoord">
                </div>

				<div class="form__field">
					<input type="submit" value="Login" class="btn btn--primary">	
				</div>
			</form>
			<a class="link-account" href="register.php"> Nog geen account? </a>
		</div>
	</div>
</body>
</html>