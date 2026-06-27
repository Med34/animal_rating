<?php

declare(strict_types=1);

namespace App\Recorder;

use App\Entity\Vote;
use App\Repository\VoteRepository;
use App\Services\NameNormalizer;
use Doctrine\ORM\EntityManagerInterface;

readonly class VoteRecorder
{
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

        $target = $this->votes->findOneByPersonName($personName) ?? $submitted;

        $target->setPersonName($personName);
        $target->setAnimalName($animalName);
        $target->setScore($submitted->getScore());

        $this->em->persist($target);
        $this->em->flush();

        return $target;
    }
}
