<?php

namespace User\Controller;

use User\Model\UserTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\UserLoginForm;
use User\Model\User;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;


class LoginController extends AbstractActionController
{

    private $table;


    public function __construct(UserTable $table)
    {
        $this->table = $table;
    }


    public function loginAction()
    {

        echo ("<script>console.log('PHP');</script>");

        $form = new UserLoginForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();


        if (!$request->isPost()) {
            return ['form' => $form];
        }

        echo ("<script>console.log('PHP2');</script>");


        $user = new User();
        $form->setInputFilter($user->getInputFilter());
        $form->setData($request->getPost());


        if (!$form->isValid()) {
            return ['form' => $form];
        }
        $user->exchangeArray($form->getData());





        $passwordValidation = function ($hash, $password) {
            return password_verify($password, $hash);
        };



        // Create a SQLite database connection
        $dbAdapter = new DbAdapter([
            'driver'   => 'Pdo',
            'dsn' => 'mysql:dbname=blog;host=Localhost',
            'username' => 'root',
            'password' => 'daten1'
        ]);

        $authAdapter = new AuthAdapter($dbAdapter);

        $authAdapter
            ->setTableName('user')
            ->setIdentityColumn('username')
            ->setCredentialColumn('password');

        $authAdapter
            ->setCredentialValidationCallback($passwordValidation);

        $authAdapter
            ->setIdentity($user->username)
            ->setCredential($user->password);

        // Perform the authentication query, saving the result
        $result = $authAdapter->authenticate();


        // Print the identity:
        echo $result->getIdentity() . "\n\n";

        // Print the result row:
        print_r($authAdapter->getResultRowObject());


        $identity = $result->getIdentity();
        $identityrow = $authAdapter->getResultRowObject();

        if ($result->isValid()) {
            return $this->redirect()->toRoute('blog');
        } else {
            return $this->redirect()->toRoute('login');
        }
    }
}