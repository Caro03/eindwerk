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
$PData = $user->allUserData();

$forum = new Forum();
$forumPost = $forum->getForumPosts();

$posts = new Forum();
//$allPosts = $posts->getForumPosts();
//$idArray = $posts->idFromPost();

if (isset($_GET['id'])) {
    $posts = new Forum();
    $allPosts = $posts->getForumPostsId();
    $postId = $posts->getPostsId();
    $allComments = $posts->getComments();
}

if (isset($_GET['user'])) {
    $posts = new Forum();
    $saveLike = $posts->saveLike();
    $printLikes = $posts->printLikes();
}

if (!empty($_POST['submitComment'])) {
    if (!empty($_POST['post'])) {
        $postContent = $_POST['post'];
        $postForum = new Forum();
        $idArray = $postForum->idFromPost();
        //$postID = $idArray['id'];
        //$postForum->setPostID($postID);
        $postForum->setPostContent($postContent);
        $post = $postForum->postComment($postContent);
        $allComments = $posts->getComments();
        //$allPosts = $posts->getForumPosts();
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

        <h1 class="font-medium text-2xl my-10">Je hebt deze comment beoordeelt als nuttig</h1>

        <a class="hover:underline hover:text-yellow-500" href="forum.php">Ga terug naar forum</a>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>