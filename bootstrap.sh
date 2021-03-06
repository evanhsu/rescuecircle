#!/usr/bin/env bash
#This shell script is called during Provisioning when a Vagrant Virtual Machine is started - it is called at the end of the Vagrantfile.

cd /var/www  #This is the vagrant shared folder

# Update Composer (it's already installed by Scotchbox)
sudo composer self-update

# Install PHP dependencies using Composer (Phinx, etc)
sudo composer install


# In order to connect to PostgreSQL from outside the VM, the PostgreSQL config files must be modified:
#   /etc/postgresql/9.3/main/postgresql.conf
#   /etc/postgresql/9.3/main/pg_hba.conf
#
# We'll attempt to overwrite the default copies with our own versions automatically:
sudo cp -f /var/www/database/postgresql.conf /etc/postgresql/9.3/main/
sudo cp -f /var/www/database/pg_hba.conf /etc/postgresql/9.3/main/

# If the files cannot be overwritten automatically, you'll need to manually add/uncomment the following line from
#  /etc/postgresql/9.3/main/postgresql.conf 
#
#  listen_addresses = '*'
#
# THEN add the following line to
#   /etc/postgresql/9.3/main/pg_hba.conf
#
#   host    all     all     all     password

# Run database migrations
php artisan migrate

# Load seed data into the database
php artisan db:seed

echo "================================="
echo "This server has been configured!"
echo "To connect to the VM, use the following:"
echo "ssh://vagrant:vagrant@127.0.0.1:2222"
