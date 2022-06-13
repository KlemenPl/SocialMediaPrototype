<!DOCTYPE html>
<html>

<head>
    <?php include("view/head_include.php"); ?>
    <meta charset="UTF-8" />
    <title>Main </title>

    <script>
        function removeActiveTabs() {
            document.getElementById("posts").classList.remove("show");
            document.getElementById("posts").classList.remove("active");

            document.getElementById("followers").classList.remove("show");
            document.getElementById("followers").classList.remove("active");

            document.getElementById("following").classList.remove("show");
            document.getElementById("following").classList.remove("active");
        }
        function showPosts() {
            removeActiveTabs();

            document.getElementById("posts").classList.add("show");
            document.getElementById("posts").classList.add("active");
        }
        function showFollowers() {
            removeActiveTabs();

            document.getElementById("followers").classList.add("show");
            document.getElementById("followers").classList.add("active");
        }
        function showFollowing() {
            removeActiveTabs();

            document.getElementById("following").classList.add("show");
            document.getElementById("following").classList.add("active");
        }
    </script>

</head>

<?php

$user = UserDB::getUserFromID($userID);

$posts = [];
$followers = [];
$following = [];

if ($user) {
    $posts = UserDB::getUserPosts($userID);
    $followerIDs = UserDB::getUserFollowers($userID);
    $followingIDs = UserDB::getUserFollowing($userID);

    foreach ($followerIDs as $id) {
        array_push($followers, UserDB::getUserFromID($id["from_id"]));
    }

    foreach ($followingIDs as $id) {
        array_push($following, UserDB::getUserFromID($id["to_id"]));
    }
}

?>

<body>
    <?php include("view/navbar.php"); ?>
    <div class="container">
        <div class="pb-3 mb-0 lh-sm border-bottom w-100">
            <div class="d-flex justify-content-between">
                <strong class="text-gray-dark"><?=$user["display_name"]?></strong>
                <?php if (User::isLoggedIn() && User::getUserID() == $userID): ?>
                    <a href="edit_profile">
                        Edit profile
                    </a>
                <?php elseif (User::isLoggedIn() && UserDB::isFollowing(User::getUserID(), $userID)): ?>
                    <a href="unfollow?id=<?=$userID?>">
                        Unfollow
                    </a>
                <?php elseif (User::isLoggedIn()): ?>
                    <a href="follow?id=<?=$userID?>">
                        Follow
                    </a>
                <?php endif; ?>

            </div>
            <span class="d-block">@<?=$user["username"]?></span>
        </div>
        <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" onclick="showPosts();" id="posts-tab" data-bs-toggle="tab" type="button" role="tab" aria-controls="home" aria-selected="true">
                    Posts(<?=count($posts)?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="followers-tab" onclick="showFollowers();" data-bs-toggle="tab" type="button" role="tab" aria-controls="profile" aria-selected="false">
                    Followers (<?=count($followers)?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="following-tab"  onclick="showFollowing();" data-bs-toggle="tab" type="button" role="tab" aria-controls="contact" aria-selected="false">
                    Following (<?=count($following)?>)
                </button>
            </li>
        </ul>
        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                    <?php foreach($posts as $post):?>
                        <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                            <div class="d-flex justify-content-between">
                                <strong class="text-gray-dark"><?=$post["title"]?></strong>
                                <?php if (User::isLoggedIn() && User::getUserID() == $userID): ?>
                                    <a href="edit_post?id=<?=$post["id"]?>">Edit</a>
                                <?php endif; ?>
                            </div>
                            <br/>
                            <span class="d-block"><?=$post["content"]?></span>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="tab-pane fade" id="followers" role="tabpanel" aria-labelledby="followers-tab">
                    <?php foreach($followers as $follower):?>
                        <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                            <div class="d-flex justify-content-between">
                                <strong class="text-gray-dark"><?=$follower["display_name"]?></strong>
                                <a href="profile?id=<?=$follower["id"]?>" >User profile</a>
                            </div>
                            <span class="d-block"><?='@' . $follower["username"]?></span>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="tab-pane fade" id="following" role="tabpanel" aria-labelledby="following-tab">
                <?php foreach($following as $following):?>
                        <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                            <div class="d-flex justify-content-between">
                                <strong class="text-gray-dark"><?=$following["display_name"]?></strong>
                                <a href="profile?id=<?=$following["id"]?>" >User profile</a>
                            </div>
                            <span class="d-block"><?='@' . $following["username"]?></span>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>

    <?php include("view/footer.php"); ?>

</body>

</html>