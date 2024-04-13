<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413201928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__occupation AS SELECT id, name FROM occupation');
        $this->addSql('DROP TABLE occupation');
        $this->addSql('CREATE TABLE occupation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO occupation (id, name) SELECT id, name FROM __temp__occupation');
        $this->addSql('DROP TABLE __temp__occupation');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F87D515E237E06 ON occupation (name)');
        $this->addSql('ALTER TABLE person ADD COLUMN slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__skill AS SELECT id, occupation_id, name, descriptiion FROM skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, occupation_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, slug VARCHAR(255) NOT NULL, CONSTRAINT FK_5E3DE47722C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO skill (id, occupation_id, name, description) SELECT id, occupation_id, name, descriptiion FROM __temp__skill');
        $this->addSql('DROP TABLE __temp__skill');
        $this->addSql('CREATE INDEX IDX_5E3DE47722C8FC20 ON skill (occupation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E3DE4775E237E06 ON skill (name)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__work AS SELECT id, name, description, status, started_at, duration FROM work');
        $this->addSql('DROP TABLE work');
        $this->addSql('CREATE TABLE work (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, status VARCHAR(50) NOT NULL, started_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , duration INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO work (id, name, description, status, started_at, duration) SELECT id, name, description, status, started_at, duration FROM __temp__work');
        $this->addSql('DROP TABLE __temp__work');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_534E68805E237E06 ON work (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__occupation AS SELECT id, name FROM occupation');
        $this->addSql('DROP TABLE occupation');
        $this->addSql('CREATE TABLE occupation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO occupation (id, name) SELECT id, name FROM __temp__occupation');
        $this->addSql('DROP TABLE __temp__occupation');
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, name, age FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, age INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO person (id, name, age) SELECT id, name, age FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
        $this->addSql('CREATE TEMPORARY TABLE __temp__skill AS SELECT id, occupation_id, name, description FROM skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, occupation_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, descriptiion CLOB DEFAULT NULL, CONSTRAINT FK_5E3DE47722C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO skill (id, occupation_id, name, descriptiion) SELECT id, occupation_id, name, description FROM __temp__skill');
        $this->addSql('DROP TABLE __temp__skill');
        $this->addSql('CREATE INDEX IDX_5E3DE47722C8FC20 ON skill (occupation_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__work AS SELECT id, name, description, status, started_at, duration FROM work');
        $this->addSql('DROP TABLE work');
        $this->addSql('CREATE TABLE work (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, status VARCHAR(50) NOT NULL, started_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , duration INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO work (id, name, description, status, started_at, duration) SELECT id, name, description, status, started_at, duration FROM __temp__work');
        $this->addSql('DROP TABLE __temp__work');
    }
}
