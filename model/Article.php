<?php

namespace model;
/**
 * Une classe représentant un article
 *
 * Cette classe permet de créer des objets articles
 * pour les requetes sur la base de données
 */

class Article{
    private $id;
    private $content;
    private $date_add;
    private $author;

    public function __construct($id, $content, $date_add, $author) {
        $this->id = $id;
        $this->content = $content;
        $this->date_add = $date_add;
        $this->author = $author;
    }

    public function getId() {
        return $this->id;
    }

    public function getContent() {
        return $this->content;
    }

    public function getDate_add() {
        return $this->date_add;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function toArray(): array {
        return array(
            'id' => $this->id,
            'content' => $this->content,
            'date_add' => $this->date_add,
            'author' => $this->author
        );
    }
}
