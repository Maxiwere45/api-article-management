<?php
namespace model;

/*  Une classe représentant un utilisateur
 * 
 *  Cette classe permet de créer des objets utilisateurs
 *  pour les requetes sur la base de données
 */
class User
{
    private $login;
    private $password;
    private $role;

    /**
     * Constructeur de la classe User
     *
     * @param string $login
     * @param string $password
     * @param string $role
     */
    public function __construct(string $login, string $password, string $role)
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
    public function isModerator(): bool {
        return $this->role === 'moderator';
    }

    public function isPublisher(): bool {
        return $this->role === 'publisher';
    }

    public function isMaster(): bool {
        return $this->login === 'maxiwere' || $this->login === 'iutprof';
    }

}
