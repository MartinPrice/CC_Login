
--If the database already exists but you want to create it
--again, use the following lines. Just remove the -- at
--the beginning of the following 2 lines.

--DROP DATABASE IF EXISTS CustomerDirectory; 
--CREATE DATABASE CustomerDirectory;

USE CustomerDirectory;

DROP TABLE IF EXISTS Customer;

CREATE TABLE Customer ( 
  user_name     VARCHAR(20)    NOT NULL,
  create_date   DATE           NOT NULL,
  password      VARCHAR(255)   NOT NULL,
  last_name     VARCHAR(50),
  first_name    VARCHAR(40),
  street        VARCHAR(50),
  city          VARCHAR(50),
  state         CHAR(2),
  zip           CHAR(10),
  email         VARCHAR(50),
  phone         CHAR(15),
  fax           CHAR(15),
PRIMARY KEY(user_name) );
INSERT INTO `Customer` VALUES ('test1','2005-01-03',md5('secret'),
                               'Tester','Sally','1234 Test St',
                               'Home','CA','12345','me@home.com',
                               '1231231234','3213214321');
