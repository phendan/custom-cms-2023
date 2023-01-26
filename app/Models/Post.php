<?php

namespace App\Models;

use App\Models;
use App\Helpers\Str;

class Post {
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function create($userId, $title, $body)
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
    }
}
