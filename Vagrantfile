# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|

  config.vm.box = "scotch/box"

  # Disable automatic box update checking. If you disable this, then
  # boxes will only be checked for updates when the user runs
  # `vagrant box outdated`. This is not recommended.
  # config.vm.box_check_update = false

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network "forwarded_port", guest: 80, host: 8080     #Web port
  config.vm.network "forwarded_port", guest: 5432, host: 5532   #PostgreSQL

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  config.vm.network "private_network", ip: "192.168.33.10"

  # Sync the project folder on the host with the 'www' folder on the vm
  config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]

  # Enable the Virtual Box GUI to watch for any VM boot messages (for troubleshooting bootup issues)
  # config.vm.provider :virtualbox do |vb|
  #   vb.gui = true
  # end

  # Configure SSH
  # config.ssh.username = 'vagrant'
  # config.ssh.password = 'vagrant'

  # Provision the server with a bash script (perform server configuration after it has booted up)
  config.vm.provision :shell, path: "bootstrap.sh"


  # Database settings for ScotchBox (MySQL & PostgreSQL):
  # dB Name:    scotchbox
  # Username:   root
  # Password:   vagrant
  # Host:       localhost

  # SSH Settings
  # Username:   vagrant
  # Password:   vagrant

end