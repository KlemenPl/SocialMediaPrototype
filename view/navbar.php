<?php

$listClass = User::isLoggedIn() ? "" : "disabled";

$url = $_SERVER['REQUEST_URI'];

$on_popular = str_ends_with($url, "/popular") ? "active" : "";
$on_search = str_ends_with($url, "/search") ? "active" : "";
$on_feed = str_ends_with($url, "/feed") ? "active" : "";
$on_profile = str_contains($url, "index.php/profile") ? "active" : "";
$on_post = str_ends_with($url, "/post") ? "active" : "";
$on_messages = str_ends_with($url, "/messages") ? "active" : "";

?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
      <div class="container-fluid">
        <a class="navbar-brand" href="popular">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link <?= $on_popular ?>" href="popular">Popular</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $on_search ?>" aria-current="page" href="search">Search</a>
            </li>        
            <li class="nav-item">
              <a class="nav-link <?= $on_feed ?><?=$listClass?>" href="feed">Feed</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $on_profile ?><?=$listClass?>" href="profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $on_post ?> <?=$listClass?>" href="post">Post</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $on_messages ?> <?=$listClass?>" href="messages">Messages</a>
            </li>
          </ul>
          <?php if (User::isLoggedIn()): ?>
            <a href="logout">
              <button class="btn btn-danger" type="submit">Logout (<?= User::getUsername() ?>)</button>
            </a>
          <?php else: ?>
            <a href="login">
              <button class="btn btn-light" type="submit">Login</button>
            </a>
            <a href="signup">
              <button class="btn btn-primary" type="submit">Register</button>
            </a>
          <?php endif; ?>
   
        </div>
    </div>
</nav>