#!/usr/bin/env bash
#This shell script is called during Provisioning when a Vagrant Virtual Machine is started - it is called at the end of the Vagrantfile.

cd /vagrant  #This is the vagrant shared folder

# Install PHINX (PHP database migration tool) using Composer
#sudo php composer.phar require robmorgan/phinx

echo "================================="
echo "This server has been configured!"
echo "To connect to the VM, use the following:"
echo "ssh://vagrant:vagrant@127.0.0.1:2222"
