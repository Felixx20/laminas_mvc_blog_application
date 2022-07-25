<?php

namespace User\Controller;

use User\Model\UserTable;

use User\Form\UserLoginForm;
use User\Model\User;
use Laminas\Mvc\Controller\AbstractActionController;


class LoginController extends AbstractActionController
{

    private $table;

    public function __construct(UserTable $table)
    {
        $this->table = $table;
    }


    public function loginAction()
    {

        $form = new UserLoginForm();
        $form->get('submit')->setValue('Login');

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

        $this->table->authenticate($user);
        //check if user is valid
        if ($this->table->getAuth()->hasIdentity()) {
            return $this->redirect()->toRoute('blog');
        } else {
            return $this->redirect()->toRoute('login');
        }
    }
}