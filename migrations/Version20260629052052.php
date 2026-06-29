<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260629052052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow up to 3 animals per person: unique on (person_name, animal_name) instead of person_name';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vote AS SELECT id, person_name, animal_name, score FROM vote');
        $this->addSql('DROP TABLE vote');
        $this->addSql('CREATE TABLE vote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, person_name VARCHAR(255) NOT NULL, animal_name VARCHAR(255) NOT NULL, score SMALLINT NOT NULL)');
        $this->addSql('INSERT INTO vote (id, person_name, animal_name, score) SELECT id, person_name, animal_name, score FROM __temp__vote');
        $this->addSql('DROP TABLE __temp__vote');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A108564D4C5FA2D190E3F32 ON vote (person_name, animal_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vote AS SELECT id, person_name, animal_name, score FROM vote');
        $this->addSql('DROP TABLE vote');
        $this->addSql('CREATE TABLE vote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, person_name VARCHAR(255) NOT NULL, animal_name VARCHAR(255) NOT NULL, score SMALLINT NOT NULL)');
        $this->addSql('INSERT INTO vote (id, person_name, animal_name, score) SELECT id, person_name, animal_name, score FROM __temp__vote');
        $this->addSql('DROP TABLE __temp__vote');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A108564D4C5FA2D ON vote (person_name)');
    }
}
