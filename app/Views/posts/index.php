<h1><?=$post->getTitle()?></h1>
<p><?=$post->getBody()?></p>
<?php foreach ($post->getImages() as $image): ?>
    <img src="<?= $image ?>">
<?php endforeach; ?>
<div>Posted at: <?=$post->getCreatedAt()?></div>
<div>Posted by: <?=$post->getUser()->getUsername()?></div>

<?php if ($user->isLoggedIn() && ($user->getId() === $post->getUserId())): ?>
    <a href="/post/delete/<?=$post->getId()?>">Delete This Post</a>
<?php endif; ?>
