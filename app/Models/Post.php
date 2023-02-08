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
    private string $updatedAt;
    private string $userId;
    private array $images;
    private array $comments;

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
            (`user_id`, `title`, `slug`, `body`, `created_at`, `updated_at`)
            VALUES (:userId, :title, :slug, :body, :createdAt, :updatedAt)
        ";

        $slug = Str::slug($title);

        $this->db->query($sql, [
            'userId' => $userId,
            'title' => $title,
            'slug' => $slug,
            'body' => $body,
            'createdAt' => time(),
            'updatedAt' => time()
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

    public function edit(string $title, string $body): bool
    {
        $sql = "
            UPDATE `posts`
            SET `title` = :title, `slug` = :slug, `body` = :body, `updated_at` = :updatedAt
            WHERE `id` = :id
        ";

        $slug = Str::slug($title);

        $postData = [
            'id' => $this->getId(),
            'title' => $title,
            'slug' => $slug,
            'body' => $body,
            'updatedAt' => time()
        ];

        $editQuery = $this->db->query($sql, $postData);
        $this->fill($postData);

        return (bool) $editQuery->count();
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

    public function addComment(int $userId, string $body)
    {
        $comment = new Comment($this->db);
        $comment->create($this->getId(), $userId, $body);
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
        if (isset($this->images)) {
            return $this->images;
        }

        $sql = "SELECT `path` FROM `posts_images` WHERE `post_id` = :postId";
        $imagesQuery = $this->db->query($sql, [ 'postId' => $this->getId() ]);

        $this->images = array_map(function ($image) {
            return DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $image['path'];
        }, $imagesQuery->results());

        return $this->images;
    }

    public function getComments(): array
    {
        if (isset($this->comments)) {
            return $this->comments;
        }

        $sql = "SELECT * FROM `post_comments` WHERE `post_id` = :postId";
        $commentsQuery = $this->db->query($sql, [ 'postId' => $this->getId() ]);

        $this->comments = array_map(function($commentData) {
            $comment = new Comment($this->db);
            $comment->fill($commentData);
            return $comment;
        }, $commentsQuery->results());

        return $this->comments;
    }
}
