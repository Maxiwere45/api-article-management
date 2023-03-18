<?php

namespace model\dao\requests;

use model\Article;
use model\dao\Database;

class ModeratorRequest
{
    private $linkpdo;

    public function __construct()
    {
        $this->linkpdo = Database::getInstance('root', "9wms351v")->getConnection();
    }

    public function getNbModerators(): int
    {
        $sql = "SELECT COUNT(*) FROM users WHERE role = 'moderator'";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }

    public function getNbPublishers(): int
    {
        $sql = "SELECT COUNT(*) FROM users WHERE role = 'publisher'";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }

    public function getNbUsers(): int
    {
        $sql = "SELECT COUNT(*) FROM users";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data[0];
    }

    public function getNbLikesFromArticle(Article $article): int
    {
        $sql = "SELECT COUNT(*) FROM likes WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':id' => $article->getId()));
        $data = $stmt->fetch();
        return $data[0];
    }

    public function getNbDislikesFromArticle(Article $article): int
    {
        $sql = "SELECT COUNT(*) FROM dislikes WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':id' => $article->getId()));
        $data = $stmt->fetch();
        return $data[0];
    }

}