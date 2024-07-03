<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702092553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, language VARCHAR(255) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id INT AUTO_INCREMENT NOT NULL, creation_date DATETIME NOT NULL, title VARCHAR(255) DEFAULT NULL, update_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation_user (conversation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5AECB5559AC0396 (conversation_id), INDEX IDX_5AECB555A76ED395 (user_id), PRIMARY KEY(conversation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, company_id INT NOT NULL, UNIQUE INDEX UNIQ_FA2425B9A76ED395 (user_id), INDEX IDX_FA2425B9979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT NOT NULL, author_id INT NOT NULL, creation_date DATETIME NOT NULL, seen TINYINT(1) DEFAULT NULL, content LONGTEXT NOT NULL, INDEX IDX_B6BD307F9AC0396 (conversation_id), INDEX IDX_B6BD307FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_team (person_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_4E062234217BBB47 (person_id), INDEX IDX_4E062234296CD8AE (team_id), PRIMARY KEY(person_id, team_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_manager (person_id INT NOT NULL, manager_id INT NOT NULL, INDEX IDX_D8BA9630217BBB47 (person_id), INDEX IDX_D8BA9630783E3463 (manager_id), PRIMARY KEY(person_id, manager_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seen_by (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, user_id INT NOT NULL, seen_date DATETIME NOT NULL, INDEX IDX_8B0C5A1C537A1329 (message_id), INDEX IDX_8B0C5A1CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, manager_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C4E0A61F979B1AD6 (company_id), INDEX IDX_C4E0A61F783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(15) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB5559AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT FK_5AECB555A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE person_team ADD CONSTRAINT FK_4E062234217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_team ADD CONSTRAINT FK_4E062234296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_manager ADD CONSTRAINT FK_D8BA9630217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_manager ADD CONSTRAINT FK_D8BA9630783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seen_by ADD CONSTRAINT FK_8B0C5A1C537A1329 FOREIGN KEY (message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE seen_by ADD CONSTRAINT FK_8B0C5A1CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE person ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_34DCD176A76ED395 ON person (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176A76ED395');
        $this->addSql('ALTER TABLE conversation_user DROP FOREIGN KEY FK_5AECB5559AC0396');
        $this->addSql('ALTER TABLE conversation_user DROP FOREIGN KEY FK_5AECB555A76ED395');
        $this->addSql('ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9A76ED395');
        $this->addSql('ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9979B1AD6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B');
        $this->addSql('ALTER TABLE person_team DROP FOREIGN KEY FK_4E062234217BBB47');
        $this->addSql('ALTER TABLE person_team DROP FOREIGN KEY FK_4E062234296CD8AE');
        $this->addSql('ALTER TABLE person_manager DROP FOREIGN KEY FK_D8BA9630217BBB47');
        $this->addSql('ALTER TABLE person_manager DROP FOREIGN KEY FK_D8BA9630783E3463');
        $this->addSql('ALTER TABLE seen_by DROP FOREIGN KEY FK_8B0C5A1C537A1329');
        $this->addSql('ALTER TABLE seen_by DROP FOREIGN KEY FK_8B0C5A1CA76ED395');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F979B1AD6');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F783E3463');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE conversation_user');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE person_team');
        $this->addSql('DROP TABLE person_manager');
        $this->addSql('DROP TABLE seen_by');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_34DCD176A76ED395 ON person');
        $this->addSql('ALTER TABLE person DROP user_id');
    }
}
