<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\BaseModel;

class UserModel extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $email, string $password, string $role): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO users (email, password_hash, role) VALUES (:email, :hash, :role) RETURNING id'
        );
        $stmt->execute(['email' => $email, 'hash' => $hash, 'role' => $role]);
        return (int)$stmt->fetchColumn();
    }

    public function all(): array
    {
        return $this->db->query('SELECT id, email, role, is_active, created_at FROM users ORDER BY id')
            ->fetchAll();
    }

    public function setActive(int $id, bool $active): void
    {
        $stmt = $this->db->prepare('UPDATE users SET is_active = :a WHERE id = :id');
        $stmt->execute(['a' => $active ? 'true' : 'false', 'id' => $id]);
    }
}
