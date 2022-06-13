<?php

require_once "DBInit.php";

class PostDB {

    public static function createPost($title, $content) {
        if (!User::isLoggedIn()) return false;

        $userID = User::getUserID();

        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("INSERT INTO posts (user_id, title, content)
            VALUES (:user_id, :title, :content)");
        $stmt->bindValue(":user_id", $userID, PDO::PARAM_INT);
        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":content", $content, PDO::PARAM_STR);
        $stmt->execute();

        return true;
    }

    public static function deletePost($id) {
        if (!User::isLoggedIn()) return false;

        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("DELETE from posts 
            WHERE id = :post_id");
        $stmt->bindValue(":post_id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return true;
    }

    public static function getPost($id) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT * FROM posts
            WHERE id = :id");
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return $user;
        } else {
            return false;
        }
    }

    public static function updatePost($id, $title, $content) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("UPDATE posts 
            SET title = :title,
            content = :content
            WHERE id = :id");
        $stmt->bindValue(":title", $title, PDO::PARAM_STR);
        $stmt->bindValue(":content", $content, PDO::PARAM_STR);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

    }

    public static function getPopularPosts() {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT users.id as 'user_id', posts.id as 'post_id', users.username, title, content FROM posts 
            INNER JOIN users ON posts.user_id=users.id
            ORDER BY posted_on DESC
            LIMIT 20");
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public static function getPopularPostsFrom($postID) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT users.id as 'user_id', posts.id as 'post_id', users.username, title, content FROM posts
            INNER JOIN users ON posts.user_id=users.id
            WHERE posts.id < :postID
            ORDER BY posted_on DESC
            LIMIT 20");
        $stmt->bindValue(":postID", $postID, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public static function getFeedPosts() {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT users.id as 'user_id', posts.id as 'post_id', users.username, title, content FROM posts 
            INNER JOIN users ON posts.user_id=users.id
            WHERE users.id IN (
                SELECT to_id
                FROM follows
                WHERE from_id = :userID
            )
            ORDER BY posted_on DESC
            LIMIT 20");
        $stmt->bindValue(":userID", User::getUserID(), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public static function getFeedPostsFrom($postID) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT users.id as 'user_id', posts.id as 'post_id', users.username, title, content FROM posts 
            INNER JOIN users ON posts.user_id=users.id
            WHERE posts.id < :postID AND
            users.id IN (
                SELECT to_id
                FROM follows
                WHERE from_id = :userID
            )
            ORDER BY posted_on DESC
            LIMIT 20");
        $stmt->bindValue(":userID", User::getUserID(), PDO::PARAM_INT);
        $stmt->bindValue(":postID", $postID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}