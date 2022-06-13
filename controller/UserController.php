<?php

require_once("ViewHelper.php");
require_once("model/UserDB.php");
require_once("model/User.php");

class UserController {


    public static function login() {
        $rules = [
            "username" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
            "password" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS]
        ];

        $data = filter_input_array(INPUT_POST, $rules);
        $user = UserDB::getUser($data["username"], $data["password"]);

        $loginErr =  empty($data["username"]) || empty($data["password"]) || $user == null ? "Invalid username or password." : "";
        if (!(empty($loginErr))) {
            ViewHelper::render("view/user_login_form.php", [
                "loginErr" => $loginErr,
            ]);
            return;
        }

        User::login($user);
        ViewHelper::redirect(BASE_URL);

    }
    public static function loginForm() {
        ViewHelper::render("view/user_login_form.php");
    }

    public static function editProfile() {
        $rules = [
            "display_name" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
        ];

        $data = filter_input_array(INPUT_POST, $rules);

        $editErr = empty($data["display_name"]) || strlen($data["display_name"]) < 3 ? "Display name must be at least 3 characters long" : "";
        if (strlen($editErr) == 0) $editErr = !empty($data["display_name"]) && strlen($data["display_name"]) >= 255 ? "Display name to long." : "";

        if (!(empty($editErr))) {
            ViewHelper::render("view/edit_profile_form.php", [
                "editErr" => $editErr,
            ]);
            return;
        }

        UserDB::update($data["display_name"]);
        ViewHelper::redirect(BASE_URL);

    }
    public static function editProfileForm() {
        ViewHelper::render("view/edit_profile_form.php");
    }

    public static function signup() {
        $rules = [
            "display_name" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
            "username" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS],
            "password" => ["filter" => FILTER_SANITIZE_SPECIAL_CHARS]
        ];

        $data = filter_input_array(INPUT_POST, $rules);
        //var_dump($data);

        $displayErr = empty($data["display_name"]) || strlen($data["display_name"]) < 3 ? "Display name must be at least 3 characters long" : "";
        if (strlen($displayErr) == 0) $displayErr = !empty($data["display_name"]) && strlen($data["display_name"]) >= 255 ? "Display name to long." : "";

        $usernameErr = empty($data["username"]) || strlen($data["username"]) < 3 ? "Username must be at least 3 characters long" : "";
        if (strlen($usernameErr) == 0) $usernameErr = !empty($data["username"]) && strlen($data["username"]) >= 64 ? "Username name to long." : "";
        if (strlen($usernameErr) == 0) $usernameErr = (UserDB::userExists($data["username"]) == true) ? "Username is already taken" : "";
        //var_dump(UserDB::userExists($data["username"]) == true);
        //var_dump((UserDB::userExists($data["username"]) == true) ? "Username is already taken" : "");

        $passwordErr = empty($data["password"]) || strlen($data["password"]) < 8 ? "Password must be at least 8 characters long" : "";
        if (strlen($passwordErr) == 0) $passwordErr = !empty($data["password"]) && strlen($data["password"]) >= 64 ? "Pasword too long." : "";

        //echo($displayErr);
        //echo($usernameErr);
        //echo($passwordErr);
        if (!empty($displayErr) || !empty($usernameErr) || !empty($passwordErr)) {
            ViewHelper::render("view/user_signup_form.php", [
                "displayErr" => $displayErr,
                "usernameErr" => $usernameErr,
                "passwordErr" => $passwordErr
            ]);
            return;
        }

        $user = UserDB::createUser($data["display_name"], $data["username"], $data["password"]);
        User::login($user);
        //var_dump($user);
        //var_dump(User::isLoggedIn());
        ViewHelper::redirect(BASE_URL);
    }
    public static function signUpForm() {
        ViewHelper::render("view/user_signup_form.php");
    }

    public static function logout() {
        User::logout();

        ViewHelper::redirect(BASE_URL);
    }

}