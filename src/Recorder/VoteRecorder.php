<?php

declare(strict_types=1);

namespace App\Recorder;

use App\Entity\Vote;
use App\Repository\VoteRepository;
use App\Services\NameNormalizer;
use Doctrine\ORM\EntityManagerInterface;

readonly class VoteRecorder
{
    private const MAX_ANIMALS = 3;

    public function __construct(
        private NameNormalizer $normalizer,
        private VoteRepository $votes,
        private EntityManagerInterface $em,
    ) {
    }

    public function record(Vote $submitted): Vote
    {
        $personName = $this->normalizer->normalize($submitted->getPersonName());
        $animalName = $this->normalizer->normalize($submitted->getAnimalName());

        // Re-voting the same animal just updates its score.
        $target = $this->votes->findOneByPersonAndAnimal($personName, $animalName) ?? $submitted;
        $target->setPersonName($personName);
        $target->setAnimalName($animalName);
        $target->setScore($submitted->getScore());

        $this->em->persist($target);
        $this->em->flush();

        $this->pruneToMax($personName, $target);

        return $target;
    }

    /** Keep at most 3 animals: the last one entered (mandatory) plus the best scores. */
    private function pruneToMax(string $personName, Vote $mandatory): void
    {
        $others = array_filter(
            $this->votes->findByPersonRanked($personName),
            static fn (Vote $vote): bool => $vote !== $mandatory,
        );

        foreach (\array_slice($others, self::MAX_ANIMALS - 1) as $vote) {
            $this->em->remove($vote);
        }

        $this->em->flush();
    }
}
