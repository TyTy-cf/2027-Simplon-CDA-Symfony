<?php

namespace App\Tests\Security;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AuthTest extends KernelTestCase
{
    /*DROITS ADMIN POUR L'ADMIN */

    public function testAdminHasIsRole(): void
    {
        $this->assertTrue($this->isGrantedAs('admin@admin.fr', 'ROLE_ADMIN'));
    }

    /* UN UTILISATEUR LAMBDA N'A PAS LES DROITS ADMIN */

    public function testSimpleUserDontHaveAdminRole(): void
    {
        $this->assertFalse($this->isGrantedAs('kelly.cassin@example.com', 'ROLE_ADMIN'));
    }

    private function isGrantedAs(string $email, string $role): bool
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $container->get(UserRepository::class)->findOneBy(['email' => $email]);
        $container->get(TokenStorageInterface::class)->setToken(
            new UsernamePasswordToken($user, 'main', $user->getRoles())
        );

        return $container->get(AuthorizationCheckerInterface::class)->isGranted($role);
    }

}