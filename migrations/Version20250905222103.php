<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905222103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables for stock management system, adapted for PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is adapted for PostgreSQL
        $this->addSql('CREATE TABLE categorie (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fournisseur (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, contact VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE localisation (id SERIAL NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mouvment_stock (id SERIAL NOT NULL, produit_id INT DEFAULT NULL, localisation_id INT NOT NULL, utilisateur_id INT NOT NULL, type VARCHAR(255) NOT NULL, quantite INT NOT NULL, date_mouvement TIMESTAMP NOT NULL, CONSTRAINT FK_F606F14FF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id), CONSTRAINT FK_F606F14FC68BE09C FOREIGN KEY (localisation_id) REFERENCES localisation (id), CONSTRAINT FK_F606F14FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "user" (id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE produit (id SERIAL NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, reference VARCHAR(255) DEFAULT NULL, seuil_d_alerte INT NOT NULL, CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE produit_fournisseur (produit_id INT NOT NULL, fournisseur_id INT NOT NULL, CONSTRAINT FK_48868EB6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE, CONSTRAINT FK_48868EB6670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE, PRIMARY KEY(produit_id, fournisseur_id))');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSONB NOT NULL, password VARCHAR(255) NOT NULL, CONSTRAINT UNIQ_IDENTIFIER_EMAIL UNIQUE (email), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP NOT NULL, available_at TIMESTAMP NOT NULL, delivered_at TIMESTAMP DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F606F14FF347EFB ON mouvment_stock (produit_id)');
        $this->addSql('CREATE INDEX IDX_F606F14FC68BE09C ON mouvment_stock (localisation_id)');
        $this->addSql('CREATE INDEX IDX_F606F14FFB88E14F ON mouvment_stock (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('CREATE INDEX IDX_48868EB6F347EFB ON produit_fournisseur (produit_id)');
        $this->addSql('CREATE INDEX IDX_48868EB6670C757F ON produit_fournisseur (fournisseur_id)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvment_stock DROP CONSTRAINT FK_F606F14FF347EFB');
        $this->addSql('ALTER TABLE mouvment_stock DROP CONSTRAINT FK_F606F14FC68BE09C');
        $this->addSql('ALTER TABLE mouvment_stock DROP CONSTRAINT FK_F606F14FFB88E14F');
        $this->addSql('ALTER TABLE produit DROP CONSTRAINT FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE produit_fournisseur DROP CONSTRAINT FK_48868EB6F347EFB');
        $this->addSql('ALTER TABLE produit_fournisseur DROP CONSTRAINT FK_48868EB6670C757F');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE localisation');
        $this->addSql('DROP TABLE mouvment_stock');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_fournisseur');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
