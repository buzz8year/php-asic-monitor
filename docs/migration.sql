drop table if exists users;
create table users
(
  id int unsigned not null primary key auto_increment comment 'UUID',
  name tinytext comment 'Firstname',
  surname tinytext comment 'Lastname',
  phone tinytext comment 'Phone Number',
  email tinytext comment 'Email',
  login tinytext comment 'Nickname',
  password tinytext comment 'Password',
  last_login datetime default null comment 'Last Login Timestamp',
  last_ip tinytext default null comment 'Last Login IP',
  active smallint not null comment 'Active Status: 1 - active (can access app), 0 - inactive (cannot access)',
  index login_idx(login(16))
) default charset utf8 engine myisam;

drop table if exists user_rights;
create table user_rights
(
  user_id int unsigned not null,
  access_id int unsigned not null,
  primary key (user_id, access_id)
) default charset utf8 engine myisam;

drop table if exists user_locations;
create table user_locations
(
  user_id int unsigned not null,
  location_id int unsigned not null
) default charset utf8 engine myisam;