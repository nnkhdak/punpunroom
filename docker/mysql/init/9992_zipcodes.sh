#!/bin/bash
set -e

# Enable local_infile on the MySQL server
mysql -u root -p"${MYSQL_ROOT_PASSWORD}" \
  -e "SET GLOBAL local_infile = 1;"

# Import UTF-8 KEN_ALL CSV (CRLF) into zipcodes table
# CSV column order:
#  1: gov_code (全国地方公共団体コード, 5桁 → 先頭2桁が都道府県コード)
#  2: old_zip  (旧郵便番号, 不要)
#  3: zipcode  (郵便番号 7桁)
#  4: prefecture_kana
#  5: city_kana
#  6: town_kana
#  7: prefecture
#  8: city
#  9: town
# 10-15: 各種フラグ (不要)
mysql --local-infile=1 \
  -u root \
  -p"${MYSQL_ROOT_PASSWORD}" \
  "${MYSQL_DATABASE}" <<'SQL'
LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/utf_ken_all.csv'
INTO TABLE zipcodes
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\r\n'
(@gov_code, @old_zip, zipcode, prefecture_kana, city_kana, town_kana, prefecture, city, town, @f1, @f2, @f3, @f4, @f5, @f6)
SET prefecture_code = CAST(LEFT(@gov_code, 2) AS UNSIGNED);
SQL
