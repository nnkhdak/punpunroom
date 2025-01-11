DROP TABLE IF EXISTS unittest_key_single;
CREATE TABLE IF NOT EXISTS unittest_key_single (
	key1 varchar(20),
	val1 varchar(50),
	val2 int,
	val3 timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(key1)
);

DROP TABLE IF EXISTS unittest_key_double;
CREATE TABLE IF NOT EXISTS unittest_key_double (
	key1 varchar(20),
	key2 int,
	val1 varchar(50),
	val2 int,
	val3 timestamp NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(key1, key2)
);

DROP TABLE IF EXISTS unittest_key_all;
CREATE TABLE IF NOT EXISTS unittest_key_all (
	key1 varchar(20),
	key2 int,
	PRIMARY KEY(key1, key2)
);

DROP TABLE IF EXISTS unittest_key_none;
CREATE TABLE IF NOT EXISTS unittest_key_none (
	val1 varchar(50),
	val2 int,
	val3 timestamp NULL DEFAULT CURRENT_TIMESTAMP
);



DROP TABLE IF EXISTS tbl_group;
CREATE TABLE IF NOT EXISTS tbl_group (
	group_id varchar(50),
	group_name varchar(50),
	group_address varchar(50),
	group_phone varchar(50),
	person_id varchar(50),
	PRIMARY KEY(group_id)
);

DROP TABLE IF EXISTS tbl_person;
CREATE TABLE IF NOT EXISTS tbl_person (
	person_id varchar(50),
	person_name varchar(50),
	person_address varchar(50),
	person_phone varchar(50),
	PRIMARY KEY(person_id)
);

DROP TABLE IF EXISTS tbl_group_person;
CREATE TABLE IF NOT EXISTS tbl_group_person (
	group_id varchar(50),
	person_id varchar(50),
	PRIMARY KEY(group_id, person_id)
);
