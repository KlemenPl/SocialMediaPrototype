<?php

require_once("ViewHelper.php");
require_once("model/PostDB.php");

class Controller {
    public static function popular() {
        ViewHelper::render("view/main.php", [
            "type" => "popular"
        ]);
    }
    public static function feed() {
        ViewHelper::render("view/main.php", [
            "type" => "feed"
        ]);
    }

    public static function searchForm() {
        ViewHelper::render("view/search.php");
    }
    public static function searchAPI() {
        $hits = [];
        if (isset($_GET["query"]) && isset($_GET["type"]) &&
            !empty($_GET["query"]) && 
            ($_GET["type"] == "people" || $_GET["type"] == "posts")) {

            
            if ($_GET["type"] == "people") {
                $hits = UserDB::searchUsers($_GET["query"]);
            } else {
                $hits = UserDB::searchPosts($_GET["query"]);
            }
        }

        header("Content-type: application/json; charset=utf-8");
        echo json_encode($hits);
    }
    public static function loadFeedAPI() {
        $hits = [];

        if (isset($_GET["type"]) &&
            ($_GET["type"] == "popular" || $_GET["type"] == "feed")) {

            $lastID = -1;
            if (isset($_GET["last_id"])) {
                $lastID = intval($_GET["last_id"]);
            }
            $type = $_GET["type"];

            if ($lastID == -1 && $type == "popular") {
                $hits = PostDB::getPopularPosts();
            } else if ($type == "popular") {
                $hits = PostDB::getPopularPostsFrom($lastID);
            } else if ($lastID == -1 && $type == "feed") {
                $hits = PostDB::getFeedPosts();
            } else {
                $hits = PostDB::getFeedPostsFrom($lastID);
            }
        }

        header("Content-type: application/json; charset=utf-8");
        echo json_encode($hits);        
    }

    public static function profile() {
        if (isset($_GET["id"])) {
            ViewHelper::render("view/profile.php", [
                "userID" => $_GET["id"]
            ]);
        } else if (User::isLoggedIn()) {
            ViewHelper::render("view/profile.php", [
                "userID" => User::getUserID()
            ]);
        } else {
            popular();
        }
    }

    public static function followUser($id) {
        $curID = User::getUserID();
        UserDB::addFollow($curID, intval($id));
    }
    public static function unfollowUser($id) {
        $curID = User::getUserID();
        UserDB::removeFollow($curID, intval($id));
    }

    public static function post() {
        $rules = [
            "title" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
            "content" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
        ];

        $data = filter_input_array(INPUT_POST, $rules);
        
        $titleErr = empty($data["title"]) || strlen($data["title"]) < 3 ? "Title must be at least 3 characters long" : "";
        if (strlen($titleErr) == 0) $titleErr = !empty($data["display_name"]) && strlen($data["display_name"]) >= 255 ? "Title is too long." : "";

        $contentErr = empty($data["content"]) || strlen($data["content"]) < 3 ? "Content must be at least 3 characters long" : "";
        if (strlen($contentErr == 0)) $usernameErr = !empty($data["username"]) && strlen($data["username"]) >= 4096 ? "Content is too long." : "";
        
        if (!(empty($titleErr) || empty($contentErr))) {
            ViewHelper::render("view/post.php", [
                "titleErr" => $titleErr,
                "contentErr" => $contentErr
            ]);
            return;
        }

        PostDB::createPost($data["title"], $data["content"]);

        ViewHelper::render("view/post_success.php");
    }
    public static function postForm() {
        ViewHelper::render("view/post.php");
    }

    public static function editPost() {
        $rules = [
            "title" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
            "content" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
            "id" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS]
        ];

        $data = filter_input_array(INPUT_POST, $rules);
        
        $titleErr = empty($data["title"]) || strlen($data["title"]) < 3 ? "Title must be at least 3 characters long" : "";
        if (strlen($titleErr) == 0) $titleErr = !empty($data["display_name"]) && strlen($data["display_name"]) >= 255 ? "Title is too long." : "";

        $contentErr = empty($data["content"]) || strlen($data["content"]) < 3 ? "Content must be at least 3 characters long" : "";
        if (strlen($contentErr == 0)) $usernameErr = !empty($data["username"]) && strlen($data["username"]) >= 4096 ? "Content is too long." : "";
        
        if (!(empty($titleErr) || empty($contentErr))) {
            ViewHelper::render("view/edit_post_form.php", [
                "titleErr" => $titleErr,
                "contentErr" => $contentErr,
                "title" => $data["title"],
                "content" => $data["content"],
                "id" => $data["id"],
            ]);
            return;
        }

        PostDB::updatePost($data["id"], $data["title"], $data["content"]);
    }
    public static function editPostForm($id) {
        $postData = PostDB::getPost($id);
        ViewHelper::render("view/edit_post_form.php", [
            "title" => $postData["title"],
            "content" => $postData["content"],
            "id" => $postData["id"],
        ]);

    }

    public static function deletePost($id) {
        PostDB::deletePost($id);
    }

    public static function messages() {
        ViewHelper::render("view/messages.php");
    }
    
}

?>