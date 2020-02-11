<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211191627 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task ADD uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD view_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD edit_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', ADD deleted TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB25D17F50A6 ON task (uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB252FC59CFA ON task (view_uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB2560B9EF45 ON task (edit_uuid)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_527EDB25D17F50A6 ON task');
        $this->addSql('DROP INDEX UNIQ_527EDB252FC59CFA ON task');
        $this->addSql('DROP INDEX UNIQ_527EDB2560B9EF45 ON task');
        $this->addSql('ALTER TABLE task DROP uuid, DROP view_uuid, DROP edit_uuid, DROP deleted');
    }
}
