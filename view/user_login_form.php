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
        <form action="<?= BASE_URL . "login" ?>" method="post">
            <h1 class="h3 mb-3 fw-normal">Please log in</h1>

            <div class="form-floating has-validation">
                <input type="text" class="form-control" name="username" id="username" placeholder="username" required minlength="3" maxlength="64">
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required minlength="8" maxlength="64">
                <label for="password">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
            <?php if (!empty($loginErr)): ?>
                    <span class="inputError"><?= $loginErr ?></p>
            <?php endif; ?>
        </form>
        <div class="invalid-feedback">
            <br/>
            Incorrect username or password.
        </div>
        <br/>
        <p> Not a member? <a href="<?=BASE_URL . "signup" ?>">Sign up</a></p>
    </main>

    <?php include("view/footer.php"); ?>

</body>

</html>