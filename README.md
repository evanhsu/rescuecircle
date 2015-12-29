# README #

The RESCUECIRCLE site displays the location of every short-haul helicopter currently available for Wildland Firefighter rescue.

### How do I get set up? ###

* Getting set up for development
	* This project includes a Vagrant file that will configure a virtual machine for you.
	* Clone the project into its own folder: `git clone https://github.com/evanhsu/rescuecircle`
	* Create a `.env` file by copying `.env.example` (the default values will be sufficient for development)
	* Run `vagrant up` in the project directory
	* View the site in your browser at `http://localhost:8080`

* Dependencies
	* In order to use the virtual development machine that's been configured for this project, you'll need Vagrant and it's dependencies:
		* Install Oracle VirtualBox (https://www.virtualbox.org/wiki/Downloads)
		* Install Vagrant (https://www.vagrantup.com/)
	* The server itself will need (the included virtual machine is already configured with these):
		* PHP 5.6
		* Composer (PHP)
		* Apache 2.2.17 (with modRewrite)
		* PostgreSQL
	* And the web app itself relies on the following 3rd party libraries:
		* Laravel 5.1
		* jQuery 1.11.3
		* Bootstrap 3.3.5
* Database configuration
	* In order to connect to PostgreSQL from outside the VM (i.e. to use pgadmin to inspect the db), the folloowing PostgreSQL config files must be modified:
		* /etc/postgresql/9.3/main/postgresql.conf
		* /etc/postgresql/9.3/main/pg_hba.conf

	* The bootstrap.sh script that runs during 'vagrant up' will attempt to overwrite these files with our updated versions automatically
	* If the files cannot be overwritten automatically, you'll need to manually add/uncomment the following lines:
	  
	  In `/etc/postgresql/9.3/main/postgresql.conf` uncomment this line:
	  `listen_addresses = '*'`

	  Then ADD the following line to `/etc/postgresql/9.3/main/pg_hba.conf`:
	  `host    all     all     all     password`


### Deploying to Windows Server / IIS / SQL Server ###
#### Here's a quick checklist to run through when switching to a Windows environment ####

* Install PHP: http://php.iis.net/
* Install Composer: https://getcomposer.org/download/
	* There is a Windows binary installer towards the bottom of the page
* Clone the code to your web folder from GitHub:
	* https://github.com/evanhsu/rescuecircle
* Update Composer, then run it in the web directory:
	* C:\Path\to\web\folder> composer self-update
	* C:\Path\to\web\folder> composer install
	* If Composer doesn't work, the app dependencies can be manually installed.
* Create a SQL database user for this app to use
* Create a .env file by copying the .env.example file in the app root
	* Add database credentials to .env
	* Change APP_ENV to 'production' instead of 'local'
	* Change APP_DEBUG to 'false'
* Run database migrations with Laravel's 'artisan' CLI
	* C:\Path\to\web\folder> php artisan migrate
* Seed the database with the default admin user account
	* C:\Path\to\web\folder> php artisan db:seed

### Who do I talk to? ###

* Evan Hsu
* GitHub/evanhsu