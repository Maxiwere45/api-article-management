<?php

namespace model;

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
}
