<?php
namespace Models;

class User
{
    private ?int $id;
    private string $email;
    private string $passwordHash;
    private string $role;
    private string $fullName;

    public function __construct(?int $id, string $email, string $passwordHash, string $role, string $fullName)
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->fullName = $fullName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function verifyPassword(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->passwordHash);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
