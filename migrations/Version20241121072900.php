<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121072900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', content_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', comment VARCHAR(255) NOT NULL, INDEX IDX_9474526C3590D879 (author_uuid), INDEX IDX_9474526C1C1DBD63 (content_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE upload (uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', path VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3590D879 FOREIGN KEY (author_uuid) REFERENCES user (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1C1DBD63 FOREIGN KEY (content_uuid) REFERENCES content (uuid)');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A93590D879');
        $this->addSql('ALTER TABLE content ADD upload_uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD meta_title VARCHAR(255) NOT NULL, ADD meta_description VARCHAR(255) NOT NULL, ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A955FBD7DF FOREIGN KEY (upload_uuid) REFERENCES upload (uuid) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A93590D879 FOREIGN KEY (author_uuid) REFERENCES user (uuid) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEC530A9989D9B62 ON content (slug)');
        $this->addSql('CREATE INDEX IDX_FEC530A955FBD7DF ON content (upload_uuid)');
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generate
    
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A955FBD7DF');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3590D879');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1C1DBD63');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE upload');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A93590D879');
        $this->addSql('DROP INDEX UNIQ_FEC530A9989D9B62 ON content');
        $this->addSql('DROP INDEX IDX_FEC530A955FBD7DF ON content');
        $this->addSql('ALTER TABLE content DROP upload_uuid, DROP meta_title, DROP meta_description, DROP slug');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A93590D879 FOREIGN KEY (author_uuid) REFERENCES user (uuid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL');
    }
}
