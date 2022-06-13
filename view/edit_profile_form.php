<!DOCTYPE html>
<html>

<head>
    <?php include("view/head_include.php"); ?>
    <meta charset="UTF-8" />
    <title>Login form </title>


</head>

<body class="d-flex flex-column h-100">
    <?php include("view/navbar.php"); ?>

    <main class="form-signin">
        <?php if (!empty($errorMessage)): ?>
            <p class="important"><?= $errorMessage ?></p>
        <?php endif; ?>
        <form action="<?= BASE_URL . "edit-profile" ?>" method="post">
            <h1 class="h3 mb-3 fw-normal">Edit profile</h1>

            <label for="display_name" class="form-label">Display name</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="display_name" id="display_name" aria-describedby="basic-addon3" autofocus  required minlength="3" maxlength="255" value="<?=User::getDisplayName()?>">
            </div>

            <label for="username" class="form-label">Username</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon3">@</span>
                <input type="text" class="form-control" disabled id="username" aria-describedby="basic-addon3" value="<?=User::getUsername()?>">
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Update</button>
            <?php if (!empty($editErr)): ?>
                    <span class="inputError"><?= $editErr ?></p>
            <?php endif; ?>
        </form>
    </main>

    <?php include("view/footer.php"); ?>

</body>

</html>