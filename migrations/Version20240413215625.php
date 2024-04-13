<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413215625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__skill AS SELECT id, occupation_id, name, description, slug FROM skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, occupation_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, slug VARCHAR(255) NOT NULL, CONSTRAINT FK_5E3DE47722C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO skill (id, occupation_id, name, description, slug) SELECT id, occupation_id, name, description, slug FROM __temp__skill');
        $this->addSql('DROP TABLE __temp__skill');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E3DE4775E237E06 ON skill (name)');
        $this->addSql('CREATE INDEX IDX_5E3DE47722C8FC20 ON skill (occupation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__skill AS SELECT id, occupation_id, name, description, slug FROM skill');
        $this->addSql('DROP TABLE skill');
        $this->addSql('CREATE TABLE skill (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, occupation_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, slug VARCHAR(255) NOT NULL, CONSTRAINT FK_5E3DE47722C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO skill (id, occupation_id, name, description, slug) SELECT id, occupation_id, name, description, slug FROM __temp__skill');
        $this->addSql('DROP TABLE __temp__skill');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E3DE4775E237E06 ON skill (name)');
        $this->addSql('CREATE INDEX IDX_5E3DE47722C8FC20 ON skill (occupation_id)');
    }
}
