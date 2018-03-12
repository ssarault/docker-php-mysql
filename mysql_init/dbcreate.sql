CREATE DATABASE phpdb;
use phpdb;

CREATE TABLE users (
  id INTEGER AUTO_INCREMENT PRIMARY KEY,
  username CHAR(255),
  password CHAR(255)
);

CREATE TABLE posts (
  id INTEGER AUTO_INCREMENT PRIMARY KEY,
  name CHAR(255),
  content TEXT,
  up_votes INTEGER,
  down_votes INTEGER,
  numner_comments INTEGER
);

CREATE TABLE comments (
  id INTEGER AUTO_INCREMENT PRIMARY KEY,
  user_id INTEGER,
  post_id INTEGER,
  parent_id INTEGER,
  tree_id INTEGER,
  left INTEGER,
  right INTEGER,
  content TEXT,
  up_votes INTEGER,
  down_votes INTEGER
);

INSERT INTO users (username, password) VALUES ('sean', 'password');
