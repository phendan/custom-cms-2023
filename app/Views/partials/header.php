<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/app.css">
    <script src="/js/main.js" defer></script>
    <title>Forum App</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <?php if ($user->isLoggedIn()): ?>
                    <li><a href="/post/create">Create Post</a></li>
                    <li><a href="/logout">Sign Out</a></li>
                <?php else: ?>
                    <li><a href="/register">Register</a></li>
                    <li><a href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="messages">
        <?php echo $session::flash('success'); ?>
    </div>

    <div class="messages error">
        <?php echo $session::flash('error'); ?>
    </div>
