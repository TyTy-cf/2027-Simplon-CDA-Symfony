<?php

// Espace de noms du test.
// Il correspond à l’emplacement : tests/Controller/Admin/AlbumControllerTest.php
namespace App\Tests\Controller\Admin;

// Repository Doctrine : permettra de vérifier que l’album a réellement été persisté.
use App\Repository\AlbumRepository;

// Ta classe de base, qui étend probablement Symfony\Bundle\FrameworkBundle\Test\WebTestCase
// et ajoute des helpers : loginUser(), getRepository(), removeEntity(), URL_ADMIN_ALBUM_NEW…
use App\Tests\AbstractWebTestCase;

// Attribut PHPUnit permettant de fournir plusieurs jeux de données au même test.
use PHPUnit\Framework\Attributes\TestWith;

// Classe Symfony qui simule un fichier envoyé par un navigateur.
use Symfony\Component\HttpFoundation\File\UploadedFile;


class AlbumControllerTest extends AbstractWebTestCase
{
    /**
     * Test d’intégration : création d’un album via le formulaire admin.
     *
     * #[TestWith] exécute ce même test une fois par jeu de données.
     * Ici, il n’y a qu’un cas : une création valide qui doit rediriger (302).
     */
    #[TestWith([
        'PIZZA3000',              // $title : titre de l’album
        '2026-10-16T13:54:55Z',   // $date : date de sortie au format ISO 8601
        '39',                     // $artist : identifiant de l’artiste dans le <select>
        'EP',                     // $type : valeur choisie dans le champ type
        'oskour.jpg',             // $poster : nom de fichier attendu / informationnel
        302                       // $code : code HTTP attendu après soumission
    ])]
    public function testFormSubmit(
        string $title,
        string $date,
        string $artist,
        string $type,
        string $poster,
        int $code
    ): void {
        // Crée un client HTTP de test.
        // Il exécute l’application Symfony en interne, sans serveur web réel.
        $client = static::createClient();

        // Simule une session authentifiée pour l’utilisateur admin.
        // C’est plus rapide et plus fiable que de soumettre le formulaire de login. [2][11]
        $this->loginUser($client, 'admin@admin.fr');

        // Requête GET vers la page admin de création d’album.
        // $crawler permet ensuite de chercher des éléments dans le HTML renvoyé.
        $crawler = $client->request('GET', self::URL_ADMIN_ALBUM_NEW);

        // Simule un fichier image sélectionné dans un input type="file".
        //
        // Attention : le premier argument doit être un chemin absolu ou résolu
        // depuis le répertoire d’exécution des tests. Ici, 'public/uploads/test.jpg'
        // suppose que le fichier existe réellement dans ton projet.
        //
        // Arguments :
        // - chemin du fichier
        // - nom original vu par le formulaire
        // - type MIME
        // - taille annoncée en octets
        $image = new UploadedFile(
            'public/uploads/test.jpg',
            'test.jpg',
            'image/jpeg',
            1949
        );

        // Sélectionne le formulaire grâce à son bouton de soumission "Ajouter".
        $form = $crawler->selectButton("Ajouter")->form();

        // Remplit les champs texte.
        $form['album[title]'] = $title;
        $form['album[releasedAt]'] = $date;

        // Pour un champ ChoiceType / EntityType rendu en <select>,
        // on sélectionne la valeur par son attribut value.
        // Ici, '39' doit donc correspondre à l’ID de l’artiste.
        $form['album[artist]']->select($artist);
        $form['album[type]']->select($type);

        // Associe le fichier simulé au champ upload.
        $form['album[poster]']->upload($image);

        // Soumet le formulaire : Symfony déclenche le contrôleur,
        // la validation, Doctrine, l’upload, etc.
        $client->submit($form);

        // Vérifie que le contrôleur renvoie bien le code HTTP attendu.
        // 302 signifie généralement : formulaire valide puis redirection.
        $this->assertResponseStatusCodeSame($code);

        // Récupère le repository Album pour interroger la base de test.
        $albumRepo = $this->getRepository(AlbumRepository::class);

        // Cherche l’album fraîchement créé à partir de son titre.
        $album = $albumRepo->findOneBy(['title' => $title]);

        // Si le test attend une création réussie :
        // - on pourrait aussi vérifier que $album n’est pas null ;
        // - puis on supprime l’entité pour laisser la base propre.
        if ($code === 302) {
            $this->removeEntity($album);
        }
    }
}