<?php

namespace App\DataFixtures;

use App\Factory\AlbumFactory;
use App\Factory\ArtistFactory;
use App\Factory\FavoriteFactory;
use App\Factory\GenreFactory;
use App\Factory\HistoryFactory;
use App\Factory\PlaylistFactory;
use App\Factory\TrackFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $genres = ["Pop", "Jazz", "Rock", "City Pop", "Synthwave", "Chillhop", "Rap", "Classical", "Electro", "Math Rock", "Blues"];
        foreach ($genres as $genre) {
            GenreFactory::createOne(["name" => $genre]);
        }

        UserFactory::createMany(2000);
        $artists = ["Woodkid", "Danger", "Nujabes", "Totorro", "Tatsuro Yamashita", "Joe Hisaishi", "Bershy", "Shubh Saran"];
        foreach ($artists as $artist) {
            ArtistFactory::createOne(['stageName' => $artist]);
        }
        ArtistFactory::createMany(30);
        $albums = ["S16", "Haven", "Modal Soul", "Sofa So Good", "Ride On Time", "The Essentials", "Radio", "Inglish"];
        $imgs = ["1.jpg", "2.jpg", "3.jpg", "4.jpg", "5.jpg", "6.jpg", "7.jpg", "8.jpg", "9.jpg", "10.jpg", "11.jpg", "12.jpg", "13.jpg", "14.jpg", "15.jpg", "16.jpg", "17.jpg", "18.jpg", "19.jpg", "20.jpg", "21.jpg", "22.jpg", "23.jpg", "24.jpg"];
        for ($i = 0; $i < count($albums); $i++) {
            AlbumFactory::createOne([
                "title" => $albums[$i],
                "artist" => ArtistFactory::findOrCreate(['stageName' => $artists[$i]]),
                'poster' => "uploads/" . $imgs[$i]
            ]);
        }
        AlbumFactory::createMany(100);
        $tracks = ["Horizons Into Battlegrounds", "08:16 Home", "Feather", "New Music", "Ride On Time", "Summer", "Radio", "Postradition"];
        for ($i = 0; $i < count($tracks); $i++) {
            TrackFactory::createOne([
                'title' => $tracks[$i],
                'album' => AlbumFactory::findOrCreate(['title' => $albums[$i]])
            ]);
        }
        TrackFactory::createMany(100);
        PlaylistFactory::createMany(50);
        FavoriteFactory::createMany(50);
        HistoryFactory::createMany(500);
        $manager->flush();
    }
}
