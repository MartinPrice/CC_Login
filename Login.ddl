--CREATE DATABASE CustomerDirectory;

USE CustomerDirectory;

CREATE TABLE Customer ( 
  user_name     VARCHAR(20),
  password      VARCHAR(255),
PRIMARY KEY(user_name) );

--INSERT INTO `Customer` VALUES ('test1','secret');
