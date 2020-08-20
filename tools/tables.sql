CREATE TABLE users (
  id int NOT NULL,
  username varchar(150) NOT NULL,
  password varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE files (
  file_id int NOT NULL,
  file_name varchar(200) NOT NULL,
  file_content longblob NOT NULL,
  file_date datetime NOT NULL,
  file_link varchar(200) NOT NULL,
  user_id int NOT NULL,
  PRIMARY KEY(file_id),
  FOREIGN KEY (user_id) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
