<!DOCTYPE html>
<html>

<head>
    <?php include("view/head_include.php"); ?>
    <meta charset="UTF-8" />
    <title>Main </title>


</head>

<body>
    <?php include("view/navbar.php"); ?>
    <div class="col-lg-6 mx-auto">
        <form class="row g-3" action="<?= BASE_URL . "edit_post" ?>" method="post">
            <div class="input-group input-group-lg">
                <span class="input-group-text" id="inputGroup-sizing-default">Title</span>
                <input type="text" class="form-control" name="title" autofocus required minlength="4" maxlength="255" value="<?=$content?>">
            </div>
            <?php if (!empty($titleErr)): ?>
                <p class="inputError"><?= $titleErr ?></p>
            <?php endif; ?>
            <div class="input-group">
                <textarea class="form-control" placeholder="Post..." name="content" required minlength="4" maxlength="4096"><?=$content?></textarea>
            </div>
            <?php if (!empty($contentErr)): ?>
                <p class="inputError"><?= $contentErr ?></p>
            <?php endif; ?>
            <input type="hidden" name="id" value="<?=$id?>">
            <button class="btn btn-primary" type="submit">Update</button>
            <a class="btn btn-danger" href="delete_post?id=<?=$id?>" onclick="return confirm('Are you sure?');">Delete</a>
        </form>
    </div>

    <?php include("view/footer.php"); ?>

</body>

</html>