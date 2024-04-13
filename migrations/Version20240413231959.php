<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413231959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__work AS SELECT id, name, description, status, started_at, duration FROM work');
        $this->addSql('DROP TABLE work');
        $this->addSql('CREATE TABLE work (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, occupation_id INTEGER NOT NULL, assigned_to_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, status VARCHAR(50) NOT NULL, started_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , duration INTEGER DEFAULT NULL, completion_level INTEGER DEFAULT NULL, CONSTRAINT FK_534E688022C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_534E6880F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO work (id, name, description, status, started_at, duration) SELECT id, name, description, status, started_at, duration FROM __temp__work');
        $this->addSql('DROP TABLE __temp__work');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_534E68805E237E06 ON work (name)');
        $this->addSql('CREATE INDEX IDX_534E688022C8FC20 ON work (occupation_id)');
        $this->addSql('CREATE INDEX IDX_534E6880F4BD7827 ON work (assigned_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__work AS SELECT id, name, description, status, started_at, duration FROM work');
        $this->addSql('DROP TABLE work');
        $this->addSql('CREATE TABLE work (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, status VARCHAR(50) NOT NULL, started_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , duration INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO work (id, name, description, status, started_at, duration) SELECT id, name, description, status, started_at, duration FROM __temp__work');
        $this->addSql('DROP TABLE __temp__work');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_534E68805E237E06 ON work (name)');
    }
}
