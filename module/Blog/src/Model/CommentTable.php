<?php

namespace Blog\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class CommentTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchComments($postID)
    {
        return $this->tableGateway->select(['postID' => $postID]);
    }

    public function getComment($commentID)
    {
        $commentID = (int) $commentID;
        $rowset = $this->tableGateway->select(['commentID' => $commentID]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $commentID
            ));
        }

        return $row;
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

    public function saveComment(Comment $comment)
    {

        $data = [
            'text' =>  $comment->text,
            'postID' => $comment->postID,
            'authorID' => $comment->authorID
        ];

        $commentID = (int)  $comment->commentID;

        if ($commentID === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getComment($commentID);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update blog with identifier %d; does not exist',
                $commentID
            ));
        }

        $this->tableGateway->update($data, ['commentID' => $commentID]);
    }

    public function deleteCpmment($commentID)
    {
        $this->tableGateway->delete(['commentID' => (int) $commentID]);
    }
}