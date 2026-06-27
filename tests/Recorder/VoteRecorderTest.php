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

    public function testSecondVoteFromSamePersonReplacesTheFirst(): void
    {
        $this->recorder->record($this->makeVote('Jean', 'Chat', 10));
        $this->recorder->record($this->makeVote('  JEAN ', 'Chien', 90));
        $all = $this->votes->findAll();

        self::assertCount(1, $all, 'One person = one entry');
        self::assertSame('chien', $all[0]->getAnimalName());
        self::assertSame(90, $all[0]->getScore());
    }

    public function testNamesAreStoredNormalised(): void
    {
        $this->recorder->record($this->makeVote('  Jean  ', '  PanThèRe  ', 50));
        $vote = $this->votes->findOneByPersonName('jean');

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
