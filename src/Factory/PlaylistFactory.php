<?php

namespace App\Factory;

use App\Entity\Playlist;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Playlist>
 */
final class PlaylistFactory extends PersistentObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]
    public static function class(): string
    {
        return Playlist::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        $tracks = [];
        for ($i = 0; $i < rand(4, 40); ++$i) {
            array_push($tracks, TrackFactory::random());
        }

        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'isPublic' => self::faker()->boolean(),
            'description' => self::faker()->paragraph(),
            'name' => self::faker()->word(),
            'user' => UserFactory::random(),
            'tracks' => $tracks,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Playlist $playlist): void {})
        ;
    }
}
