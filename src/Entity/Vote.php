<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VoteRepository;
use App\Validator\NameFormat;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le nom est requis')]
    #[NameFormat]
    private string $personName;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'animal est requis")]
    #[NameFormat]
    private string $animalName;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotNull(message: 'Le score est requis')]
    #[Assert\Type(type: 'integer', message: 'Le score doit être un entier')]
    #[Assert\Range(notInRangeMessage: 'Le score doit être compris entre {{ min }} et {{ max }}.', min: 0, max: 100)]
    private int $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonName(): string
    {
        return $this->personName;
    }

    public function setPersonName(string $personName): static
    {
        $this->personName = $personName;

        return $this;
    }

    public function getAnimalName(): string
    {
        return $this->animalName;
    }

    public function setAnimalName(string $animalName): static
    {
        $this->animalName = $animalName;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }
}
