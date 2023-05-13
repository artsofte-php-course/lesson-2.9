<?php

namespace App\Security\Voter;

use App\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter {

    const CREATE_TASK = 'create_task';

    protected function supports(string $attribute, $subject)
    {
        if (!$subject instanceof Project) {
            return false;
        }

        if (!in_array($attribute, [self::CREATE_TASK])) {
            return false;
        }

        return true;
    } 

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        if ($subject->getUser() === $token->getUser()->getUserIdentifier()) {
            return true;
        }

        if (in_array('ROLE_ADMIN', $token->getUser()->getRoles())) {
            return true;
        }

        return false;
    }

}