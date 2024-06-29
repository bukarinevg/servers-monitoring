# servers-monitoring
 Web Servers Monitoring system

## Introduction
- System for monitoring HTTP web servers 

## Features
- MVC architecture
- implemented PHP8 features as attributes, enums, types definition, etc.
- Handling api methods
- Handling errors according to the HTTP status codes and not appropiate requests
- API response in JSON format with required status codes and location headers for post requests
- CRON job for monitoring the servers runs every minute
- Asynchronous requests to the servers
- Email notifications for the server status changes 

## Endpoints
- GET /get/:id - Get details of a specific server being monitored by its ID.
- GET /get-all - List all servers currently being monitored.
- GET /get-history/:id- Get the monitoring - history of a specific server within a given date range.
- POST /post - Add a new server to monitor.
- PUT /put/:id - Update the monitoring settings for a specific server by its ID.
- DELETE /delete/:id - Remove a server from monitoring by its ID.


## Installation

1. Clone the repository.
2. Run the following command to build the docker image and start the containers:
```bash 
    docker-compose up --build
```
3.  Install the required packages:
```bash
    composer install
```
4. Run the following command to create the tables and run the migrations:
```bash
    vendor/bin/phinx migrate -e development
```

## Usage

1. Load the postman collection from `postman/` folder.
2. Run the collection to test the endpoints.
3. To run the monitoring script  run the following command:
```bash
    php script.php
```
4. Add new users to email notifications in the `config/config.php` file.

## Project Structure

- `config/` - This directory contains configuration file.
- `db/` - This directory contains migrations.
- `php-apache/` - This directory contains Dockerfile for Apache.
- `src/` - This directory contains the main application code.
  - `controllers/` - This directory contains the controller classes performing route paths.
  - `enums/` - This directory contains the enums classes (dictonaries data).
  - `models/` - This directory contains the model classes.
  - `source/` - Base code of application.


## DataBase Schema

- `web_server` - This table contains the servers to monitor.
- `web_server_work` - This table contains the monitor checks of the servers.