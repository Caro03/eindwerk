<?php

include_once(__DIR__ . "/Db.php");

class Forum
{
    private $teamID;
    private $studentID;
    private $postContent;
    private $commentContent;
    private $postID;

    public function postForum($postContent){
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO posts (user_id, content) VALUES ({$_SESSION["id"]}, :content)");
        $statement->bindParam(":content", $postContent);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getForumPosts(){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT posts.id, left(posts.content,100), users.firstname, users.lastname FROM posts INNER JOIN users ON users.id = posts.user_id ORDER BY posts.id DESC");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getForumPostsId(){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM posts INNER JOIN users ON users.id = posts.user_id where posts.id = {$_GET["id"]}");
        //$statement->bindParam(":postID", $postID);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function postComment($postContent){
        $conn = Db::getConnection();
        $statement = $conn->prepare("INSERT INTO comments (user_id, post_id, content) VALUES ({$_SESSION["id"]}, {$_GET["id"]}, :content)");
        //$statement->bindParam(":postid", $postID);
        $statement->bindParam(":content", $postContent);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function idFromPost()
    {
        //db conn
        $conn = Db::getConnection();
        //insert query
        $statement = $conn->prepare("select posts.content, users.firstname, users.lastname from posts inner join users on users.id = posts.user_id where id = {$_GET["id"]}");

        //$postContent = $this->getPostContent();
        //$statement->bindParam(":content", $postContent);

        //return result
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getComments(){
        $conn = Db::getConnection();
        $statement = $conn->prepare("SELECT * FROM comments AS comment INNER JOIN users AS user ON user.id = comment.user_id WHERE comment.post_id = {$_GET["id"]} ORDER BY comment.id DESC");
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
}
