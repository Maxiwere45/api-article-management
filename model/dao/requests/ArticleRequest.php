<?php
namespace model\dao\requests;

use model\Article;
use model\dao\Database;
use model\User;
use PDO;


class ArticleRequest{
    private $linkpdo;
    public function __construct()
    {
        $this->linkpdo = Database::getInstance('root', "9wms351v")->getConnection();
    }

    public function getMyOwnArticles(User $user): array
    {
        $sql = "SELECT * FROM article WHERE author = :author";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':author' => $user->getLogin()));
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$data) {
            die("ERROR 400 : Articles introuvable !");
        }
        $articles = array();
        foreach ($data as $article) {
            $articles[] = new Article($article['article_id'], $article['content'], $article['date'], $article['author']);
        }
        return $articles;
    }

    public function getArticle(Article $article, User $user): Article
    {
        if ($user->getRole() == "moderator" || $user->getRole() == "publisher") {
            $sql = "SELECT * FROM article WHERE article_id = :id";
        } else {
            $sql = "SELECT author, content, date_de_publication FROM article";
        }

        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(':id' => $article->getId()));
        (array) $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            die("ERROR 400 : Article introuvable !");
        }
        return new Article($data['article_id'], $data['title'], $data['content'], $data['author'], $data['date']);
    }

    public function getAllArticles(User $user): array
    {
        if ($user->getRole() == "moderator" || $user->getRole() == "publisher") {
            $sql = "SELECT * FROM article";
        } else {
            $sql = "SELECT author, content, date_de_publication FROM article";
        }
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $articles = array();
        foreach ($data as $article) {
            $articles[] = new Article($article['article_id'], $article['title'], $article['content'], $article['author'], $article['date']);
        }
        return $articles;
    }

    public function insertArticle(Article $article): bool
    {
        $sql = "INSERT INTO article(article_id,content,date_de_publication,author)
            VALUES(:article_id,:content,:date_publish,:author)";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':content' => $article->getContent(),
            ':author' => $article->getAuthor(),
            ':date' => $article->getDate_add()
        ));
    }

    public function deleteArticle(Article $article, User $user): bool
    {
        if ($user->getRole() != "moderator" && $user->getRole() != "publisher") {
            die("ERROR 403 : Vous n'avez pas les droits pour supprimer cet article !");
        }
        if ($article->getAuthor() != $user->getLogin()) {
            die("ERROR 403 : Vous n'avez pas les droits pour supprimer cet article !");
        }
        // Suppression des likes de l'article
        $sql = "DELETE FROM likes WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $res1 = $stmt->execute(array(':id' => $article->getId()));

        // Suppression des dislikes de l'article
        $sql = "DELETE FROM dislikes WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $res2 = $stmt->execute(array(':id' => $article->getId()));

        // Suppression de l'article
        $sql = "DELETE FROM article WHERE article_id = :id";
        $stmt = $this->linkpdo->prepare($sql);
        $res3 = $stmt->execute(array(':id' => $article->getId()));

        // Retourne true si les 3 requêtes ont été exécutées
        return $res1 && $res2 && $res3;
    }

}
