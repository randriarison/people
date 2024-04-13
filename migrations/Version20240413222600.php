<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413222600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, name, age, slug FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, occupation_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, age INTEGER DEFAULT NULL, slug VARCHAR(255) NOT NULL, CONSTRAINT FK_34DCD17622C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO person (id, name, age, slug) SELECT id, name, age, slug FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
        $this->addSql('CREATE INDEX IDX_34DCD17622C8FC20 ON person (occupation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__person AS SELECT id, name, slug, age FROM person');
        $this->addSql('DROP TABLE person');
        $this->addSql('CREATE TABLE person (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, age INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO person (id, name, slug, age) SELECT id, name, slug, age FROM __temp__person');
        $this->addSql('DROP TABLE __temp__person');
    }
}
