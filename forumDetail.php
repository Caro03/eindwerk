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

$posts = new Forum();
//$allPosts = $posts->getForumPosts();
//$idArray = $posts->idFromPost();

if (isset($_GET['id'])) {
    $posts = new Forum();
    $allPosts = $posts->getForumPostsId();
    $allComments = $posts->getComments();
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
    <div class="block ml-auto mr-auto w-64">
        <div class="my-10">
            <?php foreach ($allPosts as $post) : ?>
                <div class="bg-gray-200 my-5 rounded-xl px-5 py-5">
                    <p class="font-medium pb-2"><?php echo $post['firstname'] . " " . $post['lastname']; ?></p>
                    <p><?php echo $post['content']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach ($allComments as $comment) : ?>
            <div class="mb-5">
                <p class="font-medium pb-2"><?php echo $comment['firstname'] . " " . $comment['lastname']; ?></p>
                <p><?php echo $comment['content']; ?></p>
                <hr class="mt-5">
            </div>
        <?php endforeach; ?>

        <form class="form" action="" method="post">
        <textarea class="w-64 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" placeholder="Schrijf een opmerking" name="post" id="postcontent" cols="20" rows="5"></textarea>
            <input class="outline-none w-64 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Post" name="submitComment" id="submitPost">
        </form>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>