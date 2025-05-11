#!/usr/bin/env bash

# User config
read -p "Database name [shika]: " dataname
if [ -z $dataname ]
then
    dataname=shika
fi
read -p "Database username [admin]: " username
if [ -z $username ]
then
    username=admin
fi
while [ -z $password ]
do
    read -p "Database password: " password
done
while true; do
    read -p "Choose a database 1: MySQL, 2: SQLite [2] " database
    case $database in
        "1" ) break;;
        "2" ) break;;
        "" ) database="2"; break;;
        * ) echo "Invalid choice";;
    esac
done

echo ""
while true; do
    read -p read -p "Your selection: $dataname $username $password $database, y to continue [y] " confirm
    case $confirm in
        [yY]* ) break;;
        "" ) break;;
        * ) echo "Invalid choice";;
    esac
done

path=$(dirname $(realpath $0))
echo ""
if [ $database == "1" ]
then
    echo -e "DB_DSN=mysql:host=localhost;dbname=$dataname\nDB_USERNAME=$username\nDB_PASSWORD=$password" > .env
elif [ $database == "1" ]
then
    echo -e "DB_DSN=sqlite:$path/$dataname.sqlite\nDB_USERNAME=$username\nDB_PASSWORD=$password" > .env
fi
echo ".env config created at $path/.env"

php bin/migrate