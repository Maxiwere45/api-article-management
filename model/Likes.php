<?php
namespace model;

class Likes {
    private $user_id;
    private $article_id;

    public function __construct($user, $article) {
        $this->user_id = $user;
        $this->article_id = $article;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getArticleId() {
        return $this->article_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setArticleId($article_id) {
        $this->article_id = $article_id;
    }
}
