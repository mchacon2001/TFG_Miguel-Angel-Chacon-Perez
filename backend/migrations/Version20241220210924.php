<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241220210924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id VARCHAR(255) NOT NULL, document_type_id INT DEFAULT NULL, original_name VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, subdirectory VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_D8698A7661232A4F (document_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, required_document TINYINT(1) NOT NULL, entity_type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, permission_group_id INT NOT NULL, action VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, admin_managed TINYINT(1) DEFAULT 0 NOT NULL, module_dependant VARCHAR(200) DEFAULT NULL, INDEX IDX_E04992AAB6C0CF1 (permission_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, immutable TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_has_permission (id VARCHAR(255) NOT NULL, role_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_6F82580FD60322AC (role_id), INDEX IDX_6F82580FFED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id VARCHAR(255) NOT NULL, profile_img_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, last_login_at DATETIME DEFAULT NULL, temporal_hash VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A840F832 (profile_img_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_has_document (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, document_id VARCHAR(255) NOT NULL, document_type_id INT DEFAULT NULL, INDEX IDX_49C30C40A76ED395 (user_id), INDEX IDX_49C30C40C33F7837 (document_id), INDEX IDX_49C30C4061232A4F (document_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_has_permission (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, permission_id INT NOT NULL, INDEX IDX_6D8EB460A76ED395 (user_id), INDEX IDX_6D8EB460FED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_has_role (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, role_id INT NOT NULL, INDEX IDX_EAB8B535A76ED395 (user_id), INDEX IDX_EAB8B535D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7661232A4F FOREIGN KEY (document_type_id) REFERENCES document_type (id)');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AAB6C0CF1 FOREIGN KEY (permission_group_id) REFERENCES permission_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_has_permission ADD CONSTRAINT FK_6F82580FD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_has_permission ADD CONSTRAINT FK_6F82580FFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A840F832 FOREIGN KEY (profile_img_id) REFERENCES document (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_has_document ADD CONSTRAINT FK_49C30C40A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_document ADD CONSTRAINT FK_49C30C40C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_document ADD CONSTRAINT FK_49C30C4061232A4F FOREIGN KEY (document_type_id) REFERENCES document_type (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_has_permission ADD CONSTRAINT FK_6D8EB460A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_permission ADD CONSTRAINT FK_6D8EB460FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_role ADD CONSTRAINT FK_EAB8B535A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_has_role ADD CONSTRAINT FK_EAB8B535D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7661232A4F');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AAB6C0CF1');
        $this->addSql('ALTER TABLE role_has_permission DROP FOREIGN KEY FK_6F82580FD60322AC');
        $this->addSql('ALTER TABLE role_has_permission DROP FOREIGN KEY FK_6F82580FFED90CCA');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A840F832');
        $this->addSql('ALTER TABLE user_has_document DROP FOREIGN KEY FK_49C30C40A76ED395');
        $this->addSql('ALTER TABLE user_has_document DROP FOREIGN KEY FK_49C30C40C33F7837');
        $this->addSql('ALTER TABLE user_has_document DROP FOREIGN KEY FK_49C30C4061232A4F');
        $this->addSql('ALTER TABLE user_has_permission DROP FOREIGN KEY FK_6D8EB460A76ED395');
        $this->addSql('ALTER TABLE user_has_permission DROP FOREIGN KEY FK_6D8EB460FED90CCA');
        $this->addSql('ALTER TABLE user_has_role DROP FOREIGN KEY FK_EAB8B535A76ED395');
        $this->addSql('ALTER TABLE user_has_role DROP FOREIGN KEY FK_EAB8B535D60322AC');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_type');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE permission_group');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_has_permission');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_has_document');
        $this->addSql('DROP TABLE user_has_permission');
        $this->addSql('DROP TABLE user_has_role');
    }
}
