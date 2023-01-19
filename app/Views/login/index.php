<h1>Login</h1>

<?php

if (isset($data['errors'])) {
    foreach ($data['errors'] as $fieldErrors) {
        echo '<div>' . $fieldErrors[0] . '</div>';
    }
}

?>

<form action="index.php?url=login" method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Your Handle" />
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" />
    </div>

    <input type="submit" />
</form>
