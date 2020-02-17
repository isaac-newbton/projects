<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217145522 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_2FB3D0EE2FC59CFA ON project');
        $this->addSql('DROP INDEX UNIQ_2FB3D0EE60B9EF45 ON project');
        $this->addSql('ALTER TABLE project ADD owner_id INT DEFAULT NULL, ADD expire_dt DATETIME DEFAULT NULL, DROP view_uuid, DROP edit_uuid');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE7E3C61F9 ON project (owner_id)');
        $this->addSql('DROP INDEX UNIQ_527EDB252FC59CFA ON task');
        $this->addSql('DROP INDEX UNIQ_527EDB2560B9EF45 ON task');
        $this->addSql('ALTER TABLE task DROP view_uuid, DROP edit_uuid');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7E3C61F9');
        $this->addSql('DROP INDEX IDX_2FB3D0EE7E3C61F9 ON project');
        $this->addSql('ALTER TABLE project ADD view_uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', ADD edit_uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', DROP owner_id, DROP expire_dt');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE2FC59CFA ON project (view_uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2FB3D0EE60B9EF45 ON project (edit_uuid)');
        $this->addSql('ALTER TABLE task ADD view_uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\', ADD edit_uuid CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB252FC59CFA ON task (view_uuid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_527EDB2560B9EF45 ON task (edit_uuid)');
    }
}
