<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417144311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist (
                            id INT AUTO_INCREMENT NOT NULL,
                            name VARCHAR(255) NOT NULL, PRIMARY KEY(id)
                      ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist_songs (
                                playlist_id INT NOT NULL,
                                song_id INT NOT NULL,
                                INDEX IDX_19F0A8D86BBD148 (playlist_id),
                                INDEX IDX_19F0A8D8A0BDB2F3 (song_id),
                                PRIMARY KEY(playlist_id, song_id)
                            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE playlist_songs ADD CONSTRAINT FK_19F0A8D86BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_songs ADD CONSTRAINT FK_19F0A8D8A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_songs DROP FOREIGN KEY FK_19F0A8D86BBD148');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE playlist_songs');
    }
}
