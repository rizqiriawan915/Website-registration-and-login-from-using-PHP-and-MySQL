create database login_register;
use login_register;
create table users(
id int not null primary key,
full_name varchar(128) not null,
email varchar(255) not null,
password varchar(255) not null
);
alter table users modify id int not null auto_increment;
desc users;
select * from users;