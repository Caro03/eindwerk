<?php

include_once(__DIR__ . "/Db.php");

class Forum
{
    private $teamID;
    private $studentID;
    private $postContent;
    private $commentContent;
    private $postID;
    private $title;
    private $category;
    private $commentsId;

    public function postForum($postContent, $title, $category)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO posts (user_id, content, title, category, date) VALUES ({$_SESSION["id"]}, :content, :title, :category, CURRENT_TIME())");

        $statement->bindParam(":content", $postContent);
        $statement->bindParam(":title", $title);
        $statement->bindParam(":category", $category);

        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getForumPosts()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select posts.id, posts.content, posts.title, posts.category, posts.date, users.firstname, users.lastname, users.roleChoice, (select count(*) FROM comments where (comments.post_id = posts.id)), (select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1)) FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.date DESC");
        $category = $this->getCategory();
        $statement->bindParam(":category", $category);
        
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getForumLikes()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select posts.id, (select count(*) FROM comments where (comments.post_id = posts.id)), (select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1)) FROM posts");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countComments()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select count(*) from comments as comment inner join posts as post where comment.post_id = post.id");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function countLikes()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select count(likes) from comments as comment inner join posts where comment.post_id = posts.id and comment.likes = 1");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function filterDesign()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select posts.id, posts.content, posts.title, posts.category, posts.date, users.firstname, users.lastname, users.roleChoice, (select count(*) FROM comments where (comments.post_id = posts.id)), (select count(*) from comments where (comments.post_id = posts.id and comments.likes = 1)) FROM posts INNER JOIN users ON users.id = posts.user_id where posts.category = :category ORDER BY posts.date DESC");

        $category = $this->getCategory();
        $statement->bindParam(":category", $category);

        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getPostsId()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from comments inner join users as user on comments.user_id = user.id where post_id = {$_GET["id"]} limit 1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getCommentId()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from comments where post_id = {$_GET["id"]} limit 1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function printLikes()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from comments inner join users as user on comments.user_id = user.id where post_id = {$_GET["id"]}");
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getForumPostsId()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id where posts.id = {$_GET["id"]}");
        //$statement->bindParam(":postID", $postID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function postComment($postContent)
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO comments (user_id, post_id, content, likes, dislikes) VALUES ({$_SESSION["id"]}, {$_GET["id"]}, :content, 0, 0)");
        //$statement->bindParam(":postid", $postID);
        $statement->bindParam(":content", $postContent);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveLike()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("update comments set likes = 1 where post_id = {$_GET["id"]} and user_id = {$_GET["user"]}");
        //$postContent = $this->getPostContent();
        //$statement->bindParam(":content", $postContent);

        $result = $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveTest()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into test (post_id, user_id, content) values ({$_GET["id"]}, {$_GET["user"]}, {$_GET["content"]})");
        //$postContent = $this->getPostContent();
        //$statement->bindParam(":content", $postContent);

        $result = $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveDislike()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("update comments set dislikes = 1 where post_id = {$_GET["id"]} and user_id = {$_GET["user"]}");
        $postContent = $this->getPostContent();
        //$statement->bindParam(":content", $postContent);

        $result = $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getLeuk()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("select * from likes where user_id1 = ");
        $postContent = $this->getPostContent();
        $statement->bindParam(":content", $postContent);

        $result = $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveLeukComment()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into likes(user_id1, comment_id) values ({$_SESSION["id"]}, :commentsId)");
        $commentsId = $this->getCommentsId();
        $statement->bindParam(":commentsId", $commentsId);

        $result = $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function saveLikes(){

        // @todo: hook in a new function that checks if a user has already liked a post

        $conn = Db::getConnection();
        $statement = $conn->prepare("insert into likes (comment_id, user_id1) values (:commentsId, {$_SESSION["id"]})");
        $statement->bindValue(":commentsId", $this->getCommentId());
        return $statement->execute();
    }

    public function idFromPost()
    {
        //db conn
        $conn = Db::getConnection();
        //insert query
        $statement = $conn->prepare("select posts.content, posts.title, users.firstname, users.lastname from posts inner join users on users.id = posts.user_id where id = {$_GET["id"]}");

        //$postContent = $this->getPostContent();
        //$statement->bindParam(":content", $postContent);

        //return result
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getComments()
    {
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM comments AS comment INNER JOIN users AS user ON user.id = comment.user_id WHERE comment.post_id = {$_GET["id"]} ORDER BY comment.likes DESC");
        //$statement->bindParam(":postID", $postID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getTeamID()
    {
        return $this->teamID;
    }

    public function setTeamID($teamID)
    {
        $this->teamID = $teamID;

        return $this;
    }

    public function getStudentID()
    {
        return $this->studentID;
    }

    public function setStudentID($studentID)
    {
        $this->teastudentIDmID = $studentID;

        return $this;
    }

    /**
     * Get the value of postContent
     */
    public function getPostContent()
    {
        return $this->postContent;
    }

    /**
     * Set the value of postContent
     */
    public function setPostContent($postContent): self
    {
        $this->postContent = $postContent;

        return $this;
    }

    /**
     * Get the value of commentContent
     */
    public function getCommentContent()
    {
        return $this->commentContent;
    }

    /**
     * Set the value of commentContent
     */
    public function setCommentContent($commentContent): self
    {
        $this->commentContent = $commentContent;

        return $this;
    }

    /**
     * Get the value of postID
     */
    public function getPostID()
    {
        return $this->postID;
    }

    /**
     * Set the value of postID
     */
    public function setPostID($postID): self
    {
        $this->postID = $postID;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of commentsId
     */ 
    public function getCommentsId()
    {
        return $this->commentsId;
    }

    /**
     * Set the value of commentsId
     *
     * @return  self
     */ 
    public function setCommentsId($commentsId)
    {
        $this->commentsId = $commentsId;

        return $this;
    }
}
