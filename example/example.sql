CREATE TABLE SAMPLE_TABLE 
(
  id VARCHAR2(255) NOT NULL,
  name VARCHAR2(1024)
);

-- Insert rows in a Table

INSERT INTO SAMPLE_TABLE 
VALUES
(
  '001',
  'suzuki'
);

-- Select rows from a Table

SELECT *
FROM SAMPLE_TABLE;