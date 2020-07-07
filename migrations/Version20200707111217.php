<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200707111217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting CHANGE uuid uuid VARBINARY(16) NOT NULL, CHANGE organizer_uuid organizer_uuid VARBINARY(16) NOT NULL');
        $this->addSql('ALTER TABLE meeting_participant CHANGE user_uuid user_uuid VARBINARY(16) NOT NULL, CHANGE meeting_uuid meeting_uuid VARBINARY(16) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE uuid uuid VARBINARY(16) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting CHANGE uuid uuid VARBINARY(255) NOT NULL, CHANGE organizer_uuid organizer_uuid VARBINARY(255) NOT NULL');
        $this->addSql('ALTER TABLE meeting_participant CHANGE user_uuid user_uuid VARBINARY(255) NOT NULL, CHANGE meeting_uuid meeting_uuid VARBINARY(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE uuid uuid VARBINARY(255) NOT NULL');
    }
}
