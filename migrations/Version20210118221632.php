<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118221632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, bien_id INT NOT NULL, libelle VARCHAR(100) NOT NULL, quantite INT NOT NULL, position VARCHAR(100) DEFAULT NULL, INDEX IDX_23A0E66BD95B80F (bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bien (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, type_bien_id INT NOT NULL, libelle VARCHAR(100) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, centre_fiscal VARCHAR(255) DEFAULT NULL, INDEX IDX_45EDC38676C50E4A (proprietaire_id), INDEX IDX_45EDC38695B4D7FA (type_bien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, etat VARCHAR(30) NOT NULL, INDEX IDX_5E9E89CB19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_article (location_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_EC7F1DC264D218E (location_id), INDEX IDX_EC7F1DC27294869C (article_id), PRIMARY KEY(location_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proprietaire (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, INDEX IDX_69E399D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_bien (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BD95B80F FOREIGN KEY (bien_id) REFERENCES bien (id)');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC38676C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaire (id)');
        $this->addSql('ALTER TABLE bien ADD CONSTRAINT FK_45EDC38695B4D7FA FOREIGN KEY (type_bien_id) REFERENCES type_bien (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE location_article ADD CONSTRAINT FK_EC7F1DC264D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location_article ADD CONSTRAINT FK_EC7F1DC27294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE proprietaire ADD CONSTRAINT FK_69E399D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location_article DROP FOREIGN KEY FK_EC7F1DC27294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BD95B80F');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB19EB6921');
        $this->addSql('ALTER TABLE location_article DROP FOREIGN KEY FK_EC7F1DC264D218E');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC38676C50E4A');
        $this->addSql('ALTER TABLE bien DROP FOREIGN KEY FK_45EDC38695B4D7FA');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE bien');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE location_article');
        $this->addSql('DROP TABLE proprietaire');
        $this->addSql('DROP TABLE type_bien');
    }
}
