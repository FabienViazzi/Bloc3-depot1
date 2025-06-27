<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627120803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE offer ALTER id DROP DEFAULT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT fk_f5299398a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f5299398a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" RENAME COLUMN user_id TO fk_user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT FK_F52993985741EEB9 FOREIGN KEY (fk_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F52993985741EEB9 ON "order" (fk_user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER id DROP DEFAULT
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT FK_F52993985741EEB9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F52993985741EEB9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" RENAME COLUMN fk_user_id TO user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT fk_f5299398a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f5299398a76ed395 ON "order" (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE user_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('user_id_seq', (SELECT MAX(id) FROM "user"))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ALTER id SET DEFAULT nextval('user_id_seq')
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE offer_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('offer_id_seq', (SELECT MAX(id) FROM offer))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offer ALTER id SET DEFAULT nextval('offer_id_seq')
        SQL);
    }
}
