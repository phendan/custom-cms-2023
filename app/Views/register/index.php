<h1>Register</h1>

<?php

if (isset($errors)) {
    foreach ($errors as $fieldErrors) {
        echo '<div>' . $fieldErrors[0] . '</div>';
    }
}

?>

<form action="index.php?url=register" method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" name="username" id="username" placeholder="Your Handle" />
    </div>
    <div>
        <label for="email">E-Mail</label>
        <input type="text" name="email" id="email" placeholder="you@somewhere.com" />
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" />
    </div>
    <div>
        <label for="password-again">Repeat Password</label>
        <input type="password" name="passwordAgain" id="password-again" />
    </div>

    <input type="submit" />
</form>
