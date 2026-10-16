<?php


use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;

class NavBarTest extends AbstractWebTestCase
{

    #[TestWith(['EP'])]
    #[TestWith(['Singles'])]
    #[TestWith(['Albums'])]
    public function testExistsDropdown(string $expected): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $found = false;
        $crawlerTitle = $crawler->filter('h2');
        foreach ($crawlerTitle as $title) {
            /** @var DOMElement $title */
            if ($title->textContent === $expected) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }



    // créer isTestOk = true;
    // créer variable temporaire ; $tmpElement = $genres[0]
    // boucler sur les éléments (Genre) as element
    // si element !== $tmpElement, alors
    // si element supérieur à tmpElement
    // mettre à jour tmpElement avec la valeur de element
    // sinon
    // erreur : isTestOk = false
    // finsi
    // finboucle

}
