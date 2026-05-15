CREATE TABLE organizations (
    id              BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    parent_id       BIGINT UNSIGNED  NULL     COMMENT '親組織ID（NULLは最上位）',
    name            VARCHAR(100)     NOT NULL COMMENT '組織名',
    name_kana       VARCHAR(200)     NOT NULL DEFAULT '' COMMENT '組織名（カナ）',
    email           VARCHAR(254)     NOT NULL DEFAULT '' COMMENT 'メールアドレス',
    phone           VARCHAR(20)      NOT NULL DEFAULT '' COMMENT '電話番号',
    zipcode         CHAR(7)          NOT NULL DEFAULT '' COMMENT '郵便番号（ハイフンなし7桁）',
    prefecture      VARCHAR(10)      NOT NULL DEFAULT '' COMMENT '都道府県',
    city            VARCHAR(50)      NOT NULL DEFAULT '' COMMENT '市区町村',
    address_line     VARCHAR(100)     NOT NULL DEFAULT '' COMMENT '番地・建物名',
    representative_id BIGINT UNSIGNED NULL     COMMENT '代表者ID（persons.id）',
    established_date DATE             NULL     COMMENT '設立日',
    status          TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'ステータス（0:無効 1:有効）',
    created_at      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_parent_id (parent_id),
    INDEX idx_representative_id (representative_id),
    INDEX idx_name_kana (name_kana),
    INDEX idx_zipcode (zipcode),
    CONSTRAINT fk_organizations_parent
        FOREIGN KEY (parent_id) REFERENCES organizations (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_organizations_representative
        FOREIGN KEY (representative_id) REFERENCES persons (id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='組織情報';
