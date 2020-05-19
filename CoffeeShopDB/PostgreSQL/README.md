# CoffeeShopDB using PostgreSQL 12
## Connect to Database Hosted on Amazon RDS
1. Get in postgresql by typing: `sudo su - postgres`
2. Type: `psql --host=coffeeshopdb.cniwq5h2gums.us-east-2.rds-preview.amazonaws.com --port=5432 --username=coffeeshop --dbname=CoffeeShopDB`
3. Log in with `cmps3420`
## Install lastest PostgreSQL on Ubuntu using the following commands:
1. `sudo apt-get install wget ca-certificates`
2. `wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -`
3. `sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list'`
4. `sudo apt-get update`
5. `sudo apt-get install postgresql postgresql-contrib`
### Once that is done connect to PostgreSQL using:

`sudo su - postgres`
`psql`

### Steps to create database:
1. Get in postgresql by typing: `sudo su - postgres`
2. Clone the repository and make sure you are in the cloned directory
3. Type `mkdir data` and `mkdir index`
4. Log in as postgres: `psql`
5. Type: `\i crt_First.sql`
6. Log out of postgres: `\q`
7. Log in as coffeeshop: `psql -h localhost -U coffeeshop coffeeshop`
8. Type: `\i crt_Second.sql`
#### From here the database should be all set up.
* If something goes wrong during any of this make sure that the `LOCATION` is set to the right
file path
	* `LOCATION` instances can be found in some of the SQL files
* To completely remove the database log in as postgres and type: `\i drop_whole_database.sql`
