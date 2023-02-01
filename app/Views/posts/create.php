<h1>Write Your Post</h1>

<form method="post" enctype="multipart/form-data">
    <?php if (isset($errors['root'])): ?>
        <div class="error"><?=$errors['root']?></div>
    <?php endif; ?>

    <div>
        <label for="title">Title</label>

        <?php if (isset($errors['title'])): ?>
            <div class="error"><?=$errors['title'][0]?></div>
        <?php endif; ?>

        <input type="text" id="title" name="title">
    </div>

    <div>
        <label for="body">Body</label>

        <?php if (isset($errors['body'])): ?>
            <div class="error"><?=$errors['body'][0]?></div>
        <?php endif; ?>

        <textarea name="body" id="body"></textarea>
    </div>

    <div>
        <label for="image">Image</label>

        <?php if (isset($errors['image'])): ?>
            <div class="error"><?=$errors['image'][0]?></div>
        <?php endif; ?>

        <input type="file" id="image" name="image">
    </div>

    <input type="submit" value="Create Post">
</form>
