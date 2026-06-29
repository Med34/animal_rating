<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\AnimalStat;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vote>
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function findOneByPersonAndAnimal(string $personName, string $animalName): ?Vote
    {
        return $this->findOneBy(['personName' => $personName, 'animalName' => $animalName]);
    }

    /**
     * @return Vote[] best score first, most recent in backup
     */
    public function findByPersonRanked(string $personName): array
    {
        return $this->findBy(['personName' => $personName], ['score' => 'DESC', 'id' => 'DESC']);
    }

    /**
     * @return AnimalStat[] most-cited animal first
     */
    public function getAnimalStatistics(): array
    {
        $rows = $this->createQueryBuilder('v')
            ->select('v.animalName AS animalName')
            ->addSelect('COUNT(v.id) AS citations')
            ->addSelect('AVG(v.score) AS avgScore')
            ->groupBy('v.animalName')
            ->orderBy('citations', 'DESC')
            ->addOrderBy('v.animalName', 'ASC')
            ->getQuery()
            ->getResult();

        return array_map(static fn (array $row): AnimalStat => new AnimalStat(
            $row['animalName'],
            (int) $row['citations'],
            (float) $row['avgScore'],
        ), $rows);
    }
}
