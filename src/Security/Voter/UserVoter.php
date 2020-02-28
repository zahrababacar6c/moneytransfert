<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['POST_EDIT', 'POST_VIEW'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        //dd($user->getRoles()[0]);
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if($user->getRoles()[0] === "ROLE_ADMIN_SYSTEM")
        {
            //dd($user);
            return true;
           

        }
//dd($subject);
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'POST_EDIT':
                return $user->getRoles()[0] === "ROLE_ADMIN" && ($subject->getProfil()->getLibelle() === "Caissier"
                 || $subject->getProfil()->getLibelle() ==="Partenaire");
                // logic to determine if the user can EDIT
                 return true ;
                break;
            case 'BLOCAGE':
                return $user->getRoles()[0] === "ROLE_ADMIN" && ($subject->getProfil()->getLibelle() === "Caissier"
                 || $subject->getProfil()->getLibelle() ==="Partenaire");
                // logic to determine if the user can VIEW
                 return true ;
                break;
        }

        return false;
    }
}
