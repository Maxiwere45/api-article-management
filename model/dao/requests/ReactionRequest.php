<?php

namespace model\dao\requests;

use model\Article;
use model\dao\Database;
use model\User;
use PDO;

/**
 * Une classe représentant une requete sur les reactions
 *
 * Cette classe permet de faire des requetes sur les reactions (likes et dislikes)
 * des articles dans la base de données
 */
class ReactionRequest
{
    /**
     * La connexion à la base de données
     *
     * @var PDO
     */
    private $linkpdo;

    /**
     * Construit un objet ReactionRequest en initialisant la connexion à la base de données
     */
    public function __construct()
    {
        $this->linkpdo = Database::getInstance('root', "9wms351v")->getConnection();
    }

    /**
     * Permet de liker un article
     *
     * @param Article $article L'article à liker
     * @param User $user L'utilisateur qui like l'article
     * @return bool true si le like a été effectué, false sinon
     */
    public function likerArticle(Article $article, User $user): bool
    {
        // Verifier si l'utilisateur a deja like ou dislike l'article
        $sql = "SELECT * FROM likes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            die("ERROR 400 : Vous avez deja like cet article !");
        }
        $sql = "SELECT * FROM dislikes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            // Supprimer le dislike
            $this->undislikerArticle($article, $user);
        }

        // Ajouter le like
        $sql = "INSERT INTO likes(article_id,id_username)
            VALUES(:article_id,:id_username)";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
    }

    /**
     * Permet de disliker un article
     *
     * **Attention** :
     * * si l'utilisateur a deja liké l'article, le like sera supprimé
     * * L'auteur de l'article ne peut pas disliker son propre article
     *
     * @param Article $article L'article à disliker
     * @param User $user L'utilisateur qui dislike l'article
     * @return bool true si le dislike a été effectué, false sinon
     */
    public function dislikerArticle(Article $article, User $user): bool
    {
        // Verifier si l'utilisateur a deja like ou dislike l'article
        $sql = "SELECT * FROM dislikes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            die("ERROR 403 : Vous avez deja dislike cet article !");
        }
        $sql = "SELECT * FROM likes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            // Supprimer le like
            $this->unlikerArticle($article, $user);
        }

        // Verifier si l'utilisateur est l'auteur de l'article
        if ($article->getAuthor()->getLogin() == $user->getLogin()) {
            die("ERROR 403 : Vous ne pouvez pas disliker votre propre article !");
        }

        // Ajouter le dislike
        $sql = "INSERT INTO dislikes(article_id,id_username)
            VALUES(:article_id,:id_username)";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
    }

    /**
     * Permet de supprimer un like
     *
     * @param Article $article L'article dont on veut supprimer le like
     * @param User $user L'utilisateur qui a liké l'article
     * @return bool true si le like a été supprimé, false sinon
     */
    public function unlikerArticle(Article $article, User $user): bool
    {
        $sql = "DELETE FROM likes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
    }

    /**
     * Permet de supprimer un dislike
     *
     * @param Article $article L'article dont on veut supprimer le dislike
     * @param User $user L'utilisateur qui a disliké l'article
     * @return bool true si le dislike a été supprimé, false sinon
     */
    public function undislikerArticle(Article $article, User $user): bool
    {
        $sql = "DELETE FROM dislikes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        return $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
    }

    /**
     * Permet de savoir si un utilisateur a deja like un article
     *
     * @param Article $article L'article dont on veut savoir si l'utilisateur l'a deja like
     * @param User $user L'utilisateur dont on veut savoir si il a deja like l'article
     * @return bool true si l'utilisateur a deja like l'article, false sinon
     */
    public function alreadyLiked(Article $article, User $user): bool
    {
        $sql = "SELECT * FROM likes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return false;
        }
        return true;
    }

    /**
     * Permet de savoir si un utilisateur a deja dislike un article
     *
     * @param Article $article L'article dont on veut savoir si l'utilisateur l'a deja dislike
     * @param User $user L'utilisateur dont on veut savoir si il a deja dislike l'article
     * @return bool true si l'utilisateur a deja dislike l'article, false sinon
     */
    public function alreadyDisliked(Article $article, User $user): bool
    {
        $sql = "SELECT * FROM dislikes WHERE article_id = :article_id AND id_username = :id_username";
        $stmt = $this->linkpdo->prepare($sql);
        $stmt->execute(array(
            ':article_id' => $article->getId(),
            ':id_username' => $user->getLogin()
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return false;
        }
        return true;
    }


}