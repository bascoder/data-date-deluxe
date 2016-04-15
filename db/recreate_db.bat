@echo off

sqlite3 database.sqlite < dml.sql 
sqlite3 database.sqlite < seed.sql 
