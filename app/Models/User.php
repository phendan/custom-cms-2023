<?php

namespace App\Models;

use App\Models\Database;
use Exception;
use App\Helpers\Session;
use App\Traits\Fillable;

class User {
    use Fillable;

    private string $id;
    private string $username;
    private string $email;
    private string $password;
    private string $joinedAt;

    public function __construct(private Database $db)
    {}

    // Returns a boolean indicating if the user could be found
    // If they were, saves their information in object's properties
    public function find(int|string $identifier): bool
    {
        $column = is_int($identifier) ? 'id' : 'username';
        $sql = "SELECT * FROM `users` WHERE `{$column}` = :identifier";
        $userQuery = $this->db->query($sql, [ 'identifier' => $identifier ]);

        if (!$userQuery->count()) {
            return false;
        }

        $userData = $userQuery->results()[0];

        $this->fill($userData);

        return true;
    }

    public function register(string $username, string $email, string $password): void
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT, [ 'cost' => 10 ]);

        $sql = "
            INSERT INTO `users`
            (`username`, `email`, `password`, `joined_at`)
            VALUES (:username, :email, :password, :joinedAt)
        ";

        $this->db->query($sql, [
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash,
            'joinedAt' => time()
        ]);
    }

    public function login(string $username, string $password): void
    {
        // Abgleich mit DB
        // Versuchen User zu finden
        if (!$this->find($username)) {
            throw new Exception('The username could not be found.');
        }

        // Passwörter abgleichen
        if (!password_verify($password, $this->password)) {
            throw new Exception('The password was incorrect.');
        }

        // Session erstellen
        Session::set('userId', (int) $this->id);
    }

    public function logout(): void
    {
        Session::delete('userId');
    }

    public function isLoggedIn(): bool
    {
        return Session::exists('userId');
    }

    public function getId(): int
    {
        if (isset($this->id)) {
            return (int) $this->id;
        }

        return Session::get('userId');
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPosts(): array
    {
        $sql = "SELECT * FROM `posts` WHERE `user_id` = :userId";
        $postsQuery = $this->db->query($sql, [ 'userId' => $this->getId() ]);

        $posts = [];

        foreach ($postsQuery->results() as $result) {
            $posts[] = new Post($this->db, $result);
        }

        return $posts;
    }
}
