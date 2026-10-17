<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractKernelTestCase extends KernelTestCase
{
    protected function getRepository(string $repoClass): ServiceEntityRepository
    {
        return $this->getContainer()->get($repoClass);
    }
}
