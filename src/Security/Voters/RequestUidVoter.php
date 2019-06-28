<?php

namespace FreedomSex\AuthBundle\Security\Voters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\RequestStack;

use FreedomSex\AuthBundle\Security\JWTUser;

class RequestUidVoter extends Voter
{
    private $request_stack;

    public function __construct(RequestStack $request_stack)
    {
        $this->request_stack = $request_stack;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, ['REQUEST_UID'])) {
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

        $request = $this->request_stack->getCurrentRequest();
        $uid = (INT) $request->get('uid');
        if ($request and $uid === $user->getId()) {
            return true;
        }
        return false;
    }

}
