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
$allData = $user->allData();

$forum = new Forum();
$forumPost = $forum->getForumPosts();
$printLikes = $forum->printLikes();

if (isset($_GET['id'])) {
    $posts = new Forum();
    $allPosts = $posts->getForumPostsId();
    $postId = $posts->getPostsId();
    $commentId = $posts->getCommentId();
    $allComments = $posts->getComments();
}

if (!empty($_POST['submitComment'])) {
    $postContent = $_POST['content'];
    $postForum = new Forum();
    $idArray = $postForum->idFromPost();
    //$postID = $idArray['id'];
    //$postForum->setPostID($postID);
    $postForum->setPostContent($postContent);
    $post = $postForum->postComment($postContent);
    $allComments = $posts->getComments();
    //$allPosts = $posts->getForumPosts();
}

if (!empty($_POST['like'])) {
    $commentsId = $_POST['like'];
    $forum->setCommentsId($commentId);
    $like = $forum->saveDislike();
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
        <div class="my-10">
            <?php foreach ($allPosts as $post) : ?>
                <div class="bg-gray-200 my-5 rounded-xl px-5 py-5">
                    <p class="font-medium pb-2"><?php echo $post['title']; ?></p>
                    <p class="pb-5"><?php echo $post['content']; ?></p>
                    <p class="text-xs"><?php if (date('Ymd') == date('Ymd', strtotime($post['date']))) { ?>Vandaag<?php } ?><?php if (date('Ymd', strtotime($post['date'])) == date('Ymd', strtotime('-1 day'))) { ?>Gisteren<?php } ?><?php if (date('Ymd') != date('Ymd', strtotime($post['date'])) && date('Ymd', strtotime($post['date'])) != date('Ymd', strtotime('-1 day')) && date('Y') == date('Y', strtotime($post['date']))) { ?><?php echo date('d M', strtotime($post['date'])) ?><?php } ?><?php if (date('Y') != date('Y', strtotime($post['date']))) { ?><?php echo date('d M Y', strtotime($post['date'])) ?><?php } ?> gevraagd door <span class="font-medium"><?php if ($post['roleChoice'] == 0) { ?><?php echo $post['firstname'] . " " . $post['lastname']; ?></span><?php } ?><?php if ($post['roleChoice'] == 1) { ?><span class="font-medium">Anoniem<?php } ?></span></p>
                </div>
            <?php endforeach; ?>
        </div>

        <?php foreach ($allComments as $comment) : ?>
            <div class="mb-5">
                <p class="font-medium pb-2"><?php if ($comment['roleChoice'] == 0) { ?><?php echo $comment['firstname'] . " " . $comment['lastname']; ?><?php } ?><?php if ($comment['roleChoice'] == 1) { ?>Anoniem<?php } ?><?php if ($comment['role_id'] == 'docent') { ?> (docent)<?php } ?></p>
                <p><?php echo $comment['content']; ?></p>

                <?php if ($comment['likes'] != 0) { ?>
                    <p class="mt-5 text-sm my-2 rounded-lg inline-block py-1 px-2 bg-green-500 text-white">Deze comment is als nuttig beoordeelt</p>
                <?php } ?>

                <?php if ($comment['dislikes'] != 0) { ?>
                    <p class="mt-5 text-sm my-2 rounded-lg inline-block py-1 px-2 bg-red-500 text-white">Deze comment is als fout beoordeelt</p>
                <?php } ?>


                <?php if ($PData['role_id'] == 'docent') { ?>
                    <?php if ($comment['likes'] == 0 && $comment['dislikes'] == 0) { ?>
                        <?php if ($comment['role_id'] == 'student') { ?>
                            <?php foreach ($postId as $postid) : ?>
                                <?php foreach ($commentId as $commentid) : ?>
                                    <div class="mt-5">
                                        <a class="text-sm mt-2 bg-gray-100 rounded-lg inline-block py-1 px-2 hover:bg-green-500 hover:text-white" href="like.php?id=<?php echo $postid['post_id'] ?>&user=<?php echo $comment['user_id'] ?>&comment=<?php echo $commentid['id'] ?>">Deze comment als nuttig beoordelen</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                <?php if ($PData['role_id'] == 'docent') { ?>
                    <?php if ($comment['dislikes'] == 0 && $comment['likes'] == 0) { ?>
                        <?php if ($comment['role_id'] == 'student') { ?>
                            <?php foreach ($postId as $postid) : ?>
                                <?php foreach ($commentId as $commentid) : ?>
                                    <div class="mt-5">
                                        <a class="text-sm bg-gray-100 rounded-lg inline-block py-1 px-2 hover:bg-red-500 hover:text-white" href="dislike.php?id=<?php echo $postid['post_id'] ?>&user=<?php echo $comment['user_id'] ?>&comment=<?php echo $commentid['id'] ?>">Deze comment als fout beoordelen</a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>

                <hr class="mt-5">
            </div>
        <?php endforeach; ?>

        <form class="form" action="" method="post">
            <textarea class="w-64 md:w-72 lg:w-80 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" placeholder="Schrijf een opmerking" name="content" id="postcontent" cols="20" rows="5"></textarea>
            <input class="md:w-72 lg:w-80 outline-none w-64 block px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Post" name="submitComment" id="submitPost">
        </form>
    </div>

</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>