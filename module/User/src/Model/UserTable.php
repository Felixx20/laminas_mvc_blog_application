<?php

namespace User\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class UserTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
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
}