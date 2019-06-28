<?php

namespace FreedomSex\AuthBundle\Security\Voters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use FreedomSex\AuthBundle\Security\JWTUser;

class UserUidVoter extends Voter
{

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, ['UUID'])) {
            return false;
        }
        if (!is_numeric($subject)) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof JWTUser) {
            return false;
        }
        if ((INT) $subject === $user->getId()) {
            return true;
        }
        return false;
    }

}
