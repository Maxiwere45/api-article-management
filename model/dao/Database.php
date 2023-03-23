<?php
namespace model\dao;

// Singleton de la connexion à la base de données
use Exception;
use PDO;

/**
 * Class Singleton Database
 * @package model\dao
 */
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct($login, $password)
    {
        try {
            if (php_uname('n') == "MAXIWERE-IUT"){
                $this->connection = new PDO("mysql:host=localhost;dbname=api-article-db;charset=UTF8", $login, $this->decoder($password, "thou"));
            } else {
                $this->connection = new PDO("mysql:host=localhost;dbname=api-articles-db;charset=UTF8", $login, $this->decoder($password, "thou"));
            }
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

    public function decoder($text, $key): string
    {
        $key_len = strlen($key);
        $key_idx = 0;
        $plaintext = "";

        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];

            if (ctype_alpha($char)) {
                $shift = ord(strtolower($key[$key_idx])) - 97;
                $new_char = chr(((ord(strtolower($char)) - 97 - $shift + 26) % 26) + 97);
                $key_idx = ($key_idx + 1) % $key_len;
            } else {
                $new_char = $char;
            }

            $plaintext .= $new_char;
        }
        return $plaintext;
    }

    public function encoder($plaintext, $key): string
    {
        $key_len = strlen($key);
        $key_idx = 0;
        $ciphertext = "";

        for ($i = 0; $i < strlen($plaintext); $i++) {
            $char = $plaintext[$i];

            if (ctype_alpha($char)) {
                $shift = ord(strtolower($key[$key_idx])) - 97;
                $new_char = chr(((ord(strtolower($char)) - 97 + $shift) % 26) + 97);
                $key_idx = ($key_idx + 1) % $key_len;
            } else {
                $new_char = $char;
            }

            $ciphertext .= $new_char;
        }

        return $ciphertext;
    }
}

// Utilisation de la classe Singleton
/*
$login = 'root';
$db = Database::getInstance($login, "9wms351v");
$connection = $db->getConnection();
*/
