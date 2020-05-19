/*______________________________________________________
-- Create default, index, and temporary  tablespaces 
   for user Gradebook.

   In Oracle 10g, 
     1. Create the subdirectory in which dababase files
	are to be held.
     2. Do not create dbf files. Otherwise syntax error
	will occur.
_______________________________________________________*/

CREATE USER coffeeshop SUPERUSER PASSWORD 'c3m4p2s';  -- create a role.

CREATE TABLESPACE CSdata 
	 OWNER coffeeshop
	 LOCATION '/var/lib/postgresql/CoffeeShopDB/data'  -- The folder cannot be used to hold my tablespace
 --      [ WITH ( tablespace_option = value [, ... ] ) ]
	 ;
CREATE TABLESPACE CSidx 
	 OWNER coffeeshop
	 LOCATION '/var/lib/postgresql/CoffeeShopDB/index'  -- The folder cannot be used to hold my tablespace
 --      [ WITH ( tablespace_option = value [, ... ] ) ]
	 ;
CREATE DATABASE coffeeshop WITH OWNER = coffeeshop  TABLESPACE = CSdata;
