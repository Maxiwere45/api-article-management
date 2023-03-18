<?php
namespace model\dao\requests;

use model\dao\Database;
use model\User;
use PDO;

/**
 * Une classe représentant une requete sur les utilisateurs
 *
 * Cette classe permet de faire des requetes sur les utilisateurs
 * dans la base de données
 */
class UserRequest
{
    private $linkpdo;

    public function __construct()
    {
        $this->linkpdo = Database::getInstance('root', "9wms351v")->getConnection();
    }
    public function getUser(string $user): User
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':username' => $user));
        (array) $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            die("ERROR 400 : Données introuvable !");
        }
        return new User($data['username'], $data['password'], $data['role']);
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = array();
        foreach ($data as $user) {
            $users[] = new User($user['username'], $user['password'], $user['role']);
        }
        return $users;
    }

    public function insertUser(User $user): bool
    {
        $sql = "INSERT INTO users(username,password,role)
            VALUES(:username,:password,:role)";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':username' => $user->getLogin(),
            ':password' => $user->getPassword(),
            ':role' => $user->getRole()
        ));
    }

    public function getUserRole($user): string
    {
        $sql = "SELECT role FROM users WHERE username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':username' => $user));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            die("ERROR 400 : Données introuvable !");
        }
        return $data['role'];
    }

    public function deleteUser(string $user): bool
    {
        // Suppression des articles de l'utilisateur
        $sql = "DELETE FROM article WHERE author = :author";
        $stmt = $this->linkpdo->prepare($sql);
        $res2 = $stmt->execute(array(':author' => $user));

        // Suppression des likes de l'utilisateur
        $sql = "DELETE FROM likes WHERE id_username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        $res3 = $stmt->execute(array(':username' => $user));

        // Suppression des dislikes de l'utilisateur
        $sql = "DELETE FROM dislikes WHERE id_username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        $res4 = $stmt->execute(array(':username' => $user));

        // Suppression d'un utilisateur
        $sql = "DELETE FROM users WHERE username = :username AND password = :password";
        $stmt = $this->linkpdo->prepare($sql);
        $res1 = $stmt->execute(array(':username' => $user,
                                    ':password' => $user));

        // Vrai si toutes les requêtes ont été exécutées
        return $res1 && $res2 && $res3 && $res4;
    }

    public function updateUser(array $data): bool
    {
        $sql = "UPDATE users SET";
        if (isset($data['username'])) {
            $sql .= " username = :username,";
        }
        if (isset($data['password'])) {
            $sql .= " password = :password,";
        }
        if (isset($data['role'])) {
            $sql .= " role = :role,";
        }

        // Suppression de la virgule en trop
        $sql = substr($sql, 0, -1);
        $sql .= " WHERE username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':username' => $data['username'],
            ':password' => $data['password'],
            ':role' => $data['role']
        ));
    }
}
