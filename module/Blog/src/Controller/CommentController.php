<?php

namespace Blog\Controller;

use Blog\Model\CommentTable;
use Blog\Model\PostTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Blog\Form\CommentForm;
use Blog\Model\Comment;





class CommentController extends AbstractActionController
{

    private $table;


    public function __construct(CommentTable $table, PostTable $postTable)
    {
        $this->table = $table;
        $this->postTable = $postTable;
    }

    public function indexAction()
    {
        $id = (int) $this->params()->fromRoute('id');

        return new ViewModel([

            'post' => $this->postTable->getPost($id),
            'comments' => $this->table->fetchComments($id),

        ]);
    }

    public function addAction()
    {


        $form = new CommentForm();
        $form->get('submit')->setValue('Create a new Comment');

        $request = $this->getRequest();



        if (!$request->isPost()) {

            return ['form' => $form];
        }

        $comment = new Comment();
        $form->setInputFilter($comment->getInputFilter());
        $form->setData($request->getPost());




        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $id = (int) $this->params()->fromRoute('id');
        $comment->exchangeArray($form->getData());
        $comment->postID = (int) $this->params()->fromRoute('id');
        $this->table->saveComment($comment);
        return $this->redirect()->toRoute('comment', ['id' => $id]);
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}