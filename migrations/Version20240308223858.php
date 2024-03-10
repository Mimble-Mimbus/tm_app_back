<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240308223858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entertainment (id INT AUTO_INCREMENT NOT NULL, entertainment_type_id INT NOT NULL, zone_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, max_number_seats INT DEFAULT NULL, duration INT DEFAULT NULL, on_reservation TINYINT(1) NOT NULL, is_canceled TINYINT(1) NOT NULL, INDEX IDX_5DCF0F53BCD84E39 (entertainment_type_id), INDEX IDX_5DCF0F539F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entertainment_reservation (id INT AUTO_INCREMENT NOT NULL, entertainment_schedule_id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, bookings INT NOT NULL, INDEX IDX_CEC1749C2802FF1C (entertainment_schedule_id), INDEX IDX_CEC1749CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entertainment_schedule (id INT AUTO_INCREMENT NOT NULL, entertainment_id INT NOT NULL, duration INT DEFAULT NULL, start DATETIME NOT NULL, is_canceled TINYINT(1) NOT NULL, INDEX IDX_6C42CFCF84F8966A (entertainment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entertainment_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organization_id INT NOT NULL, name VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA732C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guild (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, points INT NOT NULL, INDEX IDX_75407DAB71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE open_day (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, day_start DATETIME NOT NULL, day_end DATETIME NOT NULL, INDEX IDX_773505E671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, presentation LONGTEXT NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paymentable (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, type_paymentable_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6715398671F7E88B (event_id), INDEX IDX_67153986B663E2A0 (type_paymentable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price (id INT AUTO_INCREMENT NOT NULL, paymentable_id INT NOT NULL, price_condition VARCHAR(255) DEFAULT NULL, price INT NOT NULL, INDEX IDX_CAC822D9E0A0CB34 (paymentable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quest (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, zone_id INT NOT NULL, guild_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, infos LONGTEXT NOT NULL, points INT NOT NULL, is_secret TINYINT(1) NOT NULL, password VARCHAR(255) DEFAULT NULL, INDEX IDX_4317F81771F7E88B (event_id), INDEX IDX_4317F8179F2C3FAB (zone_id), INDEX IDX_4317F8175F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reporting (id INT AUTO_INCREMENT NOT NULL, zone_id INT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, date DATETIME NOT NULL, type VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, emergency_level VARCHAR(255) NOT NULL, INDEX IDX_BD7CFA9F9F2C3FAB (zone_id), INDEX IDX_BD7CFA9FA76ED395 (user_id), INDEX IDX_BD7CFA9F71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, publisher VARCHAR(255) NOT NULL, universe VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_tag (rpg_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_A90DC985310B3E3E (rpg_id), INDEX IDX_A90DC985BAD26311 (tag_id), PRIMARY KEY(rpg_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_trigger_warning (rpg_id INT NOT NULL, trigger_warning_id INT NOT NULL, INDEX IDX_9903E6E3310B3E3E (rpg_id), INDEX IDX_9903E6E3ADD48F4F (trigger_warning_id), PRIMARY KEY(rpg_id, trigger_warning_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_activity (id INT AUTO_INCREMENT NOT NULL, rpg_zone_id INT DEFAULT NULL, user_gm_id INT NOT NULL, rpg_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, max_number_seats INT DEFAULT NULL, duration INT DEFAULT NULL, on_reservation TINYINT(1) NOT NULL, is_canceled TINYINT(1) NOT NULL, INDEX IDX_1A00CC6A8DC43294 (rpg_zone_id), INDEX IDX_1A00CC6A5FBE94E4 (user_gm_id), INDEX IDX_1A00CC6A310B3E3E (rpg_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_activity_tag (rpg_activity_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_7FAE2F40444E139E (rpg_activity_id), INDEX IDX_7FAE2F40BAD26311 (tag_id), PRIMARY KEY(rpg_activity_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_activity_trigger_warning (rpg_activity_id INT NOT NULL, trigger_warning_id INT NOT NULL, INDEX IDX_BBF6A966444E139E (rpg_activity_id), INDEX IDX_BBF6A966ADD48F4F (trigger_warning_id), PRIMARY KEY(rpg_activity_id, trigger_warning_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_reservation (id INT AUTO_INCREMENT NOT NULL, rpg_table_id INT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, bookings INT NOT NULL, INDEX IDX_87463A5D5A8BED6C (rpg_table_id), INDEX IDX_87463A5DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_table (id INT AUTO_INCREMENT NOT NULL, rpg_activity_id INT NOT NULL, duration INT DEFAULT NULL, start DATETIME NOT NULL, is_canceled TINYINT(1) NOT NULL, INDEX IDX_17CEDE7F444E139E (rpg_activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rpg_zone (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, zone_id INT NOT NULL, name VARCHAR(255) NOT NULL, available_tables INT NOT NULL, min_start_hour VARCHAR(255) NOT NULL, max_end_hour VARCHAR(255) NOT NULL, max_available_seats_per_table INT NOT NULL, INDEX IDX_4922E14C71F7E88B (event_id), UNIQUE INDEX UNIQ_4922E14C9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE started_quest (id INT AUTO_INCREMENT NOT NULL, quest_id INT NOT NULL, user_id INT NOT NULL, user_guild_id INT DEFAULT NULL, date DATE NOT NULL, is_fulfilled TINYINT(1) NOT NULL, is_aborted TINYINT(1) NOT NULL, difficulty INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_7EB011C6209E9EF4 (quest_id), INDEX IDX_7EB011C6A76ED395 (user_id), INDEX IDX_7EB011C6BFACD5EF (user_guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transit (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, available_seats INT NOT NULL, start DATETIME NOT NULL, arrival DATETIME NOT NULL, INDEX IDX_9EAEA8371F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trigger_warning (id INT AUTO_INCREMENT NOT NULL, theme VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_paymentable (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, organization_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_F47645AE71F7E88B (event_id), INDEX IDX_F47645AE32C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_params (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, all_notifications TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_EA9C15CEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tm (id INT AUTO_INCREMENT NOT NULL, guild_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, telephone VARCHAR(15) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_21DE072DE7927C74 (email), INDEX IDX_21DE072D5F2131EF (guild_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer_shift (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, zone_id INT NOT NULL, shift_start DATETIME NOT NULL, shift_end DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_9765E6B7A76ED395 (user_id), INDEX IDX_9765E6B771F7E88B (event_id), INDEX IDX_9765E6B79F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A0EBC00771F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entertainment ADD CONSTRAINT FK_5DCF0F53BCD84E39 FOREIGN KEY (entertainment_type_id) REFERENCES entertainment_type (id)');
        $this->addSql('ALTER TABLE entertainment ADD CONSTRAINT FK_5DCF0F539F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE entertainment_reservation ADD CONSTRAINT FK_CEC1749C2802FF1C FOREIGN KEY (entertainment_schedule_id) REFERENCES entertainment_schedule (id)');
        $this->addSql('ALTER TABLE entertainment_reservation ADD CONSTRAINT FK_CEC1749CA76ED395 FOREIGN KEY (user_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE entertainment_schedule ADD CONSTRAINT FK_6C42CFCF84F8966A FOREIGN KEY (entertainment_id) REFERENCES entertainment (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE open_day ADD CONSTRAINT FK_773505E671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE paymentable ADD CONSTRAINT FK_6715398671F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE paymentable ADD CONSTRAINT FK_67153986B663E2A0 FOREIGN KEY (type_paymentable_id) REFERENCES type_paymentable (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9E0A0CB34 FOREIGN KEY (paymentable_id) REFERENCES paymentable (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F81771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F8179F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F8175F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE reporting ADD CONSTRAINT FK_BD7CFA9F9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE reporting ADD CONSTRAINT FK_BD7CFA9FA76ED395 FOREIGN KEY (user_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE reporting ADD CONSTRAINT FK_BD7CFA9F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE rpg_tag ADD CONSTRAINT FK_A90DC985310B3E3E FOREIGN KEY (rpg_id) REFERENCES rpg (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_tag ADD CONSTRAINT FK_A90DC985BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_trigger_warning ADD CONSTRAINT FK_9903E6E3310B3E3E FOREIGN KEY (rpg_id) REFERENCES rpg (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_trigger_warning ADD CONSTRAINT FK_9903E6E3ADD48F4F FOREIGN KEY (trigger_warning_id) REFERENCES trigger_warning (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_activity ADD CONSTRAINT FK_1A00CC6A8DC43294 FOREIGN KEY (rpg_zone_id) REFERENCES rpg_zone (id)');
        $this->addSql('ALTER TABLE rpg_activity ADD CONSTRAINT FK_1A00CC6A5FBE94E4 FOREIGN KEY (user_gm_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE rpg_activity ADD CONSTRAINT FK_1A00CC6A310B3E3E FOREIGN KEY (rpg_id) REFERENCES rpg (id)');
        $this->addSql('ALTER TABLE rpg_activity_tag ADD CONSTRAINT FK_7FAE2F40444E139E FOREIGN KEY (rpg_activity_id) REFERENCES rpg_activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_activity_tag ADD CONSTRAINT FK_7FAE2F40BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_activity_trigger_warning ADD CONSTRAINT FK_BBF6A966444E139E FOREIGN KEY (rpg_activity_id) REFERENCES rpg_activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_activity_trigger_warning ADD CONSTRAINT FK_BBF6A966ADD48F4F FOREIGN KEY (trigger_warning_id) REFERENCES trigger_warning (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rpg_reservation ADD CONSTRAINT FK_87463A5D5A8BED6C FOREIGN KEY (rpg_table_id) REFERENCES rpg_table (id)');
        $this->addSql('ALTER TABLE rpg_reservation ADD CONSTRAINT FK_87463A5DA76ED395 FOREIGN KEY (user_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE rpg_table ADD CONSTRAINT FK_17CEDE7F444E139E FOREIGN KEY (rpg_activity_id) REFERENCES rpg_activity (id)');
        $this->addSql('ALTER TABLE rpg_zone ADD CONSTRAINT FK_4922E14C71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE rpg_zone ADD CONSTRAINT FK_4922E14C9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE started_quest ADD CONSTRAINT FK_7EB011C6209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id)');
        $this->addSql('ALTER TABLE started_quest ADD CONSTRAINT FK_7EB011C6A76ED395 FOREIGN KEY (user_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE started_quest ADD CONSTRAINT FK_7EB011C6BFACD5EF FOREIGN KEY (user_guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE transit ADD CONSTRAINT FK_9EAEA8371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE url ADD CONSTRAINT FK_F47645AE71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE url ADD CONSTRAINT FK_F47645AE32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE user_params ADD CONSTRAINT FK_EA9C15CEA76ED395 FOREIGN KEY (user_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE user_tm ADD CONSTRAINT FK_21DE072D5F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('ALTER TABLE volunteer_shift ADD CONSTRAINT FK_9765E6B7A76ED395 FOREIGN KEY (user_id) REFERENCES user_tm (id)');
        $this->addSql('ALTER TABLE volunteer_shift ADD CONSTRAINT FK_9765E6B771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE volunteer_shift ADD CONSTRAINT FK_9765E6B79F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE zone ADD CONSTRAINT FK_A0EBC00771F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entertainment DROP FOREIGN KEY FK_5DCF0F53BCD84E39');
        $this->addSql('ALTER TABLE entertainment DROP FOREIGN KEY FK_5DCF0F539F2C3FAB');
        $this->addSql('ALTER TABLE entertainment_reservation DROP FOREIGN KEY FK_CEC1749C2802FF1C');
        $this->addSql('ALTER TABLE entertainment_reservation DROP FOREIGN KEY FK_CEC1749CA76ED395');
        $this->addSql('ALTER TABLE entertainment_schedule DROP FOREIGN KEY FK_6C42CFCF84F8966A');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA732C8A3DE');
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB71F7E88B');
        $this->addSql('ALTER TABLE open_day DROP FOREIGN KEY FK_773505E671F7E88B');
        $this->addSql('ALTER TABLE paymentable DROP FOREIGN KEY FK_6715398671F7E88B');
        $this->addSql('ALTER TABLE paymentable DROP FOREIGN KEY FK_67153986B663E2A0');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9E0A0CB34');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F81771F7E88B');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F8179F2C3FAB');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F8175F2131EF');
        $this->addSql('ALTER TABLE reporting DROP FOREIGN KEY FK_BD7CFA9F9F2C3FAB');
        $this->addSql('ALTER TABLE reporting DROP FOREIGN KEY FK_BD7CFA9FA76ED395');
        $this->addSql('ALTER TABLE reporting DROP FOREIGN KEY FK_BD7CFA9F71F7E88B');
        $this->addSql('ALTER TABLE rpg_tag DROP FOREIGN KEY FK_A90DC985310B3E3E');
        $this->addSql('ALTER TABLE rpg_tag DROP FOREIGN KEY FK_A90DC985BAD26311');
        $this->addSql('ALTER TABLE rpg_trigger_warning DROP FOREIGN KEY FK_9903E6E3310B3E3E');
        $this->addSql('ALTER TABLE rpg_trigger_warning DROP FOREIGN KEY FK_9903E6E3ADD48F4F');
        $this->addSql('ALTER TABLE rpg_activity DROP FOREIGN KEY FK_1A00CC6A8DC43294');
        $this->addSql('ALTER TABLE rpg_activity DROP FOREIGN KEY FK_1A00CC6A5FBE94E4');
        $this->addSql('ALTER TABLE rpg_activity DROP FOREIGN KEY FK_1A00CC6A310B3E3E');
        $this->addSql('ALTER TABLE rpg_activity_tag DROP FOREIGN KEY FK_7FAE2F40444E139E');
        $this->addSql('ALTER TABLE rpg_activity_tag DROP FOREIGN KEY FK_7FAE2F40BAD26311');
        $this->addSql('ALTER TABLE rpg_activity_trigger_warning DROP FOREIGN KEY FK_BBF6A966444E139E');
        $this->addSql('ALTER TABLE rpg_activity_trigger_warning DROP FOREIGN KEY FK_BBF6A966ADD48F4F');
        $this->addSql('ALTER TABLE rpg_reservation DROP FOREIGN KEY FK_87463A5D5A8BED6C');
        $this->addSql('ALTER TABLE rpg_reservation DROP FOREIGN KEY FK_87463A5DA76ED395');
        $this->addSql('ALTER TABLE rpg_table DROP FOREIGN KEY FK_17CEDE7F444E139E');
        $this->addSql('ALTER TABLE rpg_zone DROP FOREIGN KEY FK_4922E14C71F7E88B');
        $this->addSql('ALTER TABLE rpg_zone DROP FOREIGN KEY FK_4922E14C9F2C3FAB');
        $this->addSql('ALTER TABLE started_quest DROP FOREIGN KEY FK_7EB011C6209E9EF4');
        $this->addSql('ALTER TABLE started_quest DROP FOREIGN KEY FK_7EB011C6A76ED395');
        $this->addSql('ALTER TABLE started_quest DROP FOREIGN KEY FK_7EB011C6BFACD5EF');
        $this->addSql('ALTER TABLE transit DROP FOREIGN KEY FK_9EAEA8371F7E88B');
        $this->addSql('ALTER TABLE url DROP FOREIGN KEY FK_F47645AE71F7E88B');
        $this->addSql('ALTER TABLE url DROP FOREIGN KEY FK_F47645AE32C8A3DE');
        $this->addSql('ALTER TABLE user_params DROP FOREIGN KEY FK_EA9C15CEA76ED395');
        $this->addSql('ALTER TABLE user_tm DROP FOREIGN KEY FK_21DE072D5F2131EF');
        $this->addSql('ALTER TABLE volunteer_shift DROP FOREIGN KEY FK_9765E6B7A76ED395');
        $this->addSql('ALTER TABLE volunteer_shift DROP FOREIGN KEY FK_9765E6B771F7E88B');
        $this->addSql('ALTER TABLE volunteer_shift DROP FOREIGN KEY FK_9765E6B79F2C3FAB');
        $this->addSql('ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC00771F7E88B');
        $this->addSql('DROP TABLE entertainment');
        $this->addSql('DROP TABLE entertainment_reservation');
        $this->addSql('DROP TABLE entertainment_schedule');
        $this->addSql('DROP TABLE entertainment_type');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP TABLE open_day');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE paymentable');
        $this->addSql('DROP TABLE price');
        $this->addSql('DROP TABLE quest');
        $this->addSql('DROP TABLE reporting');
        $this->addSql('DROP TABLE rpg');
        $this->addSql('DROP TABLE rpg_tag');
        $this->addSql('DROP TABLE rpg_trigger_warning');
        $this->addSql('DROP TABLE rpg_activity');
        $this->addSql('DROP TABLE rpg_activity_tag');
        $this->addSql('DROP TABLE rpg_activity_trigger_warning');
        $this->addSql('DROP TABLE rpg_reservation');
        $this->addSql('DROP TABLE rpg_table');
        $this->addSql('DROP TABLE rpg_zone');
        $this->addSql('DROP TABLE started_quest');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE transit');
        $this->addSql('DROP TABLE trigger_warning');
        $this->addSql('DROP TABLE type_paymentable');
        $this->addSql('DROP TABLE url');
        $this->addSql('DROP TABLE user_params');
        $this->addSql('DROP TABLE user_tm');
        $this->addSql('DROP TABLE volunteer_shift');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
