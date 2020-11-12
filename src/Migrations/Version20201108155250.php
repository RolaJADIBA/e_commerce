<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201108155250 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tailles (id INT AUTO_INCREMENT NOT NULL, options_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, INDEX IDX_337A271E3ADB05F1 (options_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tailles ADD CONSTRAINT FK_337A271E3ADB05F1 FOREIGN KEY (options_id) REFERENCES options (id)');
        $this->addSql('ALTER TABLE produits ADD tailles_id INT DEFAULT NULL, DROP tailles');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C1AEC613E FOREIGN KEY (tailles_id) REFERENCES tailles (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8C1AEC613E ON produits (tailles_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C1AEC613E');
        $this->addSql('DROP TABLE tailles');
        $this->addSql('DROP INDEX IDX_BE2DDF8C1AEC613E ON produits');
        $this->addSql('ALTER TABLE produits ADD tailles JSON NOT NULL, DROP tailles_id');
    }
}
