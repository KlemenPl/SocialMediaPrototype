<?php

require_once "DBInit.php";

class UserDB {

    public static function userExists($username) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT username FROM users
            WHERE username = :username");
        $stmt->bindValue(":username", $username);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return true;
        }
        return false;
    }

    public static function getUser($username, $password) {
        /* This function is more secure because
            1) It uses prepared statements and it binds variables;
            2) It does not store passwords in plain-text in the database

            For creating passwords, use: http://php.net/manual/en/function.password-hash.php
            For checking passwords, use: http://php.net/manual/en/function.password-verify.php
            For more information, see: http://php.net/manual/en/ref.password.php
        */
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT id, display_name, username, password FROM users 
            WHERE username = :username");
        $stmt->bindValue(":username", $username);
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            if (password_verify($password, $user["password"])) {
                unset($user["password"]);
                return $user;
            } else {
                return false;
            }
        }
    }

    public static function getUserFromID($id) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT id, display_name, username FROM users 
            WHERE id = :id");
        //var_dump($id);
        $stmt->bindValue(":id", intval($id));
        $stmt->execute();

        if ($user = $stmt->fetch()) {
            return $user;
        } else {
            return false;
        }
    }

    public static function isFollowing($fromID, $toID) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT * FROM follows
            WHERE from_id = :fromID AND
                to_id = :toID");
        $stmt->bindValue(":fromID", $fromID);
        $stmt->bindValue(":toID", $toID);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            return true;
        } else {
            return false;
        }
    }

    public static function createUser($displayName, $username, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        //var_dump($hash);
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("INSERT INTO users (display_name, username, password)
            VALUES (:display_name, :username, :password)");
        $stmt->bindValue(":display_name", $displayName, PDO::PARAM_STR);
        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->bindValue(":password", $hash, PDO::PARAM_STR);
        $stmt->execute();
        unset($hash);

        return UserDB::getUser($username, $password);
    }

    public static function update($displayName) {
        $username = User::getUsername();

        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("UPDATE users SET display_name = :display_name
        WHERE username = :username");
        $stmt->bindValue(":display_name", $displayName, PDO::PARAM_STR);
        $stmt->bindValue(":username", $username, PDO::PARAM_STR);
        $stmt->execute();

        $user = $_SESSION["user"];
        $user["display_name"] = $displayName;
        $_SESSION["user"] = $user;
    }

    public static function addFollow($from, $to) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("INSERT INTO follows (from_id, to_id)
            VALUES (:from, :to)");
        $stmt->bindParam(":from", $from, PDO::PARAM_INT);
        $stmt->bindParam(":to", $to, PDO::PARAM_INT);
        $stmt->execute();
    }
    public static function removeFollow($from, $to) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("DELETE from follows 
            WHERE from_id=:from AND to_id=:to");
        $stmt->bindParam(":from", $from, PDO::PARAM_INT);
        $stmt->bindParam(":to", $to, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function getUserPosts($userID) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT id, title, content, posted_on
            FROM posts
            WHERE user_id = :userID");
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getUserFollowers($userID) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT from_id
            FROM follows
            WHERE to_id = :userID");
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getUserFollowing($userID) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare("SELECT to_id
            FROM follows
            WHERE from_id = :userID");
        $stmt->bindParam(":userID", $userID, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function searchPosts($query) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare(
            "SELECT users.id, users.display_name, users.username, posts.title, posts.content
             FROM posts 
             INNER JOIN users ON posts.user_id=users.id
             WHERE posts.title LIKE :query OR
                   posts.content LIKE :query
             ORDER BY posts.posted_on DESC
             LIMIT 50");
        $stmt->bindValue(":query", '%' . $query . '%');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function searchUsers($query) {
        $dbh = DBInit::getInstance();
        $stmt = $dbh->prepare(
            "SELECT id, display_name, username
             FROM users 
             WHERE display_name LIKE :query OR
                   username LIKE :query
            LIMIT 50");
        $stmt->bindValue(":query", '%' . $query . '%');
        $stmt->execute();

        return $stmt->fetchAll();
    }

}