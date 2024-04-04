<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402005719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entertainment CHANGE is_canceled is_canceled TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE entertainment_schedule CHANGE is_canceled is_canceled TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE rpg_activity CHANGE is_canceled is_canceled TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE rpg_table CHANGE is_canceled is_canceled TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entertainment CHANGE is_canceled is_canceled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE entertainment_schedule CHANGE is_canceled is_canceled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE rpg_activity CHANGE is_canceled is_canceled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE rpg_table CHANGE is_canceled is_canceled TINYINT(1) NOT NULL');
    }
}
