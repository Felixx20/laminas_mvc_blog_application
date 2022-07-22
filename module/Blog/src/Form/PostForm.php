<?php

namespace Blog\Form;

use Laminas\Form\Form;

class PostForm extends Form
{
    public function __construct($name = null)
    {

        parent::__construct('form');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
        $this->add([
            'name' => 'text',
            'type' => 'textarea',
            'options' => [
                'label' => 'Post Text',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Create a new blog post',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}