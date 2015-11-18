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
		* Laravel 5.1
		* Apache 2.2.17 (with modRewrite)
		* PostgreSQL 
		* jQuery 1.11.3
		* Bootstrap 3.3.5
* Database configuration
* How to run tests
* Deployment instructions

### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Evan Hsu
* GitHub/evanhsu