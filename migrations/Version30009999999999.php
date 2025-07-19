<?php

declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Seed migration: This migration has a far-future timestamp to ensure it always runs last
 * and seeds essential data even in test environments.
 */
final class Version30009999999999 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed playlist types - ensures data exists in all environments including tests';
    }

    public function up(Schema $schema): void
    {
        // Use INSERT IGNORE to avoid duplicate key errors if types already exist
        // This is safe to run multiple times
        $this->addSql('INSERT IGNORE INTO playlist_type (id, type) VALUES
                                  (1, "summer"), (2, "spring"), (3, "autumn"), (4, "winter"), (5, "favorites"), (6, "theme")');
    }

    public function down(Schema $schema): void
    {
        // Remove seeded playlist types
        $this->addSql('DELETE FROM playlist_type WHERE type IN ("summer", "spring", "autumn", "winter", "favorites", "theme")');
    }
}