<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180212185240 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post_category_mm DROP FOREIGN KEY FK_E3B66A6D12469DE2');
        $this->addSql('CREATE TABLE contact_request (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, note LONGTEXT NOT NULL, hidden TINYINT(1) NOT NULL, created DATETIME NOT NULL, last_updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE post_category_mm');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(64) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, hidden TINYINT(1) NOT NULL, created DATETIME NOT NULL, last_updated DATETIME NOT NULL, title VARCHAR(64) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category_mm (post_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E3B66A6D4B89032C (post_id), INDEX IDX_E3B66A6D12469DE2 (category_id), PRIMARY KEY(post_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_category_mm ADD CONSTRAINT FK_E3B66A6D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE post_category_mm ADD CONSTRAINT FK_E3B66A6D4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('DROP TABLE contact_request');
        $this->addSql('ALTER TABLE user CHANGE password password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
