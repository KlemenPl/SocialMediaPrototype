<?php

class User {
	public static function login($user) {
		$_SESSION["user"] = $user;
        //var_dump($_SESSION["user"]);
	}

	public static function logout() {
		session_destroy();
	}

	public static function isLoggedIn() {
		return isset($_SESSION["user"]);
	}

	public static function getUserID() {
		return $_SESSION["user"]["id"];
	}
	public static function getUsername() {
		return $_SESSION["user"]["username"];
	}
    public static function getDisplayName() {
        return $_SESSION["user"]["display_name"];
    }
}