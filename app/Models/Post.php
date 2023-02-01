<?php

namespace App\Models;

use App\Models;
use App\Helpers\Str;
use App\Models\FileStorage;

class Post {
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create(int $userId, string $title, string $body, array $image = null)
    {
        $sql = "
            INSERT INTO `posts`
            (`user_id`, `title`, `slug`, `body`, `created_at`)
            VALUES (:userId, :title, :slug, :body, :createdAt)
        ";

        $slug = Str::slug($title);

        $this->db->query($sql, [
            'userId' => $userId,
            'title' => $title,
            'slug' => $slug,
            'body' => $body,
            'createdAt' => time()
        ]);

        // If there is an image, save it

        if ($image === null) return;

        $fileStorage = new FileStorage($image);
        $fileStorage->saveIn('images');
        $imageName = $fileStorage->getGeneratedName();

        $sql = "SELECT MAX(`id`) AS 'id' FROM `posts` WHERE `user_id` = :user_id";

        $postQuery = $this->db->query($sql, ['user_id' => $userId ]);
        $postId = $postQuery->results()[0]['id'];

        $sql = "
            INSERT INTO `posts_images`
            (`post_id`, `path`)
            VALUES (:post_id, :path)
        ";

        $this->db->query($sql, [
            'post_id' => $postId,
            'path' => $imageName
        ]);
    }
}
