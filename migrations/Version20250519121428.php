<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519121428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE order_offer (order_id INT NOT NULL, offer_id INT NOT NULL, PRIMARY KEY(order_id, offer_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AA48F3C38D9F6D38 ON order_offer (order_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_AA48F3C353C674EE ON order_offer (offer_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_offer ADD CONSTRAINT FK_AA48F3C38D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_offer ADD CONSTRAINT FK_AA48F3C353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT fk_f52993985741eeb9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT fk_f529939853c674ee
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f529939853c674ee
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f52993985741eeb9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP fk_user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP offer_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP order_date
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" RENAME COLUMN cle_achat TO clef_achat
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "order".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_offer DROP CONSTRAINT FK_AA48F3C38D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_offer DROP CONSTRAINT FK_AA48F3C353C674EE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_offer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD fk_user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD offer_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD order_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP created_at
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" RENAME COLUMN clef_achat TO cle_achat
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT fk_f52993985741eeb9 FOREIGN KEY (fk_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT fk_f529939853c674ee FOREIGN KEY (offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f529939853c674ee ON "order" (offer_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_f52993985741eeb9 ON "order" (fk_user_id)
        SQL);
    }
}
