<?php

namespace App\Models;

use App\Models;
use App\Helpers\Str;
use App\Models\Database;
use App\Models\FileStorage;

class Post {
    private Database $db;
    private string $id;
    private string $title;
    private string $slug;
    private string $body;
    private string $createdAt;
    private string $userId;

    public function __construct(Database $db, ?array $data = [])
    {
        $this->db = $db;
        $this->fill($data);
    }

    public function fill(array $data = []) {
        foreach ($data as $field => $value) {
            $this->{Str::toCamelCase($field)} = $value;
        }
    }

    public function find(int $identifier): bool
    {
        $sql = "SELECT * FROM `posts` WHERE `id` = :identifier";
        $postQuery = $this->db->query($sql, ['identifier' => $identifier]);

        if (!$postQuery->count()) {
            return false;
        }

        $this->fill($postQuery->results()[0]);
        return true;
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

    public function delete(): bool
    {
        $images = $this->getImages();

        foreach ($images as $image) {
            FileStorage::delete($image);
        }

        $sql = "DELETE FROM `posts` WHERE `id` = :id";
        $deleteQuery = $this->db->query($sql, [ 'id' => $this->getId() ]);

        return (bool) $deleteQuery->count();
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCreatedAt(): string
    {
        // Thu, 02.02.2023 20:14:23
        return date('D, d.m.Y H:i:s', $this->createdAt);
    }

    public function getUserId(): int
    {
        return (int) $this->userId;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getUser(): User
    {
        $user = new User($this->db);
        $user->find($this->getUserId());
        return $user;
    }

    public function getImages(): array
    {
        $sql = "SELECT `path` FROM `posts_images` WHERE `post_id` = :postId";
        $imagesQuery = $this->db->query($sql, [ 'postId' => $this->getId() ]);

        $images = array_map(function ($image) {
            return DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $image['path'];
        }, $imagesQuery->results());

        return $images;
    }
}
