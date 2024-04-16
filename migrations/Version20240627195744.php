<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240627195744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE occupation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2F87D515E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE occupation_skill (occupation_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_52C31DE122C8FC20 (occupation_id), INDEX IDX_52C31DE15585C142 (skill_id), PRIMARY KEY(occupation_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, occupation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, age INT DEFAULT NULL, INDEX IDX_34DCD17622C8FC20 (occupation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_skills (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, skill_id INT NOT NULL, level INT DEFAULT NULL, INDEX IDX_499426C6217BBB47 (person_id), INDEX IDX_499426C65585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5E3DE4775E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, occupation_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_20F33ED122C8FC20 (occupation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work (id INT AUTO_INCREMENT NOT NULL, occupation_id INT NOT NULL, assigned_to_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(50) NOT NULL, started_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', duration INT DEFAULT NULL, completion_level INT DEFAULT NULL, UNIQUE INDEX UNIQ_534E68805E237E06 (name), INDEX IDX_534E688022C8FC20 (occupation_id), INDEX IDX_534E6880F4BD7827 (assigned_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE occupation_skill ADD CONSTRAINT FK_52C31DE122C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE occupation_skill ADD CONSTRAINT FK_52C31DE15585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17622C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id)');
        $this->addSql('ALTER TABLE person_skills ADD CONSTRAINT FK_499426C6217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_skills ADD CONSTRAINT FK_499426C65585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED122C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E688022C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id)');
        $this->addSql('ALTER TABLE work ADD CONSTRAINT FK_534E6880F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES person (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE occupation_skill DROP FOREIGN KEY FK_52C31DE122C8FC20');
        $this->addSql('ALTER TABLE occupation_skill DROP FOREIGN KEY FK_52C31DE15585C142');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17622C8FC20');
        $this->addSql('ALTER TABLE person_skills DROP FOREIGN KEY FK_499426C6217BBB47');
        $this->addSql('ALTER TABLE person_skills DROP FOREIGN KEY FK_499426C65585C142');
        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED122C8FC20');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E688022C8FC20');
        $this->addSql('ALTER TABLE work DROP FOREIGN KEY FK_534E6880F4BD7827');
        $this->addSql('DROP TABLE occupation');
        $this->addSql('DROP TABLE occupation_skill');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_skills');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE work');
    }
}
