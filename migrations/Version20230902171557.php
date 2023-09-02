<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902171557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organization_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7F1C37890 (organization_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE open_day (id INT AUTO_INCREMENT NOT NULL, event_id_id INT NOT NULL, day_start DATETIME NOT NULL, day_end DATETIME NOT NULL, INDEX IDX_773505E63E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, apaymentable_id INT NOT NULL, price_condition VARCHAR(255) DEFAULT NULL, price INT NOT NULL, INDEX IDX_CAC822D91D0D2F1F (apaymentable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reporting (id INT AUTO_INCREMENT NOT NULL, zone_id INT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, emergency_level VARCHAR(255) NOT NULL, INDEX IDX_BD7CFA9F9F2C3FAB (zone_id), INDEX IDX_BD7CFA9FA76ED395 (user_id), INDEX IDX_BD7CFA9F71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url (id INT AUTO_INCREMENT NOT NULL, structure_id_id INT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_F47645AEAA95C5C1 (structure_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_params (id INT AUTO_INCREMENT NOT NULL, all_notifications TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tm (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, telephone VARCHAR(15) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_21DE072DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer_shift (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, zone_id INT NOT NULL, shift_start DATETIME NOT NULL, shift_end DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_9765E6B7A76ED395 (user_id), INDEX IDX_9765E6B771F7E88B (event_id), INDEX IDX_9765E6B79F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F1C37890 FOREIGN KEY (organization_id_id) REFERENCES organization (id)');
        // $this->addSql('ALTER TABLE open_day ADD CONSTRAINT FK_773505E63E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
        // $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D91D0D2F1F FOREIGN KEY (apaymentable_id) REFERENCES apaymentable (id)');
        // $this->addSql('ALTER TABLE reporting ADD CONSTRAINT FK_BD7CFA9F9F2C3FAB FOREIGN KEY (zone_id) REFERENCES azone (id)');
        // $this->addSql('ALTER TABLE reporting ADD CONSTRAINT FK_BD7CFA9FA76ED395 FOREIGN KEY (user_id) REFERENCES auser (id)');
        // $this->addSql('ALTER TABLE reporting ADD CONSTRAINT FK_BD7CFA9F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        // $this->addSql('ALTER TABLE url ADD CONSTRAINT FK_F47645AEAA95C5C1 FOREIGN KEY (structure_id_id) REFERENCES astructure (id)');
        // $this->addSql('ALTER TABLE volunteer_shift ADD CONSTRAINT FK_9765E6B7A76ED395 FOREIGN KEY (user_id) REFERENCES auser (id)');
        // $this->addSql('ALTER TABLE volunteer_shift ADD CONSTRAINT FK_9765E6B771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        // $this->addSql('ALTER TABLE volunteer_shift ADD CONSTRAINT FK_9765E6B79F2C3FAB FOREIGN KEY (zone_id) REFERENCES azone (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7F1C37890');
        $this->addSql('ALTER TABLE open_day DROP FOREIGN KEY FK_773505E63E5F2F7B');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D91D0D2F1F');
        $this->addSql('ALTER TABLE reporting DROP FOREIGN KEY FK_BD7CFA9F9F2C3FAB');
        $this->addSql('ALTER TABLE reporting DROP FOREIGN KEY FK_BD7CFA9FA76ED395');
        $this->addSql('ALTER TABLE reporting DROP FOREIGN KEY FK_BD7CFA9F71F7E88B');
        $this->addSql('ALTER TABLE url DROP FOREIGN KEY FK_F47645AEAA95C5C1');
        $this->addSql('ALTER TABLE volunteer_shift DROP FOREIGN KEY FK_9765E6B7A76ED395');
        $this->addSql('ALTER TABLE volunteer_shift DROP FOREIGN KEY FK_9765E6B771F7E88B');
        $this->addSql('ALTER TABLE volunteer_shift DROP FOREIGN KEY FK_9765E6B79F2C3FAB');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE open_day');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE reporting');
        $this->addSql('DROP TABLE url');
        $this->addSql('DROP TABLE user_params');
        $this->addSql('DROP TABLE user_tm');
        $this->addSql('DROP TABLE volunteer_shift');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
