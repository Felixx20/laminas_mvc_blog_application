<?php

namespace User\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use User\Model\User;
use Laminas\Db\Adapter\Adapter as DbAdapter;
use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session as SessionStorage;


class UserTable
{
    private $tableGateway;
    public $auth;




    public function __construct(TableGatewayInterface $tableGateway, AuthenticationService $auth)
    {
        $this->tableGateway = $tableGateway;
        $this->auth = $auth;
    }

    public function getUser($userID)
    {
        $userID = (int) $userID;
        $rowset = $this->tableGateway->select(['id' => $userID]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $userID
            ));
        }


        return $row;
    }

    public function checkUsername($user)
    {
        $rowset =  $this->tableGateway->select(['username' => $user->username]);
        $row = $rowset->current();
        if (!$row) {
            return true;
        } else {
            echo "Username already exists";
            return false;
        }
    }





    public function saveUser(User $user)
    {


        $hash = password_hash($user->password, PASSWORD_DEFAULT);

        $data = [
            'username' => $user->username,
            'password'  => $hash,
        ];

        $userID = (int) $user->userID;

        if ($userID === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getUser($userID);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $userID
            ));
        }


        $this->tableGateway->update($data, ['id' => $userID]);
    }

    public function authenticate(User $user)
    {

        $passwordValidation = function ($hash, $password) {
            return password_verify($password, $hash);
        };


        $dbAdapter = new DbAdapter([
            'driver'   => 'Pdo',
            'dsn' => 'mysql:dbname=blog;host=Localhost',
            'username' => 'your_username',
            'password' => 'your_password'
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


        $this->auth = new AuthenticationService();

        $this->auth->setStorage(new SessionStorage('UserSession'));


        // Perform the authentication query, saving the result
        $result = $this->auth->authenticate($authAdapter);

        $this->identity = $result->getIdentity();

        return $result;
    }

    public function getAuth()
    {
        return $this->auth;
    }
}