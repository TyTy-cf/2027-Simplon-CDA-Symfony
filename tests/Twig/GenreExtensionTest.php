<?php

namespace App\Tests\Twig;

use App\Entity\Genre;
use App\Twig\GenreExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenreExtensionTest extends KernelTestCase
{
    public function testGetGenreOrdered(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $ge = $this->getContainer()->get(GenreExtension::class);
        $list = $ge->getGenres();

        $testIsOk = true;
        /** @var Genre $tempElement */
        $tempElement = $list[0];

        foreach ($list as $element) {
            /** @var Genre $element */
            if ($tempElement->getName() !== $element->getName()) {
                if($element->getName() < $tempElement->getName()) {
                    $testIsOk = false;
                }
                $tempElement = $element;
            }
        }
        $this->assertTrue($testIsOk);
    }
}
