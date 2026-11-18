<?php

namespace App\Twig;

use App\Repository\GenreRepository;
use Twig\Attribute\AsTwigFunction;

final readonly class GenreExtension
{
    public function __construct(
        private GenreRepository $genreRepository,
    ) {
    }

    #[AsTwigFunction('get_genres')]
    public function getGenres(): array
    {
        return $this->genreRepository->findBy([], ['name' => 'ASC']);
    }
}
