<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628021329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_groupe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_groupe_categorie (option_groupe_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_AA172597ABDE250C (option_groupe_id), INDEX IDX_AA172597BCF5E72D (categorie_id), PRIMARY KEY(option_groupe_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE options (id INT AUTO_INCREMENT NOT NULL, option_groupe_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_D035FA87ABDE250C (option_groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, statut INT NOT NULL, montant_total NUMERIC(10, 2) NOT NULL, date DATETIME NOT NULL, numero_commande INT NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_produit (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, produits_id INT NOT NULL, quantite INT NOT NULL, prix NUMERIC(10, 2) NOT NULL, color VARCHAR(50) DEFAULT NULL, taille VARCHAR(25) DEFAULT NULL, INDEX IDX_D31F28A6F77D927C (panier_id), INDEX IDX_D31F28A6CD11A2CF (produits_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, options_id INT DEFAULT NULL, option_groupe_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, prix NUMERIC(10, 2) NOT NULL, images LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', colors LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', tailles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', image_choisi VARCHAR(255) DEFAULT NULL, in_cart TINYINT(1) DEFAULT NULL, count INT DEFAULT NULL, total NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_BE2DDF8CBCF5E72D (categorie_id), INDEX IDX_BE2DDF8C3ADB05F1 (options_id), INDEX IDX_BE2DDF8CABDE250C (option_groupe_id), FULLTEXT INDEX IDX_BE2DDF8C6C6E55B56DE44026 (nom, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_user (produits_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9044048BCD11A2CF (produits_id), INDEX IDX_9044048BA76ED395 (user_id), PRIMARY KEY(produits_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE option_groupe_categorie ADD CONSTRAINT FK_AA172597ABDE250C FOREIGN KEY (option_groupe_id) REFERENCES option_groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_groupe_categorie ADD CONSTRAINT FK_AA172597BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE options ADD CONSTRAINT FK_D035FA87ABDE250C FOREIGN KEY (option_groupe_id) REFERENCES option_groupe (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_D31F28A6CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C3ADB05F1 FOREIGN KEY (options_id) REFERENCES options (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CABDE250C FOREIGN KEY (option_groupe_id) REFERENCES option_groupe (id)');
        $this->addSql('ALTER TABLE produits_user ADD CONSTRAINT FK_9044048BCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_user ADD CONSTRAINT FK_9044048BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE option_groupe_categorie DROP FOREIGN KEY FK_AA172597BCF5E72D');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CBCF5E72D');
        $this->addSql('ALTER TABLE option_groupe_categorie DROP FOREIGN KEY FK_AA172597ABDE250C');
        $this->addSql('ALTER TABLE options DROP FOREIGN KEY FK_D035FA87ABDE250C');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CABDE250C');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C3ADB05F1');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6F77D927C');
        $this->addSql('ALTER TABLE panier_produit DROP FOREIGN KEY FK_D31F28A6CD11A2CF');
        $this->addSql('ALTER TABLE produits_user DROP FOREIGN KEY FK_9044048BCD11A2CF');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE produits_user DROP FOREIGN KEY FK_9044048BA76ED395');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE option_groupe');
        $this->addSql('DROP TABLE option_groupe_categorie');
        $this->addSql('DROP TABLE options');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_produit');
        $this->addSql('DROP TABLE produits');
        $this->addSql('DROP TABLE produits_user');
        $this->addSql('DROP TABLE user');
    }
}
