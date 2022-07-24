<?php

namespace User\Controller;

use User\Model\UserTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\UserLoginForm;
use User\Model\User;


class RegisterController extends AbstractActionController
{

    private $table;


    public function __construct(UserTable $table)
    {
        $this->table = $table;
    }


    public function indexAction()
    {
        $form = new UserLoginForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $user = new User();
        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $user->exchangeArray($form->getData());
        $this->table->saveUser($user);
        return $this->redirect()->toRoute('blog');
    }
}