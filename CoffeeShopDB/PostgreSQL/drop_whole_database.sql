\! echo "[WARNING] The entire database is being removed!"
-- removes database, tablespaces, user, indexes, and views of coffeeshop
DROP DATABASE IF EXISTS coffeeshop;
DROP TABLESPACE IF EXISTS CSdata;
DROP TABLESPACE IF EXISTS CSidx;
DROP USER IF EXISTS coffeeshop;
