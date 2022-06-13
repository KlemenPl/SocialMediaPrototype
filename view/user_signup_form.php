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
        <form action="<?= BASE_URL . "signup" ?>" method="post">
            <h1 class="h3 mb-3 fw-normal">Sign up</h1>

            <div class="form-floating">
                <input type="text" class="form-control" name="display_name" id="display_name" placeholder="Display name" required minlength="3" maxlength="255">
                <label for="display_name">Display name</label>
                <?php if (!empty($displayErr)): ?>
                    <span class="inputError"><?= $displayErr ?></p>
                <?php endif; ?>
                <span class="inputError"></span>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="floatingInput" placeholder="Username" required minlength="3" maxlength="64">
                <label for="floatingInput">Username</label>
                <?php if (!empty($usernameErr)): ?>
                    <span class="inputError"><?= $usernameErr ?></p>
                <?php endif; ?>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password"  required minlength="8" maxlength="64">
                <label for="floatingPassword">Password</label>
                <?php if (!empty($passwordErr)): ?>
                    <span class="inputError"><?= $passwordErr ?></p>
                <?php endif; ?>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
        </form>
        <br/>
        <p> Already a member? <a href="<?=BASE_URL . "login" ?>">Login</a></p>
    </main>

    <?php include("view/footer.php"); ?>

</body>

</html>