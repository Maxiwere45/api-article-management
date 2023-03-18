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
    /*
        * Construit un objet User
        * 
        * @param string $login Le login de l'utilisateur
        * @param string $password Le mot de passe de l'utilisateur
        * @param string $role Le role de l'utilisateur
        */

    public function __construct($login, $password, $role)
    /*  Une classe représentant un utilisateur
     * 
     *  Cette classe permet de créer des objets utilisateurs
     *  pour les requetes sur la base de données
     */
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

}
