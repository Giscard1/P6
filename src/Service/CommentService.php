<?php


namespace App\Service;


use App\Entity\Comment;

class CommentService extends BaseService
{
    public function createNewComment (\App\Entity\Comment $comment)
    {
        $this->persist($comment);
        $this->flush();
    }

}
