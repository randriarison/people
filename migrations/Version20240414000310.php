<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414000310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE occupation_skill (occupation_id INTEGER NOT NULL, skill_id INTEGER NOT NULL, PRIMARY KEY(occupation_id, skill_id), CONSTRAINT FK_52C31DE122C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_52C31DE15585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_52C31DE122C8FC20 ON occupation_skill (occupation_id)');
        $this->addSql('CREATE INDEX IDX_52C31DE15585C142 ON occupation_skill (skill_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE occupation_skill');
    }
}
