<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230720091359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(150) NOT NULL, contenu LONGTEXT NOT NULL, date_enregistrement DATETIME NOT NULL, categorie VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D3E9DFF83');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D3E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE avis');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D3E9DFF83');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D3E9DFF83 FOREIGN KEY (id_chambre_id) REFERENCES chambre (id) ON DELETE SET NULL');
    }
}
