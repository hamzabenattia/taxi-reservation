<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404173253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP INDEX UNIQ_FE866410B83297E7, ADD INDEX IDX_FE866410B83297E7 (reservation_id)');
        $this->addSql('ALTER TABLE facture ADD client_id INT DEFAULT NULL, DROP client');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_FE86641019EB6921 ON facture (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP INDEX IDX_FE866410B83297E7, ADD UNIQUE INDEX UNIQ_FE866410B83297E7 (reservation_id)');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('DROP INDEX IDX_FE86641019EB6921 ON facture');
        $this->addSql('ALTER TABLE facture ADD client VARCHAR(255) NOT NULL, DROP client_id');
    }
}
