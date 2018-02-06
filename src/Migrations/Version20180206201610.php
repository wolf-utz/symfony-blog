<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180206201610 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post_tag_mm DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE post_tag_mm ADD tag_id INT NOT NULL, CHANGE id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE post_tag_mm ADD CONSTRAINT FK_9740FAD44B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_tag_mm ADD CONSTRAINT FK_9740FAD4BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('CREATE INDEX IDX_9740FAD44B89032C ON post_tag_mm (post_id)');
        $this->addSql('CREATE INDEX IDX_9740FAD4BAD26311 ON post_tag_mm (tag_id)');
        $this->addSql('ALTER TABLE post_tag_mm ADD PRIMARY KEY (post_id, tag_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post_tag_mm DROP FOREIGN KEY FK_9740FAD44B89032C');
        $this->addSql('ALTER TABLE post_tag_mm DROP FOREIGN KEY FK_9740FAD4BAD26311');
        $this->addSql('DROP INDEX IDX_9740FAD44B89032C ON post_tag_mm');
        $this->addSql('DROP INDEX IDX_9740FAD4BAD26311 ON post_tag_mm');
        $this->addSql('ALTER TABLE post_tag_mm DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE post_tag_mm ADD id INT NOT NULL, DROP post_id, DROP tag_id');
        $this->addSql('ALTER TABLE post_tag_mm ADD PRIMARY KEY (id)');
    }
}
