<?php
namespace model\dao\requests;
use model\dao\Database;
use model\User;
use PDO;

class UserRequest {
    private $linkpdo = null;

    public function __construct(){
        $this->linkpdo = Database::getInstance('root', "9wms351v")->getConnection();
    }
    public function getUser(User $user){
        $sql = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':username' => $user->getLogin(),
                                ':password' => $user->getPassword()));
        (array) $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data){
            die("ERROR 400 : Données introuvable !");
        }
        return $data;
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertUser(User $user): bool{
        $sql = "INSERT INTO users(username,password,role)
            VALUES(:username,:password,:role)";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':username' => $user->getLogin(),
            ':password' => $user->getPassword(),
            ':role' => $user->getRole()
        ));
    }

    public function deleteUser(User $user): bool {
        // Suppression des articles de l'utilisateur
        $sql = "DELETE FROM article WHERE author = :author";
        $stmt = $this->linkpdo->prepare($sql);
        $res2 = $stmt->execute(array(':author' => $user->getLogin()));

        // Suppression des likes de l'utilisateur
        $sql = "DELETE FROM likes WHERE id_username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        $res3 = $stmt->execute(array(':username' => $user->getLogin()));

        // Suppression des dislikes de l'utilisateur
        $sql = "DELETE FROM dislikes WHERE id_username = :username";
        $stmt = $this->linkpdo->prepare($sql);
        $res4 = $stmt->execute(array(':username' => $user->getLogin()));

        // Suppression d'un utilisateur
        $sql = "DELETE FROM users WHERE username = :username AND password = :password";
        $stmt = $this->linkpdo->prepare($sql);
        $res1 = $stmt->execute(array(':username' => $user->getLogin(),
                                    ':password' => $user->getPassword()));

        // Vrai si toutes les requêtes ont été exécutées
        return $res1 && $res2 && $res3 && $res4;
    }
}
