<h1>Dashboard</h1>

Hallo, <?=$user->getUsername()?>

<h2>Your Profile Details</h2>
<dl>
    <dt>Email</dt>
    <dd><?=$user->getEmail()?></dd>
    <dt>Username</dt>
    <dd><?=$user->getUsername()?></dd>
</dl>

<div>
    <h2>Your Posts</h2>

    <?php if (!count($posts)): ?>
        You don't currently have any posts.
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>
        <div>
            <a href="/post/<?=$post->getId()?>/<?=$post->getSlug()?>">
                <?php echo $post->getTitle(); ?>
            </a>
            <a href="/post/edit/<?=$post->getId()?>/<?=$post->getSlug()?>">Edit Post</a>
            <a href="/post/delete/<?=$post->getId()?>?csrfToken=<?=$csrfToken?>">Delete Post</a>
        </div>
    <?php endforeach; ?>
</div>
