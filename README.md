## Stock Tracker

This is a system to redeem tickets for events. Features of this system include
- Ticket redemption
- Rate limiting system on how many tickets can be redeemed per hour

### Tech Stack
- PHP 8.1
- Laravel
- Composer
- Postgresql
- RabbitMQ
- Docker Compose (Laravel Sail)

### Prerequisites
- [Docker with docker compose](https://www.docker.com/).
- [Git](https://git-scm.com/) - to clone repository (optional)
- [PHP 8.2](https://php.net) must be installed on the command line
- [Composer](https://getcomposer.org) must be installed on the command line and should be in your PATH.

### How to set up
- Clone the repository to your computer from your terminal using `git clone https://git.jobsity.com/brytey2k/stock-tracker.git`
- Run `cp .env.example .env` from your command line to create the system environment configuration file
- Open `.env` file with your favourite editor and insert the correct configurations.
- Run `chmod u+x setup.sh` to make the setup script executable
- Run `./setup.sh`
- Wait for a few seconds before you run `./vendor/bin/sail artisan queue:work` since RabbitMQ may take a while to start up. This current command will start the queue worker so that it can listen for messages received by RabbitMQ and process them.
- A Postman collection has been provided for testing the API endpoints. File is in the root of the project `Stock Traker API.postman_collection.json`
- Subsequent starts of application must use this command `./vendor/bin/sail up -d` and also `./vendor/bin/sail artisan queue:work` (this second command should be a few seconds after running the `up` command)

### Tasks completed
- User creation and authentication (using JWT)
- Stock request endpoint (sends email through RabbitMQ queues)
- Endpoint showing history of queries made to the API by user
- Tests written and passing
- Containerize application using docker compose

### Stopping application
- Press Ctrl + C to exit from the command line
- The docker containers may still be running so you may need to execute `./vendor/bin/sail stop`

### Tests
- To run tests, run `./vendor/bin/sail artisan test`
- To generate code coverage reports with tests, run `./vendor/bin/sail artisan test --coverage-html coverage-report`

### Security Vulnerabilities

If you discover a security vulnerability within this system, please send an e-mail to Bright Nkrumah via [brytey2k@gmail.com](mailto:brytey2k@gmail.com). All security vulnerabilities will be promptly addressed.
