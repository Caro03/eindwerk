<?php
include_once(__DIR__ . "/classes/Course.php");
include_once(__DIR__ . "/classes/Team.php");
include_once(__DIR__ . "/classes/Forum.php");
include_once(__DIR__ . "/classes/User.php");

session_start();
$email = $_SESSION["user"];
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$user = new User();
$PData = $user->allData();

$posts = new Forum();
$allPosts = $posts->getForumPosts();
//$idArray = $posts->idFromPost();

$courses = new Course();
$allCourses = $courses->allCourses();

if (!empty($_POST['submitPost'])) {
    if (!empty($_POST['post'])) {
        if (!empty($_POST['title'])) {
            $postContent = $_POST['post'];
            $title = $_POST['title'];
            $category = $_POST['category'];

            $postForum = new Forum();
            $postForum->setPostContent($postContent);
            $postForum->setTitle($title);
            $postForum->setCategory($category);
            $post = $postForum->postForum($postContent, $title, $category);
            $good = "Jouw vraag is succesvol gepost";
            $allPosts = $posts->getForumPosts();
        } else {
            $error = "De titel mag niet leeg zijn";
        }
    } else {
        $error = "Jouw post mag niet leeg zijn";
    }
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
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-80">

        <?php if (isset($error)) : ?>
            <div class="mt-5 text-red-500 font-medium">
                <p>
                    <?php echo $error; ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (isset($good)) : ?>
            <div class="mt-10 mb-5 text-green-500 font-medium">
                <p class="form_error">
                    <?php echo $good; ?>
                </p>
            </div>
        <?php endif; ?>

        <form class="my-10" action="" method="post">
            <input class="md:w-72 lg:w-80 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" type="text" name="title" id="title" placeholder="Wat is de titel van jouw vraag?">
            <textarea class="w-64 md:w-72 lg:w-80 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" placeholder="Schrijf een nieuwe post" name="post" id="postcontent" cols="20" rows="5"></textarea>

            <div class="pb-5">
                <label for="cars">Welke categorie past bij jouw vraag?</label>
            </div>
            <select name="category" class="md:w-72 lg:w-80 outline-none w-56 block px-5 py-5 rounded-xl border-black border-2 mb-4" id="choiceselect">
                <option value="design">Design</option>
                <option value="marketing">Marketing</option>
                <option value="programmeren">Programmeren</option>
                <option value="taal">Taal</option>
            </select>

            <input class="outline-none w-64 md:w-72 lg:w-80 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Post" name="submitPost" id="submitPost">
        </form>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>