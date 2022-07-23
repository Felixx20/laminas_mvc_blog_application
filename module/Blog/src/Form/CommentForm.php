<?php

namespace Blog\Form;

use Laminas\Form\Form;

class CommentForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('form');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);



        $this->add([
            'name' => 'authorID',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'postID',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'text',
            'type' => 'text',
            'options' => [
                'label' => 'Comment',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Post comment',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}