<?php

namespace Blog\Model;

class Post
{
    public $postID;
    public $title;
    public $text;

    public function exchangeArray(array $data)
    {
        $this->postID   = !empty($data['postID']) ? $data['postID'] : null;
        $this->title    = !empty($data['title']) ? $data['title'] : null;
        $this->text     = !empty($data['text']) ? $data['text'] : null;
    }
}