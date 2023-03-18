<?php
namespace model;

class User
{
    private $login;
    private $password;
    private $role;

    public function __construct($login, $password, $role)
    {
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function toArray(): array
    {
        return array(
            'login' => $this->login,
            'password' => $this->password,
            'role' => $this->role
        );
    }
}
