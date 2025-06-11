#!/usr/bin/env bash
set -e

# User config
while true; do
    read -p "Choose a database 1: MySQL, 2: SQLite [2] " database
    case $database in
        "1" ) break;;
        "2" ) break;;
        "" ) database="2"; break;;
        * ) echo "Invalid choice";;
    esac
done

# Create .env file
path=$(dirname $(realpath $0))
echo ""
echo "Creating environment at $path"
if [ $database == "1" ] # MySQL
then
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
    echo -e "DB_DSN=mysql:host=localhost;dbname=$dataname\nDB_USERNAME=$username\nDB_PASSWORD=$password" > .env
elif [ $database == "2" ] #Sqlite
then
    echo -e "DB_DSN=sqlite:$path/data/database.sqlite\nDB_USERNAME=\nDB_PASSWORD=" > .env
    if [ -f data/database.sqlite ]; then
        echo "Database file already exists at data/database.sqlite, if this is unexpected you might have to delete it manually and run this script again"
    else
        mkdir -p data
        touch data/database.sqlite
    fi
    echo "Needing sudo password to give permission to database file"

    read -p "Webserver user [www-data]: " webuser
    if [ -z $webuser ]
    then
        webuser=www-data
    fi

    sudo chmod 775 data/database.sqlite
    sudo chown $webuser:$webuser data
    sudo chown $webuser:$webuser data/database.sqlite
fi

# Run php script
echo ""
echo "Running migration script"
php bin/migrate

echo ""
echo "Creating admin user, please fill the following data with the administrator credentials"
php bin/adduser
