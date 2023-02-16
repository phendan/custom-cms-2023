<h1>Login</h1>

<form method="post" class="login-form">
    <input type="hidden" name="csrfToken" value="<?=$csrfToken?>" id="csrfToken">

    <div>
        <label for="username">Username</label>

        <?php if (isset($errors['username'])): ?>
            <div class="error"><?= $errors['username'][0] ?></div>
        <?php endif; ?>

        <input type="text" name="username" id="username" placeholder="Your Handle" autocomplete="username" />
    </div>
    <div>
        <label for="password">Password</label>

        <?php if (isset($errors['password'])): ?>
            <div class="error"><?= $errors['password'][0] ?></div>
        <?php endif; ?>

        <input type="password" name="password" id="password" autocomplete="current-password" />
    </div>

    <input type="submit" />
</form>
