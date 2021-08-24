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
$countComments = $posts->countComments();
$countLikes = $posts->countLikes();
$getLikes = $posts->getForumLikes();
//$idArray = $posts->idFromPost();

$courses = new Course();
$allCourses = $courses->allCourses();

if (!empty($_POST['submitComment'])) {
    if (!empty($_POST['post'])) {
        $postContent = $_POST['post'];
        $postForum = new Forum();
        //$idArray = $postForum->idFromPost();
        //$postID = $idArray['id'];
        $postForum->setPostID($postID);
        $postForum->setPostContent($postContent);
        $post = $postForum->postComment($postID, $postContent);
        //$allPosts = $posts->getForumPosts();
    } else {
        $error = "Jouw comment mag niet leeg zijn";
    }
}

if (!empty($_POST['filter'])) {
    $posts->setCategory($_POST['category']);
    $filteren = $posts->filterDesign();
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
    <div class="block ml-auto mr-auto w-64 md:w-72 lg:w-96">

        <?php if (isset($error)) : ?>
            <div class="mt-5 text-red-500 font-medium">
                <p>
                    <?php echo $error; ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <a class="mx-auto mt-10 outline-none block px-5 py-5 rounded-xl text-white bg-blue-400 mb-4 hover:bg-blue-500" href="askForum.php">Stel hier je vraag</a>
        </div>

        <form class="my-10" action="" method="post">
            <div class="pb-5 max-w-sm mx-auto">
                <label for="cars">Ik wil enkel vragen bekijken van een bepaalde categorie:</label>
            </div>

                <select name="category" class="w-64 md:w-72 lg:w-96 outline-none block px-5 py-5 rounded-xl border-black border-2 mb-4" id="choiceselect">
                    <option value="design">Design</option>
                    <option value="marketing">Marketing</option>
                    <option value="programmeren">Programmeren</option>
                    <option value="taal">Taal</option>
                </select>

            <div class="">
                <input class="w-64 md:w-72 lg:w-96 mx-auto block outline-none px-5 py-5 rounded-xl text-white bg-yellow-400 mb-4 hover:bg-yellow-500" type="submit" value="Filter" name="filter" id="submitPost">
            </div>
        </form>

        <?php if (!empty($_POST['filter'])) : ?>
            <?php foreach ($filteren as $filter) : ?>
                <div class="max-w-sm mx-auto bg-gray-200 my-5 rounded-xl px-5 py-5 hover:bg-gray-300">

                    <a href="forumDetail.php?id=<?php echo $filter['id'] ?>">
                        <p class="font-medium text-blue-500"><?php echo $filter['title']; ?></p>
                        <p class="text-sm my-2 bg-gray-100 rounded-lg inline-block py-1 px-2"><?php echo $filter['category']; ?></p>
                        <div class="flex space-x-5 mb-4">
                            <div class="flex flex-col text-center text-sm">
                                <p class="mb-2">
                                <div class="font-medium text-blue-500"><?php echo $filter['(select count(*) FROM comments where (comments.post_id = posts.id))'] ?></div>
                                <div>Antwoorden</div>
                                </p>
                            </div>

                            <?php if ($filter['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] == 1) { ?>
                                <div class="flex flex-col text-center text-sm">
                                    <p class="mb-2">
                                    <div class="font-medium text-blue-500"><?php echo $filter['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] ?></div>
                                    <div>Nuttig</div>
                                    </p>
                                </div>
                            <?php } ?>
                            <?php if ($filter['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] != 1) { ?>
                                <div class="flex flex-col text-center text-sm">
                                    <p class="mb-2">
                                    <div class="font-medium text-blue-500"><?php echo $filter['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] ?></div>
                                    <div>Nuttige</div>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>

                        <p class="text-xs"><?php if (date('Ymd') == date('Ymd', strtotime($filter['date']))) { ?>Vandaag<?php } ?><?php if (date('Ymd', strtotime($filter['date'])) == date('Ymd', strtotime('-1 day'))) { ?>Gisteren<?php } ?><?php if (date('Ymd') != date('Ymd', strtotime($filter['date'])) && date('Ymd', strtotime($filter['date'])) != date('Ymd', strtotime('-1 day')) && date('Y') == date('Y', strtotime($filter['date']))) { ?><?php echo date('d M', strtotime($filter['date'])) ?><?php } ?><?php if (date('Y') != date('Y', strtotime($filter['date']))) { ?><?php echo date('d M Y', strtotime($filter['date'])) ?><?php } ?> gevraagd door <span class="font-medium"><?php if ($filter['roleChoice'] == 0) { ?><?php echo $filter['firstname'] . " " . $filter['lastname']; ?></span><?php } ?><?php if ($filter['roleChoice'] == 1) { ?><span class="font-medium">Anoniem<?php } ?></span></p>
                    </a>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (empty($_POST['filter'])) : ?>
            <?php foreach ($allPosts as $post) : ?>
                <?php if (date('Y') == date('Y', strtotime($post['date']))) { ?>
                <div class="max-w-sm mx-auto bg-gray-200 my-5 rounded-xl px-5 py-5 hover:bg-gray-300">

                    <a href="forumDetail.php?id=<?php echo $post['id'] ?>">
                        <p class="font-medium text-blue-500"><?php echo $post['title']; ?></p>
                        <p class="text-sm my-2 bg-gray-100 rounded-lg inline-block py-1 px-2"><?php echo $post['category']; ?></p>
                        <div class="flex space-x-5 mb-4">
                            <div class="flex flex-col text-center text-sm">
                                <p class="mb-2">
                                <div class="font-medium text-blue-500"><?php echo $post['(select count(*) FROM comments where (comments.post_id = posts.id))'] ?></div>
                                <div>Antwoorden</div>
                                </p>
                            </div>

                            <?php if ($post['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] == 1) { ?>
                                <div class="flex flex-col text-center text-sm">
                                    <p class="mb-2">
                                    <div class="font-medium text-blue-500"><?php echo $post['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] ?></div>
                                    <div>Nuttig</div>
                                    </p>
                                </div>
                            <?php } ?>
                            <?php if ($post['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] != 1) { ?>
                                <div class="flex flex-col text-center text-sm">
                                    <p class="mb-2">
                                    <div class="font-medium text-blue-500"><?php echo $post['(select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1))'] ?></div>
                                    <div>Nuttige</div>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>

                        <p class="text-xs"><?php if (date('Ymd') == date('Ymd', strtotime($post['date']))) { ?>Vandaag<?php } ?><?php if (date('Ymd', strtotime($post['date'])) == date('Ymd', strtotime('-1 day'))) { ?>Gisteren<?php } ?><?php if (date('Ymd') != date('Ymd', strtotime($post['date'])) && date('Ymd', strtotime($post['date'])) != date('Ymd', strtotime('-1 day'))) { ?><?php echo date('d M', strtotime($post['date'])) ?><?php } ?> gevraagd door <span class="font-medium"><?php if ($post['roleChoice'] == 0) { ?><?php echo $post['firstname'] . " " . $post['lastname']; ?></span><?php } ?><?php if ($post['roleChoice'] == 1) { ?><span class="font-medium">Anoniem<?php } ?></span></p>
                    </a>

                </div>
                <?php } ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
<footer>
    <?php include_once('nav.inc.php'); ?>
</footer>

</html>