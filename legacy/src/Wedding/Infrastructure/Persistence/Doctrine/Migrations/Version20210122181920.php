<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210122181920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Blank Migration that does not really';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('SELECT now()');
    }

    public function down(Schema $schema): void
    {
    }
}
