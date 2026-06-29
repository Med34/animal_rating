<?php

declare(strict_types=1);

namespace App\Tests\Recorder;

use App\Entity\Vote;
use App\Recorder\VoteRecorder;
use App\Repository\VoteRepository;
use App\Tests\AbstractDatabaseTestCase;

final class VoteRecorderTest extends AbstractDatabaseTestCase
{
    private VoteRecorder $recorder;
    private VoteRepository $votes;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recorder = VoteRecorderTest::getContainer()->get(VoteRecorder::class);
        $this->votes = VoteRecorderTest::getContainer()->get(VoteRepository::class);
    }

    public function testReVotingSameAnimalUpdatesScore(): void
    {
        $this->recorder->record($this->makeVote('Jean', 'Chat', 10));
        $this->recorder->record($this->makeVote('  JEAN ', 'chat', 90));

        $all = $this->votes->findByPersonRanked('jean');
        self::assertCount(1, $all);
        self::assertSame(90, $all[0]->getScore());
    }

    public function testPersonKeepsAtMostThreeAnimals(): void
    {
        $this->recorder->record($this->makeVote('Jean', 'Chat', 80));
        $this->recorder->record($this->makeVote('Jean', 'Chien', 70));
        $this->recorder->record($this->makeVote('Jean', 'Lapin', 60));
        // 4th animal has the lowest score but is kept because entered last,
        // so the weakest of the others (Lapin, 60) is dropped instead.
        $this->recorder->record($this->makeVote('Jean', 'Tortue', 10));

        $animals = array_map(
            static fn ($vote): string => $vote->getAnimalName(),
            $this->votes->findByPersonRanked('jean'),
        );

        self::assertCount(3, $animals);
        self::assertContains('tortue', $animals, 'last entered is mandatory');
        self::assertNotContains('lapin', $animals);
    }

    public function testNamesAreStoredNormalised(): void
    {
        $this->recorder->record($this->makeVote('  Jean  ', '  PanThèRe  ', 50));
        $vote = $this->votes->findOneByPersonAndAnimal('jean', 'panthère');

        self::assertNotNull($vote);
        self::assertSame('panthère', $vote->getAnimalName());
    }

    private function makeVote(string $person, string $animal, int $score): Vote
    {
        return (new Vote())
            ->setPersonName($person)
            ->setAnimalName($animal)
            ->setScore($score);
    }
}
