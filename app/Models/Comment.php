<?php

namespace App\Models;

use App\Models\Database;
use App\Helpers\Str;

class Comment {
    private string $id;
    private string $userId;
    private string $postId;
    private string $body;
    private string $createdAt;
    private string $updatedAt;

    private User $user;
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function fill(array $data = []) {
        foreach ($data as $field => $value) {
            $this->{Str::toCamelCase($field)} = $value;
        }
    }

    public function find(int $identifier): bool
    {
        $sql = "SELECT * FROM `post_comments` WHERE `id` = :identifier";
        $commentQuery = $this->db->query($sql, ['identifier' => $identifier]);

        if (!$commentQuery->count()) {
            return false;
        }

        $this->fill($commentQuery->results()[0]);
        return true;
    }

    public function create(int $postId, int $userId, string $body)
    {
        $sql = "
            INSERT INTO `post_comments`
            (`post_id`, `user_id`, `body`, `created_at`, `updated_at`)
            VALUES (:postId, :userId, :body, :createdAt, :updatedAt)
        ";

        $commentQuery = $this->db->query($sql, [
            'postId' => $postId,
            'userId' => $userId,
            'body' => $body,
            'createdAt' => time(),
            'updatedAt' => time()
        ]);
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getCreatedAt(): string
    {
        return date('D, d.m.Y H:i:s', $this->createdAt);
    }

    public function getUserId(): int
    {
        return (int) $this->userId;
    }

    public function getUser(): User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        $this->user = new User($this->db);
        $this->user->find($this->getUserId());

        return $this->user;
    }
}
