<?php
namespace model\dao;
use Exception;
use PDO;

include_once '../../libs/encryption.php';

// Singleton de la connexion à la base de données
class Database {
    private static $instance = null;
    private $connection;

    private function __construct($login, $password)
    {
        try {
            ini_set("error_log", "../../logs/journal.log");
            $this->connection = new PDO("mysql:host=localhost;dbname=rest_api;charset=UTF8", $login, decoder($password, "thou"));
            error_log("Connexion à la base de données réussie");
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getInstance($login, $password): ?Database
    {
        if (!self::$instance) {
            self::$instance = new Database($login, $password);
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    public function __destruct()
    {
        $this->connection = null;
    }
}

// Utilisation de la classe Singleton
/*
$login = 'root';
$db = Database::getInstance($login, "9wms351v");
$connection = $db->getConnection();
*/
