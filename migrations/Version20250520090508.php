<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520090508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet DROP CONSTRAINT fk_ea235769bfb72ee6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_ea235769bfb72ee6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet ADD order_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet ADD clef_finale VARCHAR(128) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet ADD qr_code TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet DROP fk_order_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet ADD CONSTRAINT FK_EA2357698D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_EA2357698D9F6D38 ON ebillet (order_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D6496186CA22 ON "user" (user_key)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet DROP CONSTRAINT FK_EA2357698D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_EA2357698D9F6D38
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet ADD fk_order_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet DROP order_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet DROP clef_finale
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet DROP qr_code
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ebillet ADD CONSTRAINT fk_ea235769bfb72ee6 FOREIGN KEY (fk_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX uniq_ea235769bfb72ee6 ON ebillet (fk_order_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649E7927C74
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D6496186CA22
        SQL);
    }
}
