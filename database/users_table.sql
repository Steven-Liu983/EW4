CREATE TABLE 'users' (
  'id' int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  'username' varchar(200) NOT NULL,
  'email' varchar(200) NOT NULL UNIQUE,
  'password' varchar(200) NOT NULL
);

CREATE TABLE 'comments' (
  'id' int(11) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  'name' varchar(255) NOT NULL,
  'comment' text NOT NULL
);
