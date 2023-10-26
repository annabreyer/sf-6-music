<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417190816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE playlist_types (
                                playlist_id INT NOT NULL,
                                playlist_type_id INT NOT NULL,
                                INDEX IDX_FA2C90736BBD148 (playlist_id),
                                INDEX IDX_FA2C90737A739B89 (playlist_type_id),
                                PRIMARY KEY(playlist_id, playlist_type_id)
                            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_type (
                                id INT AUTO_INCREMENT NOT NULL,
                                type VARCHAR(255) NOT NULL,
                                PRIMARY KEY(id)
                           ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist_types ADD CONSTRAINT FK_FA2C90736BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_types ADD CONSTRAINT FK_FA2C90737A739B89 FOREIGN KEY (playlist_type_id) REFERENCES playlist_type (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO playlist_type VALUES
                                  (null, "summer"), (null, "spring"), (null, "autumn"), (null, "winter"), (null, "favorites"), (null, "theme")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_types DROP FOREIGN KEY FK_FA2C90737A739B89');
        $this->addSql('DROP TABLE playlist_types');
        $this->addSql('DROP TABLE playlist_type');
    }
}
