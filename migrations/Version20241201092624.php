<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241201092624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert initial records into guest table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO guest (email, first_name, last_name, phone_number, comments, attendance) VALUES
            ('john.doe@example.com', 'John', 'Doe', '123456789', 'No comments', 1),
            ('jane.smith@example.com', 'Jane', 'Smith', '987654321', 'Vegetarian meal', 1),
            ('mark.taylor@example.com', 'Mark', 'Taylor', '555555555', NULL, 0)"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM guest WHERE email IN (
            'john.doe@example.com',
            'jane.smith@example.com',
            'mark.taylor@example.com'
        )");
    }
}
