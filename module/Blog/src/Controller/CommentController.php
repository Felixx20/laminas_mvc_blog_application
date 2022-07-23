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

        echo ("<script>console.log(1  );</script>");
        $form = new CommentForm();
        $form->get('submit')->setValue('Create a new Comment');

        $request = $this->getRequest();
        echo ("<script>console.log(2  );</script>");


        if (!$request->isPost()) {

            return ['form' => $form];
        }
        echo ("<script>console.log(2.2 );</script>");

        $comment = new Comment();
        $comment->authorID = 1;
        $comment->postID = 1;
        $form->setInputFilter($comment->getInputFilter());
        $form->setData($request->getPost());
        echo ("<script>console.log(3 );</script>");

        $string = $comment->authorID;
        if (!$form->isValid()) {
            echo ("<script>console.log($string  );</script>");

            return ['form' => $form];
        }

        echo ("<script>console.log(4  );</script>");
        $comment->exchangeArray($form->getData());
        $this->table->saveComment($comment);
        return $this->redirect()->toRoute('blog');
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}