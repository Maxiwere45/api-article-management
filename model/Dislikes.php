<?php

namespace model;

class Dislikes {
    private $article_id;
    private $user_id;

    public function __construct($article, $user) {
        $this->article_id = $article;
        $this->user_id = $user;
    }

    public function getArticleId() {
        return $this->article_id;
    }

    public function getUserId() {
        return $this->user_id;
    }
}
