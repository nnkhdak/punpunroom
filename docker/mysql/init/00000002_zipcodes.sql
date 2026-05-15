CREATE TABLE zipcodes (
    id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    zipcode        CHAR(7)         NOT NULL COMMENT '郵便番号（ハイフンなし7桁）',
    prefecture_code TINYINT UNSIGNED NOT NULL COMMENT '都道府県コード（01〜47）',
    prefecture     VARCHAR(10)     NOT NULL COMMENT '都道府県名',
    prefecture_kana VARCHAR(20)    NOT NULL COMMENT '都道府県名（カナ）',
    city           VARCHAR(50)     NOT NULL COMMENT '市区町村名',
    city_kana      VARCHAR(100)    NOT NULL COMMENT '市区町村名（カナ）',
    town           VARCHAR(100)    NOT NULL DEFAULT '' COMMENT '町域名',
    town_kana      VARCHAR(200)    NOT NULL DEFAULT '' COMMENT '町域名（カナ）',
    created_at     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='郵便番号マスタ';
