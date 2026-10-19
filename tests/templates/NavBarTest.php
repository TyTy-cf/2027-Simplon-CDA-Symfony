<?php

// Attention : il manque probablement le namespace.
// Si le fichier est dans tests/Controller/, il devrait être :
// namespace App\Tests\Controller;
use App\Tests\AbstractWebTestCase;
use PHPUnit\Framework\Attributes\TestWith;


class NavBarTest extends AbstractWebTestCase
{
    /**
     * Vérifie qu’un titre de section est bien présent dans la navbar
     * ou dans la page d’accueil.
     *
     * #[TestWith] est un data provider “inline” : PHPUnit exécute la méthode
     * une fois par valeur fournie. Chaque jeu de données est rapporté
     * comme un test distinct.
     */
    #[TestWith(['EP'])]
    #[TestWith(['Singles'])]
    #[TestWith(['Albums'])]
    public function testExistsTitle(string $expected): void
    {
        // Crée un client HTTP de test Symfony.
        $client = static::createClient();

        // Demande la page d’accueil.
        // $crawler représente le DOM de la réponse HTML.
        $crawler = $client->request('GET', '/');

        // Indicateur : le titre attendu a-t-il été trouvé ?
        $found = false;

        // Sélectionne tous les éléments <h2> de la page.
        // filter() utilise un sélecteur CSS et retourne un nouveau Crawler. [19]
        $crawlerTitle = $crawler->filter('h2');

        // Parcourt les noeuds DOM trouvés.
        foreach ($crawlerTitle as $title) {
            /** @var DOMElement $title */

            // Compare le texte brut du <h2> au titre attendu.
            // Exemple : <h2>Albums</h2> => "Albums".
            if ($title->textContent === $expected) {
                $found = true;
                break; // Inutile de continuer : on a trouvé le titre.
            }
        }

        // Échoue si aucun <h2> ne contient exactement le texte attendu.
        $this->assertTrue($found);
    }

    public function testExistsDropdown() : void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Cherche dans le crawler (DOM) un élément avec la classe '.navbar-nav'
        $crawlerGenres = $crawler->filter('.navbar-nav li');

        $numberTotalGenres = $crawlerGenres->count();
        $this->assertGreaterThan(0, $numberTotalGenres, "Aucun genre disponible.");
    }

    public function testGenresAreSortedAlphabetically() : void {

        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Cherche dans le crawler (DOM) les enfants de type <li> d'un élément avec la classe '.navbar-nav'
        // Il s'agit du dropdown des genres dans la navbar
        $crawlerGenres = $crawler->filter('.navbar-nav li');

        // Test : si le nb total de genres ($crawlerGenres ->count()) est supérieur à 0.
        // S'il n'est pas suéprieur à 0, c'est qu'aucun genre n'a été trouvé DONC que la liste du dropdown est vide.
        $numberTotalGenres = $crawlerGenres->count();
        $this->assertGreaterThan(0, $numberTotalGenres);

        // Transforme chaque noeud DOM en chaîne de caractères :
        // - $node->text() récupère le texte visible ;
        // - trim() supprime les espaces, tabulations et retours à la ligne.
        //
        // Exemple : "\n      Rock\n  " devient "Rock".
        $genres = $crawlerGenres->each(
            fn ($node) => trim($node->text())
        );

        //vérifier que chaque élément est supérieur ou égal au précédent.
        $isSorted = true;
        $previousGenre = $genres[0];
        
        foreach ($genres as $genre) {
            if ($genre !== $previousGenre) {
                if ($genre > $previousGenre) {
                    // Ordre croissant respecté : on met à jour la référence.
                    $previousGenre = $genre;
                } else {
                    // Un élément est plus petit que le précédent : mauvais ordre.
                    $isSorted = false;
                    break;
                }
            }
        }

        $this->assertTrue($isSorted);

        // $this->assertTrue(
        //     $isSorted,
        //     sprintf(
        //         'Les genres ne sont pas triés par ordre alphabétique. Ordre reçu : %s',
        //         implode(', ', $genres)
        //     )
        // );
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
