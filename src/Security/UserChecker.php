<?php

namespace App\Security;

use App\Entity\Abstract\AUser;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AUser) {
            return;
        }

        if (!$user->getIsVerified()) {
            throw new HttpException(401, 'Invalid credentials');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AUser) {
            return;
        }
    }
}