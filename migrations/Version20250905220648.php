<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905220648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, contact VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE localisation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvment_stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, localisation_id INT NOT NULL, utilisateur_id INT NOT NULL, type VARCHAR(255) NOT NULL, quantite INT NOT NULL, date_mouvement DATETIME NOT NULL, INDEX IDX_F606F14FF347EFB (produit_id), INDEX IDX_F606F14FC68BE09C (localisation_id), INDEX IDX_F606F14FFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(255) DEFAULT NULL, seuil_d_alerte INT NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_fournisseur (produit_id INT NOT NULL, fournisseur_id INT NOT NULL, INDEX IDX_48868EB6F347EFB (produit_id), INDEX IDX_48868EB6670C757F (fournisseur_id), PRIMARY KEY(produit_id, fournisseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mouvment_stock ADD CONSTRAINT FK_F606F14FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE mouvment_stock ADD CONSTRAINT FK_F606F14FC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id)');
        $this->addSql('ALTER TABLE mouvment_stock ADD CONSTRAINT FK_F606F14FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produit_fournisseur ADD CONSTRAINT FK_48868EB6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_fournisseur ADD CONSTRAINT FK_48868EB6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvment_stock DROP FOREIGN KEY FK_F606F14FF347EFB');
        $this->addSql('ALTER TABLE mouvment_stock DROP FOREIGN KEY FK_F606F14FC68BE09C');
        $this->addSql('ALTER TABLE mouvment_stock DROP FOREIGN KEY FK_F606F14FFB88E14F');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6F347EFB');
        $this->addSql('ALTER TABLE produit_fournisseur DROP FOREIGN KEY FK_48868EB6670C757F');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE mouvment_stock');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_fournisseur');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
