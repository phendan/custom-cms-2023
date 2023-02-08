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

<h2>Submit Your Comment</h2>
<form method="post" action="/comment/create/?postId=<?=$post->getId()?>">
    <div><textarea name="body"></textarea></div>

    <input type="submit" value="Submit Comment">
</form>

<?php foreach ($post->getComments() as $comment): ?>
    <h3>Comment by: <?=$comment->getUser()->getUsername()?></h3>
    <h4>Comment written: <?=$comment->getCreatedAt()?></h4>
    <p><?=$comment->getBody()?></p>
<?php endforeach; ?>
