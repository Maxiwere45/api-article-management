<?php

namespace model\dao\requests;

use model\Article;
use model\dao\Database;

/*
 * Cette classe contient toutes les requêtes SQL pour les modérateurs
 */
class ModeratorRequest
{
    private $linkpdo;

    public function __construct()
    {
        $this->linkpdo = Database::getInstance('root', "9wms351v")->getConnection();
    }

    /*
     * Cette fonction retourne le nombre de modérateurs
     * @return int
     */
    public function getNbModerators(): int
    {
        $sql = "SELECT COUNT(*) FROM users WHERE role = 'moderator'";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }

    /*
     * Cette fonction retourne le nombre de rédacteurs
     * @return int
     */
    public function getNbPublishers(): int
    {
        $sql = "SELECT COUNT(*) FROM users WHERE role = 'publisher'";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }

    /*
     * Cette fonction retourne le nombre d'utilisateurs
     * @return int
     */
    public function getNbUsers(): int
    {
        $sql = "SELECT COUNT(*) FROM users";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }

    /*
     * Cette fonction retourne le nombre de likes d'un article
     * @return int
     */
    public function getNbLikesFromArticle(Article $article): int
    {
        $sql = "SELECT COUNT(*) FROM likes WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':id' => $article->getId()));
        $data = $stmt->fetch();
        return $data[0];
    }


    /*
     * Cette fonction retourne le nombre de dislikes d'un article
     * @return int
     */
    public function getNbDislikesFromArticle(Article $article): int
    {
        $sql = "SELECT COUNT(*) FROM dislikes WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':id' => $article->getId()));
        $data = $stmt->fetch();
        return $data[0];
    }

}