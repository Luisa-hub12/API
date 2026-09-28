<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260928122342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD created_by_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD updated_by_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD deleted_by_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649C76F1F52 FOREIGN KEY (deleted_by_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_8D93D649B03A8386 ON "user" (created_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649896DBBDE ON "user" (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C76F1F52 ON "user" (deleted_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649896DBBDE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649C76F1F52');
        $this->addSql('DROP INDEX IDX_8D93D649B03A8386');
        $this->addSql('DROP INDEX IDX_8D93D649896DBBDE');
        $this->addSql('DROP INDEX IDX_8D93D649C76F1F52');
        $this->addSql('ALTER TABLE "user" DROP updated_at');
        $this->addSql('ALTER TABLE "user" DROP deleted_at');
        $this->addSql('ALTER TABLE "user" DROP created_by_id');
        $this->addSql('ALTER TABLE "user" DROP updated_by_id');
        $this->addSql('ALTER TABLE "user" DROP deleted_by_id');
        $this->addSql('ALTER TABLE "user" ALTER first_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name SET NOT NULL');
    }
}
