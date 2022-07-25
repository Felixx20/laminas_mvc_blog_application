<?php

namespace User\Controller;

use User\Model\UserTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\UserRegisterForm;
use User\Model\User;


class RegisterController extends AbstractActionController
{

    private $table;


    public function __construct(UserTable $table)
    {
        $this->table = $table;
    }


    public function registerAction()
    {

        $form = new UserRegisterForm();
        $form->get('submit')->setValue('Register');

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


        //check if username is free
        if (!$this->table->checkUsername($user)) {
            return $this->redirect()->toRoute('register');
        } else {
            $this->table->saveUser($user);
            return $this->redirect()->toRoute('login');
        }
    }
}