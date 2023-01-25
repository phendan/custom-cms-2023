<h1>Login</h1>

<form action="index.php?url=login" method="post">
    <div>
        <label for="username">Username</label>

        <?php if (isset($data['errors']['username'])): ?>
            <div class="error"><?= $data['errors']['username'][0] ?></div>
        <?php endif; ?>

        <input type="text" name="username" id="username" placeholder="Your Handle" />
    </div>
    <div>
        <label for="password">Password</label>

        <?php if (isset($data['errors']['password'])): ?>
            <div class="error"><?= $data['errors']['password'][0] ?></div>
        <?php endif; ?>

        <input type="password" name="password" id="password" />
    </div>

    <input type="submit" />
</form>
