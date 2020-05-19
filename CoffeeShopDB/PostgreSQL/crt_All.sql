-- Create database user, tablespaces and database. Both user and database names are gradebook.
\i crt_First.sql
\! echo	    user <coffeeshop> and database <coffeeshop> are created.

-- Before you create database schema objects. Otherwise the objects will be created in postgres databse. 
--	(1) must connect to the database 'coffeeshop'
--      (2) Optionally, you can set 'coffeeshop' as user or called role.
\c coffeeshop
SET ROLE coffeeshop;

-- Create all gradebook database schema objects.
\i crt_Second.sql

\q
