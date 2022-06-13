<?php

session_start();

require_once("controller/UserController.php");
require_once("controller/Controller.php");

# Define a global constant pointing to the URL of the application
define("BASE_URL", $_SERVER["SCRIPT_NAME"] . "/");
define("IMAGES_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/images/");
define("CSS_URL", rtrim($_SERVER["SCRIPT_NAME"], "index.php") . "static/css/");

# Request path after /index.php/ with leading and trailing slashes removed
$path = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER["PATH_INFO"], "/") : "";

# The mapping of URLs. It is a simple array where:
# - keys represent URLs
# - values represent functions to be called when a client requests that URL
$urls = [
    "login" => function () {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            UserController::login();
        } else {
            UserController::loginForm();
        }
    },
    "signup" => function () {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            UserController::signup();
        } else {
            UserController::signupForm();
        }
    },
    "edit_profile" => function () {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            UserController::editProfile();
        } else {
            UserController::editProfileForm();
        }
    },
    "edit_post" => function () {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            Controller::editPost();
            ViewHelper::redirect(BASE_URL . "profile");
        } else if (isset($_GET["id"])) {
            Controller::editPostForm($_GET["id"]);
        }
    },
    "popular" => function () {
        Controller::popular();
    },
    "search" => function () {
        Controller::searchForm();
    },
    "api/search" => function () {
        Controller::searchAPI();
    },
    "api/load_feed" => function () {
        Controller::loadFeedAPI();
    },
    "follow" => function () {
        if (isset($_GET["id"])) {
            Controller::followUser($_GET["id"]);
        }
        ViewHelper::redirect(BASE_URL . "profile?id=" . $_GET["id"]);
    },
    "unfollow" => function () {
        if (isset($_GET["id"])) {
            Controller::unfollowUser($_GET["id"]);
        }
        ViewHelper::redirect(BASE_URL . "profile?id=" . $_GET["id"]);
    },
    "feed" => function () {
        Controller::feed();
    },
    "profile" => function () {
        Controller::profile();
    },
    "post" => function () {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            Controller::post();
        } else {
            Controller::postForm();
        }
    },
    "delete_post" => function () {
        if (isset($_GET["id"])) {
            Controller::deletePost($_GET["id"]);
        }
        ViewHelper::redirect(BASE_URL . "profile");
    },
    /*
    "test" => function () {
        $num1 = 0;
        $num2 = 1;
      
        $counter = 0;
        while ($counter < 500){
            $title = "Fibonacci numbers";
            $content = "The " . $counter . "th fibonacci number is: " . $num1;

            PostDB::createPost($title, $content);

            $num3 = $num2 + $num1;
            $num1 = $num2;
            $num2 = $num3;
            $counter = $counter + 1;
        }
    },
    */
    "messages" => function () {
        Controller::messages();
    },
    "logout" => function () {
        UserController::logout();
    },
    "" => function () {
        echo(BASE_URL);
        ViewHelper::redirect(BASE_URL . "popular");
    }
];

# The actual router.
# Tries to invoke the function that is mapped for the given path
try {
    if (isset($urls[$path])) {
        # Great, the path is defined in the router
        $urls[$path](); // invokes function that calls the controller
    } else {
        # Fail, the path is not defined. Show an error message.
        echo "No controller for '$path'";
    }
} catch (Exception $e) {
    # Provisional: whenever there is an exception, display some info about it
    # this should be disabled in production
    ViewHelper::error400($e);
} finally {
    exit();
}