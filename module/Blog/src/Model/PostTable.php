<?php

namespace Blog\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class PostTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getPost($postID)
    {
        $postID = (int) $postID;
        $rowset = $this->tableGateway->select(['postID' => $postID]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $postID
            ));
        }

        return $row;
    }

    public function savePost(Post $post)
    {
        $data = [
            'title'  =>  $post->title,
            'text' =>  $post->text,
        ];

        $postID = (int)  $post->postID;

        if ($postID === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getPost($postID);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update blog with identifier %d; does not exist',
                $postID
            ));
        }

        $this->tableGateway->update($data, ['postID' => $postID]);
    }

    public function deletePost($postID)
    {
        $this->tableGateway->delete(['postID' => (int) $postID]);
    }
}