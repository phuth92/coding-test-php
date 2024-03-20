<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Like;
use App\Model\Table\LikesTable;
use Authorization\IdentityInterface;

/**
 * Like policy
 */
class LikePolicy
{
    /**
     * Check if $user can add Like
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Like $like
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Like $like)
    {
        return true;
    }

    /**
     * Check if $user can edit Like
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Like $like
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Like $like)
    {
        return true;
    }

    /**
     * Check if $user can delete Like
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Like $like
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Like $like)
    {
        return true;
    }

    /**
     * Check if $user can view Like
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Like $like
     * @return bool
     */
    public function canView(IdentityInterface $user, Like $like)
    {
        return true;
    }

    public function canLikeCount(IdentityInterface $user, LikesTable $likesTable)
    {
        return true;
    }

    public function canLike(IdentityInterface $user, LikesTable $likesTable)
    {
        return true;
    }
}
