#!/usr/bin/env bash
#This shell script is called during Provisioning when a Vagrant Virtual Machine is started - it is called at the end of the Vagrantfile.

cd /var/www  #This is the vagrant shared folder

# Update Composer (it's already installed by Scotchbox)
sudo composer self-update

# Install PHP dependencies using Composer (Phinx, etc)
sudo composer install

# Run database migrations with Phinx
php vendor/bin/phinx migrate -e development


echo "================================="
echo "This server has been configured!"
echo "To connect to the VM, use the following:"
echo "ssh://vagrant:vagrant@127.0.0.1:2222"
