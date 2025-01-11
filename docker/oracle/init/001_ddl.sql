DROP TABLE IF EXISTS system.unittest_key_single;
CREATE TABLE system.unittest_key_single (
	key1 varchar2(20),
	val1 varchar2(50),
	val2 int,
	val3 timestamp,
	PRIMARY KEY(key1)
);

DROP TABLE IF EXISTS system.unittest_key_double;
CREATE TABLE system.unittest_key_double (
	key1 varchar2(20),
	key2 int,
	val1 varchar2(50),
	val2 int,
	val3 timestamp,
	PRIMARY KEY(key1, key2)
);

DROP TABLE IF EXISTS system.unittest_key_all;
CREATE TABLE IF NOT EXISTS system.unittest_key_all (
	key1 varchar2(20),
	key2 int,
	PRIMARY KEY(key1, key2)
);

DROP TABLE IF EXISTS system.unittest_key_none;
CREATE TABLE IF NOT EXISTS system.unittest_key_none (
	val1 varchar2(20),
	val2 int,
	val3 timestamp
);



DROP TABLE IF EXISTS system.tbl_group;
CREATE TABLE IF NOT EXISTS system.tbl_group (
	group_id varchar2(50),
	group_name varchar2(50),
	group_address varchar2(50),
	group_phone varchar2(50),
	person_id varchar2(50),
	PRIMARY KEY(group_id)
);

DROP TABLE IF EXISTS system.tbl_person;
CREATE TABLE IF NOT EXISTS system.tbl_person (
	person_id varchar2(50),
	person_name varchar2(50),
	person_address varchar2(50),
	person_phone varchar2(50),
	PRIMARY KEY(person_id)
);

DROP TABLE IF EXISTS system.tbl_group_person;
CREATE TABLE IF NOT EXISTS system.tbl_group_person (
	group_id varchar2(50),
	person_id varchar2(50),
	PRIMARY KEY(group_id, person_id)
);
