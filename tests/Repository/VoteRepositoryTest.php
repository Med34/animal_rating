<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Vote;
use App\Repository\VoteRepository;
use App\Tests\AbstractDatabaseTestCase;

final class VoteRepositoryTest extends AbstractDatabaseTestCase
{
    private VoteRepository $votes;

    protected function setUp(): void
    {
        parent::setUp();
        $this->votes = static::getContainer()->get(VoteRepository::class);
    }

    public function testStatisticsRankMostCitedFirstWithAverageScore(): void
    {
        // chat: cited 2x (10 and 20 -> average 15), chien: cited 1x (80)
        $this->persist('alice', 'chat', 10);
        $this->persist('bob', 'chat', 20);
        $this->persist('tom', 'chien', 80);

        $stats = $this->votes->getAnimalStatistics();

        self::assertCount(2, $stats);

        self::assertSame('chat', $stats[0]->animalName);
        self::assertSame(2, $stats[0]->citations);
        self::assertSame(15.0, $stats[0]->avgScore);

        self::assertSame('chien', $stats[1]->animalName);
        self::assertSame(1, $stats[1]->citations);
        self::assertSame(80.0, $stats[1]->avgScore);
    }

    private function persist(string $person, string $animal, int $score): void
    {
        $vote = (new Vote())
            ->setPersonName($person)
            ->setAnimalName($animal)
            ->setScore($score);

        $this->em->persist($vote);
        $this->em->flush();
    }
}
