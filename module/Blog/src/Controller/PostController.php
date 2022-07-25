<?php

namespace Blog\Controller;

use Blog\Model\PostTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Blog\Form\PostForm;
use Blog\Model\Post;
use User\Model\UserTable;

class PostController extends AbstractActionController

{

    private $table;
    private $usertable;





    public function __construct(PostTable $table, UserTable $usertable)
    {
        $this->table = $table;
        $this->usertable = $usertable;
    }
    public function indexAction()
    {
        return new ViewModel([
            'posts' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {

        /*
        if (!$this->usertable->auth->hasIdentity()) {

            return $this->redirect()->toRoute('login');
        }
        */

        $form = new PostForm();
        $form->get('submit')->setValue('Create a new Blog Post');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }


        $post = new Post();
        $form->setInputFilter($post->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }


        $post->exchangeArray($form->getData());
        $this->table->savePost($post);
        return $this->redirect()->toRoute('blog');
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}